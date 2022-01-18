<?php

namespace App\Http\Controllers;
use App\Helpers\Constant;
use App\Helpers\Helper;
use App\Helpers\NotificationHelper;
use App\Models\ClientReview;
use App\Models\ClientValue;
use App\Models\Country;
use App\Models\EarlyAccessUser;
use App\Models\EmailSubscription;
use App\Models\FAQ;
use App\Models\Invoice;
use App\Models\InvoiceStripePayment;
use App\Models\Notification;
use App\Models\ReviewInvite;
use App\Models\SpecialOfferPagePurchase;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserDeveloperInvite;
use App\Models\UserForm;
use App\Models\UserGiveawayReferral;
use App\Models\UserInvoiceSetting;
use App\Models\UserSubscription;
use App\Models\UserSubscriptionLog;
use App\Models\UserTwilioPhone;
use App\Models\UserXeroMobileRedirect;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Cookie;
use Illuminate\Support\Facades\Config;
use Session;
use Stripe\Review;

class LandingPageController extends Controller
{
    public function __construct()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $auth_user = Auth::user();
        $app_cdn_url = env('APP_CDN_URL');
        $is_mobile_device = (new \Jenssegers\Agent\Agent())->isMobile();
        $pre_tag_line = 'All-in-One';
        $tagline = 'Tradie Software';
        return view('landing.index',compact(
            'auth_user',
            'app_cdn_url',
            'is_mobile_device',
            'pre_tag_line',
            'tagline'
        ));
    }

    public function landingPageFor(Request $request)
    {
        $auth_user = Auth::user();
        $url = str_replace('/','',$request->getRequestUri());
        $url = explode('?',$url)['0'];

        $tagline_items = Helper::getLandingPageTaglineOptions();
        if (!isset($tagline_items[$url])) {
            return redirect('/');
        }

        $pre_tag_line = $tagline_items[$url]['headline'].' ';
        $tagline = $tagline_items[$url]['tagline'];
        $app_cdn_url = env('APP_CDN_URL');
        $is_mobile_device = (new \Jenssegers\Agent\Agent())->isMobile();
        return view('landing.index',compact(
            'auth_user',
            'pre_tag_line',
            'tagline',
            'app_cdn_url',
            'is_mobile_device'
        ));
    }

    public function demo()
    {
        $auth_user = Auth::user();
        return view('landing.demo',compact(
            'auth_user'
        ));
    }

    public function contactUs(Request $request)
    {
        $auth_user = request()->user();
        return view('landing.contact_us',compact(
            'auth_user'
        ));
    }

    public function privacyPolicy()
    {
        $auth_user = Auth::user();
        $has_subscribed = Cookie::get('has_subscribed');
        if (!$has_subscribed && $auth_user) {
            $has_subscribed = EmailSubscription::where('user_id','=',$auth_user->user_id)->where('product','=','tradieflow')->count();
        }

        return view('landing.privacy_policy',compact(
            'auth_user',
            'has_subscribed'
        ));
    }

    public function terms()
    {
        $auth_user = Auth::user();
        $has_subscribed = Cookie::get('has_subscribed');
        if (!$has_subscribed && $auth_user) {
            $has_subscribed = EmailSubscription::where('user_id','=',$auth_user->user_id)->where('product','=','tradieflow')->count();
        }

        return view('landing.terms',compact(
            'auth_user',
            'has_subscribed'
        ));
    }

    public function cookies()
    {
        $auth_user = Auth::user();
        $has_subscribed = Cookie::get('has_subscribed');
        if (!$has_subscribed && $auth_user) {
            $has_subscribed = EmailSubscription::where('user_id','=',$auth_user->user_id)->where('product','=','tradieflow')->count();
        }

        return view('landing.cookies',compact(
            'auth_user',
            'has_subscribed'
        ));
    }

    public function subscribe(Request $request)
    {
        if (!filter_var($request['email'],FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'status' => false,
                'error' => 'Email not valid'
            ]);
        }

        $has_subscribed = false;
        $auth_user = Auth::user();
        $user_subscription = EmailSubscription::where('email','=',$request['email'])->where('product','=','tradieflow')->first();
        if ($user_subscription) {
            if (!$user_subscription->user_id && $auth_user) {
                $user_subscription->user_id = $auth_user->user_id;
                $user_subscription->update();
            }
        }
        else{
            $model = new EmailSubscription();
            $model->user_id = ($auth_user) ? $auth_user->user_id : null;
            $model->email = $request['email'];
            $model->product = 'tradieflow';
            $model->save();

            /**Create ActiveCampaign Log*/
            Helper::addActiveCampaignQueueItem($model->user_id,$model->email,'email_subscriber');
            $has_subscribed = true;
        }

        Session::put('email_subscribed',true);
        Session::save();

        return response()->json([
            'status' => true,
            'subscribed' => $has_subscribed
        ]);
    }

    public function setSubscriberDetails()
    {
        $has_subscribed = Session::get('email_subscribed');
        if ($has_subscribed) {
            Cookie::queue('has_subscribed', true, 60 * 60 * 24 * 365);
            Session::forget('email_subscribed');
        }

        return redirect()
            ->back();
    }

    public function earlyAccess($subscription_type = null)
    {
        $auth_user = Auth::user();
        if ($auth_user) {
            return redirect('settings/account');
        }

        if ($subscription_type && !in_array($subscription_type,['monthly','yearly'])) {
            return redirect('early-access');
        }

        $subscription_type = $subscription_type ? $subscription_type : 'monthly';
        $geo_country = Helper::GET_GEO_COUNTRY_IP();
        if (isset($geo_country['geoplugin_countryCode']) && $geo_country['geoplugin_countryCode'] == 'AU') {
            $currency = 'aud';
            $currency_label = 'AUD ';
        }
        else{
            $currency = 'usd';
            $currency_label = '$';
        }

        $subscription_plans = SubscriptionPlan::whereIn('plan_code',['pro','yearly'])->where('type','=','tradieflow')->get();
        $subscription_plan_data = [];
        foreach ($subscription_plans as $item) {
            $price = ($currency == 'aud') ? $item->price_aud : $item->price_usd;
            $item['price'] = $price;
            $item['discounted_price'] = sprintf('%.2f',$price / 2);
            $item['gst_discount_price'] = sprintf('%.2f',$item['discounted_price'] / 10);
            $item['gst_price'] = sprintf('%.2f',$item['price'] / 10);
            $item['total_price'] = sprintf('%.2f',$item['discounted_price'] + $item['gst_discount_price']);
            $subscription_plan_data[$item->plan_code] = $item;
        }

        $selected_plan = ($subscription_type == 'yearly') ? $subscription_plan_data['yearly'] : $subscription_plan_data['pro'];
        $has_subscribed = Cookie::get('has_subscribed');
        if (!$has_subscribed && $auth_user) {
            $has_subscribed = EmailSubscription::where('user_id','=',$auth_user->user_id)->where('product','=','tradieflow')->count();
        }

        return view('landing.early_access',compact(
            'auth_user',
            'has_subscribed',
            'selected_plan',
            'subscription_type',
            'currency',
            'currency_label',
            'subscription_plan_data'
        ));
    }

    public function purchaseEarlyAccess(Request $request)
    {
        $auth_user = Auth::user();
        if ($auth_user) {
            return response()
                ->json([
                    'status' => false,
                    'error' => 'Please logout to purchase a new plan'
                ]);
        }

        if (!$request['email'] || !filter_var($request['email'],FILTER_VALIDATE_EMAIL) || !$request['token']) {
            return response()->json([
                'status' => false,
                'error' => 'Email is not valid'
            ]);
        }

        $has_purchased = EarlyAccessUser::where('email','=',$request['email'])
            ->where('type','=','tradieflow')
            ->first();
        if ($has_purchased) {
            return response()->json([
                'status' => false,
                'error' => 'You have already purchased a '.($has_purchased->subscription_plan_code == 'pro' ? 'monthly' : 'yearly').' subscription for this email'
            ]);
        }

        /**Detect currency*/
        $currency = ($request['currency'] == 'usd') ? 'usd' : 'aud';

        /**Has account*/
        $user = User::where('email','=',$request['email'])->first();
        $has_old_subscription = false;
        if ($user) {
            /**Check for TradieReviews Subscription*/
            $had_tradiereviews_subscription = UserSubscription::where('user_id','=',$user->user_id)
                ->where('type','=','tradieflow')
                ->first();

            if ($had_tradiereviews_subscription) {
                /**Check if is expired or trial*/
                $had_paid_subscription = UserSubscription::where('user_id','=',$user->user_id)
                    ->where('subscription_plan_code','!=','trial')
                    ->where('type','=','tradieflow')
                    ->first();

                if ($had_paid_subscription) {
                    return response()->json([
                        'status' => false,
                        'error' => 'You already have an account created, please login to upgrade subscription'
                    ]);
                }

                $has_old_subscription = true;
            }

            if ($currency !== $user->currency) {
                return response()->json([
                    'status' => false,
                    'currency' => $user->currency,
                    'error' => 'You have already purchased a TradieReviews account and payed for it in '.$user->currency.'. Therefore, we can only process your payment in '.$user->currency.'. Please click on the Checkout button to complete your payment!'
                ]);
            }
        }

        $subscription_plan = SubscriptionPlan::where('plan_code','=',$request['plan'] == 'yearly' ? 'yearly' : 'pro')
            ->where('type','=','tradieflow')
            ->first();

        if ($currency == 'aud') {
            $subscription_plan_amount = $request['discount'] ? $subscription_plan->price_aud / 2 : $subscription_plan->price_aud;
        }
        else{
            $subscription_plan_amount = $request['discount'] ? $subscription_plan->price_usd / 2 : $subscription_plan->price_usd;
        }

        $subscription_plan_amount = sprintf('%.2f',$subscription_plan_amount);
        $gst_amount = sprintf('%.2f',$subscription_plan_amount / 10);
        $subscription_plan_amount += $gst_amount;
        $subscription_plan_amount = sprintf('%.2f',$subscription_plan_amount);

        /**Create Stripe Customer*/
        try {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
            $stripe_card = null;
            if ($user && $user->stripe_customer_id) {
                $stripe_card = $stripe->customers->createSource(
                    $user->stripe_customer_id,
                    ['source' => $request['token']]
                );

                /**Set Default Card*/
                $stripe->customers->update(
                    $user->stripe_customer_id,
                    ['default_source' => $stripe_card->id]
                );

                $stripe_customer = new \stdClass();
                $stripe_customer->id = $user->stripe_customer_id;
            }
            else{
                $stripe_customer = $stripe->customers->create([
                    'email' => $request['email'],
                    'description' => 'Customer early access '.$request['email'],
                    'source' => $request['token']
                ]);
            }

            /**Process Charge*/
            if (isset($stripe_customer->id) && $stripe_customer->id) {
                $charge_account = true;
                if ($stripe_card) {
                    $stripe_charge = $stripe->charges->create([
                        'amount' => $subscription_plan_amount * 100,
                        'currency' => $currency,
                        'customer' => $user->stripe_customer_id,
                        'description' => 'Charge for TradiewReviews early access ' . $request['email'],
                    ]);

                    if (isset($stripe_charge->id) && $stripe_charge->id) {
                        $charge_account = false;
                        $get_all_cards = $stripe->customers->allSources(
                            $stripe_customer->id,
                            ['object' => 'card', 'limit' => 3]
                        );

                        /**Delete old cards*/
                        foreach ($get_all_cards as $item) {
                            if ($item->id !== $stripe_card->id) {
                                $stripe->customers->deleteSource(
                                    $stripe_customer->id,
                                    $item->id,
                                    []
                                );
                            }
                        }
                    }
                    else{
                        $stripe->customers->deleteSource(
                            $stripe_customer->id,
                            $stripe_card->id,
                            []
                        );

                        return response()->json([
                            'status' => false,
                            'error' => 'Payment failed, please try a different card or try again later'
                        ]);
                    }
                }

                if ($charge_account) {
                    $stripe_charge = $stripe->charges->create([
                        'amount' => $subscription_plan_amount * 100,
                        'currency' => $currency,
                        'customer' => $stripe_customer->id,
                        'description' => 'Charge for early access ' . $request['email'],
                    ]);
                }

                if (isset($stripe_charge->id)) {
                    /**Create User*/
                    $model = new EarlyAccessUser();
                    $model->email = $request['email'];
                    $model->amount = $subscription_plan_amount;
                    $model->payment_details = json_encode($stripe_charge);
                    $model->stripe_charge_id = $stripe_charge->id;
                    $model->stripe_customer_id = $stripe_customer->id;
                    $model->subscription_plan_id = $subscription_plan->subscription_plan_id;
                    $model->subscription_plan_code = $subscription_plan->plan_code;
                    $model->type = 'tradieflow';
                    $model->has_discount_accepted = ($request['discount']) ? '1' : '0';
                    $model->signup_code = md5($model->email.uniqid());
                    $model->currency = $currency;
                    $model->gst_amount = $gst_amount;
                    $model->save();

                    /**Send Out Email Confirmation*/
                    if ($model->subscription_plan_code == 'pro') {
                        $expiry_date_format = Carbon::now()->addMonth()->format('F j, Y');
                        $expiration_date_time = Carbon::now()->copy()->addMonth(1)->format('Y-m-d H:i:s');
                    }
                    else{
                        $expiry_date_format = Carbon::now()->addYear()->format('F j, Y');
                        $expiration_date_time = Carbon::now()->copy()->addYear(1)->format('Y-m-d H:i:s');
                    }

                    /**Create New Subscription*/
                    if ($has_old_subscription) {
                        $user_subscriptions = UserSubscription::where('user_id','=',$user->user_id)
                            ->where('type','=','tradieflow')
                            ->get();

                        $insert_data = [];
                        $item_delete_ids = [];
                        foreach ($user_subscriptions as $item) {
                            $insert_data[] = $item->toArray();
                            $item_delete_ids[] = $item->user_subscription_id;
                        }

                        if ($insert_data) {
                            UserSubscriptionLog::insert($insert_data);
                            UserSubscription::whereIn('user_subscription_id',$item_delete_ids)->delete();
                        }

                        $model = new UserSubscription();
                        $model->active = '1';
                        $model->is_extendable = '1';
                        $model->type = 'tradieflow';
                        $model->user_id = $user->user_id;
                        $model->subscription_plan_id = $subscription_plan->subscription_plan_id;
                        $model->subscription_plan_name = $subscription_plan->name;
                        $model->subscription_plan_code = $subscription_plan->plan_code;
                        $model->expiry_date_time = $expiration_date_time;
                        $model->price = ($currency == 'usd') ? $subscription_plan->price_usd : $subscription_plan->price_aud;
                        $model->gst_amount = $gst_amount;
                        $model->currency = $currency;
                        $model->discount_code = 'early_access';
                        $model->discounted_price = $subscription_plan_amount;
                        $model->discount_pay_expiry_date = Carbon::createFromFormat('Y-m-d H:i:s',$expiration_date_time)->addDay('-1')->format('Y-m-d');
                        $model->save();

                        /**Update Stripe*/
                        $user->stripe_customer_id = $stripe_customer->id;
                        $user->update();
                    }

                    /**If Existing User*/
                    if ($stripe_card) {
                        NotificationHelper::earlyAccessPurchasedExistingUserNotification($subscription_plan_amount, $currency, $subscription_plan->name, $expiry_date_format, $request['email']);
                    }
                    else{
                        NotificationHelper::earlyAccessPurchasedNotification($model->signup_code, $subscription_plan_amount, $currency, $subscription_plan->name, $expiry_date_format, $request['email']);
                    }

                    return response()->json([
                        'status' => true,
                        'login_redirect' => $has_old_subscription
                    ]);
                }
            }
        } catch (\Exception $e) {

        }

        return response()->json([
            'status' => false,
            'error' => 'Payment failed, please try a different card or try again later'
        ]);
    }

    public function invoice($id)
    {
        $invoice = Invoice::with('User','Client','Country')
            ->where('invoice_unique_number','=',$id)
            ->first();

        if (!$invoice || !$invoice->User || !$invoice->Client) {
            return redirect('/');
        }

        $currency_symbols = Helper::getAvailableCurrencySybmols(true);
        $invoice_currency = isset($currency_symbols[$invoice->currency]) ? $currency_symbols[$invoice->currency] : 'N/A';
        $invoice_due_days = '';
        $is_overdue_invoice = false;
        $due_date_format = '';
        if (!$invoice->has_paid) {
            $due_date_obj = Carbon::createFromFormat('Y-m-d',$invoice->due_date);
            $invoice_due_days = Carbon::now()->diffInDays($due_date_obj);
            $is_overdue_invoice = $due_date_obj->copy()->timestamp < Carbon::now()->timestamp;
            $due_date_format = $due_date_obj->format('F j, Y');
        }

        $invoice_address = [];
        $client_country = [];
        $user_invoice_setting = UserInvoiceSetting::where('user_id','=',$invoice->user_id)->first();
        if ($user_invoice_setting) {
            $client_country = $user_invoice_setting->Country;

            if ($user_invoice_setting->address) {
                $invoice_address[] = $user_invoice_setting->address;
            }

            if (strlen($user_invoice_setting->zip_code)) {
                $invoice_address[] = $user_invoice_setting->zip_code;
            }

            if (strlen($user_invoice_setting->city)) {
                $invoice_address[] = $user_invoice_setting->city;
            }

            if (strlen($user_invoice_setting->state)) {
                $invoice_address[] = $user_invoice_setting->state;
            }
        }

        $invoice_address = $invoice_address ? implode(', ',$invoice_address) : '';

        return view('landing.invoice',compact(
            'invoice',
            'client_country',
            'invoice_currency',
            'invoice_due_days',
            'is_overdue_invoice',
            'due_date_format',
            'invoice_address',
            'user_invoice_setting'
        ));
    }

    public function payInvoice($id)
    {
        $invoice = Invoice::with([
            'User',
            'Client',
            'Country',
            'InvoiceItem' => function($query){
                $query->orderBy('order_num','asc');
            }
        ])
            ->where('invoice_unique_number','=',$id)
            ->first();

        if (!$invoice || !$invoice->User || !$invoice->Client) {
            return redirect('/');
        }

        if ($invoice->has_paid) {
            return redirect('invoice/'.$id);
        }

        $invoice_address = [];
        $user_invoice_setting = UserInvoiceSetting::where('user_id','=',$invoice->user_id)->first();

        if ($user_invoice_setting) {
            if ($user_invoice_setting->address) {
                $invoice_address[] = $user_invoice_setting->address;
            }

            if (strlen($user_invoice_setting->zip_code)) {
                $invoice_address[] = $user_invoice_setting->zip_code;
            }

            if (strlen($user_invoice_setting->city)) {
                $invoice_address[] = $user_invoice_setting->city;
            }

            if (strlen($user_invoice_setting->state)) {
                $invoice_address[] = $user_invoice_setting->state;
            }
        }

        $invoice_address = $invoice_address ? implode(', ',$invoice_address) : '';
        $currency = strtoupper($invoice->currency);
        return view('landing.invoice_pay',compact(
            'invoice',
            'invoice_address',
            'user_invoice_setting',
            'currency'
        ));
    }

    public function payInvoiceProcess(Request $request)
    {
        $invoice = Invoice::with('User', 'Client')
            ->where('invoice_unique_number','=',$request['id'])
            ->first();

        if (!$invoice) {
            return response()->json([
                'status' => false,
                'error' => 'Unable to find an invoice'
            ]);
        }

        if ($invoice->has_paid) {
            return response()->json([
                'status' => false,
                'error' => 'Invoice already paid'
            ]);
        }

        if (!$invoice->total_gross_amount) {
            return response()->json([
                'status' => false,
                'error' => 'Something wrong with the invoice, please try again later'
            ]);
        }

        try{
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
            $payment_intent = $stripe->paymentIntents->create([
                'amount' => $invoice->total_gross_amount * 100,
                'currency' => strtolower($invoice->currency),
                'confirm' => true,
                'description' => 'Invoice charge for ' . $invoice->invoice_unique_number . ' for ' . $invoice->User->name . ' invoice label ' . $invoice->invoice_number_label,
                'payment_method_data' => [
                    'type' => 'card',
                    'card' => [
                        'token' => $request['token']
                    ]
                ],
            ]);

            if (!isset($payment_intent->id)) {
                return response()->json([
                    'status' => false,
                    'error' => 'Please check your card balance'
                ]);
            }

            /**Process Charge*/
            if ($payment_intent->amount_received || (isset($payment_intent->charges->data['0']->captured) && $payment_intent->charges->data['0']->captured)) {
                $invoice->has_paid = '1';
                $invoice->update();

                /**Create Stripe Payment Queue*/
                $model = new InvoiceStripePayment();
                $model->user_id = $invoice->user_id;
                $model->invoice_id = $invoice->invoice_id;
                $model->amount = $invoice->total_gross_amount;
                $model->currency = $invoice->currency;
                $model->stripe_payment_intent_id = $payment_intent->id;
                $model->stripe_payment_response = json_encode($payment_intent);
                $model->save();

                /**Send Notification To User*/
                NotificationHelper::invoicePaymentReceived($invoice);
                return response()->json([
                    'status' => true
                ]);
            }
            else {
                $stripe->paymentIntents->cancel($payment_intent->id);
                return response()->json([
                    'status' => false,
                    'error' => 'Please check your card balance'
                ]);
            }
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Please check your card balance'
            ]);
        }
    }

    public function rateJob(Request $request, $id)
    {
        $get_client_details = ClientValue::with('Client.User.Country')->where('unique_code','=',$id)->first();
        if ($get_client_details && $get_client_details->Client) {
            if ($get_client_details->status == 'completed') {
                $review_rates = Constant::GET_REVIEW_RATE_SCORES();
                $rate = array_key_exists($request['r'],$review_rates) ? $review_rates[$request['r']] : 5;
                $client = $get_client_details->Client;
                $phone_countries = Country::where('is_twilio','=','1')->pluck('number','code');
                $user_twilio_phone = UserTwilioPhone::where('user_id','=',$get_client_details->Client->user_id)->where('status','=','active')->first();
                $rate_points = Constant::GET_RATE_SCORE_POINTS();
                $review_invite = [];
                $user = $get_client_details->Client->User;
                return view('landing.leave_review',compact(
                    'rate',
                    'client',
                    'phone_countries',
                    'user_twilio_phone',
                    'id',
                    'rate_points',
                    'review_invite',
                    'user'
                ));
            }
        }

        return redirect('/')
            ->with('error','Wrong url or link expired');
    }

    public function postReview(Request $request)
    {
        $allow_add_review = false;
        if (!Helper::validateRecaptcha($request['recaptcha_token'])) {
            return response()->json([
                'status' => false,
                'error' => 'Captcha is wrong'
            ]);
        }

        $model = new ClientReview();
        switch ($request['type']) {
            case 'invite':
                $review_invite = ReviewInvite::where('unique_code','=',$request['code'])
                    ->first();

                if ($review_invite->status == 'pending') {
                    $review_invite->status = 'completed';
                    $review_invite->update();

                    $allow_add_review = true;

                    $model->user_id = $review_invite->user_id;
                    $model->has_invited = '1';
                }
                else{
                    return response()->json([
                        'status' => false,
                        'error' => 'You have already posted a review',
                        'reload' => true
                    ]);
                }
            break;
            case 'job':
                $get_client_details = ClientValue::with('Client')->where('unique_code','=',$request['code'])->first();
                if ($get_client_details && $get_client_details->Client) {
                    if ($get_client_details->status == 'completed') {
                        $allow_add_review = true;
                        $model->user_id = $get_client_details->Client->user_id;
                        $model->client_id = $get_client_details->client_id;
                        $model->client_value_id = $get_client_details->client_value_id;
                        $model->client_name = $get_client_details->Client->name;
                        $model->client_company = $get_client_details->Client->company;
                    }
                }
            break;
            case 'public':
                $user = User::where('public_reviews_code','=',$request['code'])->first();
                if ($user) {
                    $allow_add_review = true;
                    $model->user_id = $user->user_id;
                    $model->is_public_review = '1';
                }
                //check subscription too
            break;
        }

        if ($allow_add_review) {
            /**Email Validation*/
            if (!filter_var($request['email'],FILTER_VALIDATE_EMAIL)) {
                return response()->json([
                    'status' => false,
                    'error' => 'Please provide valid email'
                ]);
            }

            /**Phone Country Validation*/
            $phone_country = Country::where('is_twilio','=','1')
                ->where('code','=',$request['phone_country'])
                ->first();

            if (!$phone_country) {
                return response()->json([
                    'status' => false,
                    'error' => 'Country not supported'
                ]);
            }

            $model->rate = $request['rate'];
            $model->reviewer_name = $request['name'];
            $model->reviewer_email = $request['email'];
            $model->reviewer_phone = $phone_country->number.preg_replace('/[^0-9.]+/', '', $request['phone']);
            $model->reviewer_phone_country = $request['phone_country'];
            $model->reviewer_phone_format = $request['phone'];
            $model->description = $request['review'];
            $model->save();
            return response()->json([
                'status' => true
            ]);
        }

        return response()->json([
            'status' => false,
            'error' => 'Link has been expired, please try again later'
        ]);
    }

    public function reviewInvite(Request $request, $id)
    {
        $review_invite = ReviewInvite::with('User')
            ->where('unique_code','=',$id)
            ->first();

        if (!$review_invite || !$review_invite->User) {
            return redirect('/');
        }

        $client = [];
        $phone_countries = Country::where('is_twilio','=','1')->pluck('number','code');
        $user_twilio_phone = UserTwilioPhone::where('user_id','=',$review_invite->user_id)->where('status','=','active')->first();
        $rate_points = Constant::GET_RATE_SCORE_POINTS();
        $review_rates = Constant::GET_REVIEW_RATE_SCORES();
        $rate = array_key_exists($request['r'],$review_rates) ? $review_rates[$request['r']] : 5;
        $user = $review_invite->User;
        return view('landing.leave_review',compact(
            'rate',
            'client',
            'phone_countries',
            'user_twilio_phone',
            'id',
            'rate_points',
            'review_invite',
            'user'
        ));
    }

    public function addPublicReview($id)
    {
        $user = User::where('public_reviews_code','=',$id)
            ->first();

        if (!$user) {
            return redirect('/');
        }

        //check subscription too
        $client = [];
        $review_invite = [];
        $phone_countries = Country::where('is_twilio','=','1')->pluck('number','code');
        $user_twilio_phone = UserTwilioPhone::where('user_id','=',$user->user_id)->where('status','=','active')->first();
        $rate_points = Constant::GET_RATE_SCORE_POINTS();
        $rate = 5;
        return view('landing.leave_review',compact(
            'rate',
            'client',
            'phone_countries',
            'user_twilio_phone',
            'id',
            'rate_points',
            'review_invite',
            'user'
        ));
    }

    /**Accept Admin Offer*/
    public function acceptAdminReferralOffer($code)
    {
        $auth_user = Auth::user();
        $admin_referral = UserGiveawayReferral::where('code','=',$code)->first();
        if ($admin_referral && $admin_referral->status == 'pending') {
            /**If logged in user, giveaway free subscription*/
            if ($auth_user) {
                $admin_referral->status = 'accepted';
                $admin_referral->update();

                $user_subscription = UserSubscription::where('user_id','=',$auth_user->user_id)
                    ->where('active','=','1')
                    ->where('type','=','tradieflow')
                    ->first();

                if ($user_subscription) {
                    $user_subscription->expiry_date_time = Carbon::createFromFormat('Y-m-d H:i:s',$user_subscription->expiry_date_time)->addMonths($admin_referral->months)->format('Y-m-d H:i:s');
                }
                else{
                    /**If Expired*/
                    return redirect('settings/subscriptions');
                }

                $user_subscription->final_expiry_date_time = null;
                $user_subscription->update();
                return redirect('settings/subscriptions');
            }
            else{
                return redirect('free-trial?ref='.$code);
            }
        }

        return redirect('/');
    }

    /**Reject Admin Offer*/
    public function rejectAdminReferralOffer($code)
    {
        $admin_referral = UserGiveawayReferral::where('code','=',$code)->first();
        if ($admin_referral && $admin_referral->status == 'pending') {
            $admin_referral->status = 'rejected';
            $admin_referral->update();
        }

        return redirect('/');
    }

    public function setupDeveloperAccount($code)
    {
        $invitation = UserDeveloperInvite::with('User.Country')->where('code','=',$code)->first();
        if ($invitation && $invitation->User) {
            $has_active_subscription = UserSubscription::where('user_id','=',$invitation->user_id)
                ->where('type','=','tradieflow')
                ->where('active','=','1')
                ->count();

            if ($has_active_subscription) {
                $user = $invitation->User;
                $user_twilio_phone = UserTwilioPhone::where('user_id','=',$invitation->user_id)
                    ->where('status','=','active')
                    ->first();

                $user_address = [];
                if ($user->Country) {
                    $user_address[] = $user->Country->name;
                }

                if ($user->zip_code) {
                    $user_address[] = $user->zip_code;
                }

                $user_address = implode(', ',$user_address);
                /**Forms*/
                $pending_forms = UserForm::where('user_id','=',$user->user_id)
                    ->whereIn('status',['pending','processing'])
                    ->count();

                $completed_forms_list = UserForm::where('user_id','=',$user->user_id)
                    ->where('status','=','completed')
                    ->orderBy('created_at','desc')
                    ->pluck('website','user_form_id')
                    ->toArray();

                $phone_countries = Country::select('number','code')
                    ->where('is_twilio','=','1')
                    ->pluck('number','code');

                $phone_number_replace_script = \JShrink\Minifier::minify(<<<EOT
                     function TF_REPLACE(a,b,element){
                        if(!element)element=document.body;
                        var nodes=$(element).contents().each(function(){
                                if(this.nodeType==Node.TEXT_NODE){
                                    var r=new RegExp(a,'i');
                                          this.textContent=this.textContent.replace(r,b);
                                } else { 
                                    if (this.tagName == 'A') {
                                        var link_href = this.getAttribute('href');
                                        if (link_href && link_href.length) {
                                            var tel_index = link_href.indexOf('tel:');
                                            if (tel_index !== -1) {
                                                var r=new RegExp(a,'gi');
                                                link_href = link_href.replace('tel:','');
                                                this.setAttribute('href','tel:' + link_href.replace(r,b));
                                            }
                                        
                                        }
                                    }
                
                                    TF_REPLACE(a,b,this);
                                }
                        });
                    }
    
                    TF_REPLACE(/^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/,'$user_twilio_phone->friendly_name');
                    TF_REPLACE(/^\(?(\d{2})\)?[- ]?(\d{4})[- ]?(\d{4})$/,'$user_twilio_phone->friendly_name');
                    TF_REPLACE(/^\(?(\d{2})\)?[- ]?(\d{3})[- ]?(\d{4})$/,'$user_twilio_phone->friendly_name');
    EOT);

                return view('landing.developer_account_setup',compact(
                    'user',
                    'user_twilio_phone',
                    'user_address',
                    'pending_forms',
                    'completed_forms_list',
                    'code',
                    'phone_countries',
                    'phone_number_replace_script'
                ));
            }
        }

        return redirect('/');
    }

    public function developerFormsTracking(Request $request)
    {
        $invitation = UserDeveloperInvite::with('User.Country')->where('code','=',$request['code'])->first();
        if ($invitation && $invitation->User) {
            $has_active_subscription = UserSubscription::where('user_id','=',$invitation->user_id)
                ->where('type','=','tradieflow')
                ->where('active','=','1')
                ->count();
            if ($has_active_subscription) {
                $user_form = UserForm::where('user_id','=',$invitation->user_id)->find($request['website_id']);
                if ($user_form) {
                    return response()->json([
                        'status' => true,
                        'tracking_code' => $user_form->tracking_code
                    ]);
                }

                return response()->json([
                    'status' => false,
                    'error' => 'Site not found'
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'Wrong or expired token'
        ]);
    }

    public function tradieDigitalCheckout(Request $request)
    {
        if ($request['stripe_token']) {
            if (!$request['name']) {
                return response()->json([
                    'status' => false,
                    'error' => 'Please provide name'
                ]);
            }

            if (!filter_var($request['email'],FILTER_VALIDATE_EMAIL)) {
                return response()->json([
                    'status' => false,
                    'error' => 'Please type valid email'
                ]);
            }

            /**Check if email taken*/
            $has_email_taken = User::where('email','=',$request['email'])->count();
            if ($has_email_taken) {
                return response()->json([
                    'status' => false,
                    'error' => 'You already have registered, please login using your account instead'
                ]);
            }

            if (!in_array($request['currency'],['aud','usd'])) {
                return response()->json([
                    'status' => false,
                    'error' => 'Currency not found'
                ]);
            }

            /**Delete Old Queue*/
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
            try{
                SpecialOfferPagePurchase::where('email','=',$request['email'])
                    ->where('status','=','pending')
                    ->chunk(1000,function($items) use ($stripe){
                        foreach ($items as $item) {
                            $stripe->customers->delete(
                                $item->stripe_customer_id,
                                []
                            );
                        }
                    });
            }
            catch (\Exception $e) {

            }

            $price_to_charge = 0;
            switch ($request['plan_code']) {
                case 'hosting_flow_reviews':
                    /**All Plans Together*/
                    $plan_label = 'Get Fast. Get Organized. Get Reviews.';
                    $price_to_charge = ($request['currency']) == 'usd' ? 349.00 : 0;
                break;
                case 'hosting_reviews':
                    /**TradieHosting & TradieReviews*/
                    $plan_label = 'Get Fast. Get Reviews.';
                    $price_to_charge = ($request['currency']) == 'usd' ? 249.00 : 0;
                break;
                case 'flow_reviews':
                    /**TradieFlow & TradieReviews*/
                    $plan_label = 'Get organized. Get reviews.';
                    $price_to_charge = ($request['currency']) == 'usd' ? 149.00 : 0;
                break;
            }

            if (!$price_to_charge) {
                return response()->json([
                    'status' => false,
                    'error' => 'Please select the plan from the list'
                ]);
            }

            /**Add GST*/
            $gst_amount = sprintf('%.2f',$price_to_charge / 10);
            $price_to_charge += $gst_amount;
            $price_to_charge = sprintf('%.2f',$price_to_charge);

            /**Process Payment*/
            try{
                $stripe_customer = $stripe->customers->create([
                    'email' => $request['email'],
                    'name' => $request['name'],
                    'description' => $plan_label.$request['name'].' '.$request['email'],
                ]);

                /**Create Stripe Customer*/
                if (!isset($stripe_customer->id) || !$stripe_customer->id) {
                    return response()->json([
                        'status' => false,
                        'error' => 'Unable to process your payment, please contact support'
                    ]);
                }

                $model = new SpecialOfferPagePurchase();
                $model->name = $request['name'];
                $model->email = $request['email'];
                $model->stripe_customer_id = $stripe_customer->id;
                $model->signup_code = md5($request['email'].uniqid());
                $model->plan_code = $request['plan_code'];
                $model->price = $price_to_charge;
                $model->status = 'pending';
                $model->currency = $request['currency'];
                $model->gst_amount = $gst_amount;
                $model->save();

                $stripe_response = $stripe->customers->update(
                    $stripe_customer->id,
                    ['source' => $request['stripe_token']]
                );

                if (!$stripe_response->id) {
                    return response()->json([
                        'status' => false,
                        'error' => 'Something went wrong with the payment, please contact support'
                    ]);
                }

                /**Charge User*/
                $charge = $stripe->charges->create([
                    'amount' => $price_to_charge * 100,
                    'currency' => $request['currency'],
                    'customer' => $stripe_customer->id,
                    'description' => 'Charge for ' . $plan_label,
                ]);

                if (!isset($charge->id)) {
                    return response()->json([
                        'status' => false,
                        'error' => 'Unable to process payment, please try with another card'
                    ]);
                }

                /**Update status*/
                $model->status = 'paid';
                $model->update();

                /**Send out emails*/
                $expiry_date_format = Carbon::now()->addMonth(1)->format('F j, Y');
                switch ($request['plan_code']) {
                    case 'hosting_flow_reviews':
                        /**All Plans Together*/
                        NotificationHelper::sendTradieDigitalReviewSignupComplete($model->signup_code, $expiry_date_format, $plan_label, $request['email']);
                        NotificationHelper::sendTradieDigitalFlowSignupComplete($model->signup_code, $expiry_date_format, $plan_label, $request['email']);
                        NotificationHelper::sendTradieDigitalHosting($expiry_date_format, $plan_label, $request['email']);
                    break;
                    case 'hosting_reviews':
                        /**TradieHosting & TradieReviews*/
                        NotificationHelper::sendTradieDigitalReviewSignupComplete($model->signup_code, $expiry_date_format, $plan_label, $request['email']);
                        NotificationHelper::sendTradieDigitalHosting($expiry_date_format, $plan_label, $request['email']);
                    break;
                    case 'flow_reviews':
                        /**TradieFlow & TradieReviews*/
                        NotificationHelper::sendTradieDigitalReviewSignupComplete($model->signup_code, $expiry_date_format, $plan_label, $request['email']);
                        NotificationHelper::sendTradieDigitalFlowSignupComplete($model->signup_code, $expiry_date_format, $plan_label, $request['email']);
                    break;
                }

                return response()->json([
                    'status' => true
                ]);
            }
            catch (\Exception $e) {

            }
        }

        return response()->json([
            'status' => false,
            'error' => 'Unable to process the payment'
        ]);
    }

    public function connectMobileXero($id)
    {
        Session::flush();
        Auth::logout();
        $get_token = UserXeroMobileRedirect::where('code','=',$id)->first();
        if ($get_token) {
            Auth::loginUsingId($get_token->user_id);
            return redirect('settings/xero/connect?from=mobile');
        }

        return redirect('mobile/xero/loading');
    }

    public function industries()
    {
        $auth_user = Auth::user();
        return view('landing.industries',compact(
            'auth_user'
        ));
    }

    public function mobileXeroLoading()
    {
        return view('landing.xero_mobile_loading');
    }

    public function handleContactUs(Request $request)
    {
        if (!Helper::validateRecaptcha($request['recaptcha_token'])) {
            return response()->json([
                'status' => false,
                'error' => 'Captcha is wrong'
            ]);
        }

        if (!isset($request['name']) || !isset($request['email']) || !isset($request['message'])) {
            return response()->json([
                'status' => false,
                'error' => 'Please fill out all fields'
            ]);
        }

        if (!filter_var($request['email'],FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'status' => false,
                'error' => 'Please type valid email'
            ]);
        }

        NotificationHelper::contactUsRequest($request);
        return response()->json([
            'status' => true
        ]);
    }

    public function getGeoCountry()
    {
        $geo_country = Helper::GET_GEO_COUNTRY_IP();
        $currency = (isset($geo_country['geoplugin_countryCode']) && $geo_country['geoplugin_countryCode'] == 'AU') ? 'aud' : 'usd';
        $prices = [];
        $get_subscription_plans = SubscriptionPlan::where('type','=','tradieflow')
            ->whereIn('plan_code',['pro','yearly'])
            ->get();

        foreach ($get_subscription_plans as $item) {
            $prices[$item->plan_code] = ($currency == 'usd') ? '$'.$item->price_usd : 'AUD '.$item->price_aud;
        }

        return response()->json([
            'status' => true,
            'prices' => $prices
        ]);
    }

    public function test()
    {
        $auth_user = Auth::user();
        $app_cdn_url = env('APP_CDN_URL');
        return view('landing.test_landing',compact(
            'auth_user',
            'app_cdn_url'
        ));
    }
}
