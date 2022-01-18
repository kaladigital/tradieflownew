<?php

namespace App\Console\Commands;

use App\Helpers\Constant;
use App\Helpers\Helper;
use App\Helpers\NotificationHelper;
use App\Models\UserNotification;
use App\Models\UserSubscription;
use App\Models\UserTwilioPhone;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SubscriptionHandleEngine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription_handle_engine';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process subscriptions';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(60 * 60 * 10);
        $current_date_timestamp = Carbon::createFromFormat('Y-m-d',Carbon::now()->format('Y-m-d'))->timestamp;
        UserSubscription::with('User')
            ->select('user_subscription.*')
            ->where('active','=','1')
            ->where('expiry_date_time','<=',Carbon::now()->format('Y-m-d H:i:s'))
            ->where('type','=','tradieflow')
            ->chunk(1000,function($items) use ($current_date_timestamp){
                foreach ($items as $item) {
                    if ($item->is_extendable) {
                        try {
                            $price_to_charge = $item->price;
                            /**Check if applicable for discounted price*/
                            if ($item->discount_pay_expiry_date) {
                                $discount_date_timestamp = Carbon::createFromFormat('Y-m-d',$item->discount_pay_expiry_date)->timestamp;
                                if ($current_date_timestamp < $discount_date_timestamp) {
                                    $price_to_charge = $item->discounted_price;
                                }
                            }

                            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
                            $charge = $stripe->charges->create([
                                'amount' => $price_to_charge * 100,
                                'currency' => $item->currency,
                                'customer' => $item->User->stripe_customer_id,
                                'description' => 'Charge for ' . $item->subscription_plan_name,
                            ]);

                            if (isset($charge->id) && $charge->id) {
                                $exp_date_obj = Carbon::createFromFormat('Y-m-d H:i:s',$item->expiry_date_time);
                                if ($item->subscription_plan_code == 'pro') {
                                    $expiration_date = $exp_date_obj->copy()->addMonth(1)->format('Y-m-d H:i:s');
                                } else {
                                    $expiration_date = $exp_date_obj->copy()->addYear(1)->format('Y-m-d H:i:s');
                                }

                                $item->expiry_date_time = $expiration_date;
                                $item->final_expiry_date_time = null;
                                $item->payment_response = json_encode($charge);
                                $item->update();

                                /**Update user*/
                                $item->User->tradieflow_subscription_expire_message = null;
                                $item->User->update();
                            }
                        } catch (\Exception $e) {
                            /**Check Extra Period*/
                            $this->handleExtraTimePeriod($item);
                        }
                    }
                    else{
                        $upcoming_subscription = UserSubscription::where('user_id', $item->user_id)
                            ->where('user_subscription_id', '>', $item->user_subscription_id)
                            ->where('type','=','tradieflow')
                            ->first();

                        if ($upcoming_subscription) {
                            /**Update current subscription*/
                            $item->active = '0';
                            $item->update();

                            /**Activate upcoming subscription*/
                            $upcoming_subscription->active = '1';
                            $upcoming_subscription->update();

                            /**Update user subscription*/
                            $item->User->tradieflow_subscription_expire_message = null;
                            $item->User->update();
                        }
                        else{
                            /**Check Extra Period*/
                            $this->handleExtraTimePeriod($item);
                        }
                    }
                }
            });
    }

    private function handleExtraTimePeriod($user_subscription)
    {
        if ($user_subscription->final_expiry_date_time) {
            $final_expiry_date_obj = Carbon::createFromFormat('Y-m-d H:i:s',$user_subscription->final_expiry_date_time);
            if ($final_expiry_date_obj->copy()->timestamp < Carbon::now()->timestamp) {
                /**Update subscription message*/
                $user_subscription->User->tradieflow_subscription_expire_message = 'Subscription expired';
                $user_subscription->User->update();

                /**Remove Twilio?*/
                $this->removeTwilioSubscription($user_subscription);
            }
            else{
                /**Send out notifications*/
                $user_subscription->User->tradieflow_subscription_expire_message = 'Your subscription expired, please upgrade to keep your phone number';
                $user_subscription->User->update();

                if (!$user_subscription->last_popup_notification_date || ($user_subscription->last_popup_notification_date && $user_subscription->last_popup_notification_date != Carbon::now()->format('Y-m-d'))) {
                    $day_diff = $final_expiry_date_obj->copy()->diffInHours(Carbon::now());
                    if ($day_diff) {
                        $day_remaining = ceil($day_diff / 24);
                        Helper::handlePopupNotifications($day_remaining, $user_subscription);
                    }
                }
            }

            /**Create ActiveCampaign Log*/
            Helper::addActiveCampaignQueueItem($user_subscription->User->user_id,$user_subscription->User->email,'expired_tag');
        }
        else{
            /**Give some extra days*/
            $padding_period = Constant::GET_FINAL_SUBSCRIPTION_EXPIRY_DAYS();
            $user_subscription->final_expiry_date_time = Carbon::createFromFormat('Y-m-d H:i:s',$user_subscription->expiry_date_time)->addDays($padding_period)->format('Y-m-d H:i:s');
            $user_subscription->update();

            /**Update subscription message*/
            $user_subscription->User->tradieflow_subscription_expire_message = 'Your subscription expired, please upgrade to keep your phone number';
            $user_subscription->User->update();

            /**Popup Notifications*/
            if (!$user_subscription->last_popup_notification_date || ($user_subscription->last_popup_notification_date && $user_subscription->last_popup_notification_date != Carbon::now()->format('Y-m-d'))) {
                Helper::handlePopupNotifications($padding_period, $user_subscription);
            }

            /**Send out notifications*/
            //Notification 1
        }
    }

    private function removeTwilioSubscription($user_subscription)
    {
        /**Update user subscription*/
        $user_subscription->active = '0';
        $user_subscription->update();

        UserTwilioPhone::where('user_id','=',$user_subscription->user_id)->chunk(1000,function($items){
            foreach ($items as $item) {
                try{
                    $twilio = new \Twilio\Rest\Client(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));
                    $twilio->incomingPhoneNumbers($item->twilio_sid)
                        ->delete();
                }
                catch (\Exception $e) {

                }

                $item->status = 'deleted';
                $item->update();
            }
        });

        /**Send out notifications*/
    }
}
