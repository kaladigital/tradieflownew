<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Country;
use App\Models\Notification;
use App\Models\SpecialOfferPagePurchase;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserGiveawayReferral;
use App\Models\UserOnboarding;
use App\Models\UserReferralCode;
use App\Models\UserRegisterQueue;
use App\Models\UserSubscription;
use App\Models\UserTwilioPhone;
use Illuminate\Http\Request;
use App\Helpers\Constant;
use App\Helpers\Helper;
use App\Helpers\NotificationHelper;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Config;
use Session;


class AuthController extends Controller
{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => [
            'getLogout',
            'getLogin'
        ]]);
    }


    public function getLogin()
    {
        /**Should be changed in next updates*/
        if (Auth::guest()) {
            $auth_user = null;
            return view('auth.login', compact(
                'auth_user'
            ));
        }
        else {
            return redirect('/');
        }
    }

    public function postLogin(Request $request)
    {
        if (Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
            $auth_user = Auth::user();
            if (!$auth_user->active) {
                $this->deleteLoggedInSessions();
                return redirect()->back()
                    ->with('error', 'Your account is not active');
            }

            if (!$auth_user->has_email_verified) {
                $this->deleteLoggedInSessions();
                return redirect()
                    ->back()
                    ->with('error', 'Your account pending activation, please check your inbox');
            }

            $has_subscriptions = UserSubscription::where('user_id','=',$auth_user->user_id)
                ->where('type','=','tradieflow')
                ->count();

            if (!$has_subscriptions) {
                $this->deleteLoggedInSessions();
                return redirect()
                    ->back()
                    ->with('error', 'You did not purchase this product please contact support');
            }

            if (!$auth_user->desktop_first_login_date_time) {
                $auth_user->desktop_first_login_date_time = Carbon::now()->format('Y-m-d H:i:s');
                $auth_user->update();
            }

            return redirect()->intended('settings/account');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Oops login failed');
    }

    public function deleteLoggedInSessions()
    {
        Session::flush();
        Auth::logout();
    }

    public function getLogout()
    {
        $this->deleteLoggedInSessions();
        return redirect('/');
    }

    public function getPasswordReset()
    {
        $auth_user = null;
        return view('auth.password_reset', compact(
            'auth_user'
        ));
    }

    public function postPasswordReset(Request $request)
    {
        $user = User::where('email', '=', $request['email'])->first();
        if (!$user) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Email not found in system');
        }

        if (!$user->active) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Unfortunately your account has been disabled');
        }

        if (!strlen($user->remember_token)) {
            $user->remember_token = hash('md5', bcrypt('password reset' . uniqid() . $user->user_id));
        }

        /**Generate OTP Code*/
        $user->otp_code = Helper::generateUniqueFourDigits();
        $user->otp_created_date = Carbon::now()->format('Y-m-d H:i:s');
        $user->remember_token = null;
        $user->save();

        /**Send out email*/
        NotificationHelper::resetPassword($user);
        Session::put('password_change_user_id',$user->user_id);
        Session::save();
        return redirect('auth/forgot-password/verify');
    }

    public function verifyPassword()
    {
        $password_user_id = Session::get('password_change_user_id');
        if ($password_user_id) {
            $user = User::find($password_user_id);

            if ($user) {
                if (!$user->active) {
                    return redirect('auth/login')
                        ->with('error', 'Unfortunately your account has been disabled');
                }

                if (!$user->has_email_verified) {
                    $user->has_email_verified = '1';

                    if (!$user->desktop_first_login_date_time) {
                        $user->desktop_first_login_date_time = Carbon::now()->format('Y-m-d H:i:s');
                    }

                    $user->update();
                }

                return view('auth.verify_change_password');
            }
        }
        else{
            return redirect('auth/login');
        }

        return redirect('auth/login');
    }

    public function checkPasswordVerificationCode(Request $request)
    {
        $password_user_id = Session::get('password_change_user_id');
        if (!$password_user_id) {
            return response()->json([
                'status' => false,
                'reload' => true
            ]);
        }

        $user = User::where('otp_code','=',$request['code'])->find($password_user_id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'error' => 'Wrong code'
            ]);
        }

        if ($user && !$user->active) {
            Session::forget('password_change_user_id');
            return response()->json([
                'status' => false,
                'reload' => true
            ]);
        }

        Session::forget('password_change_user_id');
        Session::put('verified_otp_code',$request['code']);
        Session::save();

        return response()->json([
            'status' => true
        ]);
    }

    public function resetPassword()
    {
        Session::forget('password_change_user_id');
        $verified_code = Session::get('verified_otp_code');
        if (!$verified_code) {
            return redirect('auth/login');
        }

        $user = User::where('otp_code', '=', $verified_code)->where('active','=','1')->first();
        if (!$user) {
            return redirect('auth/login')
                ->with('error','Code expired');
        }

        return view('auth.password_change');
    }

    public function saveNewPassword(Request $request)
    {
        $verified_code = Session::get('verified_otp_code');
        if (!$verified_code) {
            return redirect('auth/login');
        }

        $user = User::where('otp_code', '=', $verified_code)->where('active','=','1')->first();
        if (!$user) {
            return redirect('auth/login')
                ->with('error','Code expired');
        }

        Session::forget('password_change_user_id');
        Session::forget('verified_otp_code');

        $user->password = bcrypt($request['password']);
        $user->twilio_password = $request['password'];
        $user->remember_token = null;
        $user->has_email_verified = '1';
        $user->otp_code = null;
        $user->otp_created_date = null;
        $user->save();

        Auth::loginUsingId($user->user_id);
        return redirect('settings/account');
    }

    public function startFreeTrial(Request $request)
    {
        $all_countries = Country::pluck('name','country_id');
//        $twilio_countries = Country::select('country_id', 'name')
//            ->where('is_twilio','=','1')
//            ->selectRaw('concat(number," ",name) as country_name')
//            ->where('code','=','us')
//            ->pluck('country_name','country.country_id')
//            ->toArray();

        if (strlen($request['ref'])) {
            /**Check User Referrals*/
            $check_referral = UserReferralCode::where('referral_code','=',$request['ref'])->where('type','=','tradieflow')->first();
            if (!$check_referral) {
                /**Check Admin Referrals*/
                $admin_referral = UserGiveawayReferral::where('code','=',$request['ref'])->where('status','=','pending')->first();
                if (!$admin_referral) {
                    return redirect('free-trial');
                }
            }
        }

        $twilio_error = Session::get('twilio_error');
        if ($twilio_error) {
            Session::forget('twilio_error');
        }

        return view('auth.start_free_trial',compact(
            'all_countries',
//            'twilio_countries',
            'twilio_error',
            'request'
        ));
    }

    public function postStartFreeTrial(RegisterRequest $request)
    {
        if (!filter_var($request['email'],FILTER_VALIDATE_EMAIL)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error','Email not found valid');
        }

        if ($request['country_id']) {
            $country = Country::find($request['country_id']);
            $request['country_id'] = ($country) ? $country->country_id : null;
        }

        $phone_country = Country::where('is_twilio','=','1')->where('code','=','us')->first();
        $address_sid = null;
        $twilio = new \Twilio\Rest\Client(env("TWILIO_ACCOUNT_SID"), env("TWILIO_AUTH_TOKEN"));
        $twilio_country_availability = Constant::GET_TWILIO_COUNTRY_AVAILABLE_FILTERS();
        $local = $twilio->availablePhoneNumbers(strtoupper($phone_country->code))
            ->{$twilio_country_availability[$phone_country->code]['type']}
            ->read([
                $twilio_country_availability[$phone_country->code]['capabilities']
            ], 1);

        $phone_create_options = [
            "phoneNumber" => $local['0']->phoneNumber,
            "smsMethod" => "POST",
            "smsUrl" => env('APP_URL') . "/api/twilio/incoming/text",
            "voiceUrl" => env('TWILIO_VOICE_WEBHOOK_URL')
        ];

        if ($address_sid) {
            $phone_create_options['addressSid'] = $address_sid;
        }

        if (env('APP_ENV') == 'local') {
            $twilio_phone_obj = [];
        }
        else{
            $incoming_phone_number = $twilio->incomingPhoneNumbers
                ->create($phone_create_options);

            if (isset($incoming_phone_number->sid) && $incoming_phone_number->sid) {
                $twilio_phone_obj = [
                    'friendly_name' => $local['0']->friendlyName,
                    'phone' => $local['0']->phoneNumber,
                    'country_code' => strtolower($phone_country->code),
                    'twilio_address_sid' => $incoming_phone_number->addressSid,
                    'twilio_bundle_sid' => $incoming_phone_number->bundleSid,
                    'twilio_sid' => $incoming_phone_number->sid,
                    'type' => $twilio_country_availability[$phone_country->code]['type']
                ];
            }
            else{
                Session::put('twilio_error', 'Unable to register a new number, please try again later');
                Session::save();
                return redirect()
                    ->back()
                    ->withInput();
            }
        }

        $other_params = [
            'country_id' => $request['country_id'],
            'mobile_onboarding_completed' => '1'
        ];

        $referral = Session::get('signup_referral_code');
        $model = Helper::signupUser('desktop',$request, $other_params, $twilio_phone_obj, $referral);

        /**Send out verification email*/
        $notification = Notification::where('object_name', '=', 'registerVerification')
            ->where('active', '=', '1')
            ->first();

        if ($notification) {
            NotificationHelper::registerVerify($notification, $model);
            Session::put('verify_user_id',$model->user_id);
            Session::put('ga_signup_trigger',true);
            Session::save();
            return redirect('register/verify');
        }
    }

    public function register()
    {
        return view('auth.register_version_one');
    }

    public function verifyRegister()
    {
        $verify_user_id = Session::get('verify_user_id');
        if (!$verify_user_id) {
            return redirect('auth/login');
        }

        $ga_signup_trigger = Session::get('ga_signup_trigger');
        if ($ga_signup_trigger) {
            Session::forget('ga_signup_trigger');
        }

        return view('auth.verify_register',compact(
            'ga_signup_trigger'
        ));
    }

    public function verifyRegisterCheck(Request $request)
    {
        $verify_user_id = Session::get('verify_user_id');
        if (!$verify_user_id) {
            return response()->json([
                'status' => false,
                'reload' => true
            ]);
        }

        $user = User::where('otp_code','=',$request['code'])
            ->find($verify_user_id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'error' => 'Wrong Code'
            ]);
        }

        if (!$user->active) {
            return response()->json([
                'status' => false,
                'reload' => true
            ]);
        }

        $user->otp_code = null;
        $user->otp_created_date = null;
        $user->has_email_verified = '1';
        $user->update();

        Session::put('verify_user_id',$user->user_id);
        Session::save();

        return response()->json([
            'status' => true
        ]);
    }

    public function setRegisterPassword()
    {
        $verify_user_id = Session::get('verify_user_id');
        if (!$verify_user_id) {
            return response()->json([
                'status' => false,
                'reload' => true
            ]);
        }

        return view('auth.register_new_password');
    }

    public function saveRegisterNewPassword(Request $request)
    {
        $verify_user_id = Session::get('verify_user_id');
        if (!$verify_user_id) {
            return redirect('auth/login');
        }

        $user = User::where('active','=','1')->find($verify_user_id);
        if (!$user) {
            return redirect('auth/login');
        }

        $user->password = bcrypt($request['password']);
        $user->twilio_password = $request['password'];
        if (!$user->desktop_first_login_date_time) {
            $user->desktop_first_login_date_time = Carbon::now()->format('Y-m-d H:i:s');
        }
        $user->update();

        Auth::loginUsingId($user->user_id);
        return redirect('settings');
    }

    public function postRegister(Request $request)
    {
        /**Check if we have a user*/
        $has_user = User::where('email','=',$request['email'])
            ->orWhere('email','=',strtolower($request['email']))
            ->orWhere('email','=',strtoupper($request['email']))
            ->count();

        $special_offer_user = SpecialOfferPagePurchase::where('email','=',$request['email'])
            ->where('plan_code','!=','hosting_reviews')
            ->where('status','=','paid')
            ->first();

        if ($has_user) {
            $allow_continue = false;
            if ($special_offer_user) {
                $user = User::where('email', '=', $request['email'])->first();
                if ($user) {
                    $has_subscription = UserSubscription::where('user_id', '=', $user->user_id)
                        ->where('type', '=', 'tradieflow')
                        ->first();

                    if ($has_subscription) {
                        Session::flush();
                        return response()->json([
                            'status' => false,
                            'error' => 'You already have an account, please login',
                            'redirect' => '/auth/login'
                        ]);
                    }
                }

                $allow_continue = true;
            }

            if (!$allow_continue) {
                return response()->json([
                    'status' => false,
                    'error' => 'Email already taken'
                ]);
            }
        }

        if (!filter_var($request['email'],FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'status' => false,
                'error' => 'Please type valid email address'
            ]);
        }

        UserRegisterQueue::where('email','=',$request['email'])
            ->where('type','=','tradieflow')
            ->delete();

        $model = new UserRegisterQueue();
        $model->email = $request['email'];
        $model->verify_code = rand(1000,9999);
        $model->type = 'tradieflow';
        $model->referral_code = Session::get('signup_referral_code');
        $model->save();

        Session::forget('signup_referral_code');
        Session::put('signup_user',[
            'email' => $request['email'],
            'verified' => false,
            'password_set' => false
        ]);

        NotificationHelper::registerVersionVerify($model->verify_code,$model->email);

        return response()->json([
            'status' => true
        ]);
    }

    public function verifyRegisterVersion()
    {
        $signup_user = Session::get('signup_user');
        if (!$signup_user) {
            return redirect()->back();
        }

        if ($signup_user['verified']) {
            return redirect('free-trial-2/password');
        }

        return view('auth.register_version_verify',compact(
            'signup_user'
        ));
    }

    public function verifyRegisterProcess(Request $request)
    {
        $signup_user = Session::get('signup_user');
        if ($signup_user) {
            $check_code = UserRegisterQueue::where('email','=',$signup_user['email'])
                ->where('verify_code','=',$request['code'])
                ->where('type','=','tradieflow')
                ->first();

            if ($check_code) {
                Session::put('signup_user',[
                    'email' => $signup_user['email'],
                    'verified' => true,
                    'password_set' => false
                ]);
                return response()->json([
                    'status' => true
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'Wrong code entered'
        ]);
    }

    public function setRegisterVersionPassword()
    {
        $signup_user = Session::get('signup_user');
        if (!$signup_user) {
            return redirect('register');
        }

        if (!$signup_user['verified']) {
            return redirect('free-trial-2/verify');
        }

        return view('auth.register_version_password');
    }

    public function registerVersionSetPassword(Request $request)
    {
        $signup_user = Session::get('signup_user');
        if (!$signup_user) {
            return response()->json([
                'status' => false,
                'redirect' => '/register'
            ]);
        }

        if (!$signup_user['verified']) {
            return response()->json([
                'status' => false,
                'redirect' => 'free-trial-2/verify'
            ]);
        }

        $user = UserRegisterQueue::where('email','=',$signup_user['email'])
            ->where('type','=','tradieflow')
            ->first();

        if ($user) {
            if (!$user->name) {
                $user->name = (strlen($request['name'])) ? $request['name'] : null;
            }
            $user->password = bcrypt($request['password']);
            $user->update();

            Session::put('signup_user',[
                'email' => $signup_user['email'],
                'verified' => true,
                'password_set' => true
            ]);

            return response()->json([
                'status' => true
            ]);
        }

        return response()->json([
            'status' => false,
            'redirect' => '/register'
        ]);
    }

    public function registerStep1()
    {
        $signup_user = Session::get('signup_user');
        if (!$signup_user) {
            return redirect('register');
        }

        if (!$signup_user['verified']) {
            return redirect('free-trial-2/verify');
        }

        if (!$signup_user['password_set']) {
            return redirect('free-trial-2/password');
        }

        $user = UserRegisterQueue::where('email','=',$signup_user['email'])
            ->where('type','=','tradieflow')
            ->first();

        if (!$user) {
            return redirect('register');
        }

        return view('auth.register_version_step1',compact(
            'user'
        ));
    }

    public function registerProcessStep1(Request $request)
    {
        $signup_user = Session::get('signup_user');
        if (!$signup_user) {
            return response()->json([
                'status' => false,
                'redirect' => '/register'
            ]);
        }

        if (!$signup_user['verified']) {
            return response()->json([
                'status' => false,
                'redirect' => '/free-trial-2/verify'
            ]);
        }

        if (!$signup_user['password_set']) {
            return response()->json([
                'status' => false,
                'redirect' => '/free-trial-2/password'
            ]);
        }

        $user = UserRegisterQueue::where('email','=',$signup_user['email'])
            ->where('type','=','tradieflow')
            ->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'redirect' => '/register'
            ]);
        }

        $user->name = $request['name'];
        $user->update();
        return response()->json([
            'status' => true
        ]);
    }

    public function registerStep2()
    {
        $signup_user = Session::get('signup_user');
        if (!$signup_user) {
            return redirect('register');
        }

        if (!$signup_user['verified']) {
            return redirect('free-trial-2/verify');
        }

        if (!$signup_user['password_set']) {
            return redirect('free-trial-2/password');
        }

        $user = UserRegisterQueue::where('email','=',$signup_user['email'])
            ->where('type','=','tradieflow')
            ->first();

        if (!$user) {
            return redirect('register');
        }

        if (!strlen($user->name)) {
            return redirect('free-trial-2/step/1');
        }

        return view('auth.register_version_step2',compact(
            'user'
        ));
    }

    public function registerProcessStep2(Request $request)
    {
        if (!$request['company']) {
            return response()->json([
                'status' => false,
                'redirect' => '/free-trial-2/step/2'
            ]);
        }

        $signup_user = Session::get('signup_user');
        if (!$signup_user) {
            return response()->json([
                'status' => false,
                'redirect' => '/register'
            ]);
        }

        if (!$signup_user['verified']) {
            return response()->json([
                'status' => false,
                'redirect' => '/free-trial-2/verify'
            ]);
        }

        if (!$signup_user['password_set']) {
            return response()->json([
                'status' => false,
                'redirect' => '/free-trial-2/password'
            ]);
        }

        $user = UserRegisterQueue::where('email','=',$signup_user['email'])
            ->where('type','=','tradieflow')
            ->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'redirect' => '/register'
            ]);
        }

        if (!strlen($user->name)) {
            return response()->json([
                'status' => false,
                'redirect' => '/free-trial-2/step/1'
            ]);
        }

        $user->company = $request['company'];
        $user->update();
        return response()->json([
            'status' => true
        ]);
    }

    public function registerStep3()
    {
        $signup_user = Session::get('signup_user');
        if (!$signup_user) {
            return redirect('register');
        }

        if (!$signup_user['verified']) {
            return redirect('free-trial-2/verify');
        }

        if (!$signup_user['password_set']) {
            return redirect('free-trial-2/password');
        }

        $user = UserRegisterQueue::where('email','=',$signup_user['email'])
            ->where('type','=','tradieflow')
            ->first();

        if (!$user) {
            return redirect('register');
        }

        if (!strlen($user->name)) {
            return redirect('free-trial-2/step/1');
        }

        if (!strlen($user->company)) {
            return redirect('free-trial-2/step/2');
        }

        $countries = Country::where('is_twilio','=','1')
            ->get();

        $geo_country = Helper::GET_GEO_COUNTRY_IP();
        $country_code = (isset($geo_country['geoplugin_countryCode'])) ? strtolower($geo_country['geoplugin_countryCode']) : '';
        return view('auth.register_version_step3',compact(
            'user',
            'countries',
            'country_code'
        ));
    }

    public function registerProcessStep3(Request $request)
    {
        $signup_user = Session::get('signup_user');
        if (!$signup_user) {
            return response()->json([
                'status' => false,
                'redirect' => '/register'
            ]);
        }

        if (!$signup_user['verified']) {
            return response()->json([
                'status' => false,
                'redirect' => '/free-trial-2/verify'
            ]);
        }

        if (!$signup_user['password_set']) {
            return response()->json([
                'status' => false,
                'redirect' => '/free-trial-2/password'
            ]);
        }

        $user_queue = UserRegisterQueue::where('email','=',$signup_user['email'])
            ->where('type','=','tradieflow')
            ->first();

        if (!$user_queue) {
            return response()->json([
                'status' => false,
                'redirect' => '/register'
            ]);
        }

        if (!strlen($user_queue->name)) {
            return response()->json([
                'status' => false,
                'redirect' => '/free-trial-2/step/1'
            ]);
        }

        if (!strlen($user_queue->company)) {
            return response()->json([
                'status' => false,
                'redirect' => '/free-trial-2/step/2'
            ]);
        }

        /**Check if we have a user*/
        $has_user = User::where('email','=',$user_queue->email)
            ->orWhere('email','=',strtolower($user_queue->email))
            ->orWhere('email','=',strtoupper($user_queue->email))
            ->count();

        $user = [];
        $currency = 'usd';
        $special_offer_user = SpecialOfferPagePurchase::where('email','=',$user_queue->email)
            ->where('plan_code','!=','hosting_reviews')
            ->where('status','=','paid')
            ->first();

        if ($has_user) {
            $allow_continue = false;
            if ($special_offer_user) {
                $user = User::where('email','=',$user_queue->email)->first();
                if ($user) {
                    $has_subscription = UserSubscription::where('user_id', '=', $user->user_id)
                        ->where('type', '=', 'tradieflow')
                        ->first();

                    if ($has_subscription) {
                        Session::flush();
                        return response()->json([
                            'status' => false,
                            'redirect' => '/auth/login'
                        ]);
                    }
                }

                $allow_continue = true;
                $currency = $special_offer_user->currency;
            }

            if (!$allow_continue) {
                return response()->json([
                    'status' => false,
                    'redirect' => '/register',
                    'error' => 'User with this account already exists'
                ]);
            }
        }

        /**Process signup*/
        $country = [];
        if ($request['country_id']) {
            $country = Country::where('is_twilio','=','1')->find($request['country_id']);
            $request['country_id'] = ($country) ? $request['country_id'] : null;
        }

        $phone_country = Country::where('is_twilio','=','1')->where('code','=','us')->first();
        $address_sid = null;
        $twilio = new \Twilio\Rest\Client(env("TWILIO_ACCOUNT_SID"), env("TWILIO_AUTH_TOKEN"));
        $twilio_country_availability = Constant::GET_TWILIO_COUNTRY_AVAILABLE_FILTERS();
        $local = $twilio->availablePhoneNumbers(strtoupper($phone_country->code))
            ->{$twilio_country_availability[$phone_country->code]['type']}
            ->read([
                $twilio_country_availability[$phone_country->code]['capabilities']
            ], 1);

        $phone_create_options = [
            "phoneNumber" => $local['0']->phoneNumber,
            "smsMethod" => "POST",
            "smsUrl" => env('APP_URL') . "/api/twilio/incoming/text",
            "voiceUrl" => env('TWILIO_VOICE_WEBHOOK_URL')
        ];

        if ($address_sid) {
            $phone_create_options['addressSid'] = $address_sid;
        }

        if (env('APP_ENV') == 'local') {
            $twilio_phone_obj = [];
        }
        else{
            $incoming_phone_number = $twilio->incomingPhoneNumbers
                ->create($phone_create_options);

            if (isset($incoming_phone_number->sid) && $incoming_phone_number->sid) {
                $twilio_phone_obj = [
                    'friendly_name' => $local['0']->friendlyName,
                    'phone' => $local['0']->phoneNumber,
                    'country_code' => strtolower($phone_country->code),
                    'twilio_address_sid' => $incoming_phone_number->addressSid,
                    'twilio_bundle_sid' => $incoming_phone_number->bundleSid,
                    'twilio_sid' => $incoming_phone_number->sid,
                    'type' => $twilio_country_availability[$phone_country->code]['type']
                ];
            }
            else{
                return response()->json([
                    'status' => false,
                    'error' => 'Unable to register a new number, please try again later'
                ]);
            }
        }

        $other_params = [
            'country_id' => $request['country_id'],
            'mobile_onboarding_completed' => '1'
        ];

        $special_offer_params = [
            'user' => $user,
            'special_offer_user' => $special_offer_user,
            'country' => $country
        ];

        $model = Helper::signupUser('desktop',$user_queue, $other_params, $twilio_phone_obj, $user_queue->referral_code, $special_offer_params);
        if (!$user) {
            $model->has_email_verified = '1';
            $model->desktop_first_login_date_time = Carbon::now()->format('Y-m-d H:i:s');
            $model->password = $user_queue->password;
        }

        $model->update();

        /**Clear queue*/
        Session::forget('signup_user');
        $user_queue->delete();

        /**Login user*/
        Auth::loginUsingId($model->user_id);
        return response()->json([
            'status' => true
        ]);
    }

    public function registerPopup()
    {
        $countries = Country::where('is_twilio','=','1')->get();
        return view('auth.register_popup',compact(
            'countries'
        ));
    }

    public function referral($code)
    {
        $user_referral = UserReferralCode::with('User')->where('referral_code','=',$code)->where('type','=','tradieflow')->first();
        if ($user_referral && $user_referral->User) {
            Session::put('signup_referral_code',$code);
        }
        return redirect('register');
    }

    public function completeSpecialOfferRegistration($id)
    {
        $get_offer = SpecialOfferPagePurchase::where('signup_code','=',$id)->where('status','=','paid')
            ->where('plan_code','!=','hosting_reviews')
            ->first();

        if ($get_offer) {
            //hosting_flow_reviews, hosting_reviews, flow_reviews
            $user = User::where('email','=',$get_offer->email)->first();
            if ($user) {
                $has_subscription = UserSubscription::where('user_id','=',$user->user_id)
                    ->where('type','=','tradieflow')
                    ->first();

                if ($has_subscription) {
                    return redirect('auth/login');
                }
            }

            Session::put('signup_user',[
                'name' => $get_offer->name,
                'email' => $get_offer->email,
                'verified' => true,
                'password_set' => $user ? true : false
            ]);

            UserRegisterQueue::where('email','=',$get_offer->email)
                ->where('type','=','tradieflow')
                ->delete();

            $model = new UserRegisterQueue();
            $model->name = $get_offer->name;
            $model->email = $get_offer->email;
            $model->verify_code = null;
            $model->type = 'tradieflow';
            $model->save();

            if ($user) {
                return redirect('free-trial-2/step/1');
            }
        }

        return redirect('free-trial-2/password');
    }
}
