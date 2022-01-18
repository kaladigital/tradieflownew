<?php

namespace App\Http\Controllers;

use App\Console\Commands\TwilioRecordingProcessEngine;
use App\Helpers\Constant;
use App\Helpers\Helper;
use App\Helpers\NotificationHelper;
use App\Helpers\XeroHelper;
use App\Http\Controllers\Controller;
use App\Models\BusinessType;
use App\Models\CallHistory;
use App\Models\Client;
use App\Models\ClientHistory;
use App\Models\ClientLocation;
use App\Models\ClientNote;
use App\Models\ClientPhone;
use App\Models\ClientValue;
use App\Models\Country;
use App\Models\Event;
use App\Models\EventLocation;
use App\Models\Industry;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Notification;
use App\Models\ReviewInvite;
use App\Models\SpecialOfferPagePurchase;
use App\Models\TextMessage;
use App\Models\TextMessageMedia;
use App\Models\User;
use App\Models\UserBusinessType;
use App\Models\UserDeveloperInvite;
use App\Models\UserDevice;
use App\Models\UserForm;
use App\Models\UserFormData;
use App\Models\UserIndustry;
use App\Models\UserInvoiceSetting;
use App\Models\UserNotification;
use App\Models\UserOnboarding;
use App\Models\UserRegisterQueue;
use App\Models\UserSubscription;
use App\Models\UserTask;
use App\Models\UserTradiereviewRedirect;
use App\Models\UserTwilioPhone;
use App\Models\UserXeroAccount;
use App\Models\UserXeroMobileRedirect;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ApiController extends Controller
{
    public $TWILIO_API_URL = 'https://api.twilio.com';

    protected function decodeJWTRequest($string)
    {
        try {
            return JWT::decode($string, env('MOBILE_API_KEY'), array('HS256'));
        } catch (\Exception $e) {
            exit('wrong jwt encoding');
        }
    }

    public function login(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);

            if (isset($request_params->email) && isset($request_params->password)) {
                $user = User::with('Country','UserInvoiceSetting')->where('email', '=', $request_params->email)->first();

                if ($user && Hash::check($request_params->password, $user->password)) {
                    if (!$user->active) {
                        return response()->json([
                            'status' => false,
                            'error' => 'account_disabled'
                        ]);
                    }

                    if (!$user->has_email_verified) {
                        return response()->json([
                            'status' => false,
                            'error' => 'activation_pending'
                        ]);
                    }

                    if (!$user->mobile_login_key) {
                        $user->mobile_login_key = hash('md5', $user->email . ' ' . $user->user_id . ' ' . uniqid() . env('APP_KEY'));
                        if (!$user->mobile_first_login_date_time) {
                            $user->mobile_first_login_date_time = Carbon::now()->format('Y-m-d H:i:s');
                        }
                        $user->update();
                    }

                    /**Get User Phones*/
                    $user_phones = UserTwilioPhone::select('user_twilio_phone.user_twilio_phone_id', 'user_twilio_phone.friendly_name', 'user_twilio_phone.phone', 'user_twilio_phone.type', 'user_twilio_phone.country_code', 'user_twilio_phone.created_at','country.number as country_phone_code')
                        ->leftJoin('country','country.code','=','user_twilio_phone.country_code')
                        ->where('user_twilio_phone.user_id', '=', $user->user_id)
                        ->orderBy('user_twilio_phone.created_at', 'asc')
                        ->get();

                    $user_xero_enabled = UserXeroAccount::where('user_id','=',$user->user_id)
                        ->where('active','=','1')
                        ->count();

                    return response()->json([
                        'status' => true,
                        'user' => [
                            'name' => $user->name,
                            'email' => $user->email,
                            'mobile_login_key' => $user->mobile_login_key,
                            'phone_numbers' => $user_phones,
                            'twilio_company_unique_name' => $user->twilio_company_unique_name,
                            'onboarding_completed' => (!$user->mobile_login_key || ($user->mobile_login_key && $user->mobile_onboarding_completed)) ? true : false,
                            'walkthrough_completed' => $user->mobile_walkthrough_completed ? true : false,
                            'xero_enabled' => $user_xero_enabled ? true : false,
                            'company_name' => $user->UserInvoiceSetting ? $user->UserInvoiceSetting->company_name : '',
                            'website_url' => $user->website_url,
                            'country_id' => $user->country_id,
                            'country_name' => $user->Country ? $user->Country->name : '',
                            'twilio_first_call_made' => $user->twilio_first_call_made ? true : false
                        ],
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'error' => 'no_login'
                    ]);
                }
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function forgotPassword(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);

            if (isset($request_params->email)) {
                $user = User::where('email', '=', $request_params->email)
                    ->take(1)
                    ->first();

                if (!$user) {
                    return response()->json([
                        'status' => false,
                        'error' => 'email_not_found'
                    ]);
                }

                if (!$user->active) {
                    return response()->json([
                        'status' => false,
                        'error' => 'account_disabled'
                    ]);
                }

                $user->otp_code = Helper::generateUniqueFourDigits();
                $user->otp_created_date = Carbon::now()->format('Y-m-d H:i:s');
                $user->update();

                $notification = Notification::where('object_name', '=', 'MobileForgotPassword')
                    ->where('active', '=', '1')
                    ->first();

                if ($notification) {
                    NotificationHelper::resetPasswordMobile($notification, $user);
                }

                return response()->json([
                    'status' => true
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function verifyForgotPasswordOtp(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);

            if (isset($request_params->email)) {
                $user = User::where('email', '=', $request_params->email)
                    ->take(1)
                    ->first();

                if (!$user) {
                    return response()->json([
                        'status' => false,
                        'error' => 'email_not_found'
                    ]);
                }

                if ($user->otp_code != $request_params->otp_code) {
                    return response()->json([
                        'status' => false,
                        'error' => 'code_expired'
                    ]);
                }

                if (!$user->active) {
                    return response()->json([
                        'status' => false,
                        'error' => 'account_disabled'
                    ]);
                }

                return response()->json([
                    'status' => true
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function savePasswordOtp(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);

            if (isset($request_params->email) && isset($request_params->otp_code)) {
                $user = User::where('email', '=', $request_params->email)
                    ->where('otp_code', '=', $request_params->otp_code)
                    ->take(1)
                    ->first();

                if (!$user) {
                    return response()->json([
                        'status' => false,
                        'error' => 'code_expired'
                    ]);
                }

                if (!$user->active) {
                    return response()->json([
                        'status' => false,
                        'error' => 'account_disabled'
                    ]);
                }

                /**Check for mobile key*/
                if (!$user->mobile_login_key) {
                    $user->mobile_login_key = hash('md5', $user->email . ' ' . $user->user_id . ' ' . uniqid() . env('APP_KEY'));
                }

                if (!$user->mobile_first_login_date_time) {
                    $user->mobile_first_login_date_time = Carbon::now()->format('Y-m-d H:i:s');
                }

                $user->password = bcrypt($request_params->password);
                $user->otp_code = null;
                $user->otp_created_date = null;
                $user->has_email_verified = '1';
                $user->twilio_password = $request_params->password;
                $user->update();


                /**Get User Phones*/
                $user_phones = UserTwilioPhone::select('user_twilio_phone_id', 'friendly_name', 'phone', 'type', 'country_code', 'created_at')
                    ->where('user_id', '=', $user->user_id)
                    ->orderBy('created_at', 'asc')
                    ->get();

                return response()->json([
                    'status' => true,
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'mobile_login_key' => $user->mobile_login_key,
                        'phone_numbers' => $user_phones,
                        'twilio_first_call_made' => $user->twilio_first_call_made ? true : false
                    ]
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function changePassword(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);

            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)->first();

            if ($user) {
                $user->password = bcrypt($request_params->password);
                $user->twilio_password = $request_params->password;
                $user->update();
                return response()->json([
                    'status' => true
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'error' => 'no_login'
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function signup(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user_phones = [];
            if ($request_params->name && $request_params->email && strlen($request_params->password)) {
                $has_email_taken = User::where('email','=',$request_params->email)
                    ->orWhere('email','=',strtolower($request_params->email))
                    ->count();

                if ($has_email_taken) {
                    return response()->json([
                        'status' => true,
                        'error' => 'Email is already taken, please use another one'
                    ]);
                }

                $phone_country = Country::where('is_twilio','=','1')->where('code','=','us')->first();
                $address_sid = null;

                if (strpos($request_params->email,'dev.umer') === false) {
                    if (strpos($request_params->email,'csincsakf') === false) {
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
                        } else {
                            return response()->json([
                                'status' => true,
                                'error' => 'Unable to register a new number, please contact support'
                            ]);
                        }
                    }
                    else{
                        $twilio_phone_obj = [
                            'friendly_name' => '(214) 390-7558',
                            'phone' => '+12143907558',
                            'country_code' => 'us',
                            'twilio_address_sid' => 'PN50efbbbb43920190ac557c5f6ca00236',
                            'twilio_bundle_sid' => null,
                            'twilio_sid' => 'PN50efbbbb43920190ac557c5f6ca00236',
                            'type' => 'mobile'
                        ];
                    }
                }
                else{
                    $twilio_phone_obj = [
                        'friendly_name' => '(844) 420-1447',
                        'phone' => '+18444201447',
                        'country_code' => 'us',
                        'twilio_address_sid' => 'PN64252a0fc33184e4d9424ee3e812a52c',
                        'twilio_bundle_sid' => null,
                        'twilio_sid' => 'PN64252a0fc33184e4d9424ee3e812a52c',
                        'type' => 'mobile'
                    ];
                }

                $user_phones[] = $twilio_phone_obj;
                $other_params = [
                    'has_email_verified' => '1',
                    'mobile_login_key' => md5($request_params->email.env('APP_KEY').uniqid()),
                    'password' => bcrypt($request_params->password)
                ];

                $model = Helper::signupUser('desktop',$request_params, $other_params, $twilio_phone_obj);
                return response()->json([
                    'status' => true,
                    'user' => [
                        'name' => $model->name,
                        'email' => $model->email,
                        'mobile_login_key' => $other_params['mobile_login_key'],
                        'phone_numbers' => $user_phones,
                        'twilio_company_unique_name' => $model->twilio_company_unique_name,
                        'onboarding_completed' => false,
                        'walkthrough_completed' => false,
                        'twilio_first_call_made' => false
                    ],
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'Something went wrong'
        ]);
    }

    public function signupUpdateCompanyDetails(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::with('UserInvoiceSetting')
                ->where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                /**Save User*/
                if (!empty($request_params->company_name)) {
                    $user_invoice_setting = $user->UserInvoiceSetting;
                    if (!$user_invoice_setting) {
                        $user_invoice_setting = new UserInvoiceSetting();
                        $user_invoice_setting->user_id = $user->user_id;
                    }

                    $user_invoice_setting->company_name = $request_params->company_name;
                    $user_invoice_setting->save();
                }

                if (!empty($request_params->country_id)) {
                    $user->country_id = $request_params->country_id;
                }

                if (!empty($request_params->website_url)) {
                    $user->website_url = $request_params->website_url;
                    /**Queue Form Tracking*/
                    $model = new UserForm();
                    $model->user_id = $user->user_id;
                    $model->website = $user->website_url;
                    $model->status = 'pending';
                    $model->save();
                }

                $user->save();

                if (!empty($request_params->industry_id)) {
                    $model = new UserIndustry();
                    $model->user_id = $user->user_id;
                    $model->industry_id = $request_params->industry_id;
                    $model->save();
                }

                return response()->json([
                    'status' => true
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'Login failed'
        ]);
    }

    public function getIndustries()
    {
        $get_industries = Industry::select('industry_id','name')
            ->where('active','=','1')
            ->orderBy('order_num','asc')
            ->get();

        return response()->json([
            'status' => true,
            'industries' => $get_industries
        ]);
    }

    public function businessTypes()
    {
        $get_business_types = BusinessType::select('business_type_id','name')
            ->where('active','=','1')
            ->orderBy('order_num','asc')
            ->get();

        return response()->json([
            'status' => true,
            'business_types' => $get_business_types
        ]);
    }

    public function updateBusinessTypes(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);

            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)->first();
            if ($user) {
                $business_type_ids = [];
                foreach ($request_params->business_types as $item) {
                    $has_business_type = UserBusinessType::where('user_id','=',$user->user_id)
                        ->where('business_type_id','=',$item)
                        ->count();

                    if (!$has_business_type) {
                        $model = new UserBusinessType();
                        $model->user_id = $user->user_id;
                        $model->business_type_id = $item;
                        $model->save();
                    }

                    $business_type_ids[] = $item;
                }

                UserBusinessType::where('user_id','=',$user->user_id)
                    ->whereNotIn('business_type_id',$business_type_ids)
                    ->delete();

                return response()->json([
                    'status' => true
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'Something went wrong'
        ]);
    }

    public function signupDeveloperInvite(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)->first();

            if ($user && $request_params->email) {
                $user_developer_invite = UserDeveloperInvite::where('user_id','=',$user->user_id)
                    ->where('email','=',$request_params->email)
                    ->first();

                if (!$user_developer_invite) {
                    $user_developer_invite = new UserDeveloperInvite();
                    $user_developer_invite->user_id = $user->user_id;
                }

                $user_developer_invite->code = md5($request_params->email.env('APP_KEY').rand(1,100));
                $user_developer_invite->status = 'pending';
                $user_developer_invite->save();

                return response()->json([
                    'status' => true
                ]);
            }
        }
        return response()->json([
            'status' => false,
            'error' => 'Something went wrong'
        ]);
    }

    public function completeOnboarding(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)->first();

            if ($user) {
                $user->mobile_onboarding_completed = '1';
                $user->update();

                return response()->json([
                    'status' => true
                ]);
            }
        }
        return response()->json([
            'status' => false,
            'error' => 'Something went wrong'
        ]);
    }

    public function completeOnboardingWalkthrough(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)->first();

            if ($user) {
                $user->mobile_walkthrough_completed = '1';
                $user->update();

                return response()->json([
                    'status' => true
                ]);
            }
        }
        return response()->json([
            'status' => false,
            'error' => 'Something went wrong'
        ]);
    }

    public function updateProfile(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);

            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)->first();

            if ($user) {
                $user->name = $request_params->name;
                $user->update();
                return response()->json([
                    'status' => true
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'error' => 'no_login'
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function addEvent(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);

            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $event_model = new Event();
                $today_date_format = Carbon::now()->format('Y-m-d H:i:s');

                /**Check If Client Passed*/
                if ($request_params->status == 'other') {
                    $event_model->client_id = null;
                }
                else{
                    $client = Client::with('ClientLastValue')->where('user_id', '=', $user->user_id)->find($request_params->client_id);
                    if ($client) {
                        $event_model->client_id = $client->client_id;
                        $event_model->client_name = $request_params->client_name;
                    }
                    else{
                        return response()->json([
                            'status' => false,
                            'error' => 'Please select a client to continue'
                        ]);
                    }
                }

                /**Save Event*/
                $event_model->user_id = $user->user_id;
                $event_model->start_date_time = $request_params->start_date_time;
                $event_model->end_date_time = $request_params->end_date_time;
                $event_model->status = $request_params->status;
                $event_model->upfront_value = $request_params->upfront_value ?? null;
                $event_model->ongoing_value = $request_params->ongoing_value ?? null;
                $event_model->phone = $request_params->phone;
                $event_model->other_status_text = $request_params->other_status_text ?? null;
                $event_model->save();

                $event_locations = [];
                foreach ($request_params->locations as $item) {
                    $event_locations[] = [
                        'event_id' => $event_model->event_id,
                        'city' => $item->city,
                        'zip' => $item->zip,
                        'address' => $item->address,
                        'created_at' => $today_date_format,
                        'updated_at' => $today_date_format
                    ];
                }

                if ($event_locations) {
                    EventLocation::insert($event_locations);
                }

                /**Update Client Status If Not Listed*/
                if ($event_model->client_id && $client->status == 'not-listed') {
                    switch ($event_model->status) {
                        case 'quote-meeting':
                            $client->status = 'quote-meeting';
                            $client->update();

                            $client->ClientLastValue->status = 'quote-meeting';
                            $client->ClientLastValue->update();
                        break;
                        case 'work-in-progress':
                            $client->status = 'work-in-progress';
                            $client->update();

                            $client->ClientLastValue->status = 'work-in-progress';
                            $client->ClientLastValue->update();
                        break;
                    }
                }

                /**Check for Task Management request*/
                $show_success_badge = '';
                if ($event_model->client_id && isset($request_params->calculate_progress) && $request_params->calculate_progress) {
                    $show_success_badge = $this->calculateTotalMissedSuccessBadge($user);
                }

                /**Create Activity*/
                $events_list = Constant::GET_EVENT_STATUSES_LIST();
                $activity_description = isset($events_list[$event_model->status]) ? $events_list[$event_model->status] : '';
                Helper::addClientActivity($event_model->client_id, $event_model->event_id, 'Event Added', $activity_description, $event_model->start_date_time, $event_model->end_date_time, 'event');

                $today_date = Carbon::now()->format('Y-m-d');
                return response()->json([
                    'status' => true,
                    'event_id' => $event_model->event_id,
                    'show_success_badge' => $show_success_badge,
                    'event' => [
                        'client_id' => $event_model->client_id,
                        'phone' => $event_model->phone,
                        'start_date_time' => $event_model->start_date_time,
                        'end_date_time' => $event_model->end_date_time,
                        'status' => $event_model->status,
                        'other_status_text' => $event_model->other_status_text,
                        'upfront_value' => $event_model->upfront_value,
                        'ongoing_value' => $event_model->ongoing_value,
                        'locations' => $event_locations,
                        'created_at' => $today_date,
                        'updated_at' => $today_date,
                        'client' => [
                            'client_id' => $event_model->client_id ? $client->client_id : null,
                            'name' => $event_model->client_id ? $client->name : null,
                            'status' => $event_model->client_id ? $client->status : null,
                            'email' => $event_model->client_id ? $client->email : null,
                            'company' => $event_model->client_id ? $client->company : null
                        ],
                    ]
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    protected function calculateTotalMissedSuccessBadge($user)
    {
        $not_listed_clients = Client::selectRaw('ifnull(count(client_id),0) as total')
            ->where('user_id', '=', $user->user_id)
            ->where('status', '=', 'not-listed');

        $missed_history = CallHistory::selectRaw('ifnull(count(call_history.call_history_id),0) as total')
            ->leftJoin('client', 'client.client_id', '=', 'call_history.client_id')
            ->where('call_history.user_id', '=', $user->user_id)
            ->whereIn('call_history.type', ['incoming', 'missed'])
            ->whereNull('client.client_id');

        $missed_forms = UserFormData::selectRaw('ifnull(count(user_form_data.user_form_data_id),0) as total')
            ->where('user_form_data.user_id', '=', $user->user_id)
            ->where('user_form_data.is_converted','=','0');

        $user_twilio_phone = UserTwilioPhone::where('user_id','=',$user->user_id)->first();
        if ($user_twilio_phone) {
            $missed_messages = TextMessage::selectRaw('ifnull(count(text_message.text_message_id),0) as total')
                ->join(DB::raw("
                            (select max(m2.text_message_id) AS text_message_id
                            from  text_message as  m2
                            where m2.from_number = '$user_twilio_phone->phone' or m2.to_number = '$user_twilio_phone->phone'
                            group by  least(m2.from_number,m2.to_number), greatest(m2.to_number,m2.from_number)
                            ) m2"), function ($query) {
                    $query->on('text_message.text_message_id', '=', 'm2.text_message_id');
                })
                ->whereNull('text_message.client_id')
                ->where('text_message.user_id', '=', $user->user_id);
        }

        $missed_data = $not_listed_clients->unionAll($missed_history)
            ->unionAll($missed_forms);

        if ($user_twilio_phone) {
            $missed_data->unionAll($missed_messages);
        }

        $missed_data = $missed_data
            ->get();

        $missed_total = $missed_data['0']->total + $missed_data['1']->total + $missed_data['2']->total + $missed_data['3']->total;
        switch ($missed_total) {
            case '0':
                $success_label = 'completed';
            break;
            case '10':
                $success_label = 'show_10';
                break;
            case '25':
            $success_label = 'show_25';
                break;
            default:
                $success_label = '';
            break;
        }

        return $success_label;
    }

    public function getCalendarEventSlots(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $calendar_slots = Event::selectRaw('distinct date(start_date_time) as event_date')
                    ->where('user_id', '=', $user->user_id)
                    ->where(DB::raw('DATE(start_date_time)'), '>=', $request_params->start_date)
                    ->where(DB::raw('DATE(start_date_time)'), '<=', $request_params->end_date)
                    ->get();

                return response()->json([
                    'status' => true,
                    'calendar_slots' => $calendar_slots->toArray()
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'error' => 'no_login'
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function getCalendarEvents(Request $request)
    {
        if ($request->token) {
            $page_limit = $request_params->items_per_page ?? 10;
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $get_events = Event::select([
                    'event.event_id',
                    'event.start_date_time',
                    'event.end_date_time',
                    'event.status',
                    'event.client_name',
                    'event.other_status_text',
                    'client.company',
                ])
                    ->selectRaw('group_concat(concat(event_location.address," ",event_location.city," ",event_location.zip) SEPARATOR ", ") as address')
                    ->leftJoin('client', 'client.client_id', '=', 'event.client_id')
                    ->leftJoin('event_location', 'event_location.event_id', '=', 'event.event_id')
                    ->where('event.user_id', '=', $user->user_id)
                    ->where(DB::raw('date(event.start_date_time)'), '>=', $request_params->start_date)
                    ->where(DB::raw('date(event.start_date_time)'), '<=', $request_params->end_date);

                if (isset($request_params->client_id) && $request_params->client_id) {
                    $get_events->where('event.client_id', '=', $request_params->client_id);
                }

                if (isset($request_params->keyword) && strlen($request_params->keyword)) {
                    $get_events->where(function ($query) use ($request_params) {
                        $query->where('event.client_name', 'like', '%' . $request_params->keyword . '%')
                            ->orWhere('client.company', 'like', '%' . $request_params->keyword . '%')
                            ->orWhere('client.email', 'like', '%' . $request_params->keyword . '%')
                            ->orWhere('event.upfront_value', 'like', '%' . $request_params->keyword . '%')
                            ->orWhere('event.ongoing_value', 'like', '%' . $request_params->keyword . '%');
                    });
                }

                $request_params->page = $request_params->page ?? 1;
                $get_events = $get_events
                    ->groupBy('event.event_id')
                    ->skip(($request_params->page - 1) * $page_limit)
                    ->take($page_limit + 1)
                    ->get();

                return response()->json([
                    'status' => true,
                    'events' => $get_events->toArray(),
                    'items_per_page' => $page_limit
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'error' => 'no_login'
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function getEventDetails(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $event = Event::with('Client')->where('user_id', '=', $user->user_id)->find($request_params->event_id);
                if ($event) {
                    $locations = [];
                    $event_locations = EventLocation::where('event_id', '=', $event->event_id)->get();
                    foreach ($event_locations as $item) {
                        $locations[] = [
                            'event_location_id' => $item->event_location_id,
                            'city' => $item->city,
                            'zip' => $item->zip,
                            'address' => $item->address
                        ];
                    }

                    return response()->json([
                        'status' => true,
                        'event' => [
                            'client_id' => $event->client_id,
                            'phone' => $event->phone,
                            'start_date_time' => $event->start_date_time,
                            'end_date_time' => $event->end_date_time,
                            'status' => $event->status,
                            'other_status_text' => $event->other_status_text,
                            'upfront_value' => $event->upfront_value,
                            'ongoing_value' => $event->ongoing_value,
                            'locations' => $locations,
                            'created_at' => $event->created_at->format('Y-m-d'),
                            'updated_at' => $event->updated_at->format('Y-m-d'),
                            'client' => [
                                'client_id' => $event->client_id,
                                'name' => $event->Client ? $event->Client->name : null,
                                'status' => $event->Client ? $event->Client->status : null,
                                'email' => $event->Client ? $event->Client->email : null,
                                'company' => $event->Client ? $event->Client->company : null
                            ],
                        ]
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'error' => 'event_not_found'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'error' => 'no_login'
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function deleteEvent(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $event = Event::where('user_id', '=', $user->user_id)->find($request_params->event_id);
                if ($event) {
                    EventLocation::where('event_id', '=', $event->event_id)->delete();
                    $event->delete();
                }

                return response()->json([
                    'status' => true
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'error' => 'no_login'
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function getLeadStatuses()
    {
        return response()->json([
            [
                'type' => 'not-listed',
                'label' => 'Not Listed',
                'image' => env('APP_URL') . '/images/status/not-listed.png',
                'color' => '#F8632D'
            ],
            [
                'type' => 'lead',
                'label' => 'Lead',
                'image' => env('APP_URL') . '/images/status/target.png',
                'color' => '#16BDC8'
            ],
            [
                'type' => 'quote-meeting',
                'label' => 'Quote Meeting',
                'image' => env('APP_URL') . '/images/status/quote.png',
                'color' => '#20AEF7'
            ],
            [
                'type' => 'work-in-progress',
                'label' => 'Booked Job',
                'image' => env('APP_URL') . '/images/status/work.png',
                'color' => '#3962FA'
            ],
            [
                'type' => 'completed',
                'label' => 'Job Completed',
                'image' => env('APP_URL') . '/images/status/completed.png',
                'color' => '#43D14F'
            ],
            [
                'type' => 'cancelled',
                'label' => 'Job Cancelled',
                'image' => env('APP_URL') . '/images/status/cancelled.png',
                'color' => '#86969E'
            ]
        ]);
    }

    public function getEventStatuses()
    {
        return response()->json([
            [
                'type' => 'quote-meeting',
                'label' => 'Quote Meeting',
                'color' => '#20AEF7'
            ],
            [
                'type' => 'work-in-progress',
                'label' => 'Work',
                'color' => '#3962FA'
            ],
            [
                'type' => 'remind-me',
                'label' => 'Follow Up',
                'color' => '#9B51E0'
            ],
            [
                'type' => 'other',
                'label' => 'Other',
                'color' => '#F2C94C'
            ]
        ]);
    }

    public function updateEvent(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);

            $event_model = Event::select('event.*')
                ->leftJoin('user', 'user.user_id', '=', 'event.user_id')
                ->where('user.mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('user.active', '=', '1')
                ->find($request_params->event_id);

            if ($event_model) {
                if ($event_model->status == 'other' && isset($request_params->client_id) && $request_params->client_id) {
                    $check_client = Client::where('user_id','=',$event_model->user_id)->find($request_params->client_id);
                    if ($check_client) {
                        $event_model->client_id = $request_params->client_id;
                        $event_model->update();
                    }
                    else{
                        return response()->json([
                            'status' => false,
                            'error' => 'Client not found'
                        ]);
                    }
                }

                $event_model->start_date_time = $request_params->start_date_time;
                $event_model->end_date_time = $request_params->end_date_time;
                $event_model->status = $request_params->status;
                $event_model->other_status_text = $request_params->other_status_text ?? null;
                $event_model->client_name = $request_params->client_name;
                $event_model->upfront_value = $request_params->upfront_value ?? null;
                $event_model->ongoing_value = $request_params->ongoing_value ?? null;
                $event_model->phone = $request_params->phone;
                $event_model->update();

                /**Update Call History*/
                CallHistory::where('user_id', '=', $event_model->user_id)
                    ->whereNull('client_id')
                    ->where('phone', '=', $event_model->phone)
                    ->update(['client_id' => $event_model->client_id]);

                $event_location_ids = [];
                foreach ($request_params->locations as $item) {
                    if (isset($item->event_location_id) && $item->event_location_id) {
                        $event_location_model = EventLocation::where('event_id', '=', $event_model->event_id)->find($item->event_location_id);
                        if (!$event_location_model) {
                            continue;
                        }
                    } else {
                        $event_location_model = new EventLocation();
                        $event_location_model->event_id = $event_model->event_id;
                    }

                    $event_location_model->city = $item->city;
                    $event_location_model->zip = $item->zip;
                    $event_location_model->address = $item->address;
                    $event_location_model->save();

                    $event_location_ids[] = $event_location_model->event_location_id;
                }

                EventLocation::where('event_id', '=', $event_model->event_id)
                    ->whereNotIn('event_location_id', $event_location_ids)
                    ->delete();

                return response()->json([
                    'status' => true
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'error' => 'event_not_found'
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function addClient(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                /**Check Client Phones*/
                foreach ($request_params->phones as $item) {
                    $has_other_clients = Client::where('client.user_id','=',$user->user_id)
                        ->leftJoin('client_phone','client_phone.client_id','client.client_id')
                        ->where('client_phone.phone','=',$item->phone)
                        ->count();

                    if ($has_other_clients) {
                        return response()->json([
                            'status' => true,
                            'error' => $item->phone.' already assigned to another client, please select another one'
                        ]);
                    }
                }

                $client = new Client();
                $client->user_id = $user->user_id;
                $client->name = $request_params->name;
                $client->email = $request_params->email;
                $client->company = $request_params->company;
                $client->notes = (isset($request_params->notes) && strlen($request_params->notes)) ? $request_params->notes : null;
                $client->save();

                /**If email changed*/
                if ($client->email) {
                    Helper::addClientActivity($client->client_id, $client->client_id, 'Email Address Added', $client->email, null, null, 'email_added');

                    UserFormData::where('user_id','=',$client->user_id)
                        ->whereNull('client_id')
                        ->where('email','=',$client->email)
                        ->update([
                            'client_id' => $client->client_id
                        ]);
                }

                $today_date_format = Carbon::now()->format('Y-m-d H:i:s');

                /**Add Client Value*/
                $client_values = [];
                $latest_status = 'not-listed';
                foreach ($request_params->values as $item) {
                    $client_values[] = [
                        'client_id' => $client->client_id,
                        'project_name' => $item->project_name,
                        'status' => $item->status,
                        'upfront_value' => $item->upfront_value ?? null,
                        'ongoing_value' => $item->ongoing_value ?? null,
                        'unique_code' => md5($client->user_id.$client->client_id.'_'.uniqid()),
                        'created_at' => $today_date_format,
                        'updated_at' => $today_date_format
                    ];

                    $latest_status = $item->status;
                }

                $client->status = $latest_status;

                switch ($client->status) {
                    case 'quote-meeting':
                        $client->quote_meeting_date_time = Carbon::now()->format('Y-m-d H:i:s');
                    break;
                    case 'work-in-progress':
                        $client->work_started_date_time = Carbon::now()->format('Y-m-d H:i:s');
                    break;
                }

                $client->update();

                if ($client_values) {
                    ClientValue::insert($client_values);
                }
                else{
                    ClientValue::create([
                        'client_id' => $client->client_id,
                        'status' => $latest_status,
                        'unique_code' => md5($client->user_id.$client->client_id.'_'.uniqid())
                    ]);
                }

                /**Add Client Locations*/
                $client_locations = [];
                $location_types = Constant::GET_CLIENT_LOCATION_TYPES();
                foreach ($request_params->locations as $item) {
                    $client_locations[] = [
                        'client_id' => $client->client_id,
                        'city' => $item->city,
                        'zip' => $item->zip,
                        'address' => $item->address,
                        'location_type' => (isset($item->location_type) && array_key_exists($item->location_type, $location_types)) ? $item->location_type : null,
                        'created_at' => $today_date_format,
                        'updated_at' => $today_date_format
                    ];
                }

                if ($client_locations) {
                    ClientLocation::insert($client_locations);
                }

                /**Add Client Phones*/
                $client_phones = [];
                $client_phones_list = [];
                foreach ($request_params->phones as $item) {
                    $client_phones[] = [
                        'client_id' => $client->client_id,
                        'phone' => $item->phone,
                        'phone_format' => $item->phone_format,
                        'country_code' => $item->country_code,
                        'country_number' => $item->country_number,
                        'created_at' => $today_date_format,
                        'updated_at' => $today_date_format
                    ];

                    $client_phones_list[] = $item->phone;

                    /**Update Call History*/
                    CallHistory::where('user_id', '=', $user->user_id)
                        ->whereNull('client_id')
                        ->where('phone', '=', $item->phone)
                        ->update(['client_id' => $client->client_id]);
                }

                if ($client_phones) {
                    ClientPhone::insert($client_phones);
                }

                /**Check for Task Management request*/
                $show_success_badge = '';
                if ($client->status !== 'not-listed' && isset($request_params->calculate_progress) && $request_params->calculate_progress) {
                    $show_success_badge = $this->calculateTotalMissedSuccessBadge($user);
                }

                /**Set Forms as completed if any*/
                if ($client_phones_list) {
                    $get_user_form_data = UserFormData::where('user_id','=',$user->user_id)
                        ->where('is_converted','=','0')
                        ->whereIn('contact_phone',$client_phones_list)
                        ->whereNull('client_id')
                        ->get();

                    foreach ($get_user_form_data as $item) {
                        $item->is_converted = '1';
                        $item->client_id = $client->client_id;
                        $item->update();

                        /**Track History*/
                        Helper::addClientActivity($client->client_id, $item->user_form_data_id, 'Form Received', null, null, null, 'form');
                    }

                    /**Update Call History*/
                    CallHistory::where('user_id','=',$user->user_id)
                        ->whereNull('client_id')
                        ->whereIn('phone',$client_phones_list)
                        ->update([
                            'client_id' => $client->client_id
                        ]);

                    /**Update Text Messages*/
                    TextMessage::where('user_id','=',$user->user_id)
                        ->whereNull('client_id')
                        ->where(function($query) use ($client_phones_list){
                            $query
                                ->whereIn('to_number',$client_phones_list)
                                ->orWhereIn('from_number',$client_phones_list);
                        })
                        ->update([
                            'client_id' => $client->client_id
                        ]);
                }

                /**Try to get source*/
                $latest_form_data = UserFormData::where('user_id','=',$user->user_id)->whereIn('contact_phone',$client_phones_list)->orderBy('created_at','asc')->first();
                $latest_call_history = CallHistory::whereIn('phone',$client_phones_list)->orderBy('created_at','asc')->first();
                $latest_message = TextMessage::where(function($query) use ($client_phones_list){
                    $query
                        ->whereIn('to_number',$client_phones_list)
                        ->orWhereIn('from_number',$client_phones_list);
                })
                    ->where('user_id','=',$user->user_id)
                    ->orderBy('created_at','asc')
                    ->first();


                if ($latest_call_history || $latest_message || $latest_form_data) {
                    $current_timestamp = Carbon::now()->timestamp;
                    $latest_call_history_timestamp = ($latest_call_history) ? $latest_call_history->created_at->timestamp : $current_timestamp;
                    $latest_message_timestamp = ($latest_message) ? $latest_message->created_at->timestamp : $current_timestamp;
                    $latest_form_timestamp = ($latest_form_data) ? $latest_form_data->created_at->timestamp : $current_timestamp;

                    $latest_date_timestamp = min([$latest_call_history_timestamp, $latest_message_timestamp, $latest_form_timestamp]);
                    if ($latest_call_history_timestamp == $latest_date_timestamp) {
                        $client->source_type = 'phone';
                    }
                    elseif($latest_message_timestamp == $latest_date_timestamp) {
                        $client->source_type = 'message';
                    }
                    else{
                        $client->source_type = 'form';
                        $client->source_text = $latest_form_data->url;
                    }

                    $client->update();
                }

                return response()->json([
                    'status' => true,
                    'client_id' => $client->client_id,
                    'show_success_badge' => $show_success_badge
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'error' => 'no_login'
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    /**
     * Can be used for clients autocomplete
     */
    public function getClientsList(Request $request)
    {
        $page_limit = 200;
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);

            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $clients = Client::with([
                    'ClientValue' => function($query){
                        $query
                            ->select('client_value.client_id','client_value.upfront_value','client_value.ongoing_value')
                            ->where(function($q1) {
                                $q1
                                    ->whereNotNull('upfront_value')
                                    ->orWhereNotNull('ongoing_value');
                            });
                    }
                ])
                ->select([
                    'client.client_id',
                    'client.name',
                    'client.email',
                    'client.company',
                    'client.status',
                ])
                    ->selectRaw('group_concat(concat(client_phone.phone,"__",ifnull(client_phone.phone_format,""))) as phone_numbers')
                    ->leftJoin('client_phone','client_phone.client_id','=','client.client_id')
                    ->where('client.user_id', '=', $user->user_id);

                if (isset($request_params->status) && $request_params->status) {
                    $clients->where('client.status', '=', $request_params->status);
                }

                if (isset($request_params->keyword) && strlen($request_params->keyword)) {
                    $clients->where(function ($query) use ($request_params) {
                        $query->where('client.company', 'like', '%' . $request_params->keyword . '%')
                            ->orWhere('client.email', 'like', '%' . $request_params->keyword . '%')
                            ->orWhere('client.name', 'like', '%' . $request_params->keyword . '%');
                    });
                }

                $clients = $clients
                    ->groupBy('client.client_id')
                    ->orderBy('client.name', 'asc')
                    ->skip(($request_params->page - 1) * $page_limit)
                    ->take($page_limit + 1)
                    ->get();

                return response()->json([
                    'status' => true,
                    'clients' => $clients->toArray()
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'error' => 'no_login'
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function getClientDetails(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);

            $client = Client::select('client.*')
                ->leftJoin('user', 'user.user_id', '=', 'client.user_id')
                ->where('user.mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('user.active', '=', '1')
                ->find($request_params->client_id);

            if ($client) {
                /**Get Client Locations*/
                $locations = [];
                $client_locations = ClientLocation::where('client_id', '=', $client->client_id)->get();
                foreach ($client_locations as $item) {
                    $locations[] = [
                        'client_location_id' => $item->client_location_id,
                        'city' => $item->city,
                        'zip' => $item->zip,
                        'address' => $item->address,
                        'location_type' => $item->location_type
                    ];
                }

                /**Get Client Values*/
                $values = [];
                $client_values = ClientValue::where('client_id', '=', $client->client_id)->get();
                foreach ($client_values as $item) {
                    $values[] = [
                        'client_value_id' => $item->client_value_id,
                        'upfront_value' => $item->upfront_value,
                        'ongoing_value' => $item->ongoing_value,
                        'project_name' => $item->project_name,
                        'status' => $item->status
                    ];
                }

                $phones = [];
                $client_phones = ClientPhone::where('client_id', '=', $client->client_id)->get();
                foreach ($client_phones as $item) {
                    $phones[] = [
                        'client_phone_id' => $item->client_phone_id,
                        'phone' => $item->phone,
                        'phone_format' => $item->phone_format,
                        'country_code' => $item->country_code
                    ];
                }

                $total_messages_sent = TextMessage::where('user_id','=',$client->user_id)
                    ->where('client_id','=',$client->client_id)
                    ->where('client_sent','=','0')
                    ->count();

                /**Total Earned and Pending*/

                $total_earned = Invoice::where('client_id','=',$client->client_id)
                    ->whereIn('status',['sent-email','sent-text'])
                    ->where('has_paid','=','1')
                    ->sum('total_gross_amount');

                $total_sent = Invoice::where('client_id','=',$client->client_id)
                    ->whereIn('status',['sent-email','sent-text'])
                    ->sum('total_gross_amount');

                $outstanding_payments_percentage = sprintf('%.2f',($total_earned && $total_sent) ? ceil($total_earned/$total_sent*100) : 0);
                $outstanding_payments = sprintf('%.2f',$total_sent - $total_earned);
                $total_sent = sprintf('%.2f',$total_sent);
                $total_earned = sprintf('%.2f',$total_earned);

                $user_form = UserFormData::where('user_id','=',$client->user_id)
                    ->where('client_id','=',$client->client_id)
                    ->first();

                $first_call = CallHistory::where('user_id','=',$client->user_id)
                    ->where('client_id','=',$client->client_id)
                    ->first();

                return response()->json([
                    'status' => true,
                    'client' => [
                        'client_id' => $client->client_id,
                        'name' => $client->name,
                        'email' => $client->email,
                        'notes' => $client->notes,
                        'phones' => $phones,
                        'status' => $client->status,
                        'company' => $client->company,
                        'first_call_date' => $first_call ? $first_call->created_at->format('Y-m-d H:i:s') : null,
                        'lead_page_url' => $user_form ? $user_form->url : null,
                        'locations' => $locations,
                        'values' => $values,
                        'quote_meeting_date_time' => $client->quote_meeting_date_time,
                        'work_started_date_time' => $client->work_started_date_time,
                        'created_at' => $client->created_at->format('Y-m-d'),
                        'updated_at' => $client->updated_at->format('Y-m-d'),
                        'total_messages_sent' => $total_messages_sent,
                        'total_earned' => $total_earned,
                        'outstanding_payments' => $outstanding_payments,
                        'outstanding_payments_percentage' => $outstanding_payments_percentage,
                        'total_sent' => $total_sent
                    ]
                ]);
            }
            else {
                return response()->json([
                    'status' => false,
                    'error' => 'client_not_found'
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function updateClient(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);

            $client = Client::select('client.*')
                ->leftJoin('user', 'user.user_id', '=', 'client.user_id')
                ->where('user.mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('user.active', '=', '1')
                ->find($request_params->client_id);

            if ($client) {
                /**Track email add*/
                if ($request_params->email && $client->email != $request_params->email) {
                    Helper::addClientActivity($client->client_id, $client->client_id, 'Email Address Added', $request_params->email, null, null, 'email_added');
                }

                $client->name = $request_params->name;
                $client->email = $request_params->email;
                $client->company = $request_params->company;
                $client->notes = (isset($request_params->notes) && strlen($request_params->notes)) ? $request_params->notes : null;

                /**Save Client Values*/
                $new_value = [
                    'upfront_value' => null,
                    'ongoing_value' => null
                ];
                $client_value_ids = [];
                $latest_status = '';
                foreach ($request_params->values as $item) {
                    $old_status = $client->status;
                    if (isset($item->client_value_id) && $item->client_value_id) {
                        $client_value = ClientValue::where('client_id', '=', $client->client_id)->find($item->client_value_id);
                        if (!$client_value) {
                            continue;
                        }

                        if (isset($item->upfront_value) && $client_value->upfront_value !== $item->upfront_value) {
                            $new_value['upfront_value'] = $item->upfront_value;
                        }
                        elseif(isset($item->ongoing_value) && $client->ongoing_value !== $item->ongoing_value) {
                            $new_value['ongoing_value'] = $item->ongoing_value;
                        }

                        if (!$client_value->unique_code) {
                            $client_value->unique_code = md5($client->ClientLastValue->client_value_id.uniqid().rand(1,100));
                        }
                    } else {
                        $client_value = new ClientValue();
                        $client_value->client_id = $client->client_id;

                        if ($item->upfront_value) {
                            $new_value['upfront_value'] = isset($item->upfront_value) ? $item->upfront_value : null;
                        }

                        if ($item->ongoing_value) {
                            $new_value['ongoing_value'] = isset($item->ongoing_value) ? $item->ongoing_value : null;
                        }

                        $client_value->unique_code = md5($client->client_id.$client->user_id.uniqid());
                    }

                    $client_value->upfront_value = $item->upfront_value ?? null;
                    $client_value->ongoing_value = $item->ongoing_value ?? null;
                    $client_value->project_name = $item->project_name;
                    $client_value->status = $item->status;
                    $client_value->save();

                    $client_value_ids[] = $client_value->client_value_id;
                    $latest_status = $client_value->status;

                    /**Create Activity*/
                    if ($old_status !== $item->status) {
                        $client_statuses = Constant::GET_CLIENT_STATUS_LIST();
                        $status_label = (isset($client_statuses[$item->status])) ? $client_statuses[$item->status] : null;
                        Helper::addClientActivity($client->client_id, $client->client_id, 'Status Changed', $status_label, null, null, 'client_status');

                        if ($item->status == 'completed' && $client->email) {
//                            Helper::queueLeaveReviewRequest($client->email, 'email', $client->User, $client_value->unique_code);
                        }
                    }
                }

                $client->status = $latest_status;
                switch ($client->status) {
                    case 'not-listed':
                    case 'lead':
                        $client->quote_meeting_date_time = null;
                        $client->work_started_date_time = null;
                    break;
                    case 'quote-meeting':
                        $client->quote_meeting_date_time = Carbon::now()->format('Y-m-d H:i:s');
                        $client->work_started_date_time = null;
                    break;
                    case 'work-in-progress':
                        $client->work_started_date_time = Carbon::now()->format('Y-m-d H:i:s');
                    break;
                }

                $client->update();

                ClientValue::where('client_id', '=', $client->client_id)
                    ->whereNotIn('client_value_id', $client_value_ids)
                    ->delete();

                if (!is_null($new_value['upfront_value'])) {
                    Helper::addClientActivity($client->client_id, $client->client_id, 'Upfront Value Added', '$'.number_format($new_value['upfront_value'],2), null, null, 'upfront_value');
                }

                if (!is_null($new_value['ongoing_value'])) {
                    Helper::addClientActivity($client->client_id, $client->client_id, 'Ongoing Value Added', '$'.number_format($new_value['ongoing_value'],2), null, null, 'ongoing_value');
                }

                /**Save Client Locations*/
                $client_location_ids = [];
                $location_types = Constant::GET_CLIENT_LOCATION_TYPES();
                foreach ($request_params->locations as $item) {
                    if (isset($item->client_location_id) && $item->client_location_id) {
                        $client_location = ClientLocation::where('client_id', '=', $client->client_id)->find($item->client_location_id);
                        if (!$client_location) {
                            continue;
                        }
                    } else {
                        $client_location = new ClientLocation();
                        $client_location->client_id = $client->client_id;
                    }

                    $client_location->city = $item->city;
                    $client_location->zip = $item->zip;
                    $client_location->address = $item->address;
                    $client_location->location_type = (isset($item->location_type) && array_key_exists($item->location_type, $location_types)) ? $item->location_type : null;
                    $client_location->save();

                    $client_location_ids[] = $client_location->client_location_id;
                }

                ClientLocation::where('client_id', '=', $client->client_id)
                    ->whereNotIn('client_location_id', $client_location_ids)
                    ->delete();

                /**Save Client Phones*/
                $client_phone_ids = [];
                $client_phones_list = [];
                $client_deleted_phones_list = [];

                foreach ($request_params->phones as $item) {
                    if (isset($item->client_phone_id) && $item->client_phone_id) {
                        $client_phone = ClientPhone::where('client_id', '=', $client->client_id)->find($item->client_phone_id);
                        if (!$client_phone) {
                            $client_deleted_phones_list[] = $item->phone;
                            continue;
                        }
                    } else {
                        $client_phone = new ClientPhone();
                        $client_phone->client_id = $client->client_id;

                        /**Check for duplicates*/
                        $has_other_clients = Client::where('user_id','=',$client->user_id)
                            ->leftJoin('client_phone','client_phone.client_id','client.client_id')
                            ->where('client_phone.phone','=',$item->phone)
                            ->where('client.client_id','!=',$client->client_id)
                            ->count();

                        if ($has_other_clients) {
                            return response()->json([
                                'status' => true,
                                'error' => $item->phone.' already assigned to another client, please select another one'
                            ]);
                        }
                    }

                    $client_phone->phone = $item->phone;
                    $client_phone->phone_format = $item->phone_format;
                    $client_phone->country_code = $item->country_code;
                    $client_phone->country_number = $item->country_number;
                    $client_phone->save();

                    $client_phone_ids[] = $client_phone->client_phone_id;
                    $client_phones_list[] = $item->phone;
                }

                ClientPhone::where('client_id', '=', $client->client_id)
                    ->whereNotIn('client_phone_id', $client_phone_ids)
                    ->delete();

                /**Update Call History*/
                CallHistory::where('user_id','=',$client->user_id)
                    ->whereNull('client_id')
                    ->whereIn('phone',$client_phones_list)
                    ->update([
                        'client_id' => $client->client_id
                    ]);

                /**Update Text Messages*/
                TextMessage::where('user_id','=',$client->user_id)
                    ->whereNull('client_id')
                    ->where(function($query) use ($client_phones_list){
                        $query
                            ->whereIn('to_number',$client_phones_list)
                            ->orWhereIn('from_number',$client_phones_list);
                    })
                    ->update([
                        'client_id' => $client->client_id
                    ]);

                UserFormData::where('user_id','=',$client->user_id)
                    ->whereNull('client_id')
                    ->whereIn('contact_phone',$client_phones_list)
                    ->update([
                        'client_id' => $client->client_id
                    ]);

                /**Delete None Matches*/
                if ($client_deleted_phones_list) {
                    CallHistory::where('user_id','=',$client->user_id)
                        ->where('client_id','=',$client->client_id)
                        ->whereIn('phone',$client_deleted_phones_list)
                        ->update([
                            'client_id' => null
                        ]);

                    TextMessage::where('user_id','=',$client->user_id)
                        ->where('client_id','=',$client->client_id)
                        ->where(function($query) use ($client_deleted_phones_list){
                            $query
                                ->whereIn('to_number',$client_deleted_phones_list)
                                ->orWhereIn('from_number',$client_deleted_phones_list);
                        })
                        ->update([
                            'client_id' => null
                        ]);

                    UserFormData::where('user_id','=',$client->user_id)
                        ->where('client_id','=',$client->client_id)
                        ->whereIn('phone',$client_deleted_phones_list)
                        ->update([
                            'client_id' => null
                        ]);
                }

                return response()->json([
                    'status' => true
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'error' => 'no_client_found'
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function deleteClient(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);

            $client = Client::select('client.*')
                ->leftJoin('user', 'user.user_id', '=', 'client.user_id')
                ->where('user.mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('user.active', '=', '1')
                ->find($request_params->client_id);

            if ($client) {
                /**Remove Event Locations*/
                EventLocation::leftJoin('event', 'event.event_id', 'event_location.event_id')
                    ->where('event.client_id', '=', $client->client_id)
                    ->delete();

                /**Remove Events*/
                Event::where('client_id', $client->client_id)->delete();

                /**Remove Client Locations*/
                ClientLocation::where('client_id', $client->client_id)->delete();

                /**Remove Client Phone*/
                $get_client_phones = ClientPhone::where('client_id', $client->client_id)
                    ->get();

                $client_deleted_phones_list = [];
                foreach ($get_client_phones as $item) {
                    $client_deleted_phones_list[] = $item->phone;
                }

                /**Delete None Matches*/
                if ($client_deleted_phones_list) {
                    CallHistory::where('user_id','=',$client->user_id)
                        ->where('client_id','=',$client->client_id)
                        ->whereIn('phone',$client_deleted_phones_list)
                        ->update([
                            'client_id' => null
                        ]);

                    TextMessage::where('user_id','=',$client->user_id)
                        ->where('client_id','=',$client->client_id)
                        ->where(function($query) use ($client_deleted_phones_list){
                            $query
                                ->whereIn('to_number',$client_deleted_phones_list)
                                ->orWhereIn('from_number',$client_deleted_phones_list);
                        })
                        ->update([
                            'client_id' => null
                        ]);
                }

                ClientPhone::where('client_id', $client->client_id)->delete();

                /**Remove Client Values*/
                ClientValue::where('client_id', $client->client_id)->delete();

                /**Remove Client Values*/
                CallHistory::where('client_id', $client->client_id)->update(['client_id' => null]);

                /**Update Form Date*/
                UserFormData::where('user_id','=',$client->user_id)
                    ->where('client_id','=',$client->client_id)
                    ->update([
                        'client_id' => null
                    ]);

                /**Delete Client*/
                $client->delete();

                return response()->json([
                    'status' => true
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'error' => 'no_client_found'
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function getAvailablePhoneCountries()
    {
        return response()->json([
            'status' => true,
            'available_countries' => Constant::GET_TWILIO_AVAILABLE_NUMBERS()
        ]);
    }

    protected function encodeJWTRequest($payload)
    {
        return JWT::encode($payload, env('MOBILE_API_KEY'));
    }

    public function callHistory(Request $request)
    {
        $page_limit = $request_params->items_per_page ?? 10;
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);

            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $call_history = CallHistory::select([
                    'call_history.phone',
                    'call_history.type',
                    'call_history.created_at',
                    'client.client_id',
                    'client.name',
                    'client.company',
                    'client_value.upfront_value',
                    'client_value.ongoing_value'
                ])
                    ->leftJoin('client', 'client.client_id', '=', 'call_history.client_id')
                    ->leftJoin('client_value', 'client_value.client_id', '=', 'client.client_id')
                    ->where('call_history.user_id', '=', $user->user_id);

                if (isset($request_params->type) && $request_params->type) {
                    $call_history->where('call_history.type', '=', $request_params->type);
                }

                if (isset($request_params->client_id) && $request_params->client_id) {
                    $call_history->where('call_history.client_id', '=', $request_params->client_id);
                }

                if (isset($request_params->phone) && $request_params->phone) {
                    $call_history->where('call_history.phone', '=', $request_params->phone);
                }

                $call_history = $call_history
                    ->groupBy('call_history.call_history_id')
                    ->orderBy('call_history.created_at', 'desc')
                    ->skip(($request_params->page - 1) * $page_limit)
                    ->take($page_limit + 1)
                    ->get();

                return response()->json([
                    'status' => true,
                    'call_history' => $call_history->toArray(),
                    'items_per_page' => $page_limit
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'error' => 'no_login'
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function dialCallHistory(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $client_id = (isset($request_params->client_id) && $request_params->client_id) ? $request_params->client_id : null;
                Helper::trackOutgoingCall($user, $request_params->phone, $client_id, $request_params->twilio_call_id);
                return response()->json([
                    'status' => true
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function getAvailableHours(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);

            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $events = Event::select([
                    'client.client_id',
                    'event.event_id',
                    'client.name',
                    'event.start_date_time',
                    'event.end_date_time',
                    'event.status'
                ])
                    ->leftJoin('client', 'client.client_id', '=', 'event.client_id')
                    ->where('client.user_id', '=', $user->user_id)
                    ->where(DB::raw('date(event.start_date_time)'), '=', $request_params->date_format)
                    ->orderBy('event.start_date_time', 'asc')
                    ->orderBy('event.end_date_time', 'asc')
                    ->take(100)
                    ->get();

                $date_obj = Carbon::createFromFormat('Y-m-d', $request_params->date_format);
                $day_num = strtolower($date_obj->copy()->format('D'));
                $available_hours = [];

                /**Get Available Hours*/
                if ($user->{$day_num}) {
                    $appointment_interval = 60 * 60;//1 hour

                    $working_hours = [];
                    $start_date_obj = Carbon::createFromFormat('Y-m-d H:i', $request_params->date_format . ' ' . $user->{$day_num . '_start'});
                    $end_date_obj = Carbon::createFromFormat('Y-m-d H:i', $request_params->date_format . ' ' . $user->{$day_num . '_end'});
                    $start_time_num = $start_date_obj->copy()->timestamp;
                    $end_date_num = $end_date_obj->copy()->timestamp;
                    for ($i = $start_time_num; $i <= $end_date_num; $i += $appointment_interval) {
                        $start_obj = Carbon::createFromTimestamp($i);
                        $end_obj = $start_obj->copy()->addMinute(60);
                        $working_hours[] = [
                            'start_format' => $start_obj->copy()->format('H:i'),
                            'end_format' => $end_obj->copy()->format('H:i'),
                            'start_str' => $i,
                            'end_str' => $end_obj->copy()->timestamp
                        ];
                    }

                    /**Get Events*/
                    $busy_hours = [];
                    foreach ($events as $item) {
                        $busy_hours[] = [
                            'start_str' => Carbon::createFromFormat('Y-m-d H:i:s', $item->start_date_time)->timestamp,
                            'end_str' => Carbon::createFromFormat('Y-m-d H:i:s', $item->end_date_time)->timestamp
                        ];
                    }

                    /**Get Available Hours*/
                    foreach ($working_hours as $value) {
                        $allow_add = true;
                        foreach ($busy_hours as $busy) {
                            if (
                                ($value['start_str'] > $busy['start_str'] && $value['start_str'] <= $busy['end_str']) ||
                                ($value['end_str'] >= $busy['start_str'] && $value['end_str'] <= $busy['end_str']) ||
                                ($value['start_str'] <= $busy['start_str'] && $value['end_str'] >= $busy['end_str'])
                            ) {
                                $allow_add = false;
                            }
                        }

                        if ($allow_add) {
                            $available_hours[] = [
                                'start' => $value['start_format'],
                                'end' => $value['end_format'],
                            ];
                        }
                    }
                }

                return response()->json([
                    'status' => true,
                    'events' => $events,
                    'available_hours' => $available_hours
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function twilioIncomingCallTrack(Request $request)
    {
        file_put_contents(public_path('logs/dial_icoming.txt'), json_encode($request->all()));
        $user = UserTwilioPhone::where('phone', '=', $request['to_phone'])->first();

        if ($user) {
            $client = ClientPhone::select('client_phone.*')
                ->leftJoin('client', 'client.client_id', '=', 'client_phone.client_id')
                ->where('client.user_id', '=', $user->user_id)
                ->where('client_phone.phone', '=', $request['from_phone'])
                ->first();

            $model = new CallHistory();
            $model->user_id = $user->user_id;
            $model->client_id = $client ? $client->client_id : null;
            $model->phone = $request['from_phone'];
            $model->type = in_array($request['status'], ['incoming', 'missed']) ? $request['status'] : 'incoming';
            $model->twilio_call_id = ($request['twilio_call_id']) ?? null;
            $model->save();

            /**Create Activity*/
            Helper::addClientActivity($client->client_id, $model->call_history_id, 'Call', '', null, null, 'call');

            return response()->json([
                'status' => true
            ]);
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function getTwilioPhoneDetails(Request $request)
    {
        $user = UserTwilioPhone::with('User','UserTwilioPhoneRedirect')->where('phone', '=', $request['phone'])->first();
        if ($user) {
            if ($user->User) {
                $redirect_phones = [];
                foreach ($user->UserTwilioPhoneRedirect as $item) {
                    $redirect_phones[] = ['phone' => $item->phone];
                }

                $client_name = null;
                if ($request['caller_phone']) {
                    $client = Client::where('user_id','=',$user->user_id)
                        ->leftJoin('client_phone','client_phone.client_id','=','client.client_id')
                        ->where('client_phone.phone','=',$request['caller_phone'])
                        ->first();

                    $client_name = $client ? $client->name : null;
                }

                return response()->json([
                    'status' => true,
                    'name' => $user->User->name,
                    'user_id' => $user->user_id,
                    'twilio_company_unique_name' => $user->User->twilio_company_unique_name,
                    'phones' => $redirect_phones,
                    'client_name' => $client_name
                ]);
            }

            return response()->json([
                'status' => true,
                'name' => 'N/A',
                'user_id' => Carbon::now()->timestamp,
                'phones' => [],
                'client_name' => null
            ]);
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function getClientByPhone(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);

            $user = User::select('client.client_id', 'client.name')
                ->leftJoin('client', 'client.user_id', '=', 'user.user_id')
                ->leftJoin('client_phone', 'client_phone.client_id', '=', 'client.client_id')
                ->where('client_phone.phone', '=', $request_params->phone)
                ->where('user.mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('user.active', '=', '1')
                ->first();

            if ($user) {
                return response()->json([
                    'status' => true,
                    'name' => $user->name,
                    'client_id' => $user->client_id
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function loadMessages(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);

            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $page_limit = Constant::GET_TOPIC_MESSAGES_COUNT();
                $user_phone = UserTwilioPhone::where('user_id', '=', $user->user_id)->first();
                
                $messages = TextMessage::with('TextMessageMedia')
                    ->select([
                        'text_message.text_message_id',
                        'text_message.client_id',
                        'text_message.message',
                        'text_message.from_number',
                        'text_message.to_number',
                        'text_message.client_sent',
                        'text_message.created_at',
                        'text_message.has_read',
                        'client.name',
                    ])
                    ->leftJoin('client', 'client.client_id', '=', 'text_message.client_id')
                    ->where('text_message.user_id', '=', $user->user_id);

                if ($user_phone) {
                    $messages->join(DB::raw("
                        (select max(m2.text_message_id) AS text_message_id
                        from  text_message as  m2
                        where m2.from_number = '$user_phone->phone' or m2.to_number = '$user_phone->phone'
                        group by  least(m2.from_number,m2.to_number), greatest(m2.to_number,m2.from_number)
                        ) m2"), function ($query) {
                        $query->on('text_message.text_message_id', '=', 'm2.text_message_id');
                    });
                }

                if (isset($request_params->client_id) && $request_params->client_id) {
                    $messages->where('text_message.client_id', '=', $request_params->client_id);
                }

                if (isset($request_params->phone) && $request_params->phone) {
                    $messages->where(function ($query) use ($request_params) {
                        $query
                            ->where('text_message.from_number', '=', $request_params->phone)
                            ->orWhere('text_message.to_number', '=', $request_params->phone);
                    });
                }

                $messages = $messages
                    ->groupBy('text_message.text_message_id')
                    ->orderBy('text_message.created_at', 'desc')
                    ->skip(($request_params->page - 1) * $page_limit)
                    ->take($page_limit + 1)
                    ->get();

                $messages_data = [];
                foreach ($messages as $item) {
                    $media_files = [];
                    foreach ($item->TextMessageMedia as $media_item) {
                        $media_files[] = env('APP_URL') . '/text_media/' . $media_item->file_name;
                    }

                    $messages_data[] = [
                        'text_message_id' => $item->text_message_id,
                        'client_id' => $item->client_id,
                        'client_name' => $item->name,
                        'message' => $item->message,
                        'phone' =>  ($user_phone && $item->from_number == $user_phone->phone) ? $item->to_number : $item->from_number,
                        'has_unread' => ($user_phone && $item->to_number == $user_phone->phone && !$item->has_read) ? true : false,
                        'message_date' => $item->created_at,
                        'media_files' => $media_files
                    ];
                }

                $has_more_pages = count($messages_data) > $page_limit ? true : false;

                return response()->json([
                    'status' => true,
                    'messages' => $has_more_pages ? array_slice($messages_data,0,$page_limit) : $messages_data,
                    'has_more_pages' => $has_more_pages,
                    'items_per_page' => $page_limit
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'error' => 'no_login'
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function loadTopicMessages(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);

            if (isset($request_params->client_id) || isset($request_params->phone)) {
                $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                    ->where('active', '=', '1')
                    ->first();

                if ($user) {
                    $page_limit = Constant::GET_TOPIC_MESSAGES_COUNT();

                    $messages = TextMessage::with('TextMessageMedia')
                        ->select([
                            'text_message.text_message_id',
                            'text_message.message',
                            'text_message.client_sent',
                            'text_message.created_at'
                        ])
                        ->leftJoin('client', 'client.client_id', '=', 'text_message.client_id')
                        ->where('text_message.user_id', '=', $user->user_id);


                    if (isset($request_params->client_id) && $request_params->client_id) {
                        $messages->where('text_message.client_id', '=', $request_params->client_id);
                        TextMessage::where('user_id', '=', $user->user_id)
                            ->where('client_id', '=', $request_params->client_id)
                            ->where('client_sent', '=', '1')
                            ->where('has_read', '=', '0')
                            ->update(['has_read' => '1']);
                    } else {
                        $messages->where(function ($query) use ($request_params) {
                            $query
                                ->where('text_message.from_number', '=', $request_params->phone)
                                ->orWhere('text_message.to_number', '=', $request_params->phone);
                        });

                        TextMessage::where('user_id', '=', $user->user_id)
                            ->where(function ($query) use ($request_params) {
                                $query
                                    ->where('text_message.from_number', '=', $request_params->phone)
                                    ->orWhere('text_message.to_number', '=', $request_params->phone);
                            })
                            ->where('client_sent', '=', '1')
                            ->where('has_read', '=', '0')
                            ->update(['has_read' => '1']);
                    }

                    $messages = $messages
                        ->groupBy('text_message.text_message_id')
                        ->orderBy('text_message.created_at', 'desc')
                        ->skip(($request_params->page - 1) * $page_limit)
                        ->take($page_limit + 1)
                        ->get();

                    $messages_data = [];
                    foreach ($messages as $item) {
                        $media_files = [];
                        foreach ($item->TextMessageMedia as $media_item) {
                            $media_files[] = env('APP_URL') . '/text_media/' . $media_item->file_name;
                        }

                        $messages_data[] = [
                            'text_message_id' => $item->text_message_id,
                            'message' => $item->message,
                            'type' => ($item->client_sent) ? 'received' : 'sent',
                            'message_date' => $item->created_at,
                            'media_files' => $media_files
                        ];
                    }

                    return response()->json([
                        'status' => true,
                        'messages' => $messages_data,
                        'items_per_page' => $page_limit
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'error' => 'no_login'
                    ]);
                }
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function sendTextMessage(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $client_id = null;
                if (isset($request_params->client_id) && $request_params->client_id) {
                    $client = Client::where('user_id', '=', $user->user_id)->find($request_params->client_id);
                    if ($client) {
                        $client_id = $client->client_id;
                    }
                }

                /**Try getting client from phone*/
                if (!$client_id) {
                    $get_client = ClientPhone::select('client_phone.*')
                        ->leftJoin('client', 'client.client_id', '=', 'client_phone.client_id')
                        ->where('client.user_id', '=', $user->user_id)
                        ->where('client_phone.phone', '=', $request_params->phone)
                        ->first();

                    if ($get_client) {
                        $client_id = $get_client->client_id;
                    }
                }

                $user_phone = UserTwilioPhone::where('user_id', '=', $user->user_id)->first();

                $message_sid = true;
                if ($message_sid) {
                    $model = new TextMessage();
                    $model->user_id = $user->user_id;
                    $model->client_id = $client_id;
                    $model->message = $request_params->message;
                    $model->from_number = $user_phone ? $user_phone->phone : null;
                    $model->to_number = $request_params->phone;
                    $model->client_sent = '0';
                    $model->save();

                    $text_message_media = [];

                    if ($request['media']) {
                        foreach ($request['media'] as $item) {
                            $uploaded_image = Helper::getBase64Data($item);
                            if (in_array($uploaded_image['extension'], Constant::GET_ALLOWED_IMAGE_EXTENSIONS())) {
                                try {
                                    $media_model = new TextMessageMedia();
                                    $media_model->text_message_id = $model->text_message_id;
                                    $media_model->file_name = $model->user_id . '' . uniqid() . '.' . $uploaded_image['extension'];
                                    Storage::disk('text_media')->put($media_model->file_name, base64_decode($uploaded_image['file_data']));
                                    $media_model->save();

                                    $text_message_media[] = env('APP_URL') . '/text_media/' . $media_model->file_name;
                                } catch (\Exception $e) {

                                }
                            }
                        }
                    }

                    /**Send out Twilio Message*/
                    $twilio = new \Twilio\Rest\Client(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));
                    try{
                        $params = [
                            "body" => $request_params->message,
                            "from" => $user_phone->phone
                        ];

                        if ($text_message_media) {
                            $params["mediaUrl"] = $text_message_media;
                        }

                        $message = $twilio->messages
                            ->create($request_params->phone,$params);

                        $model->twilio_sid = $message->sid;
                        $model->update();
                    }
                    catch (\Exception $e) {
                        $model->delete();
                    }
                }

                return response()->json([
                    'status' => true
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function saveTestMessageHistory(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $model = new TextMessage();
                $model->user_id = $user->user_id;
                $model->client_id = $request_params->client_id ?? null;
                $model->message = $request_params->message;
                $model->from_number = $request_params->from_number;
                $model->to_number = $request_params->to_number;
                $model->has_read = '0';
                $model->client_sent = $request_params->client_sent;
                $model->save();

                return response()->json([
                    'status' => true
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function twilioIncomingTextMessage(Request $request)
    {
        set_time_limit(60 * 60);
        $twilio = new \Twilio\Rest\Client(env("TWILIO_ACCOUNT_SID"), env("TWILIO_AUTH_TOKEN"));
        if (isset($request->MessageSid)) {
            try {
                $twilio_message = $twilio->messages($request->MessageSid)
                    ->fetch();

                /**Find Twilio User*/
                $twilio_user = UserTwilioPhone::where('phone', '=', $twilio_message->to)->first();
                if ($twilio) {
                    /**Find Client*/
                    $client_phone = ClientPhone::select('client_phone.*')
                        ->leftJoin('client', 'client.client_id', '=', 'client_phone.client_id')
                        ->where('client.user_id', '=', $twilio_user->user_id)
                        ->where('client_phone.phone', '=', $twilio_message->from)
                        ->first();

                    $model = new TextMessage();
                    $model->user_id = $twilio_user->user_id;
                    $model->client_id = ($client_phone) ? $client_phone->client_id : null;
                    $model->message = $twilio_message->body;
                    $model->from_number = $twilio_message->from;
                    $model->to_number = $twilio_message->to;
                    $model->has_read = 0;
                    $model->client_sent = '1';
                    $model->twilio_sid = $request->MessageSid;
                    $model->save();
                }
            } catch (\Exception $e) {

            }
        } else {

        }
    }

    public function getTaskTotals(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $tasks_per_page = Constant::GET_TASK_PAGE_TASKS_PER_PAGE();
                $global_tasks_totals = UserTask::selectRaw('ifnull(count(user_task_id),0) as total')
                    ->where('user_id', '=', $user->user_id)
                    ->whereNull('user_task.client_id');

                $client_tasks_totals = UserTask::selectRaw('ifnull(count(user_task_id),0) as total')
                    ->where('user_id', '=', $user->user_id)
                    ->whereNotNull('user_task.client_id');

                $follow_up_totals = Event::selectRaw('ifnull(count(event.event_id),0) as total')
                    ->where('user_id', '=', $user->user_id)
                    ->where('status', '=', 'remind-me');

                $overdue_invoice_totals = Invoice::selectRaw('ifnull(count(invoice.invoice_id),0) as total')
                    ->where('user_id','=',$user->user_id)
                    ->whereIn('status',['sent-email', 'sent-text'])
                    ->where('has_paid','=','0')
                    ->where('due_date','<',Carbon::now()->format('Y-m-d H:i:s'));

                $get_task_totals = $global_tasks_totals
                    ->unionAll($client_tasks_totals)
                    ->unionAll($follow_up_totals)
                    ->unionAll($overdue_invoice_totals)
                    ->get();

                $global_tasks = UserTask::select([
                    'user_task.user_task_id',
                    'user_task.title',
                    'user_task.description',
                    'user_task.status',
                    'user_task.created_at'
                ])
                    ->where('user_id', '=', $user->user_id)
                    ->whereNull('user_task.client_id')
                    ->groupBy('user_task.user_task_id')
                    ->orderBy('user_task.created_at', 'desc')
                    ->take($tasks_per_page + 1)
                    ->get()
                    ->toArray();

                $client_tasks = UserTask::select([
                    'user_task.user_task_id',
                    'user_task.title',
                    'user_task.description',
                    'user_task.status',
                    'user_task.created_at',
                    'client.client_id',
                    'client.name as client_name'
                ])
                    ->leftJoin('client', 'client.client_id', '=', 'user_task.client_id')
                    ->where('user_task.user_id', '=', $user->user_id)
                    ->whereNotNull('client.client_id')
                    ->groupBy('user_task.user_task_id')
                    ->orderBy('user_task.created_at', 'desc')
                    ->take($tasks_per_page + 1)
                    ->get()
                    ->toArray();

                $has_more_global_tasks = count($global_tasks) > $tasks_per_page ? true : false;
                $has_more_client_tasks = count($client_tasks) > $tasks_per_page ? true : false;

                /**Calendar events*/
                $today_day_format = Carbon::now()->format('Y-m-d');
                $calendar_events = Event::select([
                    'event.event_id',
                    'event.start_date_time',
                    'event.end_date_time',
                    'event.status',
                    'event.other_status_text',
                    'client.name as client_name',
                    'client.company'
                ])
                    ->selectRaw('group_concat(concat(event_location.address," ",event_location.city," ",event_location.zip) SEPARATOR ", ") as address')
                    ->selectRaw('group_concat(concat(client_phone.phone,"__",ifnull(client_phone.phone_format,""))) as phone_numbers')
                    ->leftJoin('client', 'client.client_id', '=', 'event.client_id')
                    ->leftJoin('client_phone', 'client_phone.client_id', '=', 'client.client_id')
                    ->leftJoin('event_location', 'event_location.event_id', '=', 'event.event_id')
                    ->where('event.user_id', '=', $user->user_id)
                    ->where(DB::raw('date(event.start_date_time)'), '>=', $today_day_format)
                    ->where(DB::raw('date(event.end_date_time)'), '<=', $today_day_format)
                    ->groupBy('event.event_id')
                    ->get()
                    ->toArray();

                /**Missed or New Leads*/
                $new_leads_pages = Constant::GET_MISSED_NEW_LEADS_PER_PAGE_ITEMS();
                $not_listed_clients = Client::select('client.created_at as created_at')
                    ->selectRaw('client.client_id as client_id')
                    ->selectRaw('client.name as name')
                    ->selectRaw('client.company as company')
                    ->selectRaw('"not-listed" as status')
                    ->selectRaw('client_value.upfront_value as upfront_value')
                    ->selectRaw('client_value.ongoing_value as ongoing_value')
                    ->selectRaw('group_concat(client_phone.phone) as phone')
                    ->selectRaw('"client" as type')
                    ->selectRaw('client.client_id as actual_client_id')
                    ->selectRaw('client.email as email')
                    ->leftJoin('client_value', 'client_value.client_id', '=', 'client.client_id')
                    ->leftJoin('client_phone', 'client_phone.client_id', '=', 'client.client_id')
                    ->where('client.user_id', '=', $user->user_id)
                    ->where('client.status', '=', 'not-listed')
                    ->groupBy('client.client_id')
                    ->orderBy('client.created_at', 'desc');

                $missed_history = CallHistory::selectRaw('call_history.created_at as created_at')
                    ->selectRaw('call_history.call_history_id as client_id')
                    ->selectRaw('"" as name')
                    ->selectRaw('"" as company')
                    ->selectRaw('"not-listed" as status')
                    ->selectRaw('"" as upfront_value')
                    ->selectRaw('"" as ongoing_value')
                    ->selectRaw('call_history.phone as phone')
                    ->selectRaw('"missed" as type')
                    ->selectRaw('call_history.client_id as actual_client_id')
                    ->selectRaw('client.email as email')
                    ->leftJoin('client', 'client.client_id', '=', 'call_history.client_id')
                    ->where('call_history.user_id', '=', $user->user_id)
                    ->whereIn('call_history.type', ['incoming', 'missed'])
                    ->whereNull('client.client_id')
                    ->whereRaw("call_history.created_at in (
                        select max(ch.created_at) from call_history as ch
                        left join `client` as c ON c.`client_id` = `call_history`.`client_id`
                        where ch.`user_id` = $user->user_id and ch.`type` in ('incoming' , 'missed') and c.`client_id` is null
                        group by ch.phone
                    )")
                    ->groupBy('call_history.call_history_id');

                $missed_forms = UserFormData::selectRaw('user_form_data.created_at as created_at')
                    ->selectRaw('user_form_data.user_form_data_id as client_id')
                    ->selectRaw('user_form_data.contact_name as name')
                    ->selectRaw('"" as company')
                    ->selectRaw('"not-listed" as status')
                    ->selectRaw('"" as upfront_value')
                    ->selectRaw('"" as ongoing_value')
                    ->selectRaw('user_form_data.contact_phone as phone')
                    ->selectRaw('"form" as type')
                    ->selectRaw('user_form_data.client_id as actual_client_id')
                    ->selectRaw('"" as email')
                    ->where('user_form_data.user_id', '=', $user->user_id)
                    ->where('user_form_data.is_converted','=','0')
                    ->groupBy('user_form_data.user_form_data_id');

                $user_twilio_phone = UserTwilioPhone::where('user_id','=',$user->user_id)->first();
                $missed_messages = TextMessage::selectRaw('text_message.created_at as created_at')
                    ->selectRaw('text_message.text_message_id as client_id')
                    ->selectRaw('"" as name')
                    ->selectRaw('"" as company')
                    ->selectRaw('"not-listed" as status')
                    ->selectRaw('"" as upfront_value')
                    ->selectRaw('"" as ongoing_value')
                    ->selectRaw('case when text_message.client_sent = 1 then text_message.from_number else text_message.to_number end as phone')
                    ->selectRaw('"message" as type')
                    ->selectRaw('text_message.client_id as actual_client_id')
                    ->selectRaw('"" as email');

                if ($user_twilio_phone) {
                    $missed_messages->join(DB::raw("
                        (select max(m2.text_message_id) AS text_message_id
                        from  text_message as  m2
                        where m2.from_number = '$user_twilio_phone->phone' or m2.to_number = '$user_twilio_phone->phone'
                        group by  least(m2.from_number,m2.to_number), greatest(m2.to_number,m2.from_number)
                        ) m2"), function ($query) {
                        $query->on('text_message.text_message_id', '=', 'm2.text_message_id');
                    });
                }

                $missed_messages = $missed_messages
                    ->whereNull('text_message.client_id')
                    ->where('text_message.user_id', '=', $user->user_id)
                    ->groupBy('text_message.text_message_id')
                    ->orderBy('text_message.created_at', 'desc');

                $missed_data = $not_listed_clients
                    ->unionAll($missed_history)
                    ->unionAll($missed_forms)
                    ->unionAll($missed_messages)
                    ->orderBy('created_at', 'desc')
                    ->paginate($new_leads_pages + 1);

                $total_missed_items = $missed_data->total();
                $has_more_missed = $missed_data->count() > $new_leads_pages ? true : false;

                /**Follow Ups*/
                $follow_ups_per_page = Constant::GET_FOLLOW_UPS_PER_PAGE();
                $follow_ups = Event::select([
                    'event.event_id',
                    'event.client_id',
                    'event.start_date_time',
                    'event.end_date_time',
                    'event.other_status_text',
                    'event.upfront_value',
                    'event.ongoing_value',
                    'client.name as client_name',
                    'client.company',
                    'client.email'
                ])
                    ->selectRaw('group_concat(concat(event_location.address," ",event_location.city," ",event_location.zip) SEPARATOR ", ") as address')
                    ->selectRaw('group_concat(concat(client_phone.phone,"__",ifnull(client_phone.phone_format,""))) as phone_numbers')
                    ->leftJoin('client', 'client.client_id', '=', 'event.client_id')
                    ->leftJoin('client_phone','client_phone.client_id','=','client.client_id')
                    ->leftJoin('event_location', 'event_location.event_id', '=', 'event.event_id')
                    ->where('event.user_id', '=', $user->user_id)
                    ->where('event.status', '=', 'remind-me')
                    ->groupBy('event.event_id')
                    ->orderBy('event.start_date_time', 'asc')
                    ->take($follow_ups_per_page + 1)
                    ->get()
                    ->toArray();

                $has_more_follow_ups = count($follow_ups) > $follow_ups_per_page ? true : false;

                /**Overdue Invoices*/
                $invoices_per_page = Constant::GET_INVOICES_PER_PAGE();
                $overdue_invoices = Invoice::select([
                    'invoice.invoice_id',
                    'invoice.client_id',
                    'invoice.due_date',
                    'invoice.created_at',
                    'invoice.total_gross_amount',
                    'client.name',
                    'client.company',
                    'client.status'
                ])
                    ->selectRaw('invoice.status as invoice_status')
                    ->selectRaw('group_concat(concat(client_phone.phone,"__",ifnull(client_phone.phone_format,""))) as phone_numbers')
                    ->leftJoin('client','client.client_id','=','invoice.client_id')
                    ->leftJoin('client_phone','client_phone.client_id','=','client.client_id')
                    ->where('client.user_id','=',$user->user_id)
                    ->where('invoice.due_date','<',Carbon::now()->format('Y-m-d'))
                    ->where('invoice.has_paid','=','0')
                    ->whereIn('invoice.status',['sent-email', 'sent-text'])
                    ->groupBy('invoice.invoice_id')
                    ->orderBy('invoice.due_date','asc')
                    ->take($invoices_per_page + 1)
                    ->get()
                    ->toArray();

                $has_more_overdue_invoices = count($overdue_invoices) > $overdue_invoices ? true : false;
                return response()->json([
                    'status' => true,
                    'total_global_tasks' => $get_task_totals['0']->total,
                    'total_client_tasks' => $get_task_totals['1']->total,
                    'total_follow_up' => $get_task_totals['2']->total,
                    'total_overdue_invoices' => $get_task_totals['3']->total,
                    'global_tasks' => $has_more_global_tasks ? array_slice($global_tasks, 0, $tasks_per_page) : $global_tasks,
                    'has_global_more_tasks' => $has_more_global_tasks,
                    'global_tasks_per_page' => $tasks_per_page,
                    'client_tasks' => $has_more_client_tasks ? array_slice($client_tasks, 0, $tasks_per_page) : $client_tasks,
                    'has_client_more_tasks' => $has_more_client_tasks,
                    'client_tasks_per_page' => $tasks_per_page,
                    'calendar_events' => $calendar_events,
                    'missed_items' => $has_more_missed ? array_slice($missed_data->items(), 0, $new_leads_pages) : $missed_data->items(),
                    'has_more_missed_items' => $has_more_missed,
                    'total_missed_items' => $total_missed_items,
                    'follow_ups' => $has_more_follow_ups ? array_slice($follow_ups, 0, $follow_ups_per_page) : $follow_ups,
                    'has_more_follow_ups' => $has_more_follow_ups,
                    'overdue_invoices' => $overdue_invoices,
                    'has_more_overdue_invoices' => $has_more_overdue_invoices
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function loadClientTasks(Request $request)
    {
        if ($request->token) {
            $page_limit = Constant::GET_TASK_PAGE_TASKS_PER_PAGE();
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $tasks = UserTask::select([
                    'user_task.user_task_id',
                    'user_task.title',
                    'user_task.description',
                    'user_task.status',
                    'user_task.created_at',
                    'client.client_id',
                    'client.name as client_name'
                ])
                    ->leftJoin('client', 'client.client_id', '=', 'user_task.client_id')
                    ->where('user_task.user_id', '=', $user->user_id)
                    ->whereNotNull('client.client_id')
                    ->groupBy('user_task.user_task_id')
                    ->orderBy('user_task.created_at', 'desc')
                    ->skip(($request_params->page - 1) * $page_limit)
                    ->take($page_limit + 1)
                    ->get()
                    ->toArray();

                $has_more_tasks = count($tasks) > $page_limit ? true : false;
                $client_tasks_totals = UserTask::selectRaw('user_task_id')
                    ->where('user_id', '=', $user->user_id)
                    ->whereNotNull('client_id')
                    ->count();

                return response()->json([
                    'status' => true,
                    'tasks' => $has_more_tasks ? array_slice($tasks, 0, $page_limit) : $tasks,
                    'has_more_tasks' => $has_more_tasks,
                    'total_client_tasks' => $client_tasks_totals,
                    'client_tasks_per_page' => $page_limit
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function loadGlobalTasks(Request $request)
    {
        if ($request->token) {
            $page_limit = Constant::GET_TASK_PAGE_TASKS_PER_PAGE();
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $tasks = UserTask::select([
                    'user_task.user_task_id',
                    'user_task.title',
                    'user_task.description',
                    'user_task.status',
                    'user_task.created_at'
                ])
                    ->leftJoin('client', 'client.client_id', '=', 'user_task.client_id')
                    ->where('user_task.user_id', '=', $user->user_id)
                    ->whereNull('user_task.client_id')
                    ->groupBy('user_task.user_task_id')
                    ->orderBy('user_task.created_at', 'desc')
                    ->skip(($request_params->page - 1) * $page_limit)
                    ->take($page_limit + 1)
                    ->get()
                    ->toArray();

                $has_more_tasks = count($tasks) > $page_limit ? true : false;
                $global_tasks_totals = UserTask::selectRaw('user_task_id')
                    ->where('user_id', '=', $user->user_id)
                    ->whereNull('client_id')
                    ->count();

                return response()->json([
                    'status' => true,
                    'tasks' => $has_more_tasks ? array_slice($tasks, 0, $page_limit) : $tasks,
                    'has_more_tasks' => $has_more_tasks,
                    'total_global_tasks' => $global_tasks_totals,
                    'global_tasks_per_page' => $page_limit
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function loadMissedItems(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                /**Missed or New Leads*/
                $new_leads_pages = Constant::GET_MISSED_NEW_LEADS_PER_PAGE_ITEMS();
                $not_listed_clients = Client::select('client.created_at as created_at')
                    ->selectRaw('client.client_id as client_id')
                    ->selectRaw('client.name as name')
                    ->selectRaw('client.company as company')
                    ->selectRaw('"not-listed" as status')
                    ->selectRaw('client_value.upfront_value as upfront_value')
                    ->selectRaw('client_value.ongoing_value as ongoing_value')
                    ->selectRaw('group_concat(client_phone.phone) as phone')
                    ->selectRaw('"client" as type')
                    ->selectRaw('client.client_id as actual_client_id')
                    ->selectRaw('client.email as email')
                    ->leftJoin('client_value', 'client_value.client_id', '=', 'client.client_id')
                    ->leftJoin('client_phone', 'client_phone.client_id', '=', 'client.client_id')
                    ->where('client.user_id', '=', $user->user_id)
                    ->where('client.status', '=', 'not-listed')
                    ->groupBy('client.client_id')
                    ->orderBy('client.created_at', 'desc');

                $missed_history = CallHistory::selectRaw('call_history.created_at as created_at')
                    ->selectRaw('call_history.call_history_id as client_id')
                    ->selectRaw('"" as name')
                    ->selectRaw('"" as company')
                    ->selectRaw('"not-listed" as status')
                    ->selectRaw('"" as upfront_value')
                    ->selectRaw('"" as ongoing_value')
                    ->selectRaw('call_history.phone as phone')
                    ->selectRaw('"missed" as type')
                    ->selectRaw('call_history.client_id as actual_client_id')
                    ->selectRaw('client.email as email')
                    ->leftJoin('client', 'client.client_id', '=', 'call_history.client_id')
                    ->where('call_history.user_id', '=', $user->user_id)
                    ->whereIn('call_history.type', ['incoming', 'missed'])
                    ->whereNull('client.client_id')
                    ->whereRaw("call_history.created_at in (
                        select max(ch.created_at) from call_history as ch
                        left join `client` as c ON c.`client_id` = `call_history`.`client_id`
                        where ch.`user_id` = $user->user_id and ch.`type` in ('incoming' , 'missed') and c.`client_id` is null
                        group by ch.phone
                    )")
                    ->groupBy('call_history.call_history_id');

                $missed_forms = UserFormData::selectRaw('user_form_data.created_at as created_at')
                    ->selectRaw('user_form_data.user_form_data_id as client_id')
                    ->selectRaw('user_form_data.contact_name as name')
                    ->selectRaw('"" as company')
                    ->selectRaw('"not-listed" as status')
                    ->selectRaw('"" as upfront_value')
                    ->selectRaw('"" as ongoing_value')
                    ->selectRaw('user_form_data.contact_phone as phone')
                    ->selectRaw('"form" as type')
                    ->selectRaw('user_form_data.client_id as actual_client_id')
                    ->selectRaw('"" as email')
                    ->where('user_form_data.user_id', '=', $user->user_id)
                    ->where('user_form_data.is_converted','=','0')
                    ->groupBy('user_form_data.user_form_data_id');

                $user_twilio_phone = UserTwilioPhone::where('user_id','=',$user->user_id)->first();
                $missed_messages = TextMessage::selectRaw('text_message.created_at as created_at')
                    ->selectRaw('text_message.text_message_id as client_id')
                    ->selectRaw('"" as name')
                    ->selectRaw('"" as company')
                    ->selectRaw('"not-listed" as status')
                    ->selectRaw('"" as upfront_value')
                    ->selectRaw('"" as ongoing_value')
                    ->selectRaw('case when text_message.client_sent = 1 then text_message.from_number else text_message.to_number end as phone')
                    ->selectRaw('"message" as type')
                    ->selectRaw('text_message.client_id as actual_client_id')
                    ->selectRaw('"" as email');

                if ($user_twilio_phone) {
                    $missed_messages->join(DB::raw("
                        (select max(m2.text_message_id) AS text_message_id
                        from  text_message as  m2
                        where m2.from_number = '$user_twilio_phone->phone' or m2.to_number = '$user_twilio_phone->phone'
                        group by  least(m2.from_number,m2.to_number), greatest(m2.to_number,m2.from_number)
                        ) m2"), function ($query) {
                        $query->on('text_message.text_message_id', '=', 'm2.text_message_id');
                    });
                }

                $missed_messages = $missed_messages
                    ->whereNull('text_message.client_id')
                    ->where('text_message.user_id', '=', $user->user_id)
                    ->groupBy('text_message.text_message_id')
                    ->orderBy('text_message.created_at', 'desc');

                Paginator::currentPageResolver(function () use ($request_params) {
                    return $request_params->page;
                });

                $missed_data = $not_listed_clients
                    ->unionAll($missed_history)
                    ->unionAll($missed_forms)
                    ->unionAll($missed_messages)
                    ->orderBy('created_at', 'desc')
                    ->paginate($new_leads_pages);

                $total_missed_items = $missed_data->total();
                $has_more_missed = ($total_missed_items && ceil($total_missed_items / $new_leads_pages) > $request_params->page) ? true : false;
                return response()->json([
                    'status' => true,
                    'missed_items' => $has_more_missed ? array_slice($missed_data->items(), 0, $new_leads_pages) : $missed_data->items(),
                    'has_more_missed_items' => $has_more_missed,
                    'total_missed_items' => $total_missed_items,
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function loadFollowUpItems(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $page_limit = Constant::GET_FOLLOW_UPS_PER_PAGE();
                $follow_ups = Event::select([
                    'event.client_id',
                    'event.event_id',
                    'event.start_date_time',
                    'event.end_date_time',
                    'event.other_status_text',
                    'event.upfront_value',
                    'event.ongoing_value',
                    'client.name as client_name',
                    'client.company',
                    'client.email'
                ])
                    ->selectRaw('group_concat(concat(event_location.address," ",event_location.city," ",event_location.zip) SEPARATOR ", ") as address')
                    ->selectRaw('group_concat(concat(client_phone.phone,"__",ifnull(client_phone.phone_format,""))) as phone_numbers')
                    ->leftJoin('client', 'client.client_id', '=', 'event.client_id')
                    ->leftJoin('client_phone','client_phone.client_id','=','client.client_id')
                    ->leftJoin('event_location', 'event_location.event_id', '=', 'event.event_id')
                    ->where('event.user_id', '=', $user->user_id)
                    ->where('client.user_id', '=', $user->user_id)
                    ->where('event.status', '=', 'remind-me')
                    ->groupBy('event.event_id')
                    ->orderBy('event.start_date_time', 'asc')
                    ->skip(($request_params->page - 1) * $page_limit)
                    ->take($page_limit + 1)
                    ->get()
                    ->toArray();

                $follow_up_totals = Event::select('event.event_id')
                    ->where('user_id', '=', $user->user_id)
                    ->where('status', '=', 'remind-me')
                    ->count();

                $has_more_follow_ups = count($follow_ups) > $page_limit ? true : false;
                return response()->json([
                    'status' => true,
                    'follow_ups' => $has_more_follow_ups ? array_slice($follow_ups, 0, $page_limit) : $follow_ups,
                    'has_more_follow_ups' => $has_more_follow_ups,
                    'total_follow_ups' => $follow_up_totals
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function addGlobalTask(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $model = new UserTask();
                $model->user_id = $user->user_id;
                $model->title = $request_params->title;
                $model->description = $request_params->description;
                $model->status = 'pending';
                $model->save();

                return response()->json([
                    'status' => true,
                    'user_task_id' => $model->user_task_id
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function updateGlobalTask(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $user_task = UserTask::where('user_id', '=', $user->user_id)
                    ->whereNull('client_id')
                    ->find($request_params->user_task_id);

                if ($user_task) {
                    $user_task->title = $request_params->title ?? $user_task->title;
                    $user_task->description = $request_params->description ?? $user_task->description;
                    $user_task->status = ($request_params->status && in_array($request_params->status, Constant::GET_TASK_STATUS_TYPES())) ? $request_params->status : $user_task->status;
                    $user_task->update();
                    return response()->json([
                        'status' => true
                    ]);
                }
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function deleteGlobalTask(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $user_task = UserTask::where('user_id', '=', $user->user_id)
                    ->whereNull('client_id')
                    ->find($request_params->user_task_id);

                if ($user_task) {
                    $user_task->delete();
                    return response()->json([
                        'status' => true
                    ]);
                }
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function addClientTask(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $client = Client::where('user_id', '=', $user->user_id)->find($request_params->client_id);
                if ($client) {
                    $model = new UserTask();
                    $model->user_id = $user->user_id;
                    $model->client_id = $client->client_id;
                    $model->title = $request_params->title;
                    $model->description = $request_params->description;
                    $model->status = 'pending';
                    $model->save();

                    return response()->json([
                        'status' => true,
                        'user_task_id' => $model->user_task_id
                    ]);
                }
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function updateClientTask(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $user_task = UserTask::where('user_id', '=', $user->user_id)
                    ->whereNotNull('client_id')
                    ->find($request_params->user_task_id);

                if ($user_task) {
                    $user_task->title = $request_params->title ?? $user_task->title;
                    $user_task->description = $request_params->description ?? $user_task->description;
                    $user_task->status = ($request_params->status && in_array($request_params->status, Constant::GET_TASK_STATUS_TYPES())) ? $request_params->status : $user_task->status;
                    $user_task->update();
                    return response()->json([
                        'status' => true
                    ]);
                }
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function deleteClientTask(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $user_task = UserTask::where('user_id', '=', $user->user_id)
                    ->whereNotNull('client_id')
                    ->find($request_params->user_task_id);

                if ($user_task) {
                    $user_task->delete();
                    return response()->json([
                        'status' => true
                    ]);
                }
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function countries(Request $request)
    {
        $user = [];
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();
        }

        $countries = Country::select('country_id', 'name')
            ->get()
            ->toArray();

        return response()->json([
            'status' => true,
            'countries' => $countries,
            'default_currency' => $user ? Helper::getCountryCurrencyCode($user->Country) : null,
            'available_currencies' => Helper::getAvailableCurrenciesList()
        ]);
    }

    public function addInvoice(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $client = Client::where('user_id', '=', $user->user_id)->find($request_params->client_id);

                if ($client) {
                    $model = new Invoice();
                    $model->user_id = $user->user_id;
                    $model->client_id = $client->client_id;
                    $model->phone = $request_params->phone;
                    $model->email = $request_params->email;
                    $model->city = $request_params->city;
                    $model->state = $request_params->state;
                    $model->zip = $request_params->zip;
                    $model->address = $request_params->address;
                    $model->country_id = $request_params->country_id;
                    $model->gst_number = $request_params->gst_number;
                    $model->issued_date = $request_params->issued_date;
                    $model->due_date = $request_params->due_date;
                    $model->fulfillment_date = $request_params->fulfillment_date;
                    $model->payment_deadline_days = $request_params->payment_deadline_days;
                    $model->payment_method = $request_params->payment_method;
                    $model->currency = $request_params->currency;
                    $model->discount_type = in_array($request_params->discount_type, Constant::GET_DISCOUNT_TYPES()) ? $request_params->discount_type : 'percentage';
                    $model->discount = $request_params->discount;
                    $model->online_payment_type = $request_params->online_payment_type;
                    $model->status = 'pending';
                    $model->note = $request_params->note;
                    $model->is_public_note = $request_params->is_public_note ? 1 : 0;
                    $model->net_value_without_tax = $request_params->net_value_without_tax;
                    $model->tax_amount = $request_params->tax_amount;
                    $model->amount_without_discount = $request_params->amount_without_discount;
                    $model->discount_amount = $request_params->discount_amount;
                    $model->total_gross_amount = $request_params->total_gross_amount;
                    $model->invoice_unique_number = $user->user_id.$model->client_id.uniqid();
                    $model->save();

                    $model->invoice_number_label = Carbon::now()->format('Y-nj').($model->invoice_id + 1000);
                    $model->update();

                    if (isset($request_params->invoice_items)) {
                        $invoice_items = [];
                        $today_date = Carbon::now()->format('Y-m-d H:i:s');
                        foreach ($request_params->invoice_items as $key => $item) {
                            $invoice_items[] = [
                                'invoice_id' => $model->invoice_id,
                                'title' => $item->title,
                                'description' => $item->description,
                                'unit_price' => $item->unit_price,
                                'tax_rate' => strlen($item->tax_rate) ? $item->tax_rate : null,
                                'qty' => $item->qty,
                                'order_num' => $key,
                                'created_at' => $today_date,
                                'updated_at' => $today_date
                            ];
                        }

                        if ($invoice_items) {
                            InvoiceItem::insert($invoice_items);
                        }
                    }

                    /**Track History*/
                    Helper::addClientActivity($client->client_id, $model->invoice_id, 'Invoice Sent', null, null, null, 'invoice-added');

                    return response()->json([
                        'status' => true,
                        'invoice_id' => $model->invoice_id
                    ]);
                }
            }

            return response()->json([
                'status' => false,
                'error' => 'client not found'
            ]);
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function deleteInvoice(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $invoice = Invoice::where('user_id', '=', $user->user_id)->find($request_params->invoice_id);
                if ($invoice) {
                    InvoiceItem::where('invoice_id', '=', $invoice->invoice_id)->delete();
                    $invoice->delete();
                }

                return response()->json([
                    'status' => true
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function invoiceDetails(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::with('UserInvoiceSetting')
                ->where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $invoice = Invoice::with([
                    'Client',
                    'Country',
                    'InvoiceItem' => function ($query) {
                        $query->orderBy('invoice_item.order_num', 'asc');
                    }])
                    ->where('user_id', '=', $user->user_id)
                    ->find($request_params->invoice_id);

                if ($invoice) {
                    $invoice_items = [];
                    foreach ($invoice->InvoiceItem as $item) {
                        $invoice_items[] = [
                            'invoice_item_id' => $item->invoice_item_id,
                            'title' => $item->title,
                            'description' => $item->description,
                            'unit_price' => $item->unit_price,
                            'tax_rate' => $item->tax_rate,
                            'qty' => $item->qty
                        ];
                    }

                    $company_details = [
                        'company_name' => null,
                        'invoice_email' => null,
                        'country' => null,
                        'zip' => null,
                        'city' => null,
                        'state' => null,
                        'address' => null,
                        'vat_id' => null,
                        'company_registration_number' => null,
                        'na_vat_id' => null,
                        'bank_account_number' => null,
                        'iban' => null,
                        'bank_name' => null,
                        'swift' => null
                    ];

                    $user_invoice = UserInvoiceSetting::where('user_id','=',$user->user_id)->first();
                    if ($user_invoice) {
                        $company_details['company_name'] = $user_invoice->company_name;
                        $company_details['invoice_email'] = $user_invoice->email;
                        $company_details['country'] = $user_invoice->Country ? $user_invoice->Country->name : null;
                        $company_details['zip_code'] = $user_invoice->zip_code;
                        $company_details['city'] = $user_invoice->city;
                        $company_details['state'] = $user_invoice->state;
                        $company_details['address'] = $user_invoice->address;
                        $company_details['vat_id'] = $user_invoice->gst_vat;
                        $company_details['company_registration_number'] = $user_invoice->company_registration_number;
                        $company_details['na_vat_id'] = '';
                        $company_details['bank_account_number'] = $user_invoice->bank_account_number;
                        $company_details['iban'] = $user_invoice->bank_account_iban;
                        $company_details['bank_name'] = '';
                        $company_details['swift'] = $user_invoice->bank_account_routing_swift;
                    }

                    return response()->json([
                        'status' => true,
                        'invoice' => [
                            'client_id' => $invoice->client_id,
                            'client_name' => $invoice->Client ? $invoice->Client->name : '',
                            'country_name' => $invoice->Country ? $invoice->Country->name : '',
                            'phone' => $invoice->phone,
                            'email' => $invoice->email,
                            'city' => $invoice->city,
                            'state' => $invoice->state,
                            'zip' => $invoice->zip,
                            'address' => $invoice->address,
                            'gst_number' => $invoice->gst_number,
                            'issued_date' => $invoice->issued_date,
                            'due_date' => $invoice->due_date,
                            'fulfillment_date' => $invoice->fulfillment_date,
                            'payment_deadline_days' => $invoice->payment_deadline_days,
                            'payment_method' => $invoice->payment_method,
                            'currency' => $invoice->currency,
                            'discount_type' => $invoice->discount_type,
                            'discount' => $invoice->discount,
                            'online_payment_type' => $invoice->online_payment_type,
                            'status' => $invoice->status,
                            'note' => $invoice->note,
                            'is_public_note' => $invoice->is_public_note,
                            'net_value_without_tax' => $invoice->net_value_without_tax,
                            'tax_amount' => $invoice->tax_amount,
                            'amount_without_discount' => $invoice->amount_without_discount,
                            'discount_amount' => $invoice->discount_amount,
                            'total_gross_amount' => $invoice->total_gross_amount,
                            'invoice_items' => $invoice_items
                        ],
                        'company' => $company_details
                    ]);
                }
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function sendInvoice(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);

            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $invoices = Invoice::with('Client','InvoiceItem')->where('user_id','=',$user->user_id)->whereIn('invoice_id',$request_params->invoice_ids)->get();
                if ($invoices->count()) {
                    $target = (isset($request_params->target) && $request_params->target) ? $request_params->target : null;
                    $status =  Helper::sendInvoices($user, $invoices, $request_params->type, $request_params->content, $request_params->invoice_ids, $target);
                    if ($status) {
                        return response()->json([
                            'status' => true
                        ]);
                    }
                    else{
                        return response()->json([
                            'status' => false,
                            'error' => 'Unable to send an invoice, please try again later'
                        ]);
                    }

                } else {
                    return response()->json([
                        'status' => false,
                        'error' => 'invoice_not_found'
                    ]);
                }
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function getInvoices(Request $request)
    {
        if ($request->token) {
            $page_limit = Constant::GET_INVOICES_PER_PAGE();
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $get_invoices = Invoice::select([
                    'invoice.invoice_id',
                    'invoice.client_id',
                    'invoice.due_date',
                    'invoice.created_at',
                    'invoice.total_gross_amount',
                    'invoice.has_paid',
                    'invoice.status as invoice_status',
                    'invoice.currency',
                    'invoice.has_paid',
                    'client.name',
                    'client.company',
                    'client.status'
                ])
                    ->selectRaw('invoice.status as invoice_status')
                    ->selectRaw('group_concat(concat(client_phone.phone,"__",ifnull(client_phone.phone_format,""))) as phone_numbers')
                    ->leftJoin('client','client.client_id','=','invoice.client_id')
                    ->leftJoin('client_phone','client_phone.client_id','=','client.client_id')
                    ->where('client.user_id','=',$user->user_id);

                if (isset($request_params->client_id) && $request_params->client_id) {
                    $get_invoices->where('invoice.client_id','=',$request_params->client_id);
                }

                $has_overdue_filter = (isset($request_params->is_overdue) && $request_params->is_overdue) ? true : false;
                if ($has_overdue_filter) {
                    $get_invoices
                        ->where('invoice.due_date','<',Carbon::now()->format('Y-m-d'))
                        ->where('invoice.has_paid','=','0')
                        ->whereIn('invoice.status',['sent-email', 'sent-text']);
                }

                $invoices_obj = clone $get_invoices;
                $total_invoices = $invoices_obj->count();

                $request_params->page = $request_params->page ?? 1;
                $get_invoices = $get_invoices
                    ->groupBy('invoice.invoice_id')
                    ->orderBy('invoice.due_date','asc')
                    ->skip(($request_params->page - 1) * $page_limit)
                    ->take($page_limit + 1)
                    ->get()
                    ->toArray();

                foreach ($get_invoices as &$item) {
                    $item['is_overdue'] = false;
                    if ($has_overdue_filter || (in_array($item['invoice_status'],['sent-email','sent-text']) && !$item['has_paid'] && Carbon::createFromFormat('Y-m-d',$item['due_date'])->timestamp < Carbon::createFromFormat('Y-m-d',Carbon::now()->format('Y-m-d'))->timestamp)) {
                        $item['is_overdue'] = true;
                    }
                }

                $has_more_invoices = count($get_invoices) > $page_limit ? true : false;
                return response()->json([
                    'status' => true,
                    'invoices' => $has_more_invoices ? array_slice($get_invoices, 0, $page_limit) : $get_invoices,
                    'has_more_invoices' => $has_more_invoices,
                    'total_invoices' => $total_invoices
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function setLead(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $client = Client::with('ClientLastValue')->where('user_id','=',$user->user_id)->find($request_params->client_id);
                if ($client) {
                    $client->status = 'lead';
                    $client->update();

                    if ($client->ClientLastValue) {
                        $client->ClientLastValue->status = 'lead';
                        $client->ClientLastValue->update();
                    }

                    /**Check for Task Management request*/
                    $show_success_badge = '';
                    if (isset($request_params->calculate_progress) && $request_params->calculate_progress) {
                        $show_success_badge = $this->calculateTotalMissedSuccessBadge($user);
                    }

                    return response()->json([
                        'status' => true,
                        'show_success_badge' => $show_success_badge
                    ]);
                }
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function updateClientStatus(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);

            $client = Client::with('ClientLastValue','User')
                ->select('client.*')
                ->leftJoin('user', 'user.user_id', '=', 'client.user_id')
                ->where('user.mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('user.active', '=', '1')
                ->find($request_params->client_id);

            $client_statuses = Constant::GET_CLIENT_STATUS_LIST();
            if ($client && array_key_exists($request_params->status,$client_statuses)) {
                $old_status = $client->status;
                $client->status = $request_params->status;

                if ($client->ClientLastValue) {
                    $client->ClientLastValue->status = $request_params->status;
                    $client->ClientLastValue->update();

                    switch ($client->status) {
                        case 'not-listed':
                        case 'lead':
                            $client->quote_meeting_date_time = null;
                            $client->work_started_date_time = null;
                        break;
                        case 'quote-meeting':
                            $client->quote_meeting_date_time = Carbon::now()->format('Y-m-d H:i:s');
                            $client->work_started_date_time = null;
                        break;
                        case 'work-in-progress':
                            $client->work_started_date_time = Carbon::now()->format('Y-m-d H:i:s');
                        break;
                        case 'completed':
                            if ($old_status !== 'completed' && $client->email) {
                                if (!$client->ClientLastValue->unique_code) {
                                    $client->ClientLastValue->unique_code = md5($client->ClientLastValue->client_value_id.uniqid().rand(1,100));
                                    $client->ClientLastValue->unique_code->update();
                                }

                                Helper::queueLeaveReviewRequest($client->email, 'email', $client->User, $client->ClientLastValue->unique_code);
                            }
                        break;
                    }
                }

                $client->update();

                /**Create Activity*/
                if ($old_status !== $client->status) {
                    $status_label = (isset($client_statuses[$client->status])) ? $client_statuses[$client->status] : null;
                    Helper::addClientActivity($client->client_id, $client->client_id, 'Status Changed', $status_label, null, null, 'client_status');
                }

                return response()->json([
                    'status' => true
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function setInvoicePaid(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $invoice = Invoice::select('invoice.*')
                ->leftJoin('client', 'client.client_id', '=', 'invoice.client_id')
                ->leftJoin('user','user.user_id','=','client.user_id')
                ->where('user.mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('user.active', '=', '1')
                ->find($request_params->invoice_id);

            if ($invoice) {
                $invoice->has_paid = '1';
                $invoice->update();

                return response()->json([
                    'status' => true
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function getClientActivity(Request $request)
    {
        if ($request->token) {
            $page_limit = Constant::GET_ACITIVIES_PER_PAGE();
            $request_params = $this->decodeJWTRequest($request->token);

            $client = Client::select('client.*')
                ->leftJoin('user', 'user.user_id', '=', 'client.user_id')
                ->where('user.mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('user.active', '=', '1')
                ->find($request_params->client_id);

            if ($client) {
                $request_params->page = $request_params->page ?? 1;
                $client_activity = ClientHistory::select([
                    'client_history_id',
                    'related_id',
                    'title',
                    'description',
                    'type',
                    'start_date_time',
                    'end_date_time',
                    'created_at'
                ])
                    ->where('client_id','=',$client->client_id)
                    ->groupBy('client_history_id')
                    ->orderBy('created_at','asc')
                    ->skip(($request_params->page - 1) * $page_limit)
                    ->take($page_limit + 1)
                    ->get()
                    ->toArray();

                $has_more_activity = count($client_activity) > $page_limit ? true : false;
                return response()->json([
                    'status' => true,
                    'activities' => $client_activity ? array_slice($client_activity, 0, $page_limit) : $client_activity,
                    'has_more_activity' => $has_more_activity,
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function getFormDetails(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $user_form = UserFormData::where('user_id','=',$user->user_id)->find($request_params->id);
                if ($user_form) {
                    return response()->json([
                        'status' => true,
                        'form' => [
                            'contact_name' => $user_form->contact_name,
                            'contact_phone' => $user_form->contact_phone,
                            'contact_response' => $user_form->contact_response,
                            'is_converted' => $user_form->is_converted,
                            'created_at' => $user_form->created_at
                        ]
                    ]);
                }
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function setFormLead(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $user_form = UserFormData::where('user_id','=',$user->user_id)->find($request_params->id);

                if ($user_form) {
                    $user_form->is_converted = '1';
                    $user_form->client_id = (isset($request_params->client_id)) ? $request_params->client_id : null;
                    $user_form->update();

                    /**Track History*/
                    if ($user_form->client_id) {
                        Helper::addClientActivity($user_form->client_id, $user_form->user_form_data_id, 'Form Received', null, null, null, 'form');
                    }
                }

                return response()->json([
                    'status' => true
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function getLatestInteraction(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $interactions = null;
                $latest_call_history = null;
                $latest_message = null;
                $latest_form_data = null;

                switch ($request_params->type) {
                    case 'client':
                        $client = Client::where('user_id','=',$user->user_id)->find($request_params->id);
                        if ($client) {
                            $latest_call_history = CallHistory::where('client_id','=',$client->client_id)->orderBy('created_at','asc')->first();
                            $latest_message = TextMessage::where('client_id','=',$client->client_id)->orderBy('created_at','asc')->first();
                            $latest_form_data = UserFormData::where('client_id','=',$client->client_id)->first();
                        }
                    break;
                    case 'history':
                        $latest_call_history = CallHistory::where('user_id','=',$user->user_id)->find($request_params->id);
                        if ($latest_call_history) {
                            $latest_message = TextMessage::where('user_id','=',$user->user_id)
                                ->where(function($query) use ($latest_call_history){
                                    $query
                                        ->where('to_number','=',$latest_call_history->phone)
                                        ->orWhere('from_number','=',$latest_call_history->phone);
                                })
                                ->orderBy('created_at','asc')
                                ->take(1)
                                ->get()
                                ->first();

                            $latest_form_data = UserFormData::where('user_id','=',$user->user_id)
                                ->where('contact_phone','=',$latest_call_history->phone)
                                ->orderBy('created_at','asc')
                                ->first();
                        }
                    break;
                    case 'form':
                        $latest_form_data = UserFormData::where('user_id','=',$user->user_id)->find($request_params->id);
                        if ($latest_form_data && $latest_form_data->contact_phone) {
                            $latest_call_history = CallHistory::where('user_id','=',$user->user_id)
                                ->where('phone','=',$latest_form_data->contact_phone)
                                ->orderBy('created_at','asc')
                                ->first();

                            $latest_message = TextMessage::where('user_id','=',$user->user_id)
                                ->where(function($query) use ($latest_form_data){
                                    $query
                                        ->where('to_number','=',$latest_form_data->contact_phone)
                                        ->orWhere('from_number','=',$latest_form_data->contact_phone);
                                })
                                ->orderBy('created_at','asc')
                                ->first();
                        }
                    break;
                    case 'message':
                        $latest_message = TextMessage::where('user_id','=',$user->user_id)->find($request_params->id);
                        if ($latest_message) {
                            $user_twilio = UserTwilioPhone::where('user_id','=',$user->user_id)->first();
                            if ($user_twilio) {
                                $phone = ($user_twilio == $latest_message->from_number) ? $latest_message->to_number : $latest_message->from_number;
                                $latest_call_history = CallHistory::where('phone','=',$phone)->orderBy('created_at','asc')->first();
                                $latest_form_data = UserFormData::where('contact_phone','=',$phone)->first();
                            }
                        }
                    break;
                }

                if ($latest_call_history || $latest_message || $latest_form_data) {
                    $current_timestamp = Carbon::now()->timestamp;
                    $latest_call_history_timestamp = ($latest_call_history) ? $latest_call_history->created_at->timestamp : $current_timestamp;
                    $latest_message_timestamp = ($latest_message) ? $latest_message->created_at->timestamp : $current_timestamp;
                    $latest_form_timestamp = ($latest_form_data) ? $latest_form_data->created_at->timestamp : $current_timestamp;

                    $latest_date_timestamp = min([$latest_call_history_timestamp, $latest_message_timestamp, $latest_form_timestamp]);

                    if ($latest_call_history_timestamp == $latest_date_timestamp) {
                        /**Latest Call History*/
                        $interactions = [
                            'date_obj' => $latest_call_history->created_at,
                            'call_history_type' => $latest_call_history->type,
                            'type' => 'history'
                        ];
                    }
                    elseif($latest_message_timestamp == $latest_date_timestamp) {
                        /**Latest Message*/
                        $interactions = [
                            'date_obj' => $latest_message->created_at,
                            'message' => $latest_message->message,
                            'sender' => $latest_message->client_sent ? 'client' : 'customer',
                            'type' => 'message'
                        ];
                    }
                    else{
                        /**Latest Form*/
                        $interactions = [
                            'date_obj' => $latest_form_data->created_at,
                            'url' => $latest_form_data->url,
                            'form_data' => $latest_form_data->contact_response,
                            'type' => 'form'
                        ];
                    }
                }

                return response()->json([
                    'status' => true,
                    'interactions' => $interactions
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function deleteTopicMessages(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            if (isset($request_params->text_message_ids)) {
                $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                    ->where('active', '=', '1')
                    ->first();

                if ($user) {
                    $total_items_deleted = 0;
                    TextMessage::select('text_message.*',)
                        ->where('text_message.user_id', '=', $user->user_id)
                        ->whereIn('text_message.text_message_id',$request_params->text_message_ids)
                        ->chunk(1000,function($items) use ($user,&$total_items_deleted) {
                            foreach ($items as $item) {
                                $total_items_deleted++;
                                TextMessage::where('user_id','=',$user->user_id)
                                    ->whereRaw("
                                        (
                                            (from_number = '$item->from_number' and to_number = '$item->to_number') or
                                            (to_number = '$item->from_number' and from_number = '$item->to_number')
                                        )"
                                    )
                                    ->delete();
                            }
                        });

                    $messages_data = [];
                    if (isset($request_params->page) && $total_items_deleted > 0) {
                        $page_limit = Constant::GET_TOPIC_MESSAGES_COUNT();
                        $total_pages_deleted = ceil($total_items_deleted / $page_limit);
                        $user_phone = UserTwilioPhone::where('user_id', '=', $user->user_id)->first();

                        $messages = TextMessage::with('TextMessageMedia')
                            ->select([
                                'text_message.text_message_id',
                                'text_message.client_id',
                                'text_message.message',
                                'text_message.from_number',
                                'text_message.to_number',
                                'text_message.client_sent',
                                'text_message.created_at',
                                'text_message.has_read',
                                'client.name',
                            ])
                            ->leftJoin('client', 'client.client_id', '=', 'text_message.client_id')
                            ->where('text_message.user_id', '=', $user->user_id);

                            if ($user_phone) {
                                $messages->join(DB::raw("
                                    (select max(m2.text_message_id) AS text_message_id
                                    from  text_message as  m2
                                    where m2.from_number = '$user_phone->phone' or m2.to_number = '$user_phone->phone'
                                    group by  least(m2.from_number,m2.to_number), greatest(m2.to_number,m2.from_number)
                                    ) m2"), function ($query) {
                                    $query->on('text_message.text_message_id', '=', 'm2.text_message_id');
                                });
                            }

                        if (isset($request_params->client_id) && $request_params->client_id) {
                            $messages->where('text_message.client_id', '=', $request_params->client_id);
                        }

                        if (isset($request_params->phone) && $request_params->phone) {
                            $messages->where(function ($query) use ($request_params) {
                                $query
                                    ->where('text_message.from_number', '=', $request_params->phone)
                                    ->orWhere('text_message.to_number', '=', $request_params->phone);
                            });
                        }

                        $messages = $messages
                            ->groupBy('text_message.text_message_id')
                            ->orderBy('text_message.created_at', 'desc')
                            ->skip(($request_params->page - $total_pages_deleted) * $page_limit)
                            ->take($total_pages_deleted * $page_limit + 1)
                            ->get();

                        $messages_data = [];
                        foreach ($messages as $item) {
                            $media_files = [];
                            foreach ($item->TextMessageMedia as $media_item) {
                                $media_files[] = env('APP_URL') . '/text_media/' . $media_item->file_name;
                            }

                            $messages_data[] = [
                                'text_message_id' => $item->text_message_id,
                                'client_id' => $item->client_id,
                                'client_name' => $item->name,
                                'message' => $item->message,
                                'phone' => ($item->from_number == $user_phone->phone) ? $item->to_number : $item->from_number,
                                'has_unread' => ($item->to_number == $user_phone->phone && !$item->has_read) ? true : false,
                                'message_date' => $item->created_at,
                                'media_files' => $media_files
                            ];
                        }
                    }

                    $has_more_pages = count($messages_data) > $page_limit ? true : false;

                    return response()->json([
                        'status' => true,
                        'items' => $has_more_pages ? array_slice($messages_data,0,$page_limit) : $messages_data,
                        'has_more_pages' => $has_more_pages
                    ]);
                }
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function markTopicMessagesRead(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            if (isset($request_params->text_message_ids)) {
                $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                    ->where('active', '=', '1')
                    ->first();

                if ($user) {
                    TextMessage::select('text_message.*',)
                        ->where('text_message.user_id', '=', $user->user_id)
                        ->whereIn('text_message.text_message_id',$request_params->text_message_ids)
                        ->chunk(1000,function($items) use ($user) {
                            foreach ($items as $item) {
                                TextMessage::where('user_id','=',$user->user_id)
                                    ->whereRaw("
                                        (
                                            (from_number = '$item->from_number' and to_number = '$item->to_number') or
                                            (to_number = '$item->from_number' and from_number = '$item->to_number')
                                        )"
                                    )
                                    ->where('client_sent','=','0')
                                    ->update([
                                        'has_read' => '1'
                                    ]);
                            }
                        });

                    return response()->json([
                        'status' => true
                    ]);
                }
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function addDeviceToken(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);

            if (isset($request_params->device_token) && isset($request_params->device_id)) {
                $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                    ->where('active', '=', '1')
                    ->first();

                if ($user) {

                    if (!$user->mobile_first_login_date_time) {
                        $user->mobile_first_login_date_time = Carbon::now()->format('Y-m-d H:i:s');
                        $user->update();
                    }

                    $model = UserDevice::where('user_id','=',$user->user_id)
                        ->where('device_id','=',$request_params->device_id)
                        ->where('device_token','=',$request_params->device_token)
                        ->first();

                    if ($model) {
                        $model->device_token = $request_params->device_token;
                        $model->twilio_expiry_date_time = Carbon::createFromTimestamp($request_params->expiry_timestamp)->format('Y-m-d H:i:s');
                        $model->update();
                    }
                    else{
                        $user_devices = UserDevice::where('user_id','=',$user->user_id)->count();

                        if ($user_devices == 40) {
                            UserDevice::where('user_id','=',$user->user_id)->orderBy('created_at','asc')
                                ->take('1')
                                ->first()
                                ->delete();
                        }

                        $model = new UserDevice();
                        $model->user_id = $user->user_id;
                        $model->type = $request_params->type;
                        $model->device_id = $request_params->device_id;
                        $model->device_token = $request_params->device_token;
                        $model->twilio_expiry_date_time = Carbon::createFromTimestamp($request_params->expiry_timestamp)->format('Y-m-d H:i:s');
                        $model->save();
                    }

                    return response()->json([
                        'status' => true
                    ]);
                }
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function unregisterDeviceToken(Request $request)
    {
        $request_params = $this->decodeJWTRequest($request->token);
        if (isset($request_params->device_token) && $request_params->device_token) {
            UserDevice::where('device_token','=',$request_params->device_token)->delete();
            return response()->json([
                'status' => true
            ]);
        }

        return response()->json([
            'status' => false,
            'error' => 'unknown'
        ]);
    }

    public function getClientLocationTypes()
    {
        $get_location_types = Constant::GET_CLIENT_LOCATION_TYPES();
        $location_types = [];
        foreach ($get_location_types as $key => $item) {
            $location_types[] = [
                'type' => $key,
                'label' => $item
            ];
        }

        return response()->json([
            'status' => true,
            'types' => $location_types
        ]);
    }

    public function getPhoneCountries(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('active', '=', '1')
                ->first();

            if ($user) {
                $countries = [];
                $get_countries = Country::select('name','code','number','pattern')
                    ->where('is_twilio','=','1')
                    ->orWhereIn('code',['bd','hu','pk'])
                    ->get();

                foreach ($get_countries as $item) {
                    $countries[] = [
                        'name' => $item->name,
                        'code' => $item->code,
                        'number' => $item->number,
                        'pattern' => $item->pattern,
                        'flag' => env('APP_URL').'/images/flags/'.$item->code.'.png'
                    ];
                }

                $user_twilio_phone = UserTwilioPhone::where('user_id','=',$user->user_id)->first();
                return response()->json([
                    'status' => true,
                    'default_country_code' => ($user_twilio_phone) ? strtolower($user_twilio_phone->country_code) : '',
                    'countries' => $countries
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'Unable to get phone countries'
        ]);
    }

    public function tradieReviewsMobileIframe(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::select('user.user_id','user.email')
                ->leftJoin('user_subscription', function($query){
                    $query
                        ->on('user_subscription.user_id','=','user.user_id')
                        ->where('user_subscription.type','=','tradiereview');
                })
                ->where('user.mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('user.active', '=', '1')
                ->whereNotNull('user_subscription.user_subscription_id')
                ->first();

            if ($user) {
                $get_previous_redirect = new UserTradiereviewRedirect();
                $get_previous_redirect->user_id = $user->user_id;
                $get_previous_redirect->code = md5(uniqid().$user->email.'redirect'.env('APP_KEY'));
                $get_previous_redirect->ios = '1';
                $get_previous_redirect->save();

                return response()->json([
                    'status' => true,
                    'url' => env('TRADIEREVIEWS_URL').'/mobile/iframe/'.$get_previous_redirect->code
                ]);
            }
            else{
                return response()->json([
                    'status' => false,
                    'error' => 'No Tradie Reviews subscription'
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'Something went wrong'
        ]);
    }

    public function getTwilioPhoneOwnerName(Request $request)
    {
        $user_phone = 'N/A';
        $user = User::where('mobile_login_key', '=', $request['mobile_login_key'])
            ->where('active', '=', '1')
            ->first();

        if ($user) {
            $get_client_phone = ClientPhone::select('client.name')
                ->leftJoin('client','client.client_id','=','client_phone.client_id')
                ->where('client.user_id','=',$user->user_id)
                ->where('client_phone.phone', '=', $request['phone'])
                ->first();

            if ($get_client_phone) {
                $user_phone = $get_client_phone->name;
            }
            else{
                $get_user_phone = UserTwilioPhone::select('user.name')
                    ->leftJoin('user','user.user_id','=','user_twilio_phone.user_id')
                    ->where('user_twilio_phone.phone', '=', $request['phone'])
                    ->first();

                if ($get_user_phone) {
                    $user_phone = $get_user_phone->name;
                }
            }
        }

        return response()->json([
            'name' => $user_phone
        ]);
    }

    public function tradieReviewsAccessCheck(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::select('user.user_id','user.email')
                ->leftJoin('user_subscription', function($query){
                    $query
                        ->on('user_subscription.user_id','=','user.user_id')
                        ->where('user_subscription.type','=','tradiereview');
                })
                ->where('user.mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('user.active', '=', '1')
                ->whereNotNull('user_subscription.user_subscription_id')
                ->first();

            if ($user) {
                return response()->json([
                    'status' => true,
                ]);
            }
        }

        return response()->json([
            'status' => false
        ]);
    }

    public function sendTradieReviewReview(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::select('user.user_id', 'user.email')
                ->leftJoin('user_subscription', function ($query) {
                    $query
                        ->on('user_subscription.user_id', '=', 'user.user_id')
                        ->where('user_subscription.type', '=', 'tradiereview');
                })
                ->where('user.mobile_login_key', '=', $request_params->mobile_login_key)
                ->where('user.active', '=', '1')
                ->whereNotNull('user_subscription.user_subscription_id')
                ->first();

            if ($user) {
                if ($request_params->type == 'email') {
                    try{
                        foreach ($request_params->emails as $item) {
                            /**Create invite record*/
                            $model = new ReviewInvite();
                            $model->user_id = $user->user_id;
                            $model->type = 'email';
                            $model->target = $item;
                            $model->status = 'pending';
                            $model->unique_code = md5($user->user_id.'review_invite'.uniqid());
                            $model->save();
                            NotificationHelper::sendLeaveReviewEmail($model->unique_code, null, $user->name, $user->reviews_logo, $item);
                        }
                    }
                    catch (\Exception $e) {
                        return response()->json([
                            'status' => false,
                            'error' => 'Unable to send emails, please try again later',
                        ]);
                    }
                }
                else{
                    $model = new ReviewInvite();
                    $model->user_id = $user->user_id;
                    $model->type = 'phone';
                    $model->target = $request_params->phone;
                    $model->status = 'pending';
                    $model->unique_code = md5($user->user_id.'review_invite'.uniqid());

                    /**Send out Twilio Message*/
                    $twilio = new \Twilio\Rest\Client(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));
                    $user_phone = UserTwilioPhone::where('user_id', '=', $user->user_id)->where('status','=','active')->first();
                    if (!$user_phone) {
                        $user_phone = new \stdClass();
                        $user_phone->phone = env('SMS_GLOBAL_NUMBER');
                    }

                    try{
                        $params = [
                            "body" => Helper::generateTradieReviewSendTextMessage($user, $model->unique_code),
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

                return response()->json([
                    'status' => true
                ]);
            }
        }

        return response()->json([
            'status' => false
        ]);
    }

    public function removeTestAccount(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::select('user.*')
                ->where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->first();

            if ($user) {
                if (strpos($user->email,'dev.umer') === false) {
                    return response()->json([
                        'status' => false,
                        'error' => 'Can not delete this user, is not Umer\'s account'
                    ]);
                }
                else{
                    if ($user->email == 'dev.umer.naseer@gmail.com') {
                        return response()->json([
                            'status' => false,
                            'error' => 'Can not delete this account'
                        ]);
                    }
                    else{
                        CallHistory::where('user_id','=',$user->user_id)->delete();
                        ClientValue::leftJoin('client','client.client_id','client_value.client_id')
                            ->where('client.user_id','=',$user->user_id)
                            ->delete();
                        ClientPhone::leftJoin('client','client.client_id','client_phone.client_id')
                            ->where('client.user_id','=',$user->user_id)
                            ->delete();
                        ClientNote::leftJoin('client','client.client_id','client_note.client_id')
                            ->where('client.user_id','=',$user->user_id)
                            ->delete();
                        ClientLocation::leftJoin('client','client.client_id','client_location.client_id')
                            ->where('client.user_id','=',$user->user_id)
                            ->delete();
                        ClientHistory::leftJoin('client','client.client_id','client_history.client_id')
                            ->where('client.user_id','=',$user->user_id)
                            ->delete();
                        Client::where('client.user_id','=',$user->user_id)
                            ->delete();
                        EventLocation::leftJoin('event','event.event_id','event_location.event_id')
                            ->where('event.user_id','=',$user->user_id)
                            ->delete();
                        Event::where('user_id','=',$user->user_id)->delete();
                        UserIndustry::where('user_id','=',$user->user_id)->delete();
                        UserNotification::where('user_id','=',$user->user_id)->delete();
                        UserOnboarding::where('user_id','=',$user->user_id)->delete();
                        UserSubscription::where('user_id','=',$user->user_id)->delete();
                        UserTask::where('user_id','=',$user->user_id)->delete();
                        UserTwilioPhone::where('user_id','=',$user->user_id)->delete();
                        $user->delete();
                        return response()->json([
                            'status' => true
                        ]);
                    }
                }
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'Something went wrong'
        ]);
    }

    public function checkEmailRegistered(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            if (isset($request_params->email)) {
                $has_email_taken = User::select('user.user_id', 'user_subscription.user_subscription_id')
                    ->leftJoin('user_subscription', function ($query) {
                        $query
                            ->on('user_subscription.user_id', '=', 'user.user_id')
                            ->where('user_subscription.type', '=', 'tradieflow');
                    })
                    ->where('user.email','=',$request_params->email)
                    ->whereNotNull('user_subscription.user_subscription_id')
                    ->count();
            }
            else{
                return response()->json([
                    'status' => false,
                    'error' => 'Email not found'
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'email_taken' => $has_email_taken ? true : false
        ]);
    }

    public function updatePassword(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::select('user.*')
                ->where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->first();

            if ($user) {
                if (Hash::check($request_params->current_password, $user->password)) {
                    $user->password = bcrypt($request_params->new_password);
                    $user->update();
                    return response()->json([
                        'status' => true
                    ]);
                }
                else{
                    return response()->json([
                        'status' => false,
                        'error' => 'Current password is wrong'
                    ]);
                }
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'Something went wrong'
        ]);
    }

    public function updateUser(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::with('Country','UserInvoiceSetting')
                ->select('user.*')
                ->where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->first();

            if ($user) {
                $user->name = $request_params->name;
                $user->country_id = $request_params->country_id;
                $user->website_url = $request_params->website_url;
                $user->update();

                $user_invoice_setting = $user->UserInvoiceSetting;
                if (!$user_invoice_setting) {
                    $user_invoice_setting = new UserInvoiceSetting();
                    $user_invoice_setting->user_id = $user->user_id;
                }

                $user_invoice_setting->company_name = $request_params->company_name;
                $user_invoice_setting->save();

                /**Get User Phones*/
                $user_phones = UserTwilioPhone::select('user_twilio_phone.user_twilio_phone_id', 'user_twilio_phone.friendly_name', 'user_twilio_phone.phone', 'user_twilio_phone.type', 'user_twilio_phone.country_code', 'user_twilio_phone.created_at','country.number as country_phone_code')
                    ->leftJoin('country','country.code','=','user_twilio_phone.country_code')
                    ->where('user_twilio_phone.user_id', '=', $user->user_id)
                    ->orderBy('user_twilio_phone.created_at', 'asc')
                    ->get();

                $user_xero_enabled = UserXeroAccount::where('user_id','=',$user->user_id)
                    ->where('active','=','1')
                    ->count();

                return response()->json([
                    'status' => true,
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'mobile_login_key' => $user->mobile_login_key,
                        'phone_numbers' => $user_phones,
                        'twilio_company_unique_name' => $user->twilio_company_unique_name,
                        'onboarding_completed' => (!$user->mobile_login_key || ($user->mobile_login_key && $user->mobile_onboarding_completed)) ? true : false,
                        'walkthrough_completed' => $user->mobile_walkthrough_completed ? true : false,
                        'xero_enabled' => $user_xero_enabled ? true : false,
                        'company_name' => $user_invoice_setting->company_name,
                        'website_url' => $user->website_url,
                        'country_id' => $user->country_id,
                        'country_name' => $user->Country ? $user->Country->name : '',
                        'twilio_first_call_made' => $user->twilio_first_call_made ? true : false
                    ]
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'Something went wrong'
        ]);
    }

    public function sendSignupVerificationCode(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $has_email_taken = User::select('user.user_id','user_subscription.user_subscription_id')
                ->leftJoin('user_subscription',function($query){
                    $query
                        ->on('user_subscription.user_id','=','user.user_id')
                        ->where('user_subscription.type','=','tradieflow');
                })
                ->where('user.email','=',$request_params->email)
                ->whereNotNull('user_subscription.user_subscription_id')
                ->count();

            if ($has_email_taken) {
                return response()->json([
                    'status' => false,
                    'error' => 'Email already taken'
                ]);
            }

            /**Process sending OTP code*/
            $register_queue = UserRegisterQueue::where('email','=',$request_params->email)
                ->where('type','=','tradieflow')
                ->first();

            if (!$register_queue) {
                $register_queue = new UserRegisterQueue();
                $register_queue->email = $request_params->email;
                $register_queue->type = 'tradieflow';
            }

            $register_queue->verify_code = rand(1000,9999);
            $register_queue->save();
            NotificationHelper::registerVersionVerify($register_queue->verify_code,$register_queue->email);

            return response()->json([
                'status' => true
            ]);
        }

        return response()->json([
            'status' => false,
            'error' => 'Something went wrong'
        ]);
    }

    public function confirmSignupVerificationCode(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $has_email_taken = User::select('user.user_id', 'user_subscription.user_subscription_id')
                ->leftJoin('user_subscription',function ($query) {
                    $query
                        ->on('user_subscription.user_id', '=', 'user.user_id')
                        ->where('user_subscription.type', '=', 'tradieflow');
                })
                ->where('user.email', '=', $request_params->email)
                ->whereNotNull('user_subscription.user_subscription_id')
                ->count();

            if ($has_email_taken) {
                return response()->json([
                    'status' => false,
                    'error' => 'Email already taken'
                ]);
            }

            $check_code = UserRegisterQueue::where('email','=',$request_params->email)
                ->where('verify_code','=',$request_params->code)
                ->where('type','=','tradieflow')
                ->count();

            if ($check_code) {
                return response()->json([
                    'status' => true
                ]);
            }

            return response()->json([
                'status' => false,
                'error' => 'Wrong code entered'
            ]);
        }

        return response()->json([
            'status' => false,
            'error' => 'Something went wrong'
        ]);
    }

    public function fullSignup(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);

            $phone_country = Country::where('is_twilio','=','1')->where('code','=','us')->first();
            $address_sid = null;

            if (!isset($request_params->name) || !isset($request_params->email) || !isset($request_params->password)) {
                return response()->json([
                    'status' => false,
                    'error' => 'Name, email and password are required'
                ]);
            }

            if (strpos($request_params->email,'dev.umer') === false) {
                if (strpos($request_params->email,'csincsakf') === false) {
                    $twilio = new \Twilio\Rest\Client(env("TWILIO_ACCOUNT_SID"), env("TWILIO_AUTH_TOKEN"));
                    $twilio_country_availability = Constant::GET_TWILIO_COUNTRY_AVAILABLE_FILTERS();
                    $local = $twilio->availablePhoneNumbers(strtoupper($phone_country->code))
                        ->{$twilio_country_availability[$phone_country->code]['type']}
                        ->read([
                            $twilio_country_availability[$phone_country->code]['capabilities']
                        ], 1);

                    $phone_create_options = [
                        "phoneNumber" => $local['0']->phoneNumber,
                        "smsMethod" => 'POST',
                        "smsUrl" => env('APP_URL') . '/api/twilio/incoming/text',
                        "voiceUrl" => env('TWILIO_VOICE_WEBHOOK_URL')
                    ];

                    if ($address_sid) {
                        $phone_create_options['addressSid'] = $address_sid;
                    }

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
                    } else {
                        return response()->json([
                            'status' => true,
                            'error' => 'Unable to register a new number, please contact support'
                        ]);
                    }
                }
                else{
                    $twilio_phone_obj = [
                        'friendly_name' => '(214) 390-7558',
                        'phone' => '+12143907558',
                        'country_code' => 'us',
                        'twilio_address_sid' => 'PN50efbbbb43920190ac557c5f6ca00236',
                        'twilio_bundle_sid' => null,
                        'twilio_sid' => 'PN50efbbbb43920190ac557c5f6ca00236',
                        'type' => 'mobile'
                    ];
                }
            }
            else{
                $twilio_phone_obj = [
                    'friendly_name' => '(844) 420-1447',
                    'phone' => '+18444201447',
                    'country_code' => 'us',
                    'twilio_address_sid' => 'PN64252a0fc33184e4d9424ee3e812a52c',
                    'twilio_bundle_sid' => null,
                    'twilio_sid' => 'PN64252a0fc33184e4d9424ee3e812a52c',
                    'type' => 'mobile'
                ];
            }

            $other_params = [
                'has_email_verified' => '1',
                'mobile_login_key' => md5($request_params->email.env('APP_KEY').uniqid()),
                'password' => bcrypt($request_params->password)
            ];

            $model = Helper::signupUser('desktop',$request_params, $other_params, []);
            if ($model->user_id) {
                $twilio_phone_obj['user_id'] = $model->user_id;
                UserTwilioPhone::create($twilio_phone_obj);
            }

            /**Add Industry*/
            if (isset($request_params->industry_id) && $request_params->industry_id) {
                $user_industry = new UserIndustry();
                $user_industry->user_id = $model->user_id;
                $user_industry->industry_id = $request_params->industry_id;
                $user_industry->save();
            }

            /**Add Business Types*/
            if (isset($request_params->business_types) && $request_params->business_types) {
                foreach ($request_params->business_types as $item) {
                    $user_business_type = new UserBusinessType();
                    $user_business_type->user_id = $model->user_id;
                    $user_business_type->business_type_id = $item;
                    $user_business_type->save();
                }
            }

            if (isset($request_params->company_name) && $request_params->company_name) {
                $user_invoice_setting = new UserInvoiceSetting();
                $user_invoice_setting->user_id = $model->user_id;
                $user_invoice_setting->company_name = $request_params->company_name;
                $user_invoice_setting->save();
            }

            return response()->json([
                'status' => true,
                'user' => [
                    'name' => $model->name,
                    'email' => $model->email,
                    'mobile_login_key' => $other_params['mobile_login_key'],
                    'phone_numbers' => [$twilio_phone_obj],
                    'twilio_company_unique_name' => $model->twilio_company_unique_name,
                    'onboarding_completed' => false,
                    'walkthrough_completed' => false,
                    'twilio_first_call_made' => false
                ],
            ]);

        }

        return response()->json([
            'status' => false,
            'error' => 'Something went wrong'
        ]);
    }

    public function disableXero(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::select('user.*')
                ->where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->first();

            if ($user) {
                UserXeroAccount::where('user_id','=',$user->user_id)
                    ->update([
                        'active' => '0'
                    ]);

                return response()->json([
                    'status' => true
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'Something went wrong'
        ]);
    }

    public function getXeroStatus(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::select('user.*')
                ->where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->first();

            if ($user) {
                $user_xero_enabled = UserXeroAccount::where('user_id','=',$user->user_id)
                    ->where('active','=','1')
                    ->count();

                return response()->json([
                    'status' => true,
                    'enabled' => $user_xero_enabled ? true : false
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'Something went wrong'
        ]);
    }

    public function xeroConnect(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::select('user.*')
                ->where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->first();

            if ($user) {
                /**Create Token*/
                $model = new UserXeroMobileRedirect();
                $model->user_id = $user->user_id;
                $model->code = md5(uniqid().$user->user_id);
                $model->save();

                return response()->json([
                    'status' => true,
                    'url' => env('APP_URL').'/mobile/xero/connect/'.$model->code
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'Something went wrong'
        ]);
    }

    public function updateFirstCallStage(Request $request)
    {
        if ($request->token) {
            $request_params = $this->decodeJWTRequest($request->token);
            $user = User::select('user.*')
                ->where('mobile_login_key', '=', $request_params->mobile_login_key)
                ->first();

            if ($user) {
                $user->twilio_first_call_made = $request_params->stage ? '1' : '0';
                $user->update();
                return response()->json([
                    'status' => true
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'Something went wrong'
        ]);
    }

    public function test(Request $request)
    {
        $payload = [
            'email' => 'qido.js+261121@gmail.com'
//            'mobile_login_key' => 'eb9337b817355bbaef8007126e208036',
//            'business_types' => [1,2]
//            'device_token' => '1234567890',
//            'device_id' => '123',
//            'type' => 'ios',
//            'expiry_timestamp' => '1627558350'
//            'name' => 'Joe',
//            'email' => 'qido.js+0906113@gmail.com',
//            'password' => '0000'
        ];
//
        $token = self::encodeJWTRequest($payload);
        var_dump($token);die;
    }
}
