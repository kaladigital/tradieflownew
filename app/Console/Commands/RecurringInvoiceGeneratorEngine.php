<?php

namespace App\Console\Commands;

use App\Helpers\Constant;
use App\Helpers\NotificationHelper;
use App\Models\EmailQueue;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RecurringInvoiceGeneratorEngine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recurring_invoice_generator_engine';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate recurring invoice';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(60 * 60 * 5);
        Invoice::select('invoice.*')
            ->leftJoin('user','user.user_id','=','invoice.user_id')
            ->where('next_recurring_date','=',Carbon::now()->format('Y-m-d'))
            ->where('user.active','=','1')
            ->chunk(1000,function($items){
                foreach ($items as $item) {
                    /**Create Invoice*/
                    $invoice_data = $item->toArray();
                    unset($invoice_data['invoice_id']);
                    unset($invoice_data['xero_invoice_id']);
                    unset($invoice_data['created_at']);
                    unset($invoice_data['updated_at']);
                    $invoice_data['invoice_unique_number'] = $item->user_id . $item->client_id . uniqid();
                    $invoice_data['status'] = 'pending';
                    $invoice_data['has_paid'] = '0';
                    $invoice_data['paid_date'] = null;
                    $invoice_data['is_recurring'] = '0';
                    $invoice_data['recurring_type'] = null;
                    $invoice_data['recurring_num'] = null;
                    $invoice_data['next_recurring_date'] = null;
                    $model = Invoice::create($invoice_data);
                    $model->invoice_number_label = Carbon::now()->format('Y-nj') . ($model->invoice_id + 1000);
                    $model->update();

                    /**Update recurring*/
                    if ($item->is_recurring && $item->recurring_type && $item->recurring_num && array_key_exists($item->recurring_type,Constant::GET_INVOICE_RECURRING_PERIODS())) {
                        switch ($item->recurring_type) {
                            case 'day':
                                $item->next_recurring_date = Carbon::now()->addDays($item->recurring_num)->format('Y-m-d');
                            break;
                            case 'month':
                                $item->next_recurring_date = Carbon::now()->addMonths($item->recurring_num)->format('Y-m-d');
                            break;
                            case 'year':
                                $item->next_recurring_date = Carbon::now()->addYears($item->recurring_num)->format('Y-m-d');
                            break;
                        }
                    }
                    else{
                        $item->next_recurring_date = null;
                    }

                    $item->update();
                }
            });
    }
}
