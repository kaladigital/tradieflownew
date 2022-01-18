<?php

namespace App\Http\Controllers;
use App\Helpers\Helper;
use App\Helpers\NotificationHelper;
use App\Models\Country;
use App\Models\ReviewInvite;
use App\Models\UserReferralCode;
use App\Models\UserReferralEmailSentLog;
use App\Models\UserTwilioPhone;
use Auth;
use Illuminate\Http\Request;

class ReferralsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('active_subscription');
    }

    public function index()
    {
        $auth_user = Auth::user();
        $phone_countries = Country::select('number','code')->where('is_twilio','=','1')->pluck('number','code');
        $user_referral_code = UserReferralCode::where('user_id','=',$auth_user->user_id)->where('type','=','tradieflow')->first();
        /**Referral Codes*/
        if (!$user_referral_code) {
            $user_referral_code = new UserReferralCode();
            $user_referral_code->user_id = $auth_user->user_id;
            $user_referral_code->type = 'tradieflow';
            $user_referral_code->referral_code = md5(uniqid().env('APP_KEY').$auth_user->user_id);
            $user_referral_code->save();
        }

        $referral_share_url = env('APP_URL').'/referrals/'.$user_referral_code->referral_code;
        return view('referrals.index',compact(
            'auth_user',
            'phone_countries',
            'referral_share_url'
        ));
    }

    public function referralInvite(Request $request)
    {
        $auth_user = Auth::user();
        $user_referral_code = UserReferralCode::where('user_id','=',$auth_user->user_id)->where('type','=','tradieflow')->first();
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

            $get_referral_code = UserReferralCode::where('user_id','=',$auth_user->user_id)
                ->where('type','tradieflow')
                ->first();

            foreach ($request['email'] as $item) {
                NotificationHelper::sendSignupReferralEmailInvite($auth_user, $item, $get_referral_code->referral_code);
            }

            /**Update Email Sent Logs*/
            $user_referral_email_log = UserReferralEmailSentLog::where('user_id','=',$auth_user->user_id)
                ->where('type','=','tradieflow')
                ->first();

            if (!$user_referral_email_log) {
                $user_referral_email_log = new UserReferralEmailSentLog();
                $user_referral_email_log->user_id = $auth_user->user_id;
                $user_referral_email_log->type = 'tradieflow';
                $user_referral_email_log->total_sent = 0;
            }

            $total_emails_sent = count($request['email']);
            $user_referral_email_log->total_sent += $total_emails_sent;
            $user_referral_email_log->save();
        }
        else{
            $phone_country = Country::where('code','=',$request['country'])->where('is_twilio','=','1')->first();
            if ($phone_country) {
                $target_phone = $phone_country->number.preg_replace('/[^0-9.]+/', '', $request['phone']);

                /**Send out Twilio Message*/
                $twilio = new \Twilio\Rest\Client(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));
                $user_phone = UserTwilioPhone::where('user_id', '=', $auth_user->user_id)->where('status','=','active')->first();
                if (!$user_phone) {
                    $user_phone = new \stdClass();
                    $user_phone->phone = env('SMS_GLOBAL_NUMBER');
                }

                try{
                    $params = [
                        "body" => Helper::generateSignupReferralSendTextMessage($auth_user, $user_referral_code->referral_code),
                        "from" => $user_phone->phone
                    ];

                    $message = $twilio->messages
                        ->create($target_phone,$params);

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
}
