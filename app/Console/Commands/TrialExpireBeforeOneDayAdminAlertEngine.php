<?php

namespace App\Console\Commands;

use App\Helpers\Constant;
use App\Helpers\Helper;
use App\Helpers\NotificationHelper;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TrialExpireBeforeOneDayAdminAlertEngine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trial_expire_before_one_day_admin_alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify admin about free trial subscriptions that going to be expired after 1 day';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(60 * 60 * 10);
        $expired_date = Carbon::now()->addDay()->format('Y-m-d');
        $final_expire_padding = Constant::GET_FINAL_SUBSCRIPTION_EXPIRY_DAYS();
        /**Trial Expire Logic*/
        UserSubscription::with('User')
            ->select('user_subscription.*')
            ->where('type','=','tradieflow')
            ->where('subscription_plan_code','=','trial')
            ->whereNull('final_expiry_date_time')
            ->where(DB::raw('date(expiry_date_time)'),'=',$expired_date)
            ->chunk(1000,function($items) use ($final_expire_padding) {
                foreach ($items as $item) {
                    NotificationHelper::sendAdminSubscriptionPreExpireAlert($item->User, $final_expire_padding);
                }
            });

        /**Full Expire Logic*/
        UserSubscription::with('User')
            ->select('user_subscription.*')
            ->where('type','=','tradieflow')
            ->where('subscription_plan_code','=','trial')
            ->where(DB::raw('date(final_expiry_date_time)'),'=',$expired_date)
            ->chunk(1000,function($items) {
                foreach ($items as $item) {
                    NotificationHelper::sendAdminSubscriptionFullExpireAlert($item->User);
                }
            });
    }
}
