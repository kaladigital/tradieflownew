<?php
namespace App\Helpers;

use App\Models\ActiveCampaignQueue;
use App\Models\CallHistory;
use App\Models\Client;
use App\Models\ClientHistory;
use App\Models\ClientPhone;
use App\Models\Country;
use App\Models\EarlyAccessUser;
use App\Models\EmailQueue;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\SubscriptionPlan;
use App\Models\TextMessage;
use App\Models\User;
use App\Models\UserBusinessType;
use App\Models\UserFormPage;
use App\Models\UserFormPageForm;
use App\Models\UserGiveawayReferral;
use App\Models\UserIndustry;
use App\Models\UserInvoiceSetting;
use App\Models\UserNotification;
use App\Models\UserOnboarding;
use App\Models\UserReferralCode;
use App\Models\UserReferralMonthQueue;
use App\Models\UserRegisterQueue;
use App\Models\UserSubscription;
use App\Models\UserTwilioPhone;
use App\Models\UserXeroAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Session;

class Helper
{
    public static function generateUniqueFourDigits()
    {
        do {
            $four_digits = rand(pow(10, 3), pow(10, 4)-1);
            $has_taken = User::where('otp_code','=',$four_digits)
                ->count();

            if (!$has_taken) {
                return $four_digits;
            }
        }
        while ($has_taken);
    }

    public static function getBase64Data($file_data)
    {
        return [
            'file_data' => substr($file_data, strpos($file_data, ',') + 1),
            'extension' => explode('/', explode(':', substr($file_data, 0, strpos($file_data, ';')))[1])[1]
        ];
    }

