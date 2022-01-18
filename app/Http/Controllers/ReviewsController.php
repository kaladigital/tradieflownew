<?php

namespace App\Http\Controllers;
use App\Helpers\Constant;
use App\Helpers\Helper;
use App\Helpers\NotificationHelper;
use App\Models\ClientReview;
use App\Models\Country;
use App\Models\ReviewInvite;
use App\Models\UserTwilioPhone;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ReviewsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('active_subscription');
    }

    public function index(Request $request)
    {
        $auth_user = Auth::user();
        $get_average_reviews = ClientReview::selectRaw('ifnull(avg(rate),0) as num')
            ->where('user_id','=',$auth_user->user_id);

        $total_received_reviews = ClientReview::selectRaw('count(*) as num')
            ->where('user_id','=',$auth_user->user_id);

        $total_five_star_reviews = ClientReview::selectRaw('count(*) as num')
            ->where('user_id','=',$auth_user->user_id)
            ->where('rate','=','5');

        $total_four_star_reviews = ClientReview::selectRaw('count(*) as num')
            ->where('user_id','=',$auth_user->user_id)
            ->where('rate','=','4');

        $total_three_star_reviews = ClientReview::selectRaw('count(*) as num')
            ->where('user_id','=',$auth_user->user_id)
            ->where('rate','=','3');

        $total_two_star_reviews = ClientReview::selectRaw('count(*) as num')
            ->where('user_id','=',$auth_user->user_id)
            ->where('rate','=','2');

        $total_one_star_reviews = ClientReview::selectRaw('count(*) as num')
            ->where('user_id','=',$auth_user->user_id)
            ->where('rate','=','1');

        $totals = $get_average_reviews
            ->unionAll($total_received_reviews)
            ->unionAll($total_five_star_reviews)
            ->unionAll($total_four_star_reviews)
            ->unionAll($total_three_star_reviews)
            ->unionAll($total_two_star_reviews)
            ->unionAll($total_one_star_reviews)
            ->get();

        $rate_points = Constant::GET_RATE_SCORE_POINTS();
        $avg_reviews_received = $totals['0']->num ? round($totals['0']->num,1) : 0;
        $avg_reviews_received_star = round($avg_reviews_received);
        $total_reviews_received = intval($totals['1']->num);
        $five_start_review_percentage = $total_reviews_received ? ($totals['2']->num * 100) / $total_reviews_received : 0;
        $five_start_percentage_rounded = ceil($five_start_review_percentage);
        $four_start_review_percentage = $total_reviews_received ? ($totals['3']->num * 100) / $total_reviews_received : 0;
        $four_start_percentage_rounded = ceil($four_start_review_percentage);
        $three_start_review_percentage = $total_reviews_received ? ($totals['4']->num * 100) / $total_reviews_received : 0;
        $three_start_percentage_rounded = ceil($three_start_review_percentage);
        $two_start_review_percentage = $total_reviews_received ? ($totals['5']->num * 100) / $total_reviews_received : 0;
        $two_start_percentage_rounded = ceil($two_start_review_percentage);
        $one_start_review_percentage = $total_reviews_received ? ($totals['6']->num * 100) / $total_reviews_received : 0;
        $one_start_percentage_rounded = ceil($one_start_review_percentage);
        $reviews = ClientReview::where('user_id','=',$auth_user->user_id)
            ->orderBy('created_at','desc')
            ->paginate(Constant::GET_REVIEWS_PAGE_DISPLAY_ITEMS());

        $user_twilio_phone = UserTwilioPhone::where('user_id','=',$auth_user->user_id)->first();
        $phone_countries = Country::select('number','code')
            ->where('is_twilio','=','1')
            ->pluck('number','code');
        return view('reviews.index',compact(
            'auth_user',
            'reviews',
            'rate_points',
            'avg_reviews_received',
            'totals',
            'total_reviews_received',
            'five_start_review_percentage',
            'five_start_percentage_rounded',
            'four_start_review_percentage',
            'four_start_percentage_rounded',
            'three_start_review_percentage',
            'three_start_percentage_rounded',
            'two_start_review_percentage',
            'two_start_percentage_rounded',
            'one_start_review_percentage',
            'one_start_percentage_rounded',
            'avg_reviews_received_star',
            'user_twilio_phone',
            'phone_countries'
        ));
    }

    public function filter(Request $request)
    {
        $auth_user = request()->user();
        $reviews = ClientReview::select([
            'client_review.client_id',
            'client_review.rate',
            'client_review.reviewer_name',
            'client_review.reviewer_phone',
            'client_review.reviewer_phone_format',
            'client_review.description',
            'client_review.created_at'
        ])
            ->where('client_review.user_id','=',$auth_user->user_id);

        $rates = [];

        if ($request['five_star_filter']) {
            $rates[] = 5;
        }

        if ($request['four_star_filter']) {
            $rates[] = 4;
        }

        if ($request['three_star_filter']) {
            $rates[] = 3;
        }

        if ($request['two_star_filter']) {
            $rates[] = 2;
        }

        if ($request['one_star_filter']) {
            $rates[] = 1;
        }

        if ($rates) {
            $reviews->whereIn('client_review.rate',$rates);
        }
        else{
            $reviews->where('client_review.rate','>','5');
        }

        if ($request['written_reviews']) {
            $reviews->whereNotNull('client_review.description');
        }

        if ($request['stars_only_reviews']) {
            $reviews->whereNull('client_review.description');
        }

        if ($request['sort_by_latest']) {
            $reviews->orderBy('client_review.created_at','desc');
        }

        if ($request['sort_by_oldest']) {
            $reviews->orderBy('client_review.created_at','asc');
        }

        Paginator::currentPageResolver(function () use ($request) {
            return $request->page;
        });

        $reviews = $reviews
            ->paginate(Constant::GET_REVIEWS_PAGE_DISPLAY_ITEMS())
            ->toArray();

        $review_data = [];
        if (isset($reviews['data'])) {
            foreach ($reviews['data'] as $item) {
                $item['created_at'] = Carbon::parse($item['created_at'])->format('j F, Y');
                $review_data[] = $item;
            }
        }

        return response()->json([
            'status' => true,
            'items' => $review_data,
            'total_pages' => $reviews['last_page']
        ]);
    }

    public function setup()
    {
        $auth_user = Auth::user();
        return view('reviews.setup',compact(
            'auth_user'
        ));
    }

    public function update(Request $request)
    {
        $auth_user = Auth::user();
        $request['google_business_address'] = trim($request['google_business_address']);
        $has_google_error = false;
        if (strlen($request['google_review_address']) > 0) {
            try{
                $get_business_address = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input='.urlencode($request['google_review_address']).'&inputtype=textquery&fields=place_id&key='.env('GOOGLE_API_KEY')));
                if (isset($get_business_address->candidates['0']->place_id)) {
                    $auth_user->google_review_place_id = $get_business_address->candidates['0']->place_id;
                    $auth_user->google_review_address = $request['google_review_address'];
                }
                else{
                    $has_google_error = true;
                }
            }
            catch (\Exception $e) {

            }
        }
        else{
            $auth_user->google_review_place_id = null;
            $auth_user->google_review_address = null;
        }

        $auth_user->facebook_reviews_url = $request['facebook_reviews_url'] && filter_var($request['facebook_reviews_url'], FILTER_VALIDATE_URL) ? $request['facebook_reviews_url'] : null;
        $auth_user->update();

        if ($has_google_error) {
            return redirect()
                ->back()
                ->with('error','Unable to find the place '.$request['google_business_address']);
        }

        return redirect()
            ->back()
            ->with('success','Settings successfully updated');
    }

    public function sendInvite(Request $request)
    {
        $auth_user = Auth::user();
        if ($request['type'] == 'email') {
            if (count($request['email']) > 100) {
                return response()->json([
                    'status' => false,
                    'error' => 'Please use up to 100 emails'
                ]);
            }

            foreach ($request['email'] as $item) {
                if (!filter_var($item,FILTER_VALIDATE_EMAIL)) {
                    return response()->json([
                        'status' => false,
                        'error' => $item.' is not a valid email'
                    ]);
                }
            }

            try{
                foreach ($request['email'] as $item) {
                    /**Create invite record*/
                    $model = new ReviewInvite();
                    $model->user_id = $auth_user->user_id;
                    $model->type = 'email';
                    $model->target = $item;
                    $model->status = 'pending';
                    $model->unique_code = md5($auth_user->user_id.'review_invite'.uniqid());
                    $model->save();
                    NotificationHelper::sendLeaveReviewEmail('invite', $model->unique_code, null, $auth_user['name'], $auth_user->reviews_logo, $item);
                }
            }
            catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'error' => 'Unable to send emails, please try again later',
                    'reload' => true
                ]);
            }
        }
        else{
            $phone_country = Country::where('code','=',$request['country'])
                ->where('is_twilio','=','1')
                ->first();

            if ($phone_country) {
                $model = new ReviewInvite();
                $model->user_id = $auth_user->user_id;
                $model->phone_country_id = $phone_country->country_id;
                $model->type = 'phone';
                $model->target = $phone_country->number.preg_replace('/[^0-9.]+/', '', $request['phone']);
                $model->status = 'pending';
                $model->unique_code = md5($auth_user->user_id.'review_invite'.uniqid());

                /**Send out Twilio Message*/
                $twilio = new \Twilio\Rest\Client(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));
                $user_phone = UserTwilioPhone::where('user_id', '=', $auth_user->user_id)->where('status','=','active')->first();
                if (!$user_phone) {
                    $user_phone = new \stdClass();
                    $user_phone->phone = env('SMS_GLOBAL_NUMBER');
                }

                try{
                    $params = [
                        "body" => Helper::generateReviewSendTextMessage($auth_user, $model->unique_code),
                        "from" => $user_phone->phone
                    ];

                    $message = $twilio->messages
                        ->create($model->target,$params);

                    $model->twilio_sms_sid = $message->sid;
                    $model->save();

                    return response()->json([
                        'status' => true
                    ]);
                }
                catch (\Exception $e) {
                    return response()->json([
                        'status' => false,
                        'error' => 'Unable to send text message to that number, please double check the number or try again later'
                    ]);
                }
            }
            else{
                return response()->json([
                    'status' => false,
                    'error' => 'Not supported country'
                ]);
            }
        }

        return response()->json([
            'status' => true
        ]);
    }

    public function uploadBusinessLogo(Request $request)
    {
        if ($request->hasFile('qqfile')) {
            $file = $request->file('qqfile');
            $ext = $file->getClientOriginalExtension();
            if (in_array($ext, Constant::GET_ALLOWED_IMAGE_EXTENSIONS())) {
                if ($file->getSize() > Constant::GET_ALLOWED_UPLOAD_IMAGE_SIZE()) {
                    return response()->json([
                        'status' => false,
                        'error' => 'Please upload images less than 2mb'
                    ]);
                }

                $auth_user = Auth::user();
                if ($auth_user->reviews_logo && Storage::disk('review_logo')->exists($auth_user->reviews_logo)) {
                    Storage::disk('review_logo')->delete($auth_user->reviews_logo);
                }

                $auth_user->reviews_logo = $auth_user->user_id.uniqid() . '.' . $file->getClientOriginalExtension();
                $auth_user->update();
                Storage::disk('review_logo')->put($auth_user->reviews_logo, File::get($file));
                return response()->json([
                    'status' => true,
                    'file_name' => $auth_user->reviews_logo
                ]);
            }
            else{
                return response()->json([
                    'status' => false,
                    'error' => 'Please upload only jpg or png images'
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'File is not supported'
        ]);
    }

    public function removeBusinessLogo(Request $request)
    {
        $auth_user = Auth::user();
        if ($auth_user->reviews_logo && Storage::disk('review_logo')->exists($auth_user->reviews_logo)) {
            Storage::disk('review_logo')->delete($auth_user->reviews_logo);
        }

        $auth_user->reviews_logo = null;
        $auth_user->update();
        return response()->json([
            'status' => true
        ]);
    }
}
