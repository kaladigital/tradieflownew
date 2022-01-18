<?php

namespace App\Helpers;
use App\Models\NotificationLog;
use App\Models\UserTwilioPhone;

class NotificationHelper
{

    private static $toEmail = '';
    private static $toSubject = '';

    public static function SendEmailMessage($template, $emails, $subject, $args)
    {
        $log = new NotificationLog();
        $log->notification_type = 'email';
        $log->target = implode(', ',$emails);
        $log->subject = $subject;
        $log_args = $args;
        $log->body = 'Template=' . $template . ' - Args=' . json_encode($log_args);

        try {
            foreach ($emails as $item) {
                if (!filter_var($item, FILTER_VALIDATE_EMAIL)) {
                    throw new \Exception('Wrong email');
                }
            }

            self::$toEmail = $emails['0'];
            self::$toSubject = strip_tags($subject);

            \Mail::send('emails.' . $template, $args, function ($message) use ($emails) {
                $message
                    ->to(self::$toEmail)
                    ->subject(self::$toSubject)
                    ->from(env('MAIL_FROM_EMAIL'), env('MAIL_FROM_NAME'));

                if (count($emails) > 1) {
                    $other_cc = array_slice($emails, 1);
                    $message->cc($other_cc);
                }
            });

            $log->status = 'success';
            $log->save();
            return 'success';
        } catch (\Exception $err) {
            $log->status = 'Error: ' . $err->getMessage();
            $log->save();
            return $err->getMessage();
        }
    }

    public static function SendRawHtmlEmailMessage($subject, $body, $email)
    {
        $log = new NotificationLog();
        $log->notification_type = 'email';
        $log->target = $email;
        $log->subject = $subject;
        $log->body = 'Template=' . $body . ' - Args=' . $email;

        try {
            \Mail::send([], [], function ($message) use ($subject, $body, $email) {
                $message->to($email)
                    ->subject(strip_tags($subject))
                    ->from(env('MAIL_FROM_EMAIL'), env('MAIL_FROM_NAME'))
                    ->setBody($body, 'text/html');
            });

            $log->status = 'success';
            $log->save();
            return 'success';
        } catch (\Exception $err) {
            $log->status = 'Error: ' . $err->getMessage();
            $log->save();
            return $err->getMessage();
        }
    }

    public static function sendEmailFromVariables($template, $subject, $message, $variables, $emails)
    {
        foreach ($variables as $key => $value) {
            $subject = str_replace($key, $value, $subject);
            $message = str_replace($key, $value, $message);
        }

        $variables['APP_CDN_URL'] = env('APP_CDN_URL');
        static::SendEmailMessage($template, $emails, $subject, ['body' => $message, 'other_parameters' => $variables]);
    }

    public static function signupEmailConfirmationMobile($notification, $user)
    {
        $email_variables = [
            '{USER_NAME}' => $user->name,
            '{CODE}' => $user->otp_code
        ];

        self::sendEmailFromVariables('generic',$notification->subject, $notification->body, $email_variables, [$user->email]);
    }

    public static function resetPasswordMobile($notification, $user)
    {
        $email_variables = [
            '{USER_NAME}' => $user->name,
            '{CODE}' => $user->otp_code
        ];

        self::sendEmailFromVariables('generic',$notification->subject, $notification->body, $email_variables, [$user->email]);
    }

    public static function resetPassword($user)
    {
        $email_variables = [
            'user_name' => $user->name,
            'code' => $user->otp_code
        ];

        self::sendEmailFromVariables('forgot_password','Account Recovery Code', '', $email_variables, [$user->email]);
    }

    public static function contactUsRequest($request)
    {
        $email_variables = [
            'app_url' => env('APP_URL'),
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
            'ip' => $_SERVER['REMOTE_ADDR']
        ];

        self::sendEmailFromVariables('contact_us_request','Contact Us Enquiry', '', $email_variables, explode(',',env('CONTACT_US_EMAILS')));
    }

    public static function registerVerify($notification, $user)
    {
        $email_variables = [
            '{USER_NAME}' => $user->name,
            '{EMAIL}' => $user->email,
            '{CODE}' => $user->otp_code
        ];

        self::sendEmailFromVariables('confirm_account',$notification->subject, $notification->body, $email_variables, [$user->email]);
    }

