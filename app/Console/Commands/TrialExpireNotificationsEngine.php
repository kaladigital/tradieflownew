<?php

namespace App\Console\Commands;

use App\Helpers\Constant;
use App\Helpers\Helper;
use App\Models\UserNotification;
use App\Models\UserSubscription;
use App\Models\UserTwilioPhone;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TrialExpireNotificationsEngine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trial_expire_popup_notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trial expire popup notifications engine';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(60 * 60 * 10);
        $today_date_obj = Carbon::now();
        $today_date = $today_date_obj->copy()->format('Y-m-d');
        /**Trial Logic*/
        UserSubscription::with('User')
            ->select('user_subscription.*')
            ->where('active','=','1')
            ->where('subscription_plan_code','=','trial')
            ->whereNull('final_expiry_date_time')
            ->where('expiry_date_time','>',Carbon::now()->format('Y-m-d H:i:s'))
            ->where('type','=','tradieflow')
            ->chunk(1000,function($items) use ($today_date_obj, $today_date){
                foreach ($items as $item) {
                    $hours_diff = Carbon::createFromFormat('Y-m-d H:i:s',$item->expiry_date_time)->diffInHours($today_date_obj);
                    $days = ceil($hours_diff / 24);
                    if ($days < 7) {
                        if (!$item->last_popup_notification_date || ($item->last_popup_notification_date && $item->last_popup_notification_date != $today_date)) {
                            Helper::handlePopupNotifications($days, $item);
                        }
                    }
                }
            });

        /**Monthly Yearly with cancelled subscription*/
        UserSubscription::with('User')
            ->select('user_subscription.*')
            ->leftJoin('user_subscription as upcoming_subscription',function($query){
                $query->on('upcoming_subscription.user_id','=','user_subscription.user_id')
                    ->where('upcoming_subscription.user_subscription_id','>','user_subscription.user_subscription_id')
                    ->where('upcoming_subscription.type','=','tradieflow');
            })
            ->whereIn('user_subscription.subscription_plan_code',['pro','yearly'])
            ->whereNull('user_subscription.final_expiry_date_time')
            ->where('user_subscription.is_extendable','=','0')
            ->where('user_subscription.expiry_date_time','>',Carbon::now()->format('Y-m-d H:i:s'))
            ->whereNull('upcoming_subscription.user_subscription_id')
            ->where('user_subscription.type','=','tradieflow')
            ->groupBy('user_subscription.user_subscription_id')
            ->chunk(1000,function($items) use ($today_date_obj, $today_date){
                foreach ($items as $item) {
                    $hours_diff = Carbon::createFromFormat('Y-m-d H:i:s',$item->expiry_date_time)->diffInHours($today_date_obj);
                    $days = ceil($hours_diff / 24);
                    if ($days < 7) {
                        if (!$item->last_popup_notification_date || ($item->last_popup_notification_date && $item->last_popup_notification_date != $today_date)) {
                            Helper::handlePopupNotifications($days, $item);
                        }
                    }
                }
            });
    }
}
