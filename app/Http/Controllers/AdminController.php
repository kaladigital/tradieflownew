<?php

namespace App\Http\Controllers;

use App\Helpers\Constant;
use App\Helpers\Helper;
use App\Helpers\NotificationHelper;
use App\Models\Country;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserAdminNote;
use App\Models\UserGiveawayReferral;
use App\Models\UserOnboarding;
use App\Models\UserReferralCode;
use App\Models\UserSubscription;
use App\Models\UserTwilioPhone;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin_auth');
    }

    /**
     * Display a dashboard of the resource.
     *
     * @return Response
     */
    public function dashboard()
    {
        $auth_user = Auth::user();
        return view('admin.dashboard', compact(
            'auth_user'
        ));
    }

    public function generate()
    {
        $auth_user = Auth::user();
        $country_id = Country::where('is_twilio','=','1')
            ->pluck('name','country_id');

        $users = User::select('user.*','user_twilio_phone.phone',
            'user_onboarding.status as onboarding_status',
            'user_onboarding.created_at as onboarding_created',
            'user_onboarding.updated_at as onboarding_updated',
        )
            ->leftJoin('user_twilio_phone','user_twilio_phone.user_id','=','user.user_id')
            ->leftJoin('user_onboarding','user_onboarding.user_id','=','user.user_id')
            ->where('user.is_lead','=','1')
            ->orderBy('user.created_at','desc')
            ->get();

        return view('admin.generate',compact(
            'auth_user',
            'country_id',
            'users'
        ));
    }

    public function processLeadGeneration(Request $request)
    {
        $has_email = User::where('email','=',$request['email'])->count();
        if ($has_email) {
            return response()->json([
                'status' => false,
                'error' => 'Email already taken'
            ]);
        }

        if (!filter_var($request['email'],FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'status' => false,
                'error' => 'Email not valid'
            ]);
        }

        $phone_country = Country::where('is_twilio','=','1')->find($request['country_id']);
        if (!$phone_country) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error','Selected country code not supported');
        }

        $twilio_country_availability = Constant::GET_TWILIO_COUNTRY_AVAILABLE_FILTERS();

        try {
            $twilio = new \Twilio\Rest\Client(env("TWILIO_ACCOUNT_SID"), env("TWILIO_AUTH_TOKEN"));;
            $local = $twilio->availablePhoneNumbers(strtoupper($phone_country->code))
                ->{$twilio_country_availability[$phone_country->code]['type']}
                ->read([
                    $twilio_country_availability[$phone_country->code]['capabilities']
                ], 1);

            $incoming_phone_number = $twilio->incomingPhoneNumbers
                ->create([
                    "phoneNumber" => $local['0']->phoneNumber,
                    "smsMethod" => "POST",
                    "smsUrl" => env('APP_URL') . "/api/twilio/incoming/text",
                    "voiceUrl" => env('TWILIO_VOICE_WEBHOOK_URL'),
                    "addressSid" => 'ADb77faaf6ee88c0b6554fd256040735ea'
                ]);

            $twilio_phone_obj = [
                'friendly_name' => $local['0']->friendlyName,
                'phone' => $local['0']->phoneNumber,
                'country_code' => $phone_country->code,
                'twilio_address_sid' => $incoming_phone_number->addressSid,
                'twilio_bundle_sid' => $incoming_phone_number->bundleSid,
                'twilio_sid' => $incoming_phone_number->sid,
                'type' => $twilio_country_availability[$phone_country->code]['type']
            ];
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Unable to register a new number, please contact support'
            ]);
        }

        $model = new User();
        $model->name = $request['name'];
        $model->email = $request['email'];
        $model->active = '1';
        $model->mobile_login_key = '';
        $model->role = 'user';
        $model->country_id = $request['country_id'];
        $model->name_initials = Helper::generateInitials($request['name']);
        $model->remember_token = null;
        $model->has_email_verified = '1';
        $model->password = bcrypt($request['password']);
        $model->twilio_password = $request['password'];
        $model->is_lead = '1';
        $model->twilio_password = $request['password'];
        $model->public_reviews_code = uniqid();
        $model->mobile_onboarding_completed = '1';
        $model->mobile_walkthrough_completed = '1';
        $model->currency = $phone_country->code == 'au' ? 'aud' : 'usd';

        $week_nums = Constant::GET_WEEK_DAYS();
        foreach ($week_nums as $key => $item) {
            $model->{$key} = '1';
            $model->{$key.'_start'} = '09:00';
            $model->{$key.'_end'} = '23:00';
        }

        $subscription_plan = SubscriptionPlan::where('plan_code','=','trial')->where('type','=','tradieflow')->first();
        $has_more_days = $subscription_plan->duration_num == 1 ? 's' : '';
        $model->tradieflow_subscription_expire_message = $subscription_plan->duration_num.' day'.($has_more_days ? 's' : '').' remaining of your free trial';
        $model->save();

        /**Referral Codes*/
        $user_referral_code = new UserReferralCode();
        $user_referral_code->user_id = $model->user_id;
        $user_referral_code->type = 'tradieflow';
        $user_referral_code->referral_code = md5(uniqid().env('APP_KEY').$model->user_id);
        $user_referral_code->save();

        /**Create Twilio Number*/
        $twilio_phone_obj['user_id'] = $model->user_id;
        UserTwilioPhone::create($twilio_phone_obj);

        /**Create Unique Name for Twilio*/
        $model->twilio_company_unique_name = 'pedestal_'.Carbon::now()->timestamp.$model->user_id;
        $model->public_reviews_code = $model->user_id.uniqid();
        $model->update();

        /**Create Subscription*/
        $user_subscription = new UserSubscription();
        $user_subscription->user_id = $model->user_id;
        $user_subscription->subscription_plan_id = $subscription_plan->subscription_plan_id;
        $user_subscription->subscription_plan_name = $subscription_plan->name;
        $user_subscription->subscription_plan_code = $subscription_plan->plan_code;
        $user_subscription->expiry_date_time = Carbon::now()->addDays($subscription_plan->duration_num)->format('Y-m-d H:i:s');
        $user_subscription->active = '1';
        $user_subscription->price = '0';
        $user_subscription->type = 'tradieflow';
        $user_subscription->currency = $model->currency;
        $user_subscription->save();

        /**Create Onboarding*/
        $user_onboarding = new UserOnboarding();
        $user_onboarding->user_id = $model->user_id;
        $user_onboarding->status = 'pending';
        $user_onboarding->type = 'tradieflow';
        $user_onboarding->save();

        /**Create ActiveCampaign Log*/
        try{
            Helper::addActiveCampaignQueueItem($model->user_id,$model->email,'trial_tag');
        }
        catch (\Exception $e) {

        }

        return response()->json([
            'status' => true,
            'phone' => $twilio_phone_obj['phone']
        ]);
    }

    public function referrals(Request $request)
    {
        $auth_user = Auth::user();
        $referrals = UserGiveawayReferral::select('*')
            ->selectRaw('case when status = "accepted" then 1 else 0 end as accepted_stage')
            ->orderBy('created_at','desc');
        $referral_months = Constant::GET_ADMIN_REFERRAL_GIVEAWAY_MONTHS();

        switch ($request['sort_by']) {
            case 'name_asc':
                $referrals->orderBy('name','asc');
            break;
            case 'name_desc':
                $referrals->orderBy('name', 'desc');
            break;
            case 'email_asc':
                $referrals->orderBy('email', 'asc');
            break;
            case 'email_desc':
                $referrals->orderBy('email', 'desc');
            break;
            case 'type_asc':
                $referrals->orderBy('months', 'asc');
            break;
            case 'type_desc':
                $referrals->orderBy('months', 'desc');
            break;
            case 'sent_asc':
                $referrals->orderBy('created_at', 'asc');
            break;
            case 'sent_desc':
                $referrals->orderBy('created_at', 'desc');
            break;
            case 'status_asc':
                $referrals->orderBy('status', 'asc');
                break;
            case 'status_desc':
                $referrals->orderBy('status', 'desc');
            break;
            case 'accepted_asc':
                $referrals
                    ->orderBy('accepted_stage','asc')
                    ->orderBy('updated_at','asc');
            break;
            case 'accepted_desc':
                $referrals
                    ->orderBy('accepted_stage','desc')
                    ->orderBy('updated_at','desc');
            break;
        }

        $referrals = $referrals
            ->groupBy('user_giveaway_referral_id')
            ->paginate(10);

        return view('admin.referrals',compact(
            'auth_user',
            'referrals',
            'referral_months',
            'request'
        ));
    }

    public function sendReferral(Request $request)
    {
        $auth_user = request()->user();
        if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error','Please type a valid email');
        }

        if (!$request['name']) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error','Please specify first name');
        }

        if (!array_key_exists($request['total_months'],Constant::GET_ADMIN_REFERRAL_GIVEAWAY_MONTHS())) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error','Please specify first name');
        }

        /**Check if we have a user*/
        $user = User::where('email','=',$request['email'])
            ->orWhere('email','=',strtolower($request['email']))
            ->orWhere('email','=',strtoupper($request['email']))
            ->first();

        /**Save queue*/
        $model = new UserGiveawayReferral();
        $model->user_id = ($user) ? $user->user_id : null;
        $model->name = $request['name'];
        $model->email = $request['email'];
        $model->code = md5($auth_user->user_id.uniqid().rand(1,100));
        $model->status = 'pending';
        $model->months = $request['total_months'];
        $model->save();

        /**Email User*/
        NotificationHelper::sendAdminGiveawayReferral($request['name'], $request['total_months'], $model->code, $request['email']);
        return redirect()
            ->back()
            ->with('success','Invitation sent successfully');
    }

    public function user()
    {
        $auth_user = Auth::user();
        $users = User::with('UserInvoiceSetting')
            ->select('user.*','user_twilio_phone.phone','user_admin_note.note','user_admin_note.status as admin_status')
            ->selectRaw('group_concat(industry.name SEPARATOR ", ") as industries')
            ->selectRaw('group_concat(business_type.name SEPARATOR ", ") as business_types')
            ->leftJoin('user_industry','user_industry.user_id','=','user.user_id')
            ->leftJoin('industry','industry.industry_id','=','user_industry.industry_id')
            ->leftJoin('user_business_type','user_business_type.user_id','=','user.user_id')
            ->leftJoin('business_type','business_type.business_type_id','=','user_business_type.business_type_id')
            ->leftJoin('user_admin_note','user_admin_note.user_id','=','user.user_id')
            ->where('user.role','=','user')
            ->where('user.active','=','1')
            ->leftJoin('user_subscription',function($query){
                $query
                    ->on('user_subscription.user_id','=','user.user_id')
                    ->where('user_subscription.type','=','tradieflow');
            })
            ->leftJoin('user_twilio_phone',function($query){
                $query
                    ->on('user_twilio_phone.user_id','=','user.user_id')
                    ->where('user_twilio_phone.status','active');
            })
            ->whereNotNull('user_subscription.user_subscription_id')
            ->groupBy('user.user_id')
            ->get();

        $admin_user_statuses = Constant::GET_ADMIN_USER_STATUSES();
        return view('admin.user',compact(
            'auth_user',
            'users',
            'admin_user_statuses'
        ));
    }

    public function updateUserStatus(Request $request)
    {
        $user = User::select('user.*')
            ->where('user.role','=','user')
            ->where('user.active','=','1')
            ->leftJoin('user_subscription',function($query){
                $query
                    ->on('user_subscription.user_id','=','user.user_id')
                    ->where('user_subscription.type','=','tradieflow');
            })
            ->whereNotNull('user_subscription.user_subscription_id')
            ->find($request['user_id']);

        if ($user && array_key_exists($request['status'],Constant::GET_ADMIN_USER_STATUSES())) {
            $user_note = UserAdminNote::where('user_id','=',$user->user_id)->first();
            if (!$user_note) {
                $user_note = new UserAdminNote();
                $user_note->user_id = $user->user_id;
            }

            $user_note->status = $request['status'];
            $user_note->save();

            return response()->json([
                'status' => true
            ]);
        }

        return response()->json([
            'status' => false,
            'error' => 'Something went wrong'
        ]);
    }

    public function updateUserNote(Request $request)
    {
        $user = User::select('user.*')
            ->where('user.role','=','user')
            ->where('user.active','=','1')
            ->leftJoin('user_subscription',function($query){
                $query
                    ->on('user_subscription.user_id','=','user.user_id')
                    ->where('user_subscription.type','=','tradieflow');
            })
            ->whereNotNull('user_subscription.user_subscription_id')
            ->find($request['user_id']);

        if ($user) {
            $user_note = UserAdminNote::where('user_id','=',$user->user_id)->first();
            if (!$user_note) {
                $user_note = new UserAdminNote();
                $user_note->user_id = $user->user_id;
            }

            $user_note->note = $request['note'] ? $request['note'] : null;
            $user_note->save();
            return response()->json([
                'status' => true
            ]);
        }

        return response()->json([
            'status' => false,
            'error' => 'Something went wrong'
        ]);
    }

    public function impersonate($user_id)
    {
        $user = User::select('user.*')
            ->leftJoin('user_subscription',function($query){
                $query
                    ->on('user_subscription.user_id','=','user.user_id')
                    ->where('user_subscription.type','=','tradieflow');
            })
            ->whereNotNull('user_subscription.user_subscription_id')
            ->where('role','=','user')
            ->find($user_id);

        if ($user) {
            Session::flush();
            Auth::logout();
            Auth::loginUsingId($user->user_id);
            return redirect('settings/account');
        }

        return redirect()
            ->back()
            ->with('error','User not found');
    }
}
