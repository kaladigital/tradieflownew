<?php

namespace App\Console\Commands;

use App\Helpers\XeroHelper;
use App\Models\Invoice;
use App\Models\UserSubscription;
use App\Models\UserXeroAccount;
use Carbon\Carbon;
use Illuminate\Console\Command;

class XeroInvoicePaidStatusEngine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xero_invoice_paid_engine';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Xero invoice paid engine';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(60 * 60 * 10);
        Invoice::select('invoice.*','user_xero_token.user_xero_token_id')
            ->leftJoin('user','user.user_id','=','invoice.user_id')
            ->leftJoin('user_xero_token','user_xero_token.user_id','=','user.user_id')
            ->where('invoice.has_paid','=','0')
            ->whereNotNull('invoice.xero_invoice_id')
            ->whereNotNull('user_xero_token.user_xero_token_id')
            ->chunk(1000,function($items){
                foreach ($items as $item) {
                    try {
                        $user_xero_account = UserXeroAccount::where('active','=','1')->find($item->user_xero_token_id);

                        $apiInstance = XeroHelper::getXeroInstance($user_xero_account);
                        $result = $apiInstance->getInvoice($item->tenant_id, $item->xero_invoice_id, null);

                        if (strtoupper($result['0']['status']) == 'PAID') {
                            $item->has_paid = '1';
                            $item->paid_date = Carbon::now()->format('Y-m-d');
                            $item->update();
                        }
                    }
                    catch (\Exception $e) {

                    }
                }
            });
    }
}
