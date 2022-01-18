<?php

namespace App\Console\Commands;

use App\Helpers\Constant;
use App\Models\UserSubscription;
use App\Models\UserTwilioPhone;
use Carbon\Carbon;
use Illuminate\Console\Command;
use DB;

class SubscriptionExpireMessageEngine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription_expire_message_engine';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process subscription expire message';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(60 * 60 * 10);
        UserSubscription::with('User')
            ->select('user_subscription.*')
            ->where('active','=','1')
            ->where('is_extendable','=','0')
            ->where('type','=','tradieflow')
            ->where(DB::raw('date_add(expiry_date_time, INTERVAL -7 DAY)'),'<=',Carbon::now()->format('Y-m-d H:i:s'))
            ->chunk(1000,function($items){
                foreach ($items as $item) {
                    if ($item->final_expiry_date_time) {
                        $days_remaining = Carbon::createFromFormat('Y-m-d H:i:s',$item->final_expiry_date_time)->diffInDays(Carbon::now());
                    }
                    else{
                        $days_remaining = Carbon::createFromFormat('Y-m-d H:i:s',$item->expiry_date_time)->diffInDays(Carbon::now());
                    }
                    $has_more_days = $days_remaining == 1 ? '' : 's';
                    $item->User->tradieflow_subscription_expire_message = $days_remaining.' day'.$has_more_days.' remaining of your';
                    if ($item->subscription_plan_code == 'trial') {
                        $item->User->tradieflow_subscription_expire_message .= ' free trial';
                    }
                    else{
                        $item->User->tradieflow_subscription_expire_message .= $item->subscription_plan_name;
                    }

                    $item->User->update();
                }
            });
    }
}
