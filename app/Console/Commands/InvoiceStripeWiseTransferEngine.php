<?php

namespace App\Console\Commands;

use App\Helpers\Constant;
use App\Helpers\Helper;
use App\Helpers\NotificationHelper;
use App\Models\EmailQueue;
use App\Models\Event;
use App\Models\Invoice;
use App\Models\InvoiceStripePayment;
use App\Models\TextMessage;
use Carbon\Carbon;
use Illuminate\Console\Command;
use DB;

class InvoiceStripeWiseTransferEngine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice_stripe_transfer_engine';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send money from Stripe to Wise';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(60 * 60 * 5);
        $stripe_wise_account_id = env('STRIPE_WISE_ACCOUNT_ID');
        InvoiceStripePayment::select('*')
            ->where('has_stripe_wise_transferred','=','0')
            ->chunk(1000,function($items) use ($stripe_wise_account_id){
                foreach ($items as $item) {
                    /**Send money to Stripe*/
                    try{
                        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
                        $stripe_transfer = $stripe->transfers->create([
                            'amount' => $item->amount * 100,
                            'currency' => $item->currency,
                            'destination' => $stripe_wise_account_id
                        ]);

                        if (isset($stripe_transfer) && $stripe_transfer->id) {
                            $item->has_stripe_wise_transferred = '1';
                            $item->stripe_transfer_id = $stripe_transfer->id;
                            $item->stripe_transfer_response = json_encode($stripe_transfer);
                            $item->update();
                        }
                    }
                    catch (\Exception $e) {
                        /**Notify admin about failure*/
                    }
                }
            });
    }
}