    public static function getTwilioRecording($file_url)
    {
        $ch = curl_init($file_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($status == 200) ? $output : null;
    }

    public static function addClientActivity($client_id, $related_id, $title, $description, $start_date_time, $end_date_time, $type)
    {
        $model = new ClientHistory();
        $model->client_id = $client_id;
        $model->related_id = $related_id;
        $model->title = $title;
        $model->description = $description;
        $model->start_date_time = $start_date_time;
        $model->end_date_time = $end_date_time;
        $model->type = $type;
        $model->save();
        return true;
    }

    public static function calculateClientProgress($status)
    {
        $progress = 0;
        switch ($status) {
            case 'not-listed':
            case 'cancelled':
                $progress = 0;
            break;
            case 'lead':
                $progress = 25;
            break;
            case 'quote-meeting':
                $progress = 50;
            break;
            case 'work-in-progress':
                $progress = 75;
            break;
            case 'completed':
                $progress = 100;
            break;
        }

        return $progress;
    }

    public static function getCountryList()
    {
        return Country::pluck('name', 'country_id');
    }

    public static function calculateEstimateTime($date_time_format)
    {
        $expiration_date_time_obj = Carbon::createFromFormat('Y-m-d H:i:s',$date_time_format);
        $now_time_obj = Carbon::now();

        if ($now_time_obj->copy()->timestamp > $expiration_date_time_obj->copy()->timestamp) {
            $hours_format = '';
        }
        else{
            $diff_obj = $now_time_obj->copy()->diff($expiration_date_time_obj);
            $hours_format = $diff_obj->days ? $diff_obj->days.' days ' : '';
            $hours_format .= $diff_obj->h ? $diff_obj->h.' hours' : '';
        }

        return $hours_format;
    }

    public static function calculateEstimateFullTime($date_time_format)
    {
        $expiration_date_time_obj = Carbon::createFromFormat('Y-m-d H:i:s',$date_time_format);
        $diff_obj = (Carbon::now())->diff($expiration_date_time_obj);
        $items = [];
        if ($diff_obj->days) {
            $items[] = $diff_obj->days.' days';
        }

        if ($diff_obj->h) {
            $items[] = $diff_obj->h.' hours';
        }

        if ($diff_obj->i) {
            $items[] = $diff_obj->i.' minutes';
        }

        return $items ? implode(' ',$items) : '0 minutes';
    }

    public static function generateFormPageTrackingCode($user_form, $user_form_pages = [])
    {
        $track_form_url = env('TRACK_FORM_API_URL');
        if (!$user_form_pages) {
            $user_form_pages = UserFormPage::where('user_form_id','=',$user_form->user_form_id)
                ->get();
        }

        /**Build JS template*/
        $track_items = [];
        foreach ($user_form_pages as $form_page) {
            $form_page_forms = UserFormPageForm::where('user_form_page_id','=',$form_page->user_form_page_id)->get();
            foreach ($form_page_forms as $k => $form_page_form) {
                $form_name = 'TF__form_'.$k;
                switch ($form_page_form->form_type) {
                    case 'id':
                        $form_element = "const $form_name = document.getElementById('$form_page_form->form_name');";
                        break;
                    case 'name':
                        $form_element = "
                                const TF__form__sub_$k = document.getElementsByName('$form_page_form->form_name'); 
                                const $form_name = TF__form__sub_{$k}['0'];";
                        break;
                    case 'class':
                        $form_element = "
                                const TF__form__sub_$k = document.querySelectorAll('form.$form_page_form->form_name'); 
                                const $form_name = TF__form__sub_{$k}['0'];";
                        break;
                    case 'index':
                        $form_element = "const $form_name = document.querySelectorAll('form')['$form_page_form->form_name']";
                        break;
                }

                $form_submit_code = <<<EOT
                    if (location.href == '$form_page->url' || '$form_page->url/') {
                        $form_element
                        if ($form_name) {
                            $form_name.addEventListener('submit', (event) => {
                                let form_data = new FormData(event.target);
                                let form_entries = form_data.entries();
                                const json_data = Object.fromEntries(form_data.entries());
                                
                                for (let i in json_data) {
                                    if (typeof(json_data[i]) !== 'string') {
                                        delete json_data[i];
                                    }
                                }
                    
                                json_data.special_tfw_token = '$user_form->tracking_key';
                                var xhr = new XMLHttpRequest();
                                    xhr.open("POST", '$track_form_url', true);
                                    xhr.setRequestHeader('Content-Type', 'application/json');
                                    xhr.send(JSON.stringify(json_data));
                            });
                        }
                    }
EOT;
                $track_items[] = $form_submit_code;
            }
        }

        $track_item_content = implode(' ',$track_items);
        $user_form->status = 'completed';
        $user_form->tracking_code = \JShrink\Minifier::minify(<<<EOT
             window.onload = function(){
                $track_item_content
             }
EOT);
        $user_form->update();

        return $user_form->tracking_code;
    }

    public static function generateInitials($name)
    {
        $name_obj = explode(' ',$name);
        $initials = $name_obj['0']['0'];
        if (isset($name_obj['1'])) {
            $initials .= $name_obj['1']['0'];
        }

        return strtoupper($initials);
    }

    public static function addUserNotification($user_id, $title, $description, $url, $type, $status)
    {
        $model = new UserNotification();
        $model->user_id = $user_id;
        $model->title = $title;
        $model->description = $description;
        $model->url = $url;
        $model->type = $type;
        $model->status = $status;
        $model->product = 'tradieflow';
        $model->save();
    }

    public static function getLandingPageTaglineOptions()
    {
        return [
            'tradiecrm' => [
                'headline' => 'Best',
                'tagline' => 'CRM for Tradies'
            ],
            'tradiesoftware' => [
                'headline' => 'Best',
                'tagline' => 'Tradie Software'
            ],
            'crmfortradies' => [
                'headline' => 'Best',
                'tagline' => 'CRM For Tradies'
            ],
            'crmfortradesmen' => [
                'headline' => 'Best',
                'tagline' => 'CRM For Tradesmen'
            ],
            'tradesmencrm' => [
                'headline' => 'Best',
                'tagline' => 'Tradesmen CRM'
            ],
            'tradesmensoftware' => [
                'headline' => 'Best',
                'tagline' => 'Tradesman Software'
            ],
            'builderscrm' => [
                'headline' => 'Best',
                'tagline' => 'Builders CRM'
            ],
            'contractorscrm' => [
                'headline' => 'Best',
                'tagline' => 'Contractors CRM'
            ],
            'electricianscrm' => [
                'headline' => 'Best',
                'tagline' => 'Electrician CRM'
            ],
            'plumberscrm' => [
                'headline' => 'Best',
                'tagline' => 'Plumbers CRM'
            ],
            'handymancrm' => [
                'headline' => 'Best',
                'tagline' => 'Handyman CRM'
            ],
            'contractorsoftware' => [
                'headline' => 'Best',
                'tagline' => 'Contractor Software'
            ],
            'electriciansoftware' => [
                'headline' => 'Best',
                'tagline' => 'Electrician Software'
            ],
            'plumberssoftware' => [
                'headline' => 'Best',
                'tagline' => 'Plumbers Software'
            ],
            'hanydmansoftware' => [
                'headline' => 'Best',
                'tagline' => 'Handyman Software'
            ],
            'bestappforbuilders' => [
                'headline' => 'Best',
                'tagline' => 'App for Builders'
            ],
            'bestcontractorapp' => [
                'headline' => 'Best',
                'tagline' => 'App for Contractors'
            ],
            'bestappfortradies' => [
                'headline' => 'Best',
                'tagline' => 'App for Tradies'
            ],
            'invoiceappforcontractors' => [
                'headline' => 'Best',
                'tagline' => 'Invoice App for Contractors'
            ],
            'invoiceappforbuilders' => [
                'headline' => 'Best',
                'tagline' => 'Invoice App for Builders'
            ],
            'invoiceappfortradies' => [
                'headline' => 'Best',
                'tagline' => 'Invoice App for Tradies'
            ],
            'electricianapp' => [
                'headline' => 'Best',
                'tagline' => 'App for Electricians'
            ],
            'plumbersapp' => [
                'headline' => 'Best',
                'tagline' => 'Plumbers App'
            ],
            'handymanapp' => [
                'headline' => 'Best',
                'tagline' => 'Handyman App'
            ],
            'decoratorssoftware' => [
                'headline' => 'Best',
                'tagline' => 'Decorators Software'
            ]
        ];
    }

    public static function caclulateUserOnboardingState($user, $user_onboarding)
    {
        $total_items = $user_onboarding->account + $user_onboarding->phone_numbers + $user_onboarding->calendar + $user_onboarding->forms + $user_onboarding->invoices + $user_onboarding->integrations + $user_onboarding->subscriptions + $user_onboarding->help;
        return $total_items ? ceil($total_items / 8 * 100)  : 0;
    }

    public static function getCountryCurrencyCode($country)
    {
        $default_currency = 'usd';
        if ($country) {
            switch ($country->code) {
                case 'us':
                    $default_currency = 'usd';
                break;
                case 'ca':
                    $default_currency = 'cad';
                break;
                case 'gb':
                    $default_currency = 'gbp';
                break;
                case 'au':
                    $default_currency = 'aud';
                break;
            }
        }

        return $default_currency;
    }

    public static function getAvailableCurrenciesList()
    {
        return [
            'usd' => 'USD',
            'cad' => 'CAD',
            'gbp' => 'GBP',
            'aud' => 'AUD'
        ];
    }

    public static function getAvailableCurrencySybmols($show_space = false)
    {
        return [
            'usd' => '$',
            'cad' => 'CAD'.($show_space ? ' ' : ''),
            'gbp' => '£',
            'aud' => 'AUD'.($show_space ? ' ' : ''),
        ];
    }

    public static function handlePopupNotifications($days, $user_subscription)
    {
        $user_subscription->last_popup_notification_date = Carbon::now()->format('Y-m-d');
        $user_subscription->update();

        $model = new UserNotification();
        switch ($user_subscription->subscription_plan_code) {
            case 'trial':
                $model->title = 'Free Trial';
                if ($days == 1) {
                    $model->description = 'Your Free Trial will expire in 24 hours. If you do not switch to a Pro Subscription you will lose your phone number.';
                }
                else{
                    $model->description = 'Your Free Trial will expire in '.$days.' days. If yo do not switch to a Pro Subscription you will lose your phone number.';
                }
            break;
            case 'pro':
                $model->title = 'Payment Failed';
                if ($days == 1) {
                    $model->description = 'Your Monthly Subscription will expire in 24 hours. If you do not renew it, you will lose your phone number.';
                }
                else{
                    $model->description = 'Your Monthly Subscription will expire in '.$days.' days. If you do not renew it, you will lose your phone number.';
                }
            break;
            case 'yearly':
                $model->title = 'Payment Failed';
                if ($days == 1) {
                    $model->description = 'Your Yearly Subscription will expire in 24 hours. If you do not renew it, you will lose your phone number.';
                }
                else{
                    $model->description = 'Your Yearly Subscription will expire in '.$days.' days. If you do not renew it, you will lose your phone number.';
                }
            break;
        }

        $model->user_id = $user_subscription->user_id;
        $model->url = '/settings/subscriptions';
        $model->type = 'subscription';
        $model->status = 'fail';
        $model->product = 'tradieflow';
        $model->save();
    }

    public static function clientHistoryEventDateFormat($start_date_time, $end_date_time)
    {
        $start_date_time_obj = Carbon::createFromFormat('Y-m-d H:i:s',$start_date_time);
        $end_date_time_obj = Carbon::createFromFormat('Y-m-d H:i:s',$end_date_time);

        if ($start_date_time_obj->copy()->format('Y-m-d') == $end_date_time_obj->copy()->format('Y-m-d')) {
            return $start_date_time_obj->copy()->format('F j, Y H:i').' - '.$end_date_time_obj->copy()->format('H:i');
        }
        else{
            return $start_date_time_obj->copy()->format('F j, Y H:i').' - '.$end_date_time_obj->copy()->format('F j, Y H:i');
        }
    }

    public static function convertDateToFriendlyFormat($date_time, $show_full = false)
    {
        $diff = Carbon::now()->diff($date_time);
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $period_options = [
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        ];

        foreach ($period_options as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            }
            else {
                unset($period_options[$k]);
            }
        }

        if (!$show_full) {
            $period_options = array_slice($period_options, 0, 1);
        }
        return $period_options ? implode(', ', $period_options) . ' ago' : 'just now';
    }

