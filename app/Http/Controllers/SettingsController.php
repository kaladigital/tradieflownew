<?php

namespace App\Http\Controllers;

use App\Helpers\Constant;
use App\Helpers\Helper;
use App\Helpers\NotificationHelper;
use App\Helpers\XeroHelper;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Country;
use App\Models\DiscountCode;
use App\Models\Notification;
use App\Models\SubscriptionPlan;
use App\Models\TextMessage;
use App\Models\TwilioAuPhoneRegion;
use App\Models\User;
use App\Http\Requests\InvoiceSettingsRequest;
use App\Models\UserForm;
use App\Models\UserFormPage;
use App\Models\UserFormPageForm;
use App\Models\UserInvoiceSetting;
use App\Models\UserNotification;
use App\Models\UserOnboarding;
use App\Models\UserReferralCode;
use App\Models\UserSubscription;
use App\Models\UserTradiereviewRedirect;
use App\Models\UserTwilioPhone;
use App\Models\UserTwilioPhoneRedirect;
use App\Models\UserXeroAccount;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Constraint\Count;
use Session;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $auth_user = Auth::user();
        $user_onboarding = Helper::getUserOnboarding($auth_user);
        if ($user_onboarding->status == 'completed') {
            return redirect('settings/account');
        }

        $auth_user->onboarding_state = Helper::caclulateUserOnboardingState($auth_user, $user_onboarding);
        return view('settings.index',compact(
            'auth_user',
            'user_onboarding'
        ));
    }

    public function account()
    {
        $auth_user = Auth::user();
        $countries = Helper::getCountryList();
        $user_onboarding = Helper::getUserOnboarding($auth_user);
        if ($user_onboarding->status == 'pending') {
            if (!$user_onboarding->account) {
                $user_onboarding->account = '1';
                $user_onboarding->update();
            }
            $auth_user->onboarding_state = Helper::caclulateUserOnboardingState($auth_user, $user_onboarding);
        }

        if (!$user_onboarding->first_onboarding_passed) {
            $user_onboarding->first_onboarding_passed = '1';
            $user_onboarding->update();
        }

        return view('settings.account', compact(
            'auth_user',
            'countries',
            'user_onboarding'
        ));
    }

    public function updateAccount(UpdateAccountRequest $request)
    {
        $auth_user = Auth::user();
        /**Check Email*/
        if (!filter_var($auth_user->email, FILTER_VALIDATE_EMAIL)) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors('Email is not valid');
        }

        $email_taken = User::where('email', '=', $auth_user->email)->where('user_id', '!=', $auth_user->user_id)->count();
        if ($email_taken) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors('Email is already taken');
        }

        if (!Country::find($request['country_id'])) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors('Country is required');
        }

        $request['name_initials'] = Helper::generateInitials($request['name']);
        if (!$auth_user->gmail_token) {
            $request['gmail_token'] = md5($auth_user->user_id.env('APP_KEY').'_gmail'.uniqid());
        }

        $auth_user->update($request->only([
            'name',
            'name_initials',
            'email',
            'website_url',
            'country_id',
            'zip_code',
            'gmail_username',
            'gmail_password',
            'gmail_token'
        ]));
        return $this->skipOnboarding('account');
    }

    public function skipOnboarding($type)
    {
        $user_onboarding = Helper::getUserOnboarding(request()->user());
        switch ($type) {
            case 'account':
                if ($user_onboarding->status == 'pending') {
                    if ($user_onboarding->account) {
                        if (!$user_onboarding->phone_numbers) {
                            return redirect('settings/phone-numbers');
                        }
                        elseif(!$user_onboarding->calendar) {
                            return redirect('settings/calendar');
                        }
                        elseif(!$user_onboarding->forms) {
                            return redirect('settings/forms');
                        }
                        elseif(!$user_onboarding->invoices) {
                            return redirect('settings/invoices');
                        }
                        elseif(!$user_onboarding->integrations) {
                            return redirect('settings/integrations');
                        }
                        elseif(!$user_onboarding->subscriptions) {
                            return redirect('settings/subscriptions');
                        }
                        else{
                            return redirect('ready-to-go');
                        }
                    }
                    else{
                        $user_onboarding->account = '1';
                        $user_onboarding->update();
                        return redirect('settings/phone-numbers');
                    }
                }
                else{
                    return redirect('settings/account')
                        ->with('success', 'Account updated successfully');
                }
            break;
            case 'phone-numbers':
                if ($user_onboarding->status == 'pending') {
                    if ($user_onboarding->phone_numbers) {
                        if (!$user_onboarding->calendar) {
                            return redirect('settings/calendar');
                        }
                        elseif(!$user_onboarding->forms) {
                            return redirect('settings/forms');
                        }
                        elseif(!$user_onboarding->invoices) {
                            return redirect('settings/invoices');
                        }
                        elseif(!$user_onboarding->integrations) {
                            return redirect('settings/integrations');
                        }
                        elseif(!$user_onboarding->subscriptions) {
                            return redirect('settings/subscriptions');
                        }
                        else{
                            return redirect('ready-to-go');
                        }
                    }
                    else{
                        $user_onboarding->phone_numbers = '1';
                        $user_onboarding->update();
                        return redirect('settings/calendar');
                    }
                }
                else{
                    return redirect('settings/phone-numbers')
                        ->with('success', 'Phone numbers updated successfully');
                }
            break;
            case 'calendar':
                if ($user_onboarding->status == 'pending') {
                    if ($user_onboarding->calendar) {
                        if (!$user_onboarding->forms) {
                            return redirect('settings/forms');
                        }
                        elseif(!$user_onboarding->invoices) {
                            return redirect('settings/invoices');
                        }
                        elseif(!$user_onboarding->integrations) {
                            return redirect('settings/integrations');
                        }
                        elseif(!$user_onboarding->subscriptions) {
                            return redirect('settings/subscriptions');
                        }
                        else{
                            return redirect('ready-to-go');
                        }
                    }
                    else{
                        $user_onboarding->calendar = '1';
                        $user_onboarding->update();
                        return redirect('settings/forms');
                    }
                }
                else{
                    return redirect('settings/calendar')
                        ->with('success', 'Calendar updated successfully');
                }
            break;
            case 'forms':
                if ($user_onboarding->status == 'pending') {
                    if ($user_onboarding->forms) {
                        if (!$user_onboarding->invoices) {
                            return redirect('settings/invoices');
                        }
                        elseif(!$user_onboarding->integrations) {
                            return redirect('settings/integrations');
                        }
                        elseif(!$user_onboarding->subscriptions) {
                            return redirect('settings/subscriptions');
                        }
                        else{
                            return redirect('ready-to-go');
                        }
                    }
                    else{
                        $user_onboarding->forms = '1';
                        $user_onboarding->update();
                        return redirect('settings/invoices');
                    }
                }
                else{
                    return redirect('settings/forms');
                }
            break;
            case 'invoices':
                if ($user_onboarding->status == 'pending') {
                    if ($user_onboarding->invoices) {
                        if (!$user_onboarding->integrations) {
                            return redirect('settings/integrations');
                        }
                        elseif(!$user_onboarding->subscriptions) {
                            return redirect('settings/subscriptions');
                        }
                        else{
                            return redirect('ready-to-go');
                        }
                    }
                    else{
                        $user_onboarding->invoices = '1';
                        $user_onboarding->update();
                        return redirect('settings/integrations');
                    }
                }
                else{
                    return redirect('settings/invoices')
                        ->with('success','Invoices updated successfully');
                }
            break;
            case 'integrations':
                if ($user_onboarding->status == 'pending') {
                    if ($user_onboarding->integrations) {
                        if (!$user_onboarding->subscriptions) {
                            return redirect('settings/subscriptions');
                        }
                        else{
                            return redirect('ready-to-go');
                        }
                    }
                    else{
                        $user_onboarding->integrations = '1';
                        $user_onboarding->update();
                        return redirect('settings/subscriptions');
                    }
                }
                else{
                    return redirect('settings/integrations');
                }
            break;
            case 'subscriptions':
                if ($user_onboarding->status == 'pending') {
                    if (!$user_onboarding->subscriptions) {
                        $user_onboarding->subscriptions = '1';
                        $user_onboarding->update();
                    }

                    return redirect('ready-to-go');
                }
                else{
                    return redirect('settings/subscriptions');
                }
            break;
            case 'help':
                if ($user_onboarding->status == 'pending') {
                    $user_onboarding->help = '1';
                    $user_onboarding->status = 'completed';
                    $user_onboarding->update();
                }
                return redirect('ready-to-go');
            break;
        }
    }

    public function phoneNumbers(Request $request)
    {
        $auth_user = Auth::user();
        $user_onboarding = Helper::getUserOnboarding($auth_user);
        if ($user_onboarding->status == 'pending') {
            if (!$user_onboarding->phone_numbers) {
                $user_onboarding->phone_numbers = '1';
                $user_onboarding->update();
            }
            $auth_user->onboarding_state = Helper::caclulateUserOnboardingState($auth_user, $user_onboarding);
        }

        if (!$user_onboarding->first_onboarding_passed) {
            $user_onboarding->first_onboarding_passed = '1';
            $user_onboarding->update();
        }

        $user_twilio_number = UserTwilioPhone::with(['UserTwilioPhoneRedirect' => function($query){
            $query->orderBy('created_at','desc');
        }, 'UserTwilioPhoneRedirect.Country'])
            ->where('user_id','=',$auth_user->user_id)
            ->first();

        $redirect_numbers = [];
        foreach ($user_twilio_number->UserTwilioPhoneRedirect as $item) {
            $redirect_numbers[] = [
                'id' => $item->user_twilio_phone_redirect_id,
                'country_id' => $item->country_id,
                'name' => $item->name,
                'phone' => $item->phone_format,
                'code' => ($item->Country) ? $item->Country->code : ''
            ];
        }

        $redirect_number_data = [
            'phone_numbers' => [],
            'show_more' => []
        ];

        if ($redirect_numbers) {
            $redirect_number_data['phone_numbers'] = array_slice($redirect_numbers,0,5);
            $redirect_number_data['show_more'] = array_slice($redirect_numbers,5);
        }

        $phone_number_colors = ['green', 'orange', 'blue', 'pink', 'green'];
        $countries = Country::select('country_id','code', 'number')->where('is_twilio','=','1')->get();
        return view('settings.phone_numbers',compact(
            'auth_user',
            'user_onboarding',
            'user_twilio_number',
            'redirect_number_data',
            'phone_number_colors',
            'countries',
            'redirect_numbers'
        ));
    }

    public function updatePhoneNumbers(Request $request)
    {
        $auth_user = Auth::user();
        $user_twilio_number = UserTwilioPhone::with(['UserTwilioPhoneRedirect' => function($query){
            $query->orderBy('created_at','desc');
        }, 'UserTwilioPhoneRedirect.Country'])
            ->where('user_id','=',$auth_user->user_id)
            ->first();

        $phone_numbers = json_decode($request['phone_numbers']);

        $phone_number_ids = [];
        $country_data = [];
        foreach ($phone_numbers as $item) {
            if (isset($item->id) && $item->id) {
                $model = UserTwilioPhoneRedirect::where('user_twilio_phone_id','=',$user_twilio_number->user_twilio_phone_id)->find($item->id);
                if (!$model) {
                    continue;
                }
            }
            else{
                $model = new UserTwilioPhoneRedirect();
                $model->user_twilio_phone_id = $user_twilio_number->user_twilio_phone_id;
            }

            if (!isset($country_data[$item->country_id])) {
                $country = Country::find($item->country_id);
                if (!$country) {
                    continue;
                }

                $country_data[$item->country_id] = $country;
            }

            $model->country_id = $item->country_id;
            $model->name = $item->name;
            $model->phone = $country_data[$item->country_id]->number.preg_replace('/[^0-9.]+/', '', $item->phone);
            $model->phone_format = $item->phone;
            $model->save();
            $phone_number_ids[] = $model->user_twilio_phone_redirect_id;
        }

        UserTwilioPhoneRedirect::where('user_twilio_phone_id','=',$user_twilio_number->user_twilio_phone_id)
            ->whereNotIn('user_twilio_phone_redirect_id',$phone_number_ids)
            ->delete();

        return $this->skipOnboarding('phone-numbers');
    }

    public function calendar()
    {
        $auth_user = Auth::user();
        $weeks_days = Constant::GET_WEEK_DAYS();
        $working_days_hours = Constant::GET_TIME_INTERVAL_VALUES();
        $user_onboarding = Helper::getUserOnboarding($auth_user);
        if ($user_onboarding->status == 'pending') {
            if (!$user_onboarding->calendar) {
                $user_onboarding->calendar = '1';
                $user_onboarding->update();
            }
            $auth_user->onboarding_state = Helper::caclulateUserOnboardingState($auth_user, $user_onboarding);
        }

        if (!$user_onboarding->first_onboarding_passed) {
            $user_onboarding->first_onboarding_passed = '1';
            $user_onboarding->update();
        }

        return view('settings.calendar', compact(
            'auth_user',
            'weeks_days',
            'working_days_hours',
            'user_onboarding'
        ));
    }

    public function updateCalendar(Request $request)
    {
        $auth_user = Auth::user();
        $request_data = $request->only([
            'sun',
            'sun_start',
            'sun_end',
            'mon',
            'mon_start',
            'mon_end',
            'tue',
            'tue_start',
            'tue_end',
            'wed',
            'wed_start',
            'wed_end',
            'thu',
            'thu_start',
            'thu_end',
            'fri',
            'fri_start',
            'fri_end',
            'sat',
            'sat_start',
            'sat_end',
        ]);

        /**Handle Working Hours*/
        $week_days = Constant::GET_WEEK_DAYS();
        $week_day_hours = Constant::GET_TIME_INTERVAL_VALUES();
        foreach ($week_days as $key => $item) {
            if (!$request[$key] || !array_key_exists($request[$key . '_start'], $week_day_hours) || !array_key_exists($request[$key . '_end'], $week_day_hours)) {
                $request_data[$key] = 0;
                $request_data[$key . '_start'] = null;
                $request_data[$key . '_end'] = null;
            }
        }

        $auth_user->update($request_data);
        return $this->skipOnboarding('calendar');
    }

    public function invoice()
    {
        $auth_user = Auth::user();
        $countries = Helper::getCountryList();
        $user_onboarding = Helper::getUserOnboarding($auth_user);
        if ($user_onboarding->status == 'pending') {
            if (!$user_onboarding->invoices) {
                $user_onboarding->invoices = '1';
                $user_onboarding->update();
            }
            $auth_user->onboarding_state = Helper::caclulateUserOnboardingState($auth_user, $user_onboarding);
        }

        if (!$user_onboarding->first_onboarding_passed) {
            $user_onboarding->first_onboarding_passed = '1';
            $user_onboarding->update();
        }

        $account_holder_types = Constant::GET_ACCOUNT_HOLDER_TYPES();
        $user_invoice_setting = UserInvoiceSetting::where('user_id','=',$auth_user->user_id)->first();
        if (!$user_invoice_setting) {
            $user_invoice_setting = new UserInvoiceSetting();
            $user_invoice_setting->bank_account_holder_name = $auth_user->name;
        }

        $currencies = Helper::getAvailableCurrenciesList();
        return view('settings.invoices', compact(
            'auth_user',
            'countries',
            'user_onboarding',
            'account_holder_types',
            'user_invoice_setting',
            'currencies'
        ));
    }

    public function updateInvoice(InvoiceSettingsRequest $request)
    {
        $auth_user = Auth::user();
        $request['email'] = (filter_var($request['email'], FILTER_VALIDATE_EMAIL)) ? $request['email'] : null;
        $update_data = $request->only([
            'company_name',
            'email',
            'country_id',
            'zip_code',
            'city',
            'state',
            'address',
            'gst_vat',
            'company_registration_number',
            'bank_account_holder_name',
            'bank_account_holder_type',
            'bank_account_country_id',
            'bank_account_currency',
            'bank_account_number',
            'bank_account_iban',
            'bank_account_routing_swift',
            'bank_bsb_code'
        ]);

        $user_invoice_setting = UserInvoiceSetting::where('user_id','=',$auth_user->user_id)->first();
        if ($user_invoice_setting) {
            $user_invoice_setting->update($update_data);
        }
        else {
            $update_data['user_id'] = $auth_user->user_id;
            UserInvoiceSetting::create($update_data);
        }
        return $this->skipOnboarding('invoices');
    }

    public function readyToGo()
    {
        $auth_user = Auth::user();
        $user_onboarding = Helper::getUserOnboarding($auth_user);
        if ($user_onboarding->status == 'pending') {
            if (!$user_onboarding->help) {
                $user_onboarding->help = '1';
                $user_onboarding->update();
            }
            $auth_user->onboarding_state = Helper::caclulateUserOnboardingState($auth_user, $user_onboarding);
        }

        if (!$user_onboarding->first_onboarding_passed) {
            $user_onboarding->first_onboarding_passed = '1';
            $user_onboarding->update();
        }

        $user_onboarding->help = '1';
        $user_onboarding->status = 'completed';
        $user_onboarding->update();

        return view('settings.ready_to_go',compact(
            'auth_user',
            'user_onboarding'
        ));
    }

    public function integrations()
    {
        $auth_user = Auth::user();
        $countries = Helper::getCountryList();
        $user_onboarding = Helper::getUserOnboarding($auth_user);
        if ($user_onboarding->status == 'pending') {
            if (!$user_onboarding->integrations) {
                $user_onboarding->integrations = '1';
                $user_onboarding->update();
            }
            $auth_user->onboarding_state = Helper::caclulateUserOnboardingState($auth_user, $user_onboarding);
        }

        if (!$user_onboarding->first_onboarding_passed) {
            $user_onboarding->first_onboarding_passed = '1';
            $user_onboarding->update();
        }

        $user_xero_account = UserXeroAccount::where('user_id','=',$auth_user->user_id)->first();
        return view('settings.integrations', compact(
            'auth_user',
            'countries',
            'user_onboarding',
            'user_xero_account'
        ));
    }

    public function updateIntegrations(Request $request)
    {
        $auth_user = Auth::user();
        $user_onboarding = Helper::getUserOnboarding($auth_user);
        if ($user_onboarding->status == 'pending' && (!$user_onboarding->account || !$user_onboarding->phone_numbers || !$user_onboarding->calendar || !$user_onboarding->forms || !$user_onboarding->invoices)) {
            if (!$user_onboarding->account) {
                return redirect('settings/account');
            }
            elseif(!$user_onboarding->phone_numbers) {
                return redirect('settings/phone-numbers');
            }
            elseif(!$user_onboarding->calendar) {
                return redirect('settings/calendar');
            }
            elseif(!$user_onboarding->forms) {
                return redirect('settings/forms');
            }
            else{
                return redirect('settings/invoices');
            }
        }

        $auth_user->update($request->only('gmail_username','gmail_password'));
        return $this->skipOnboarding('integrations');
    }

    public function subscriptions()
    {
        $auth_user = Auth::user();
        $user_onboarding = Helper::getUserOnboarding($auth_user);
        if ($user_onboarding->status == 'pending') {
            if (!$user_onboarding->subscriptions) {
                $user_onboarding->subscriptions = '1';
                $user_onboarding->update();
            }
            $auth_user->onboarding_state = Helper::caclulateUserOnboardingState($auth_user, $user_onboarding);
        }

        if (!$user_onboarding->first_onboarding_passed) {
            $user_onboarding->first_onboarding_passed = '1';
            $user_onboarding->update();
        }

        $current_subscription = UserSubscription::where('user_id', $auth_user->user_id)
            ->where('active', '=', '1')
            ->where('type','=','tradieflow')
            ->latest()
            ->first();

        $upcoming_subscription = [];
        $old_subscription = [];

        if ($current_subscription) {
            $upcoming_subscription = UserSubscription::where('user_id', $auth_user->user_id)
                ->where('user_subscription_id', '>', $current_subscription->user_subscription_id)
                ->where('type','=','tradieflow')
                ->first();
        } else {
            $old_subscription = UserSubscription::where('user_id', $auth_user->user_id)
                ->where('type','=','tradieflow')
                ->latest()
                ->first();
        }

        /**Check Payment Methods*/
        $card_details = [
            'card_type' => 'N/A',
            'last_digits' => '....'
        ];

        /**Check Payment Methods*/
        try {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
            $payment_methods = $stripe->paymentMethods->all([
                'customer' => $auth_user->stripe_customer_id,
                'type' => 'card',
            ]);

            $card_details = [
                'card_type' => isset($payment_methods->data['0']->card->brand) ? strtolower($payment_methods->data['0']->card->brand) : null,
                'last_digits' => isset($payment_methods->data['0']->card->last4) ? $payment_methods->data['0']->card->last4 : null
            ];

            $has_payment_method = count($payment_methods) ? true : false;
        } catch (\Exception $e) {
            $has_payment_method = false;
        }

        $phone_countries = Country::select('country_id', 'name')
            ->where('is_twilio','=','1')
            ->selectRaw('concat(number," ",name) as country_name')
            ->pluck('country_name','country.country_id');

        $all_countries = Country::pluck('name','country_id');

        /**Update Notifications*/
        UserNotification::where('user_id','=',request()->user()->user_id)
            ->where('has_read','=','0')
            ->where('product','=','tradieflow')
            ->whereIn('type',['subscription','success_payment','fail_payment'])
            ->update(['has_read' => '1']);

        /**Get Subscription Prices*/
        $get_subscription_plans = SubscriptionPlan::select('price_usd','price_aud','plan_code','name')
            ->where('type','=','tradieflow')
            ->get();

        $subscription_plans = [];
        foreach ($get_subscription_plans as $item) {
            $subscription_plans[$item->plan_code] = [
                'price_usd' => $item->price_usd,
                'price_aud' => $item->price_aud,
                'name' => $item->name
            ];
        }

        /**Get TradieReviews Subscription*/
        $tradiereview_current_subscription = UserSubscription::where('user_id', $auth_user->user_id)
            ->where('active', '=', '1')
            ->where('type','=','tradiereview')
            ->latest()
            ->first();

        $tradiereview_old_subscription = [];

        if (!$tradiereview_current_subscription) {
            $tradiereview_old_subscription = UserSubscription::where('user_id', $auth_user->user_id)
                ->where('type','=','tradiereview')
                ->latest()
                ->first();
        }

        $actual_subscription = $upcoming_subscription ? $upcoming_subscription : $current_subscription;
        $au_phone_area_codes = Constant::GET_TWILIO_AUSTRALIA_AREA_CODES_LIST();
        $twilio_region_codes = [];
        $get_region_codes = TwilioAuPhoneRegion::get();
        foreach ($get_region_codes as $item) {
            $twilio_region_codes[$item->region_code] = [
                'has_mobile' => $item->has_mobile,
                'has_local' => $item->has_local,
                'has_toll_free' => $item->has_toll_free
            ];
        }

        return view('settings.subscriptions', compact(
            'auth_user',
            'current_subscription',
            'upcoming_subscription',
            'has_payment_method',
            'old_subscription',
            'user_onboarding',
            'phone_countries',
            'all_countries',
            'subscription_plans',
            'card_details',
            'tradiereview_current_subscription',
            'tradiereview_old_subscription',
            'actual_subscription',
            'au_phone_area_codes',
            'twilio_region_codes',
        ));
    }

    public function updateCard(Request $request)
    {
        $auth_user = Auth::user();
        try {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
            $stripe_response = $stripe->customers->update(
                $auth_user->stripe_customer_id,
                ['source' => $request['token']]
            );

            if (isset($stripe_response->id) && $stripe_response->id) {
                return response()->json([
                    'status' => true
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => true,
                'error' => 'Something went wrong'
            ]);
        }
    }

    public function updateSubscription(Request $request)
    {
        $auth_user = Auth::user();
        $new_subscription = SubscriptionPlan::where('plan_code', '=', $request['subscription'])->where('type','=','tradieflow')->first();

        if ($new_subscription) {
            $current_subscription = UserSubscription::where('user_id', $auth_user->user_id)
                ->where('active', '=', '1')
                ->where('type', '=', 'tradieflow')
                ->latest()
                ->first();

            $upcoming_subscription = [];

            if ($current_subscription) {
                $upcoming_subscription = UserSubscription::where('user_id', $auth_user->user_id)
                    ->where('user_subscription_id', '>', $current_subscription->user_subscription_id)
                    ->where('type', '=', 'tradieflow')
                    ->first();
            }

            /**Check Discount Code First*/
            $price_to_charge = $auth_user->currency == 'usd' ? $new_subscription->price_usd : $new_subscription->price_aud;
            $discount_code_obj = null;
            if ($request['discount_code']) {
                $discount_code = DiscountCode::where('type', '=', 'tradieflow')
                    ->where('code', '=', $request['discount_code'])
                    ->first();

                if ($discount_code) {
                    $price_to_charge -= $price_to_charge * $discount_code->discount_percentage / 100;
                    $price_to_charge = sprintf('%.2f', $price_to_charge);
                    $discount_code_obj = $discount_code;
                }
            }


            /**Add GST to the price*/
            $price_to_charge = sprintf('%.2f',$price_to_charge);
            $gst_amount = sprintf('%.2f',$price_to_charge / 10);
            $price_to_charge += $gst_amount;
            $price_to_charge = sprintf('%.2f',$price_to_charge);

            /**Process Payment*/
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
            if ($request['token'] and $request['token'] !== 'false') {
                /**Check if User exists or no*/
                if (!$auth_user->stripe_customer_id) {
                    $stripe_customer = $stripe->customers->create([
                        'email' => $auth_user->email,
                        'name' => $auth_user->name,
                        'description' => 'TradieReviews Customer',
                    ]);

                    /**Process Charge*/
                    if (isset($stripe_customer->id) && $stripe_customer->id) {
                        $auth_user->stripe_customer_id = $stripe_customer->id;
                        $auth_user->update();
                    } else {
                        Helper::addUserNotification($auth_user->user_id, 'Payment Failed', 'We could not process your payment. Please try again!', null, 'fail_payment', 'fail');
                        $get_notifications = Helper::getNotificationItems($auth_user);
                        return response()->json([
                            'status' => false,
                            'error' => 'Unable to process your payment, please contact support',
                            'open_notifications' => true,
                            'notifications' => $get_notifications['unread_notifications'],
                            'has_more_items' => $get_notifications['has_more_items']
                        ]);
                    }

                    /**Update User Card*/
                    try {
                        $stripe_response = $stripe->customers->update(
                            $auth_user->stripe_customer_id,
                            ['source' => $request['token']]
                        );

                        if (!$stripe_response->id) {
                            Helper::addUserNotification($auth_user->user_id, 'Payment Failed', 'We could not process your payment. Please try again!', null, 'fail_payment', 'fail');
                            $get_notifications = Helper::getNotificationItems($auth_user);
                            return response()->json([
                                'status' => false,
                                'open_notifications' => true,
                                'notifications' => $get_notifications['unread_notifications'],
                                'has_more_items' => $get_notifications['has_more_items']
                            ]);
                        }
                    } catch (\Exception $e) {
                        Helper::addUserNotification($auth_user->user_id, 'Payment Failed', 'We could not process your payment. Please try again!', null, 'fail_payment', 'fail');
                        $get_notifications = Helper::getNotificationItems($auth_user);
                        return response()->json([
                            'status' => false,
                            'open_notifications' => true,
                            'notifications' => $get_notifications['unread_notifications'],
                            'has_more_items' => $get_notifications['has_more_items']
                        ]);
                    }
                }
            }

            /**Charge User*/
            try {
                $charge = $stripe->charges->create([
                    'amount' => $price_to_charge * 100,
                    'currency' => $auth_user->currency,
                    'customer' => $auth_user->stripe_customer_id,
                    'description' => 'Charge for ' . $new_subscription->name,
                ]);

                if (!isset($charge->id)) {
                    Helper::addUserNotification($auth_user->user_id, 'Payment Failed', 'We could not process your payment. Please try again!', null, 'fail_payment', 'fail');
                    $get_notifications = Helper::getNotificationItems($auth_user);
                    return response()->json([
                        'status' => false,
                        'open_notifications' => true,
                        'notifications' => $get_notifications['unread_notifications'],
                        'has_more_items' => $get_notifications['has_more_items']
                    ]);
                }
            } catch (\Exception $e) {
                Helper::addUserNotification($auth_user->user_id, 'Payment Failed', 'We could not process your payment. Please try again!', null, 'fail_payment', 'fail');
                $get_notifications = Helper::getNotificationItems($auth_user);
                return response()->json([
                    'status' => false,
                    'open_notifications' => true,
                    'notifications' => $get_notifications['unread_notifications'],
                    'has_more_items' => $get_notifications['has_more_items']
                ]);
            }

            $model = new UserSubscription();
            $model->active = '0';
            $model->is_extendable = '1';
            $model->type = 'tradieflow';
            $model->gst_amount = $gst_amount;

            if ($discount_code_obj) {
                $model->discount_code = $discount_code_obj->code;
                $model->discounted_price = $price_to_charge;
                $model->discount_code_id = $discount_code_obj->discount_code_id;
            }

            if ($upcoming_subscription) {
                $payment_success_description = 'You have paid for ' . $new_subscription->name;
                $exp_date_obj = Carbon::createFromFormat('Y-m-d H:i:s', $upcoming_subscription->expiry_date_time);
            } else {
                /**Handle old subscription*/
                if (!$current_subscription || ($current_subscription->subscription_plan_code == 'trial' || !$current_subscription->active || !$current_subscription->is_extendable)) {
                    UserSubscription::where('user_id', '=', $auth_user->user_id)
                        ->where('type', '=', 'tradieflow')
                        ->where('active', '=', '1')
                        ->update([
                            'active' => '0'
                        ]);

                    $exp_date_obj = Carbon::now();
                    $model->active = '1';
                    $model->payment_response = json_encode($charge);

                    /**Mark old subscription as non active*/
                    if ($current_subscription) {
                        $current_subscription->active = '0';
                        $current_subscription->update();
                    }

                    /**Create ActiveCampaign Log*/
                    Helper::addActiveCampaignQueueItem($auth_user->user_id,$auth_user->email,'purchase_tag');

                    $payment_success_description = 'You have just switched to a ' . $new_subscription->name;
                } else {
                    $exp_date_obj = Carbon::createFromFormat('Y-m-d H:i:s', $current_subscription->expiry_date_time);
                    $payment_success_description = 'You have paid for ' . $new_subscription->name;
                }
            }

            if ($new_subscription->plan_code == 'pro') {
                $expiration_date_time = $exp_date_obj->copy()->addMonth(1)->format('Y-m-d H:i:s');
                /**If discount make it discount to pay for 12 months*/
                if ($model->discount_code) {
                    $model->discount_pay_expiry_date = $exp_date_obj->copy()->addMonth(11)->addDays(Constant::GET_FINAL_SUBSCRIPTION_EXPIRY_DAYS())->format('Y-m-d H:i:s');
                }
            } else {
                $expiration_date_time = $exp_date_obj->copy()->addYear(1)->format('Y-m-d H:i:s');
            }

            /**Set new plan*/
            $model->user_id = $auth_user->user_id;
            $model->subscription_plan_id = $new_subscription->subscription_plan_id;
            $model->subscription_plan_name = $new_subscription->name;
            $model->subscription_plan_code = $new_subscription->plan_code;
            $model->expiry_date_time = $expiration_date_time;
            $model->price = ($auth_user->currency == 'usd') ? $new_subscription->price_usd : $new_subscription->price_aud;
            $model->currency = $auth_user->currency;
            $model->save();

            /**Update expire message*/
            $auth_user->tradieflow_subscription_expire_message = null;
            $auth_user->update();

            /**Add popup notification*/
            Helper::addUserNotification($auth_user->user_id, 'Successful Payment', $payment_success_description, null, 'success_payment', 'success');

            /**Change Phone Number*/
            try{
                if (isset($request['phone_number_address']) && $request['phone_number_address'] && $request['phone_number_address']['phone_country']) {
                    $twilio = new \Twilio\Rest\Client(env("TWILIO_ACCOUNT_SID"), env("TWILIO_AUTH_TOKEN"));
                    $phone_country = Country::where('is_twilio', '=', '1')->find($request['phone_number_address']['phone_country']);
                    if ($phone_country) {
                        $twilio_country_availability = Constant::GET_TWILIO_COUNTRY_AVAILABLE_FILTERS();
                        $phone_create_options = [
                            'smsMethod' => 'POST',
                            'smsUrl' => env('APP_URL') . '/api/twilio/incoming/text',
                            'voiceUrl' => env('TWILIO_VOICE_WEBHOOK_URL')
                        ];

                        if ($phone_country->name == 'Australia') {
                            switch ($request['phone_type']) {
                                case 'local':
                                    $type = 'local';
                                break;
                                case 'mobile':
                                    $type = 'mobile';
                                break;
                                case 'toll_free':
                                    $type = 'tollFree';
                                break;
                            }

                            $twilio_phone = $twilio->availablePhoneNumbers('AU')
                                ->{$type}
                                ->read([
                                    "voiceEnabled" => true,
                                    "contains" => $request['phone_number_address']['phone_area'] . "********"
                                ], 1);

                            $phone_create_options['addressSid'] = $auth_user->twilio_address_sid;
                        }
                        else{
                            $twilio_phone = $twilio->availablePhoneNumbers(strtoupper($phone_country->code))
                                ->{$twilio_country_availability[$phone_country->code]['type']}
                                ->read([
                                    $twilio_country_availability[$phone_country->code]['capabilities']
                                ], 1);
                        }

                        $phone_create_options['phoneNumber'] = $twilio_phone['0']->phoneNumber;

                        $incoming_phone_number = $twilio->incomingPhoneNumbers
                            ->create($phone_create_options);

                        if (isset($incoming_phone_number->sid) && $incoming_phone_number->sid) {
                            $user_twilio_phone = UserTwilioPhone::where('user_id', '=', $auth_user->user_id)->first();
                            $old_phone = $user_twilio_phone->phone;
                            $old_twilio_sid = $user_twilio_phone->twilio_sid;
                            $user_twilio_phone->phone = $twilio_phone['0']->phoneNumber;
                            $user_twilio_phone->friendly_name = $twilio_phone['0']->friendlyName;
                            $user_twilio_phone->twilio_sid = $incoming_phone_number->sid;
                            $user_twilio_phone->twilio_address_sid = $incoming_phone_number->addressSid;
                            $user_twilio_phone->twilio_bundle_sid = $incoming_phone_number->bundleSid;
                            $user_twilio_phone->update();

                            /**Update From Messages*/
                            TextMessage::where('user_id', '=', $auth_user->user_id)
                                ->where('from_number', '=', $old_phone)
                                ->update([
                                    'from_number' => $user_twilio_phone->phone
                                ]);

                            /**Update Messages*/
                            TextMessage::where('user_id', '=', $auth_user->user_id)
                                ->where('to_number', '=', $old_phone)
                                ->update([
                                    'to_number' => $user_twilio_phone->phone
                                ]);

                            $twilio->incomingPhoneNumbers($old_twilio_sid)
                                ->delete();
                        }
                    }
                }
            }
            catch (\Exception $e) {

            }

            $get_notifications = Helper::getNotificationItems($auth_user);
            return response()->json([
                'status' => true,
                'open_notifications' => true,
                'notifications' => $get_notifications['unread_notifications'],
                'has_more_items' => $get_notifications['has_more_items']
            ]);
        }

        Helper::addUserNotification($auth_user->user_id, 'Payment Failed', 'We could not process your payment. Please try again!', null, 'fail_payment', 'fail');
        $get_notifications = Helper::getNotificationItems($auth_user);
        return response()->json([
            'status' => false,
            'open_notifications' => true,
            'notifications' => $get_notifications['unread_notifications'],
            'has_more_items' => $get_notifications['has_more_items']
        ]);
    }

    public function removeUserCard(Request $request)
    {
        $auth_user = Auth::user();
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        try {
            $payment_methods = $stripe->paymentMethods->all([
                'customer' => $auth_user->stripe_customer_id,
                'type' => 'card',
            ]);

            if ($payment_methods && $payment_methods->data) {
                $delete_subscription = $stripe->customers->deleteSource(
                    $auth_user->stripe_customer_id,
                    $payment_methods->data['0']->id,
                    []
                );

                if ($delete_subscription) {
                    UserSubscription::where('user_id', '=', $auth_user->user_id)
                        ->where('is_extendable', '=', '1')
                        ->where('type','=','tradieflow')
                        ->update(['is_extendable' => '0']);
                }
            }

        } catch (\Exception $e) {

        }

        return response()->json([
            'status' => true
        ]);
    }

    public function cancelRenewal(Request $request)
    {
        $auth_user = Auth::user();
        UserSubscription::where('user_id', '=', $auth_user->user_id)
            ->where('is_extendable', '=', '1')
            ->where('type','=','tradieflow')
            ->update(['is_extendable' => '0']);

        return response()->json([
            'status' => true
        ]);
    }

    public function connectXero(Request $request)
    {
        $xero_login_url = 'https://login.xero.com/identity/connect/authorize?response_type=code&client_id='.env('XERO_CLIENT_ID').'&redirect_uri='.env('APP_URL').'/settings/xero/account&scope=offline_access accounting.transactions accounting.contacts accounting.settings.read';
        if (isset($request['from']) && $request['from'] == 'mobile') {
            Session::put('is_xero_mobile',true);
        }
        return redirect($xero_login_url);
    }

    public function xeroResponse(Request $request)
    {
        if ($request['code']) {
            try{
                $provider = new \League\OAuth2\Client\Provider\GenericProvider([
                    'clientId'                => env('XERO_CLIENT_ID'),
                    'clientSecret'            => env('XERO_CLIENT_SECRET'),
                    'redirectUri'             => env('APP_URL').'/settings/xero/account',
                    'urlAuthorize'            => 'https://login.xero.com/identity/connect/authorize',
                    'urlAccessToken'          => 'https://identity.xero.com/connect/token',
                    'urlResourceOwnerDetails' => 'https://api.xero.com/api.xro/2.0/Organisation'
                ]);

                $accessToken = $provider->getAccessToken('authorization_code', [
                    'code' => $request['code']
                ]);

                $config = \XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken( (string)$accessToken->getToken() );
                $identityApi = new \XeroAPI\XeroPHP\Api\IdentityApi(
                    new \GuzzleHttp\Client(),
                    $config
                );

                $result = $identityApi->getConnections();

                /**Has Xero Account*/
                $auth_user = Auth::user();
                $user_xero_account = UserXeroAccount::where('user_id','=',$auth_user->user_id)->first();
                if (!$user_xero_account) {
                    $user_xero_account = new UserXeroAccount();
                    $user_xero_account->user_id = $auth_user->user_id;
                }

                $user_xero_account->access_token = $accessToken->getToken();
                $user_xero_account->tenant_id = $result[0]->getTenantId();
                $user_xero_account->refresh_token = $accessToken->getRefreshToken();
                $user_xero_account->save();

                /**Fetch the Bank Account*/
                $xero_instance = XeroHelper::getXeroInstance($user_xero_account);
                $result = $xero_instance->getAccounts($user_xero_account->tenant_id, '', $where = '', '');
                $has_bank_found = false;
                foreach ($result as $item) {
                    if (!$has_bank_found && $item['type'] == 'BANK') {
                        $auth_user->invoice_bank_name = $item['name'];
                        $auth_user->invoice_bank_number = $item['bank_account_number'];
                        $auth_user->update();
                        $has_bank_found = true;
                    }
                }

                /**Fetch Company Invoice Details*/
                $result = $xero_instance->getOrganisations($user_xero_account->tenant_id);
                $i = 0;
                foreach ($result as $item) {
                    if ($i == 0) {
                        $user_invoice_setting = UserInvoiceSetting::where('user_id','=',$auth_user->user_id)->first();
                        if (!$user_invoice_setting) {
                            $user_invoice_setting = new UserInvoiceSetting();
                            $user_invoice_setting->user_id = $auth_user->user_id;
                        }

                        $user_invoice_setting->company_name = $item['legal_name'] ? $item['legal_name'] : $item['name'];

                        if ($item['country_code']) {
                            $country = Country::where('code','=',strtolower($item['country_code']))->first();
                            if ($country) {
                                $user_invoice_setting->country_id = $country->country_id;
                            }
                        }

                        if (isset($item['addresses']['0'])) {
                            $user_invoice_setting->zip_code = $item['addresses']['0']['postal_code'];
                            $user_invoice_setting->city = $item['addresses']['0']['city'];
                            if (!$user_invoice_setting->country_id) {
                                $country = Country::where('name','=',$item['addresses']['0']['country'])->first();
                                if ($country) {
                                    $user_invoice_setting->country_id = $country->country_id;
                                }
                            }

                            $user_invoice_setting->state = $item['addresses']['0']['region'];
                            $user_invoice_setting->address = $item['addresses']['0']['address_line1'].($item['addresses']['0']['address_line2'] ? ' '.$item['addresses']['0']['address_line2'] : '');
                        }

                        $user_invoice_setting->company_registration_number = $item['registration_number'];
                        $user_invoice_setting->save();
                    }
                    $i++;
                }

                if (Session::get('is_xero_mobile')) {
                    return redirect('mobile/xero/loading');
                }
                else{
                    return redirect('settings/integrations');
                }

            }
            catch (\Exception $e) {

            }
        }

        if (Session::get('is_xero_mobile')) {
            return redirect('mobile/xero/loading');
        }
        else{
            return redirect('settings/integrations');
        }
    }

    public function forms()
    {
        $auth_user = Auth::user();
        $user_onboarding = Helper::getUserOnboarding($auth_user);
        if ($user_onboarding->status == 'pending') {
            if (!$user_onboarding->forms) {
                $user_onboarding->forms = '1';
                $user_onboarding->update();
            }
            $auth_user->onboarding_state = Helper::caclulateUserOnboardingState($auth_user, $user_onboarding);
        }

        if (!$user_onboarding->first_onboarding_passed) {
            $user_onboarding->first_onboarding_passed = '1';
            $user_onboarding->update();
        }

        $pending_forms = UserForm::where('user_id','=',$auth_user->user_id)
            ->whereIn('status',['pending','processing'])
            ->orderBy('created_at','desc')
            ->paginate(10);

        $completed_forms_list = UserForm::where('user_id','=',$auth_user->user_id)
            ->where('status','=','completed')
            ->orderBy('created_at','desc')
            ->pluck('website','user_form_id')
            ->toArray();

        /**Update Notifications*/
        UserNotification::where('user_id','=',request()->user()->user_id)
            ->where('has_read','=','0')
            ->where('type','=','form')
            ->update(['has_read' => '1']);

        return view('settings.forms',compact(
            'pending_forms',
            'completed_forms_list',
            'auth_user',
            'user_onboarding'
        ));
    }

    public function trackForms(Request $request)
    {
        $auth_user = Auth::user();
        if (!$request['website'] || !filter_var($request['website'],FILTER_VALIDATE_URL)) {
            return redirect()
                ->back()
                ->with('error','Please specify valid URL starting with http:// or https://');
        }

        $websites = [$request['website']];
        if (substr($request['website'],-1) == '/') {
            $websites[] = substr($request['website'],0,-1);
        }
        else{
            $websites[] = $request['website'].'/';
        }

        $has_form = UserForm::where('user_id','=',$auth_user->user_id)
            ->whereIn('website',$websites)
            ->count();

        if ($has_form) {
            return redirect()
                ->back()
                ->with('error','URL already has been added');
        }

        if (substr($request['website'],-1) == '/') {
            $request['website'] = substr($request['website'],0,-1);
        }

        $model = new UserForm();
        $model->user_id = $auth_user->user_id;
        $model->website = $request['website'];
        $model->status = 'pending';
        $model->save();
        return redirect('settings/forms');
    }

    public function getWebsiteDetails(Request $request)
    {
        $auth_user = Auth::user();
        $user_form = UserForm::where('user_id','=',$auth_user->user_id)->find($request['id']);
        if ($user_form) {
            $user_form_pages = UserFormPageForm::select([
                'user_form_page_form.user_form_page_form_id',
                'user_form_page_form.display_name',
                'user_form_page_form.allow_track',
                'user_form_page.url',
                'user_form.tracking_code'
            ])
                ->leftJoin('user_form_page','user_form_page.user_form_page_id','=','user_form_page_form.user_form_page_id')
                ->leftJoin('user_form','user_form.user_form_id','=','user_form_page.user_form_id')
                ->where('user_form.user_id','=',$auth_user->user_id)
                ->where('user_form.user_form_id','=',$user_form->user_form_id)
                ->get();

            return response()->json([
                'status' => true,
                'user_form_pages' => $user_form_pages
            ]);
        }

        return response()->json([
            'status' => false
        ]);
    }

    public function checkFormAllowTracking(Request $request)
    {
        $auth_user = Auth::user();
        $user_form = UserForm::where('user_id','=',$auth_user->user_id)->find($request['id']);
        if ($user_form) {
            UserFormPageForm::select('user_form_page_form.*')
                ->leftJoin('user_form_page','user_form_page.user_form_page_id','=','user_form_page_form.user_form_page_id')
                ->leftJoin('user_form','user_form.user_form_id','=','user_form_page.user_form_id')
                ->where('user_form.user_id','=',$auth_user->user_id)
                ->where('user_form.user_form_id','=',$user_form->user_form_id)
                ->update([
                    'allow_track' => $request['allow_track'] ? '1' : '0'
                ]);

            /**Update Tracking Code*/
            $tracking_code = Helper::generateFormPageTrackingCode($user_form);
            return response()->json([
                'status' => true,
                'tracking_code' => $tracking_code
            ]);
        }

        return response()->json([
            'status' => false
        ]);
    }

    public function removeAllFormPages(Request $request)
    {
        $auth_user = Auth::user();
        $user_form = UserForm::where('user_id', '=', $auth_user->user_id)->find($request['id']);
        if ($user_form) {
            UserFormPageForm::select('user_form_page_form.*')
                ->leftJoin('user_form_page', 'user_form_page.user_form_page_id', '=', 'user_form_page_form.user_form_page_id')
                ->leftJoin('user_form', 'user_form.user_form_id', '=', 'user_form_page.user_form_id')
                ->where('user_form.user_id', '=', $auth_user->user_id)
                ->where('user_form.user_form_id', '=', $user_form->user_form_id)
                ->delete();

            UserFormPage::select('user_form_page.*')
                ->leftJoin('user_form', 'user_form.user_form_id', '=', 'user_form_page.user_form_id')
                ->where('user_form.user_id', '=', $auth_user->user_id)
                ->where('user_form.user_form_id', '=', $user_form->user_form_id)
                ->delete();

            $user_form->delete();
        }

        return response()->json([
            'status' => true
        ]);
    }

    public function removeFormPage(Request $request)
    {
        $auth_user = Auth::user();
        $user_form = UserForm::where('user_id', '=', $auth_user->user_id)->find($request['form_id']);
        if ($user_form) {
            $page_form = UserFormPageForm::select('user_form_page_form.*')
                ->leftJoin('user_form_page', 'user_form_page.user_form_page_id', '=', 'user_form_page_form.user_form_page_id')
                ->leftJoin('user_form', 'user_form.user_form_id', '=', 'user_form_page.user_form_id')
                ->where('user_form.user_id', '=', $auth_user->user_id)
                ->where('user_form.user_form_id', '=', $user_form->user_form_id)
                ->find($request['id']);

            if ($page_form) {
                $page_form->delete();

                /**Update Tracking Code*/
                $tracking_code = Helper::generateFormPageTrackingCode($user_form);
                return response()->json([
                    'status' => true,
                    'tracking_code' => $tracking_code
                ]);
            }
        }

        return response()->json([
            'status' => false
        ]);
    }

    public function updateFormPageTracking(Request $request)
    {
        $auth_user = Auth::user();
        $user_form = UserForm::where('user_id', '=', $auth_user->user_id)->find($request['form_id']);
        if ($user_form) {
            $page_form = UserFormPageForm::select('user_form_page_form.*')
                ->leftJoin('user_form_page', 'user_form_page.user_form_page_id', '=', 'user_form_page_form.user_form_page_id')
                ->leftJoin('user_form', 'user_form.user_form_id', '=', 'user_form_page.user_form_id')
                ->where('user_form.user_id', '=', $auth_user->user_id)
                ->where('user_form.user_form_id', '=', $user_form->user_form_id)
                ->find($request['id']);

            if ($page_form) {
                $page_form->allow_track = $request['allow_track'] == '1' ? '1' : '0';
                $page_form->update();

                /**Update Tracking Code*/
                $tracking_code = Helper::generateFormPageTrackingCode($user_form);
                return response()->json([
                    'status' => true,
                    'tracking_code' => $tracking_code
                ]);
            }
        }

        return response()->json([
            'status' => false
        ]);
    }

    public function manualFormTracking(Request $request)
    {
        if (filter_var($request['url'],FILTER_VALIDATE_URL)) {
            if (substr($request['url'],-1) == '/') {
                $request['url'] = substr($request['url'],0,-1);
            }

            $auth_user = Auth::user();
            $model = new UserForm();
            $model->user_id = $auth_user->user_id;
            $model->website = $request['url'];
            $model->status = 'pending';
            $model->is_manual_tracking = '1';
            $model->save();
            return response()->json([
                'status' => true
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'error' => 'Please type valid URL'
            ]);
        }
    }

    public function emails()
    {
        $auth_user = Auth::user();
        $fonts = Constant::GET_FONTS();
        return view('settings.emails', compact('auth_user', 'fonts'));
    }

    public function updateEmails(Request $request)
    {
        $auth_user = Auth::user();
        $user = User::find($auth_user->user_id);
        $user->update($request->only([
            'email_font_type',
            'email_signature',
        ]));
        return redirect('settings/emails');
    }

    public function users()
    {
        $auth_user = Auth::user();
        if ($auth_user->role != 'user') {
            return redirect('/');
        }
        $users = User::where('company_user_id', $auth_user->user_id)
            ->orWhere('user_id', $auth_user->user_id)
            ->get();
        return view('settings.users', compact('auth_user', 'users'));
    }

    public function updateUsers(Request $request)
    {
        foreach ($request->email as $key => $value) {
            User::updateOrCreate(['user_id' => $key],
                ['email' => $value, 'company_user_id' => Auth::user()->user_id, 'role' => $request->role[$key]]);
        }

        if (isset($request->removes_id)) {
            foreach ($request->removes_id as $value) {
                if ($value != Auth::user()->user_id) {
                    User::where('user_id', $value)->delete();
                }
            }
        }
        return redirect('settings/users');
    }

    public function security()
    {
        return view('settings.security');
    }

    public function updateSecurity(Request $request)
    {
        $auth_user = Auth::user();
        if (Hash::check($request['current_password'], $auth_user->password)) {
            $auth_user->password = bcrypt($request['new_password']);
            $auth_user->twilio_password = $request['new_password'];
            $auth_user->update();
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Current password is wrong');
        }

        return redirect('settings/security')
            ->with('success', 'Password updated successfully');
    }

    public function xeroRemove(Request $request)
    {
        $auth_user = Auth::user();
        $user_xero_account = UserXeroAccount::where('user_id','=',$auth_user->user_id)->first();
        if ($user_xero_account) {
            $user_xero_account->delete();
        }

        return response()->json([
            'status' => true
        ]);
    }

    public function email()
    {
        $auth_user = Auth::user();
        return view('settings.emails',compact(
            'auth_user'
        ));
    }

    public function updateLeadStatus(Request $request)
    {
        $user = User::where('is_lead','=','1')->find($request['user_id']);
        if ($user) {
            $user->active = ($request['status']) ? '1' : '0';
            $user->update();

            $user_twilio_phone = UserTwilioPhone::where('user_id','=',$user->user_id)->first();
            if ($user_twilio_phone) {
                try {
                    $twilio = new \Twilio\Rest\Client(env("TWILIO_ACCOUNT_SID"), env("TWILIO_AUTH_TOKEN"));
                    $twilio->incomingPhoneNumbers($user_twilio_phone->twilio_sid)
                        ->update([
                                "smsUrl" => $user->active ? env('APP_URL') . "/api/twilio/incoming/text" : env('APP_URL'),
                                "voiceUrl" => $user->active ? env('TWILIO_VOICE_WEBHOOK_URL') : env('APP_URL')
                            ]
                        );
                }
                catch (\Exception $e) {

                }
            }
        }

        return response()->json([
            'status' => true
        ]);
    }

    public function removeXero()
    {
        UserXeroAccount::where('user_id','=',request()->user()->user_id)->delete();
        return redirect('settings/integrations');
    }

    public function checkXero(Request $request)
    {
        $user_xero_account = UserXeroAccount::where('user_id','=',request()->user()->user_id)->first();
        $redirect = false;
        if ($request['checked']) {
            if ($user_xero_account) {
                $user_xero_account->active = '1';
                $user_xero_account->update();
            }
            else{
                $redirect = true;
            }
        }
        else{
            if ($user_xero_account) {
                $user_xero_account->active = '0';
                $user_xero_account->update();
            }
        }

        return response()->json([
            'status' => true,
            'redirect' => $redirect
        ]);
    }

    public function checkSubscription()
    {
        $auth_user = request()->user();
        $current_subscription = UserSubscription::where('user_id', $auth_user->user_id)
            ->where('active', '=', '1')
            ->where('type','=','tradieflow')
            ->latest()
            ->first();

        $upcoming_subscription = [];

        if ($current_subscription) {
            $upcoming_subscription = UserSubscription::where('user_id', $auth_user->user_id)
                ->where('user_subscription_id', '>', $current_subscription->user_subscription_id)
                ->where('type','=','tradieflow')
                ->first();
        }

        $expired = false;
        $phone_lost = false;
        if (!$upcoming_subscription && (!$current_subscription || ($current_subscription->subscription_plan_code == 'trial' || !$current_subscription->is_extendable))) {
            $expired = true;

            $user_twilio_phone = UserTwilioPhone::where('user_id','=',$auth_user->user_id)->first();
            if ($user_twilio_phone->status == 'deleted') {
                $phone_lost = true;
            }
        }

        return response()->json([
            'status' => true,
            'expired' => $expired,
            'phone_lost' => $phone_lost
        ]);
    }

    public function purchaseNewAddress(Request $request)
    {
        try {
            $twilio = new \Twilio\Rest\Client(env("TWILIO_ACCOUNT_SID"), env("TWILIO_AUTH_TOKEN"));
            if (!$request['address'] || !$request['city'] || !$request['state'] || !$request['zip_code'] || !$request['country_id']) {
                return response()->json([
                    'status' => false,
                    'error' => 'Missing fields, please refresh page and try again'
                ]);
            }

            $country = Country::find($request['country_id']);
            if (!$country) {
                return response()->json([
                    'status' => false,
                    'error' => 'Not supported country'
                ]);
            }

            $auth_user = Auth::user();
            $twilio_address = $twilio->addresses
                ->create($auth_user->name,
                    $request['address'],
                    $request['city'],
                    $request['state'],
                    $request['zip_code'],
                    strtoupper($country->code)
                );

            if (isset($twilio_address->sid) && $twilio_address->sid && $twilio_address->validated){
                /**Delete old address*/
                if ($auth_user->twilio_address_sid) {
                    $twilio->addresses($auth_user->twilio_address_sid)->delete();
                }

                /**Add New Address**/
                $auth_user->twilio_address_sid = $twilio_address->sid;
                $auth_user->update();

                if ($country->name == 'Australia') {
                    switch ($request['phone_type']) {
                        case 'local':
                            $type = 'local';
                        break;
                        case 'mobile':
                            $type = 'mobile';
                        break;
                        case 'toll_free':
                            $type = 'tollFree';
                        break;
                        default:
                            return response()->json([
                                'status' => false,
                                'error' => 'Unable to find an available phone number'
                            ]);
                        break;
                    }

                    $get_phone = $twilio->availablePhoneNumbers('AU')
                        ->{$type}
                        ->read([
                            "voiceEnabled" => true,
                            "contains" => $request['phone_area']."********"
                        ], 1);

                    if (!isset($get_phone['0'])) {
                        return response()->json([
                            'status' => false,
                            'error' => 'Not available phone numbers found'
                        ]);
                    }
                }
                return response()->json([
                    'status' => true
                ]);
            }
        }
        catch (\Exception $e) {

        }

        return response()->json([
            'status' => false,
            'error' => 'Address is not valid, please try again later'
        ]);
    }

    public function setupTradieFlow()
    {
        $auth_user = Auth::user();
        $user_onboarding = Helper::getUserOnboarding($auth_user);
        if ($user_onboarding->status == 'pending') {
            if (!$user_onboarding->account) {
                return redirect('settings/account');
            }

            if (!$user_onboarding->phone_numbers) {
                return redirect('settings/phone-numbers');
            }

            if (!$user_onboarding->calendar) {
                return redirect('settings/calendar');
            }

            if (!$user_onboarding->forms) {
                return redirect('settings/forms');
            }

            if (!$user_onboarding->integrations) {
                return redirect('settings/integrations');
            }

            if (!$user_onboarding->invoices) {
                return redirect('settings/invoices');
            }

            if (!$user_onboarding->subscriptions) {
                return redirect('settings/subscriptions');
            }

            if (!$user_onboarding->help) {
                return redirect('ready-to-go');
            }
        }

        return redirect()
            ->back();
    }

    public function onboarding()
    {
        $auth_user = Auth::user();
        $user_onboarding = Helper::getUserOnboarding($auth_user);
        return view('settings.onboarding',compact(
            'auth_user',
            'user_onboarding'
        ));
    }

    public function onboardingDemo()
    {
        $auth_user = Auth::user();
        $user_onboarding = Helper::getUserOnboarding($auth_user);
        return view('settings.onboarding_demo',compact(
            'auth_user',
            'user_onboarding'
        ));
    }

    public function getTradieReviews()
    {
        /**Has subscription*/
        $auth_user = Auth::user();

        $user_subscription = UserSubscription::where('user_id','=',$auth_user->user_id)
            ->where('type','=','tradiereview')
            ->first();

        if (!$user_subscription) {
            /**Update public review URL*/
            $user_invoice_setting = UserInvoiceSetting::where('user_id','=',$auth_user->user_id)->first();
            if ($user_invoice_setting && $user_invoice_setting->company_name) {
                $auth_user->reviews_company_name = $user_invoice_setting->company_name;
            }

            $auth_user->public_reviews_code = $auth_user->user_id.uniqid();
            $auth_user->save();

            $subscription_plan = SubscriptionPlan::where('plan_code','=','trial')->where('type','=','tradieflow')->first();

            /**Create Subscription*/
            $user_subscription = new UserSubscription();
            $user_subscription->user_id = $auth_user->user_id;
            $user_subscription->subscription_plan_id = $subscription_plan->subscription_plan_id;
            $user_subscription->subscription_plan_name = $subscription_plan->name;
            $user_subscription->subscription_plan_code = $subscription_plan->plan_code;
            $user_subscription->type = 'tradiereview';
            $user_subscription->expiry_date_time = Carbon::now()->addDays($subscription_plan->duration_num)->format('Y-m-d H:i:s');
            $user_subscription->active = '1';
            $user_subscription->price = '0';
            $user_subscription->currency = $auth_user->currency;
            $user_subscription->save();

            /**Create Onboarding*/
            $user_onboarding = new UserOnboarding();
            $user_onboarding->user_id = $auth_user->user_id;
            $user_onboarding->status = 'pending';
            $user_onboarding->type = 'tradiereview';
            $user_onboarding->save();
        }

        $get_previous_redirect = UserTradiereviewRedirect::where('user_id','=',$auth_user->user_id)->first();
        if (!$get_previous_redirect) {
            $get_previous_redirect = new UserTradiereviewRedirect();
            $get_previous_redirect->user_id = $auth_user->user_id;
            $get_previous_redirect->code = md5(uniqid().$auth_user->email.'redirect'.env('APP_KEY'));
            $get_previous_redirect->save();
        }

        /**Save Referral Code*/
        $user_referral_code = new UserReferralCode();
        $user_referral_code->user_id = $auth_user->user_id;
        $user_referral_code->referral_code = md5(uniqid().env('APP_KEY').$auth_user->user_id);
        $user_referral_code->type = 'tradiereview';
        $user_referral_code->save();

        return redirect(env('TRADIEREVIEWS_URL').'/get/product/'.$get_previous_redirect->code);
    }

    public function checkDiscountCode(Request $request)
    {
        $discount_code = DiscountCode::where('type','=','tradieflow')
            ->where('code','=',$request['code'])
            ->first();

        if ($discount_code) {
            return response()->json([
                'status' => true,
                'percentage' => $discount_code->discount_percentage
            ]);
        }

        return response()->json([
            'status' => false
        ]);
    }

    public function updateFormTitle(Request $request)
    {
        $auth_user = request()->user();
        $model = UserFormPageForm::select('user_form_page_form.*')
            ->leftJoin('user_form_page','user_form_page.user_form_page_id','=','user_form_page_form.user_form_page_id')
            ->leftJoin('user_form','user_form.user_form_id','=','user_form_page.user_form_id')
            ->where('user_form.user_id','=',$auth_user->user_id)
            ->find($request['id']);

        if ($model && $request['title']) {
            $model->display_name = $request['title'];
            $model->update();
        }

        return response()->json([
            'status' => true
        ]);
    }
}