    public static function earlyAccessPurchasedNotification($signup_code, $amount, $currency, $plan_name, $expiry_date_format, $email)
    {
        $emails = [$email];
        $app_url = env('APP_URL');
        $email_variables = [
            'app_url' => $app_url,
            'amount' => $amount,
            'plan_name' => $plan_name,
            'expiry_date_format' => $expiry_date_format,
            'complete_signup_url' => $app_url.'/early-access/complete/'.$signup_code
        ];

        if (env('APP_ENV') !== 'local') {
            $emails[] = 'carl@tradiedigital.co';
        }

        self::sendEmailFromVariables('early_access_purchase','Payment Successful', '', $email_variables, $emails);
    }

    public static function earlyAccessPurchasedExistingUserNotification($amount, $currency, $plan_name, $expiry_date_format, $email)
    {
        $emails = [$email];
        $app_url = env('APP_URL');
        $email_variables = [
            'app_url' => $app_url,
            'amount' => $amount,
            'plan_name' => $plan_name,
            'expiry_date_format' => $expiry_date_format,
            'login_url' => $app_url.'/auth/login'
        ];

        if (env('APP_ENV') !== 'local') {
            $emails[] = 'carl@tradiedigital.co';
        }

        self::sendEmailFromVariables('early_access_existing_user_purchase','Payment Successful', '', $email_variables, $emails);
    }

    public static function sendInvoiceViewEmail($notification, $email, $content, $invoice_number, $user)
    {
        $app_url = env('APP_URL');
        $email_variables = [
            'MESSAGE_CONTENT' => $content,
            'SENDER_NAME' => $user->name,
            'INVOICE_URL' => $app_url.'/invoice/'.$invoice_number,
            'APP_URL' => $app_url
        ];

        self::sendEmailFromVariables('send_invoice',$notification->subject, $notification->body, $email_variables, [$email]);
    }

    public static function signupAdminAlert($user_name, $user_email, $company, $phone, $industry, $help_business)
    {
        $email_variables = [
            'name' => $user_name,
            'email' => $user_email,
            'company_name' => $company,
            'phone' => $phone,
            'industry' => $industry,
            'help_business' => $help_business,
        ];

        self::sendEmailFromVariables('user_register_admin_notification','New user signup', '', $email_variables, explode(',',env('CONTACT_US_EMAILS')));
    }

    public static function referralUserFreeMonthsOnPaymentAlert($user_name, $referral_user_name, $duration, $email)
    {
        $email_variables = [
            'name' => $user_name,
            'referral_user_name' => $referral_user_name,
            'duration' => $duration
        ];

        self::sendEmailFromVariables('referral_user_free_months_on_payment','Free '.$duration.' received', '', $email_variables, [$email]);
    }

    public static function sendSignupReferralEmailInvite($user, $email, $referral_code)
    {
        $email_variables = [
            'user' => $user,
            'referral_code' => $referral_code
        ];

        self::sendEmailFromVariables('send_signup_referral_invite','Signup Referral Received', '', $email_variables, [$email]);
    }

    public static function sendAdminGiveawayReferral($name, $months, $code, $email)
    {
        $email_variables = [
            'name' => $name,
            'duration' => $months.' month'.($months > 1 ? 's' : ''),
            'code' => $code
        ];

        self::sendEmailFromVariables('admin_giveaway_referral','Free Subscription Offer', '', $email_variables, [$email]);
    }

    public static function registerVersionVerify($code, $email)
    {
        $email_variables = [
            'email' => $email,
            'code' => $code
        ];

        self::sendEmailFromVariables('register_version_confirm_account','Register', '', $email_variables, [$email]);
    }

    public static function developerInvite($code, $client_name, $email)
    {
        $email_variables = [
            'EMAIL' => $email,
            'CLIENT_NAME' => $client_name,
            'URL' => env('APP_URL').'/developer/setup/'.$code,
            'APP_URL' => env('APP_URL')
        ];

        self::sendEmailFromVariables('developer_invite','TradieFlow setup invitation', null, $email_variables, [$email]);
    }

