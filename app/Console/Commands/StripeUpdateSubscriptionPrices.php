<?php

namespace App\Console\Commands;

use App\Helpers\Constant;
use App\Models\SubscriptionPlan;
use Carbon\Carbon;
use Illuminate\Console\Command;
use DB;

class StripeUpdateSubscriptionPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe_update_subscription_prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(60 * 60 * 1);
//        try {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
            SubscriptionPlan::whereIn('type',['tradieflow','other'])
                ->whereNotNull('stripe_usd_product_id')
                ->whereNotNull('stripe_aud_product_id')
                ->chunk(1000, function($items) use ($stripe){
                    foreach ($items as $item) {
                        $usd_price = $stripe->prices->all([
                            'limit' => 1,
                            'active' => true,
                            'product' => $item->stripe_usd_product_id
                        ]);

                        if ($usd_price && isset($usd_price['data']['0']->unit_amount_decimal)) {
                            $item->price_usd = $usd_price['data']['0']->unit_amount_decimal / 100;
                            $item->update();
                        }

                        $aud_price = $stripe->prices->all([
                            'limit' => 1,
                            'active' => true,
                            'product' => $item->stripe_aud_product_id
                        ]);

                        if ($aud_price && isset($aud_price['data']['0']->unit_amount_decimal)) {
                            $item->price_aud = $aud_price['data']['0']->unit_amount_decimal / 100;
                            $item->update();
                        }
                    }

                });
//        }
//        catch (\Exception $e) {
//
//        }
    }
}
