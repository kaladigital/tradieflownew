<?php

namespace App\Console\Commands;

use App\Helpers\Constant;
use App\Models\SpecialOfferPagePurchase;
use Carbon\Carbon;
use Illuminate\Console\Command;
use DB;
use Spatie\Sitemap\Tags\Url;
use Spatie\Sitemap\Sitemap;

class TradieDigitalSpecialOfferQueueClean extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tradie_digital_special_offer_queue_clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean old queue items';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        $yesterday_day = Carbon::now()->addDay(-1)->format('Y-m-d H:i:s');
        SpecialOfferPagePurchase::where('status','=','pending')
            ->where('created_at','<=',$yesterday_day)
            ->chunk(1000,function($items) use ($stripe) {
                foreach ($items as $item) {
                    $stripe->customers->delete(
                        $item->stripe_customer_id,
                        []
                    );
                }
            })
            ->delete();
    }
}
