<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\RemoveExpiredOtpCodeEngine::class,
        \App\Console\Commands\SubscriptionHandleEngine::class,
        \App\Console\Commands\XeroAccessTokenUpdateEngine::class,
        \App\Console\Commands\UserFormQueueProcessEngine::class,
        \App\Console\Commands\XeroInvoicePaidStatusEngine::class,
        \App\Console\Commands\RemoveTempQuoteImages::class,
        \App\Console\Commands\DatabaseTableCleanEngine::class,
        \App\Console\Commands\SubscriptionExpireMessageEngine::class,
        \App\Console\Commands\TrialExpireNotificationsEngine::class,
        \App\Console\Commands\EmailQueueProcessEngine::class,
        \App\Console\Commands\RecurringInvoiceGeneratorEngine::class,
        \App\Console\Commands\UserDeveloperInviteEmailEngine::class,
        \App\Console\Commands\EventSMSReminderEngine::class,
        \App\Console\Commands\SitemapGeneratorEngine::class,
        \App\Console\Commands\TradieDigitalSpecialOfferQueueClean::class,
        \App\Console\Commands\TrialExpireBeforeOneDayAdminAlertEngine::class,
        \App\Console\Commands\TwilioAuPhoneRegionAvailableEngine::class,
        \App\Console\Commands\ActiveCampaignQueueEngine::class,
        \App\Console\Commands\InvoiceStripeWiseTransferEngine::class,
        \App\Console\Commands\InvoiceWisePayoutTransferEngine::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('remove_expired_otp_codes')
            ->everyTenMinutes();

        $schedule->command('subscription_handle_engine')
            ->hourly()
            ->withoutOverlapping();

        $schedule->command('twilio_recording_process_engine')
            ->everyTenMinutes()
            ->withoutOverlapping();

        $schedule->command('xero_access_token_update_engine')
            ->hourly()
            ->withoutOverlapping();

        $schedule->command('user_form_queue_process_engine')
            ->everyFiveMinutes()
            ->withoutOverlapping();

        $schedule->command('xero_invoice_paid_engine')
            ->hourly()
            ->withoutOverlapping();

        $schedule->command('remove_temp_quote_images')
            ->hourly()
            ->withoutOverlapping();

        $schedule->command('database_table_clean_engine')
            ->hourly()
            ->withoutOverlapping();

        $schedule->command('subscription_expire_message_engine')
            ->hourly()
            ->withoutOverlapping();

        $schedule->command('twilio_access_token_expire_engine')
            ->everyFiveMinutes()
            ->withoutOverlapping();

        $schedule->command('trial_expire_popup_notifications')
            ->hourly()
            ->withoutOverlapping();

        $schedule->command('email_queue_process_engine')
            ->everyThreeMinutes()
            ->withoutOverlapping();

        $schedule->command('recurring_invoice_generator_engine')
            ->hourly()
            ->withoutOverlapping();

        $schedule->command('user_developer_invite_email')
            ->everyTenMinutes()
            ->withoutOverlapping();

        $schedule->command('event_sms_reminder_engine')
            ->everyMinute()
            ->withoutOverlapping();

        $schedule->command('generate_sitemap')
            ->everyTwoHours()
            ->withoutOverlapping();

        $schedule->command('tradie_digital_special_offer_queue_clean')
            ->hourly()
            ->withoutOverlapping();

        $schedule->command('trial_expire_before_one_day_admin_alert')
            ->daily()
            ->withoutOverlapping();

        $schedule->command('twilio_au_phone_region_availability_check')
            ->daily();

        $schedule->command('active_campaign_queue_process_engine')
            ->everyFiveMinutes()
            ->withoutOverlapping();

//        $schedule->command('invoice_stripe_transfer_engine')
//            ->everyThirtyMinutes()
//            ->withoutOverlapping();
//
//        $schedule->command('invoice_wise_payout_transfer_engine')
//            ->everyThirtyMinutes()
//            ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
