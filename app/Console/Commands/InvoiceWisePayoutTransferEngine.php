<?php

namespace App\Console\Commands;

use App\Helpers\Constant;
use App\Helpers\Helper;
use App\Helpers\NotificationHelper;
use App\Helpers\WiseHelper;
use App\Models\EmailQueue;
use App\Models\Event;
use App\Models\Invoice;
use App\Models\InvoiceStripePayment;
use App\Models\TextMessage;
use Carbon\Carbon;
use Illuminate\Console\Command;
use DB;

class InvoiceWisePayoutTransferEngine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice_wise_payout_transfer_engine';

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
        InvoiceStripePayment::with('User.UserInvoiceSetting.Country','Invoice.Client')
            ->select('invoice_stripe_payment.*')
            ->where('has_stripe_wise_transferred','=','1')
            ->where('has_customer_paid','=','0')
            ->where('updated_at','<',Carbon::now()->addDays(-2)->format('Y-m-d H:i:s'))
            ->orderBy('updated_at','asc')
            ->chunk(1000,function($items){
                foreach ($items as $item) {
                    if (!$item->User || !$item->User->UserInvoiceSetting) {
                        //notify admin
                        continue;
                    }

                    if ($item->currency !== 'au') {
                        continue;
                    }

                    /**Send money to Stripe*/
                    try{
                        $wise_helper = new WiseHelper();
                        $currency = strtoupper($item->currency);
                        /**Create Quote*/
                        $quote = $wise_helper->createQuote([
                            "sourceCurrency" => $currency,
                            "targetCurrency" => $currency,
                            "sourceAmount" => $item->amount,
                            "profile" =>  $wise_helper->business_profile_id
                        ]);

                        if (isset($quote->id) && $quote->id) {
                            /**Create Recipient*/
//                            switch ($currency) {
//                                case 'USD':
//                                    continue;
////                                    $details = [
////                                        'accountNumber' => $item->User->UserInvoiceSetting->bank_account_number,
////                                        'abartn' => '', //routing number
////                                        'accountType' => 'CHECKING',
////                                        'swiftCode' => '',
////                                        'address' => [
////                                            'country' => null,
////                                            'countryCode' => null,
////                                            'firstLine' => null,
////                                            'postCode' => null,
////                                            'city' => null,
////                                            'state' => null
////                                        ]
////                                    ];
//                                break;
//                                case 'AUD':
//                                    $details = [
//                                        'bsbCode' => '',
//                                        'accountNumber' => $item->User->UserInvoiceSetting->bank_account_number,
//                                        'address' => [
//                                            'country' => $item->User->UserInvoiceSetting->Country ? $item->User->UserInvoiceSetting->Country->name : '',
//                                            'countryCode' => strtoupper($item->User->UserInvoiceSetting->Country->code),
//                                            'firstLine' => $item->User->UserInvoiceSetting->address,
//                                            'postCode' => $item->User->UserInvoiceSetting->zip_code,
//                                            'city' => $item->User->UserInvoiceSetting->city,
//                                            'state' => $item->User->UserInvoiceSetting->state
//                                        ]
//                                    ];
//                                break;
//                                case 'CAD':
//                                    continue;
//                                break;
//                                case 'GPB':
//                                    continue;
//                                    $details = [
//                                        'sortCode' => "231470",
//                                        'accountNumber' => $item->User->UserInvoiceSetting->bank_account_number
//                                    ];
//                                break;
//                            }

                            $details = [
                                'bsbCode' => $item->User->UserInvoiceSetting->bank_bsb_code,
                                'accountNumber' => $item->User->UserInvoiceSetting->bank_account_number,
                                'address' => [
                                    'country' => $item->User->UserInvoiceSetting->Country ? $item->User->UserInvoiceSetting->Country->name : '',
                                    'countryCode' => strtoupper($item->User->UserInvoiceSetting->Country->code),
                                    'firstLine' => $item->User->UserInvoiceSetting->address,
                                    'postCode' => $item->User->UserInvoiceSetting->zip_code,
                                    'city' => $item->User->UserInvoiceSetting->city,
                                    'state' => $item->User->UserInvoiceSetting->state
                                ]
                            ];

                            $recipient = $wise_helper->createRecipient([
                                "currency" => $currency,
                                "type" => "sort_code",
                                "profile" => $wise_helper->business_profile_id,
                                "accountHolderName" => $item->User->UserInvoiceSetting->bank_account_holder_name,
                                "legalType" => $item->User->UserInvoiceSetting->bank_account_holder_type == 'individual' ? 'PRIVATE' : 'BUSINESS',
                                "details" => $details
                            ]);

                            if (isset($recipient->id) && $recipient->id) {
                                /**Create Transfer*/
                                $unique_uuid = Helper::generateUUID();
                                $reference = 'Invoice payment';
                                if ($item->Invoice) {
                                    $reference = 'Invoice Nr.: '.$item->Invoice->invoice_number_label;

                                    if ($item->Invoice->Client) {
                                        $reference .= ', Client: '.$item->Invoice->Client->name;
                                    }
                                }

                                $transfer = $wise_helper->createTransfer([
                                    "targetAccount" => $recipient->id,
                                    "quoteUuid" => $quote->id,
                                    "customerTransactionId" => $unique_uuid,
                                    "details" => [
                                        "reference" => $reference,
                                        "transferPurpose" => "verification.transfers.purpose.pay.bills",
                                        "sourceOfFunds" => "verification.source.of.funds.other"
                                    ]
                                ]);

                                if (isset($transfer->id) && $transfer->id) {
                                    /**Fund Transfer*/
                                    $transfer_fund = $wise_helper->fundTransfer($transfer->id, [
                                        'type' => 'BALANCE'
                                    ]);

                                    if (isset($transfer_fund['status']) && $transfer_fund['status'] == 'COMPLETED') {
                                        $item->has_customer_paid = '1';
                                        $item->wise_quote_id = $quote->id;
                                        $item->wise_recipient_id = $recipient->id;
                                        $item->wise_transfer_id = $transfer->id;
                                        $item->update();
                                    }
                                }
                                else{
                                    //failed to create transfer, notify admin
                                }
                            }
                            else{
                                //failed to create recipient, notify admin
                            }
                        }
                        else{
                            //failed to create quote, notify admin
                        }
                    }
                    catch (\Exception $e) {
                        /**Notify admin about failure*/
                    }
                }
            });
    }
}