    public static function generateEventStartEndDateFormat($start_date_time, $end_date_time)
    {
        $start_date_obj = Carbon::createFromFormat('Y-m-d H:i:s',$start_date_time);
        $end_date_obj = Carbon::createFromFormat('Y-m-d H:i:s',$end_date_time);
        if ($start_date_obj->copy()->format('Y-m-d') == $end_date_obj->copy()->format('Y-m-d')) {
            return $start_date_obj->copy()->format('m/d/Y').' '.$start_date_obj->copy()->format('H:i').' - '.$end_date_obj->copy()->format('H:i');
        }
        else{
            return $start_date_obj->copy()->format('m/d/Y').' '.$start_date_obj->copy()->format('H:i').' - '.$end_date_obj->copy()->format('m/d/Y').' '.$end_date_obj->copy()->format('H:i');
        }
    }

    public static function trackOutgoingCall($user, $phone, $client_id, $twilio_call_id)
    {
        $model = new CallHistory();
        $model->user_id = $user->user_id;

        if ($client_id) {
            /**Check Client*/
            $client = Client::where('user_id', '=', $user->user_id)->find($client_id);
            if ($client) {
                $model->client_id = $client->client_id;
            }
        }

        $model->phone = $phone;
        $model->type = 'outgoing';
        $model->twilio_call_id = $twilio_call_id;
        $model->save();

        /**Track Call History*/
        if (isset($model->client_id) && $model->client_id) {
            self::addClientActivity($model->client_id, $model->call_history, 'Call', null, null, null, 'call');
        }

        /**Update Source*/
        Client::select('client.*')
            ->leftJoin('client_phone','client_phone.client_id','=','client.client_id')
            ->where('client.user_id','=',$user->user_id)
            ->where('client.source_type','=',null)
            ->where('client_phone.phone','=',$phone)
            ->update([
                'source_type' => 'phone'
            ]);

        return true;
    }

