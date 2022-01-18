<?php

namespace App\Console\Commands;

use App\Helpers\Constant;
use App\Helpers\NotificationHelper;
use App\Models\UserReferralMonthQueue;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Console\Command;
use DB;

class ReferralFreeMonthReceiveEngine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'referral_free_month_receive_engine';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Receive Free Months When Both Parties Paid';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(60 * 60 * 5);
        UserReferralMonthQueue::select([
            'user_referral_month_queue.*',
            'sender_user.name as sender_user_name',
            'receiver_user.name as receiver_user_name'
        ])
            ->leftJoin('user as sender_user','sender_user.user_id','=','user_referral_month_queue.sent_user_id')
            ->leftJoin('user_subscription as sender_subscription',function($query){
                $query->on('sender_subscription.user_id','=','sender_user.user_id')
                    ->where('sender_subscription.type','=','tradieflow')
                    ->where('sender_subscription.active','=','1')
                    ->where('sender_subscription.subscription_plan_code','!=','trial');
            })
            ->leftJoin('user as receiver_user','receiver_user.user_id','=','user_referral_month_queue.received_user_id')
            ->leftJoin('user_subscription as receiver_subscription',function($query){
                $query->on('receiver_subscription.user_id','=','receiver_user.user_id')
                    ->where('receiver_subscription.type','=','tradieflow')
                    ->where('receiver_subscription.active','=','1')
                    ->where('receiver_subscription.subscription_plan_code','!=','trial');
            })
            ->where('user_referral_month_queue.status','=','pending')
            ->where('user_referral_month_queue.type','=','tradieflow')
            ->whereNotNull('sender_subscription.user_subscription_id')
            ->whereNotNull('receiver_subscription.user_subscription_id')
            ->groupBy('user_referral_month_queue.user_referral_month_queue_id')
            ->orderBy('user_referral_month_queue.created_at','desc')
            ->chunk(1000,function($items){
                foreach ($items as $item) {
                    /**Add Free Month to sender*/
                    try{
                        $sender_latest_subscription = UserSubscription::with('User')
                            ->where('user_id','=',$item->sent_user_id)
                            ->where('type','=','tradieflow')
                            ->orderBy('created_at','desc')
                            ->take('1')
                            ->first();

                        $free_days = Constant::GET_REFERRAL_RECEIVED_FREE_DAYS();
                        $sender_latest_subscription->final_expiry_date_time = null;
                        $sender_latest_subscription->expiry_date_time = Carbon::createFromFormat('Y-m-d H:i:s',$sender_latest_subscription->expiry_date_time)->addDays($free_days)->format('Y-m-d H:i:s');
                        $sender_latest_subscription->update();

                        /**Remove subscription expire popup if any*/
                        $sender_latest_subscription->User->tradieflow_subscription_expire_message = null;
                        $sender_latest_subscription->update();

//                        NotificationHelper::referralUserFreeMonthsOnPaymentAlert($sender_latest_subscription->User->name, $item->receiver_user_name, $free_days.' days', $sender_latest_subscription->User->email);
                    }
                    catch (\Exception $e) {
                        //
                    }

                    /**Add Free Month to receiver*/
                    try{
                        $receiver_latest_subscription = UserSubscription::with('User')
                            ->where('user_id','=',$item->received_user_id)
                            ->where('type','=','tradieflow')
                            ->orderBy('created_at','desc')
                            ->take('1')
                            ->first();

                        $receiver_latest_subscription->final_expiry_date_time = null;
                        $receiver_latest_subscription->expiry_date_time = Carbon::createFromFormat('Y-m-d H:i:s',$receiver_latest_subscription->expiry_date_time)->addDays(Constant::GET_REFERRAL_RECEIVED_FREE_DAYS())->format('Y-m-d H:i:s');
                        $receiver_latest_subscription->update();

                        /**Remove subscription expire popup if any*/
                        $receiver_latest_subscription->User->tradieflow_subscription_expire_message = null;
                        $receiver_latest_subscription->update();
                    }
                    catch (\Exception $e) {
                        //
                    }


                    $item->status = 'completed';
                    $item->update();
                }
            });
    }
}