    public static function siteFormCompleted($code, $client_name, $email)
    {
        $email_variables = [
            'EMAIL' => $email,
            'CLIENT_NAME' => $client_name,
            'URL' => env('APP_URL').'/developer/setup/'.$code,
            'APP_URL' => env('APP_URL')
        ];

        self::sendEmailFromVariables('developer_form_setup_invite','TradieFlow setup forms invitation', null, $email_variables, [$email]);
    }

    public static function sendLeaveReviewEmail($id, $company_name, $name, $logo, $email)
    {
        $name_obj = explode(' ',$name);
        $app_url = env('TRADIEREVIEWS_URL');
        $email_variables = [
            'company_name' => $company_name,
            'name' => $name,
            'first_name' => $name_obj['0'],
            'app_url' => $app_url,
            'url' => $app_url.'/rate/'.$id,
            'logo' => $logo
        ];

        self::sendEmailFromVariables('leave_review','Review work done', '', $email_variables, [$email]);
    }

    public static function sendTradieDigitalReviewSignupComplete($signup_code, $expiry_date_format, $plan_label, $email)
    {
        $tradie_reviews_url = env('TRADIEREVIEWS_URL');
        $email_variables = [
            'app_url' => $tradie_reviews_url,
            'expiry_date_format' => $expiry_date_format,
            'plan_name' => $plan_label,
            'signup_url' => $tradie_reviews_url.'/complete/registration/'.$signup_code
        ];
        self::sendEmailFromVariables('tradie_digital_reviews_signup_complete',' Payment Successful', '', $email_variables, [$email]);
    }

    public static function sendTradieDigitalFlowSignupComplete($signup_code, $expiry_date_format, $plan_label, $email)
    {
        $app_url = env('APP_URL');
        $email_variables = [
            'app_url' => $app_url,
            'expiry_date_format' => $expiry_date_format,
            'plan_name' => $plan_label,
            'signup_url' => $app_url.'/complete/registration/'.$signup_code
        ];
        self::sendEmailFromVariables('tradie_digital_flow_signup_complete',' Payment Successful', '', $email_variables, [$email]);
    }

    public static function sendTradieDigitalHosting($expiry_date_format, $plan_label, $email)
    {
        $email_variables = [
            'expiry_date_format' => $expiry_date_format,
            'plan_name' => $plan_label
        ];
        self::sendEmailFromVariables('tradie_digital_hosting_purchase',' Payment Successful', '', $email_variables, [$email]);
    }

    public static function sendAdminSubscriptionPreExpireAlert($user, $days_padding)
    {
        $get_user_twilio_phone = UserTwilioPhone::where('user_id','=',$user->user_id)->first();
        $email_variables = [
            'app_url' => env('APP_URL'),
            'user_name' => $user->name,
            'email' => $user->email,
            'padding_days' => $days_padding,
            'company' => '',
            'phone' => $get_user_twilio_phone ? $get_user_twilio_phone->phone : ''
        ];

        self::sendEmailFromVariables('notify_admin_for_pre_expire_trial','Subscription Expiry', '', $email_variables, explode(',',env('CONTACT_US_EMAILS')));
    }

    public static function sendAdminSubscriptionFullExpireAlert($user)
    {
        $get_user_twilio_phone = UserTwilioPhone::where('user_id','=',$user->user_id)->first();
        $email_variables = [
            'app_url' => env('APP_URL'),
            'user_name' => $user->name,
            'email' => $user->email,
            'company' => '',
            'phone' => $get_user_twilio_phone ? $get_user_twilio_phone->phone : ''
        ];

        self::sendEmailFromVariables('notify_admin_for_full_expire_trial','Subscription Expiry', '', $email_variables, explode(',',env('CONTACT_US_EMAILS')));
    }

    public static function invoicePaymentReceived($invoice)
    {
        $email_variables = [
            'app_url' => env('APP_URL'),
            'invoice' => $invoice
        ];

        self::sendEmailFromVariables('invoice_paid_alert','Invoice Paid', '', $email_variables, [$invoice->User->email]);
    }
}