    public static function getPhoneCallHistory($auth_user, $phone, $client_id, $page)
    {
        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });
        $call_history = CallHistory::select([
            'call_history_id',
            'recorded_audio_file',
            'recorded_playtime_format',
            'type',
            'created_at'
        ])
            ->where('user_id','=',$auth_user->user_id)
            ->where('phone','=',$phone);

        if ($client_id) {
            $call_history->where('client_id','=',$client_id);
        }

        $call_history = $call_history
            ->orderBy('created_at','desc')
            ->paginate(10);

        $history_items = [];
        foreach ($call_history as $item) {
            $history_items[] = [
                'user_friendly_format' => self::convertDateToFriendlyFormat($item->created_at->format('Y-m-d H:i:s')),
                'full_format' => $item->created_at->format('d/m/Y H:i'),
                'time_format' => $item->created_at->format('H:i'),
                'recorded_audio_file' => $item->recorded_audio_file,
                'recorded_playtime_format' => $item->recorded_playtime_format,
                'type' => $item->type,
                'id' => $item->call_history_id
            ];
        }

        return [
            'total_pages' => $call_history->lastPage(),
            'items' => $history_items
        ];
    }

    public static function generateReviewSendTextMessage($user, $id)
    {
        return 'Hey,'."\r\n".'Thanks for choosing '.$user->name.' as your provider. Please review the service at this link: '.env('APP_URL').'/rate/'.$id."\r\n".'Best,'.$user->name;
    }

    public static function generateTradieReviewSendTextMessage($user, $id)
    {
        return 'Hey,'."\r\n".'Thanks for choosing '.$user->name.' as your provider. Please review the service at this link: '.env('TRADIEREVIEWS_URL').'/rate/'.$id."\r\n".'Best,'.$user->name;
    }

    public static function validateRecaptcha($recaptcha_response)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                'secret' => env('GOOGLE_RECAPTCHA_SITE_SECRET'),
                'response' => $recaptcha_response
            )
        ));
        $response = json_decode(curl_exec($curl));
        curl_close($curl);

        return (isset($response->success) && $response->success) ? true : false;
    }

    public static function convertPhoneToFormat($country_code, $phone_number)
    {
        $clean_phone = preg_replace('/[^0-9.]+/', '', $phone_number);
        $phone_format = null;

        try {
            switch ($country_code) {
                case 'us':
                case 'ca':
                    if (preg_match( '/^(\d{3})(\d{3})(\d{4})$/',$clean_phone, $matches)) {
                        $phone_format = '('.$matches['1'].') '.$matches['2'].'-'.$matches['3'];
                    }
                    break;
                case 'au':
                    if (preg_match( '/^(\d{2})(\d{4})(\d{4})$/',$clean_phone, $matches)) {
                        $phone_format = '('.$matches['1'].') '.$matches['2'].' '.$matches['3'];
                    }
                    break;
                case 'gb':
                    if (preg_match( '/^(\d{2})(\d{3})(\d{4})$/',$clean_phone, $matches)) {
                        $phone_format = $matches['1'].' '.$matches['2'].' '.$matches['3'];
                    }
                    break;
            }
        }
        catch (\Exception $e) {

        }

        return $phone_format;
    }

    public static function generateSignupReferralSendTextMessage($user, $referral_code)
    {
        return $user->name.' just sent you a referral and a 1-month premium subscription for free on TradieFlow.'."\r\n".
            'TradieFlow handles your leads, schedules quotes, books in your jobs, sends invoices, and collects payment, all from the very same app. If you subscribe to TradieFlow, you and '.$user->name.' will get a 1-month premium subscription for free, and everybody wins!'."\r\n".
            'Join TradieFlow now on the following link: '.env('APP_URL').'/referrals/'.$referral_code;
    }

    public static function queueLeaveReviewRequest($target, $type, $user, $code)
    {
//        $name_obj = explode(' ',$user->name);
//        $other_parameters = [
//            'company_name' => $user->reviews_company_name ? $user->reviews_company_name : $user->invoice_company_name,
//            'name' => $user->name,
//            'first_name' => $name_obj['0'],
//            'url' => env('APP_URL').'/rate/job/'.$code,
//            'logo' => $user->reviews_logo
//        ];
//
//        $email_content = view('emails.leave_review', compact('other_parameters'))->render();
//
//        $model = new EmailQueue();
//        $model->target = $target;
//        $model->type = $type;
//        $model->subject = 'Leave a Review';
//        $model->message = $email_content;
//        $model->status = 'pending';
//        $model->save();

        return true;
    }

    public static function signupUser($type, $request, $other_params, $twilio_phone_obj, $referral_code = null, $special_offer_params = [])
    {
        if (isset($special_offer_params['user']) && $special_offer_params['user']) {
            $model = $special_offer_params['user'];
            $subscription_plan = SubscriptionPlan::where('plan_code','=',$special_offer_params['special_offer_user']->plan_code)->where('type','=','other')->first();
            $subscription_expiry_date_obj = Carbon::createFromFormat('Y-m-d H:i:s',$special_offer_params['special_offer_user']->created_at)->addMonth($subscription_plan->duration_num);
            $currency = $model->currency;
            /**Create ActiveCampaign Log*/
            Helper::addActiveCampaignQueueItem($model->user_id,$model->email,'purchase_tag');
        }
        else{
            /**Create User*/
            $model = new User();
            $model->name = $request->name;
            $model->email = $request->email;

            /**Get currency*/
            $currency = 'usd';
            $early_access_user = EarlyAccessUser::where('email','=',$request->email)
                ->where('type','=','tradieflow')
                ->first();

            if ($early_access_user) {
                $subscription_plan = SubscriptionPlan::where('plan_code','=',$early_access_user->subscription_plan_code)->where('type','=','tradieflow')->first();
                if ($early_access_user->subscription_plan_code == 'pro') {
                    $subscription_expiry_date_obj = Carbon::createFromFormat('Y-m-d H:i:s',$early_access_user->created_at)->addMonth();
                }
                else{
                    $subscription_expiry_date_obj = Carbon::createFromFormat('Y-m-d H:i:s',$early_access_user->created_at)->addYear();
                }

                $currency = $early_access_user->currency;
                $model->stripe_customer_id = $early_access_user->stripe_customer_id;
                /**Create ActiveCampaign Log*/
                Helper::addActiveCampaignQueueItem($model->user_id,$model->email,'purchase_tag');
            }
            else{
                $subscription_plan = SubscriptionPlan::where('plan_code','=','trial')->where('type','=','tradiereview')->first();
                $subscription_expiry_date_obj = Carbon::now()->addDays($subscription_plan->duration_num);
                if (isset($special_offer_params['country']) && $special_offer_params['country']) {
                    $currency = ($special_offer_params['country']->code == 'au') ? 'aud' : 'usd';
                }

                $has_more_days = $subscription_plan->duration_num == 1 ? '' : 's';
                $model->tradieflow_subscription_expire_message = $subscription_plan->duration_num.' day'.($has_more_days ? 's' : '').' remaining of your free trial';
                /**Create ActiveCampaign Log*/
                Helper::addActiveCampaignQueueItem($model->user_id,$model->email,'trial_tag');
            }

            $model->active = '1';
            $model->role = 'user';
            $model->name_initials = self::generateInitials($request->name);
            $model->otp_code = self::generateUniqueFourDigits();
            $model->otp_created_date = Carbon::now()->format('Y-m-d H:i:s');
            $model->currency = $currency;

            $week_nums = Constant::GET_WEEK_DAYS();
            foreach ($week_nums as $key => $item) {
                $model->{$key} = '1';
                $model->{$key.'_start'} = '09:00';
                $model->{$key.'_end'} = '23:00';
            }
        }

        if (isset($other_params) && $other_params) {
            foreach ($other_params as $key => $value) {
                $model->{$key} = $value;
            }
        }

        if (isset($special_offer_params['special_offer_user']) && $special_offer_params['special_offer_user']) {
            $model->stripe_customer_id = $special_offer_params['special_offer_user']->stripe_customer_id;
        }

        $model->save();

        if ($referral_code) {
            /**Check User Referrals*/
            $user_referral = UserReferralCode::where('referral_code','=',$referral_code)->where('type','=','tradieflow')->first();
            if ($user_referral) {
                $referral_queue = new UserReferralMonthQueue();
                $referral_queue->sent_user_id = $user_referral->user_id;
                $referral_queue->received_user_id = $model->user_id;
                $referral_queue->has_admin_sent = '0';
                $referral_queue->type = 'tradieflow';
                $referral_queue->status = 'pending';
                $referral_queue->save();
            }
            else{
                /**Check Admin Referrals*/
                $admin_referral = UserGiveawayReferral::where('code','=',$referral_code)->where('status','=','pending')->where('type','=','tradieflow')->first();
                if ($admin_referral) {
                    $admin_referral->registered_user_id = $model->user_id;
                    $admin_referral->status = 'accepted';
                    $admin_referral->update();

                    /**Add new expiry days*/
                    $model->tradieflow_subscription_expire_message = null;
                    $model->update();
                    $subscription_expiry_date_obj = Carbon::now()->addMonths($admin_referral->months);
                }
            }

            /**Create Referral Session*/
            Session::forget('signup_referral_code');
        }

        /**Referral Codes*/
        $user_referral_code = new UserReferralCode();
        $user_referral_code->user_id = $model->user_id;
        $user_referral_code->type = 'tradieflow';
        $user_referral_code->referral_code = md5(uniqid().env('APP_KEY').$model->user_id);
        $user_referral_code->save();

        /**Create Twilio Number*/
        if (isset($twilio_phone_obj) && $twilio_phone_obj) {
            $twilio_phone_obj['user_id'] = $model->user_id;
            UserTwilioPhone::create($twilio_phone_obj);
        }

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
        $user_subscription->type = 'tradieflow';
        $user_subscription->expiry_date_time = $subscription_expiry_date_obj->copy()->format('Y-m-d H:i:s');
        $user_subscription->active = '1';

        if (isset($special_offer_params['special_offer_user']) && $special_offer_params['special_offer_user']) {
            $user_subscription->price = $special_offer_params['special_offer_user']->price;
            $user_subscription->gst_amount = $special_offer_params['special_offer_user']->gst_amount;
        }
        elseif(isset($early_access_user) && $early_access_user) {
            $user_subscription->price = $early_access_user->amount;
            $user_subscription->gst_amount = $early_access_user->gst_amount;
        }
        else{
            $user_subscription->price = 0;
        }

        $user_subscription->currency = $currency;
        $user_subscription->save();

        /**Notify Admin*/
        try{
            $phone = ($twilio_phone_obj) ? $twilio_phone_obj['phone'] : null;
            $user_industry = UserIndustry::selectRaw('group_concat(industry.name SEPARATOR ", ") as industries')
                ->leftJoin('industry','industry.industry_id','=','user_industry.industry_id')
                ->where('user_industry.user_id','=',$model->user_id)
                ->first();
            $industry = $user_industry ? $user_industry->industries : null;
            $user_business_types = UserBusinessType::selectRaw('group_concat(business_type.name SEPARATOR ", ") as business_types')
                ->leftJoin('business_type','business_type.business_type_id','=','user_business_type.business_type_id')
                ->where('user_business_type.user_id','=',$model->user_id)
                ->first();

            $invoice_settings = UserInvoiceSetting::where('user_id','=',$model->user_id)->first();
            $company_name = ($invoice_settings) ? $invoice_settings->company_name : '';

            $help_business = $user_business_types ? $user_business_types->business_types : null;
            NotificationHelper::signupAdminAlert($model->name, $model->email, $company_name, $phone, $industry, $help_business);
        }
        catch (\Exception $e) {

        }

        /**Create Onboarding*/
        $user_onboarding = new UserOnboarding();
        $user_onboarding->user_id = $model->user_id;
        $user_onboarding->status = 'pending';
        $user_onboarding->type = 'tradieflow';
        $user_onboarding->save();

        /**Clean data if any*/
        UserRegisterQueue::where('email','=',$model->email)
            ->where('type','=','tradieflow')
            ->delete();

        return $model;
    }

    public static function getUserOnboarding($user)
    {
        return UserOnboarding::where('user_id','=',$user->user_id)
            ->where('type','=','tradieflow')
            ->first();
    }

    public static function sendInvoices($user, $invoices, $type, $message_content, $invoice_ids, $target)
    {
        /**Send to Xero First*/
        $xero_account = UserXeroAccount::where('user_id','=',$user->user_id)
            ->where('active','=','1')
            ->first();

        if ($xero_account) {
            try {
                $xero_instance = XeroHelper::getXeroInstance($xero_account);

                if ($invoices['0']->Client->xero_id) {
                    $xero_client_id = $invoices['0']->Client->xero_id;
                }
                else{
                    $xero_client_id = null;
                    /**1. Check By Email*/
                    $get_xero_contact = [];
                    if ($invoices['0']->Client->email) {
                        $where = 'EmailAddress="'.$invoices['0']->Client->email.'"';
                        $get_xero_contact = $xero_instance->getContacts($xero_account->tenant_id, null, $where, null, null, $page = 1, $includeArchived = true, true);
                    }

                    /**2. Find By Name*/
                    if (!isset($get_xero_contact['0']['contact_id'])) {
                        $where = 'Name="'.$invoices['0']->Client->name.'"';
                        $get_xero_contact = $xero_instance->getContacts($xero_account->tenant_id, null, $where, null, null, $page = 1, $includeArchived = true, true);
                    }

                    /**Update Client or create a new Xero Contact*/
                    if (isset($get_xero_contact['0']['contact_id'])) {
                        $xero_client_id = $get_xero_contact['0']['contact_id'];
                        Client::where('client_id','=',$invoices['0']->client_id)
                            ->update(['xero_id' => $xero_client_id]);
                    }
                    else{
                        $get_client_phones = ClientPhone::where('client_id','=',$invoices['0']->client_id)->get();
                        $phones = [];

                        foreach ($get_client_phones as $item) {
                            $phone = new \XeroAPI\XeroPHP\Models\Accounting\Phone;
                            $phone->setPhoneNumber($item->phone);
                            $phone->setPhoneType(\XeroAPI\XeroPHP\Models\Accounting\Phone::PHONE_TYPE_MOBILE);
                            $phones = [$phone];
                        }

                        $contact = new \XeroAPI\XeroPHP\Models\Accounting\Contact;
                        $contact->setName($invoices['0']->Client->name);
                        if ($invoices['0']->Client->email) {
                            $contact->setEmailAddress($invoices['0']->Client->email);
                        }
                        if ($phones) {
                            $contact->setPhones($phones);
                        }

                        $contacts = new \XeroAPI\XeroPHP\Models\Accounting\Contacts;
                        $contacts->setContacts([$contact]);

                        $result = $xero_instance->createContacts($xero_account->tenant_id, $contacts, false);
                        if (isset($result['0']['contact_id'])) {
                            $xero_client_id = $result['0']['contact_id'];
                            Client::where('client_id','=',$invoices['0']->client_id)
                                ->update(['xero_id' => $xero_client_id]);
                        }
                    }
                }

                if ($xero_client_id) {
//                                foreach ($invoices as $invoice_item) {
//                                    $contact = new \XeroAPI\XeroPHP\Models\Accounting\Contact;
//                                    $contact->setContactID($xero_client_id);
//                                    $lineItems = [];
//                                    foreach ($invoice_item->InvoiceItem as $invoice_line_item) {
//                                        $lineItem = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
//                                        $lineItem->setDescription($invoice_line_item->description);
//                                        $lineItem->setQuantity($invoice_line_item->qty);
//                                        $lineItem->setUnitAmount($invoice_line_item->unit_price);
//                                        $lineItem->setAccountCode('000');
//                                        $lineItems = [$lineItem];
//                                    }
//
//                                    $invoice = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;
//                                    $invoice->setType(\XeroAPI\XeroPHP\Models\Accounting\Invoice::TYPE_ACCREC);
//                                    $invoice->setContact($contact);
//                                    $invoice->setDate(new \DateTime(Carbon::now()->format('Y-m-d')));
//                                    $invoice->setDueDate(new \DateTime($invoice_item->due_date));
//                                    $invoice->setLineItems($lineItems);
//                                    $invoice->setReference('Invoice');
//                                    $invoice->setStatus(\XeroAPI\XeroPHP\Models\Accounting\Invoice::STATUS_DRAFT);
//
//                                    $invoices = new \XeroAPI\XeroPHP\Models\Accounting\Invoices;
//                                    $invoices->setInvoices([$invoice]);
//
//                                    $result = $xero_instance->createInvoices($xero_account->tenant_id, $invoices, $summarizeErrors = true, $unitdp = 2);
//                                }
                }
            } catch (\Exception $e) {

            }
        }

        if ($type == 'email') {
            $notification = Notification::where('object_name', '=', 'sendInvoiceNotification')
                ->where('active', '=', '1')
                ->first();

            if ($notification) {
                $target = ($target) ? $target : $invoices['0']->email;
                NotificationHelper::sendInvoiceViewEmail($notification, $target, $message_content, $invoices['0']->invoice_unique_number, $user);
            }

            Invoice::where('user_id','=',$user->user_id)->whereIn('invoice_id',$invoice_ids)
                ->update([
                    'status' => 'sent-email'
                ]);

            return true;
        }
        else {
            /**Send out Twilio Message*/
            $user_phone = UserTwilioPhone::where('user_id', '=', $user->user_id)->first();

            if ($user_phone) {
                $sid = env('TWILIO_ACCOUNT_SID');
                $token = env('TWILIO_AUTH_TOKEN');
                $twilio = new \Twilio\Rest\Client($sid, $token);

                try {
                    $message_content = str_replace('<br>',"\r\n",$message_content);
                    $params = [
                        "body" => $message_content."\r\n".env('APP_URL').'/invoice/'.$invoices['0']->invoice_unique_number,
                        "from" => $user_phone->phone
                    ];

                    $target = ($target) ? $target : $invoices['0']->phone;
                    $message = $twilio->messages
                        ->create($target, $params);

                    if ($message) {
                        /**Save to messages*/
                        $model = new TextMessage();
                        $model->user_id = $user->user_id;
                        $model->client_id = $invoices['0']->client_id;
                        $model->message = $message_content;
                        $model->from_number = $user_phone->phone;
                        $model->to_number = $target;
                        $model->client_sent = '0';
                        $model->twilio_sid = $message->sid;
                        $model->save();
                    }

                    Invoice::where('user_id','=',$user->user_id)->whereIn('invoice_id',$invoice_ids)
                        ->update([
                            'status' => 'sent-text'
                        ]);

                    return true;
                } catch (\Exception $e) {
                    return false;
                }
            }
        }
    }

    public static function GET_GEO_COUNTRY_IP()
    {
        $get_geo_data = null;
        try{
            $get_geo_data = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR']));
        }
        catch (\Exception $e) {

        }

        return $get_geo_data;
    }

    public static function getNotificationItems($user)
    {
        $unread_notifications = UserNotification::select([
            'user_notification_id',
            'title',
            'description',
            'url',
            'type',
            'status',
            'created_at as created_date_format'
        ])
            ->where('user_id','=',$user->user_id)
            ->where('has_read','=','0')
            ->where('product','=','tradieflow')
            ->orderBy('created_at','desc')
            ->take(6)
            ->get()
            ->toArray();

        $notifications_data = [];
        $has_more_items = count($unread_notifications) > 5 ? true : false;
        $unread_notifications = array_slice($unread_notifications,0,5);
        foreach ($unread_notifications as &$item) {
            $item['timeframe'] = Helper::calculateEstimateFullTime($item['created_date_format']);
            $notifications_data[] = $item;
            unset($item['created_date_format']);
        }

        return [
            'has_more_items' => $has_more_items,
            'unread_notifications' => $unread_notifications
        ];
    }

    public static function addActiveCampaignQueueItem($user_id, $email, $action)
    {
        $model = new ActiveCampaignQueue();
        $model->user_id = $user_id;
        $model->email = $email;
        $model->action = $action;
        $model->type = 'tradieflow';
        $model->status = 'pending';
        $model->save();
    }

    public static function generateUUID() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }

    public static function generateSendInvoiceMessage($client, $invoice, $sender)
    {
        return "Dear ".$client->name.",<br><br>I attach my Invoices of ".strtoupper($invoice->currency)." ".number_format($invoice->total_gross_amount,2)." with the Payment Date of ".$invoice->due_date_format.". You can check the invoice by clicking on the below button.<br><br>Best Regards, <br>".$sender->name;
    }
}
