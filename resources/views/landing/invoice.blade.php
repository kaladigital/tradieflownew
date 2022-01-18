@extends('layouts.invoice')
@section('content')
    <main class="main invoice-main">
        <header class="header secondary-header">
            <div class="container d-flex align-items-center navbar-expand-md">
                <a href="/" class="main-logo">
                    <img src="/images/main-logo.svg" alt="Main logo">
                </a>
            </div>
        </header>
        <div class="secondary-content-body">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="invoice-wrapper">
                            <div class="invoices-header d-flex">
                                @if(!$invoice->has_paid)
                                    <a href="/invoice/{{ $invoice->invoice_unique_number }}/pay" class="btn btn-primary btn--sqr pay-now">Pay Now</a>
                                @endif
                                <div class="invoice-info">
                                    <h4>
                                        ${{ number_format($invoice->total_gross_amount,2) }}
                                        <small>{{ $invoice->currency }}</small>
                                    </h4>
                                    @if(!$invoice->has_paid)
                                        @if($is_overdue_invoice)
                                            <h5 class="red-text">
                                                {{ $invoice_due_days }} days overdue
                                            </h5>
                                        @else
                                            <h5>
                                                {{ $due_date_format }}
                                            </h5>
                                        @endif
                                    @endif
                                </div>
                                <div class="actions mt-4 mt-lg-0 ml-lg-auto d-none d-md-flex align-items-center">
                                    <span>Save As:</span>
                                    <a href="#" class="btn btn-outline btn--sqr">PDF</a>
                                    <a href="#" class="btn btn-outline btn--sqr">CSV</a>
                                    <a href="#" class="btn btn-outline btn--sqr">Xero</a>
                                </div>
                                <div class="actions mt-4 mt-lg-0 d-md-none">
                                    <select name="saveAs" id="saveAs">
                                        <option>Save As:</option>
                                        <option value="pdf">PDF</option>
                                        <option value="csv">CSV</option>
                                        <option value="xero">Xero</option>
                                    </select>
                                </div>
                            </div>
                            <div class="invoice-inner d-flex flex-direction-column">
                                <div class="invoice-heading d-flex align-items-center">
                                    <h1 class="title">{{ $user_invoice_setting ? $user_invoice_setting->company_name : '' }}</h1>
                                    <div class="icon ml-auto">
                                        <img src="/images/invoice-icon.svg" alt="Invoice icon">
                                    </div>
                                </div>
                                <div class="invoice-body">
                                    <section class="description-section">
                                        <div class="row">
                                            <div class="col-lg-6 col-12 seller-info">
                                                <h5 class="description-label">Seller</h5>
                                                @if($user_invoice_setting)
                                                    @if(strlen($user_invoice_setting->company_name))
                                                        <h2>{{ $user_invoice_setting->company_name }}</h2>
                                                    @endif
                                                    @if(strlen($user_invoice_setting->address))
                                                        <h6>{{ $user_invoice_setting->address }}</h6>
                                                    @endif
                                                    @if($invoice_address)
                                                        <h6>{{ $invoice_address }}</h6>
                                                    @endif
                                                    @if($client_country)
                                                        <h6>{{ $client_country->name }}</h6>
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="col-lg-6 col-12 buyer-info">
                                                <h5 class="description-label">Buyer</h5>
                                                <h2>{{ $invoice->Client->name }}</h2>
                                                @if(strlen($invoice->address))
                                                    <h6>{{ $invoice->address }}</h6>
                                                @endif
                                                <?php
                                                $buyer_address = [];
                                                if (strlen($invoice->zip)) {
                                                    $buyer_address[] = $invoice->zip;
                                                }

                                                if (strlen($invoice->city)) {
                                                    $buyer_address[] = $invoice->city;
                                                }

                                                if (strlen($invoice->state)) {
                                                    $buyer_address[] = $invoice->state;
                                                }
                                                ?>
                                                @if($buyer_address)
                                                    <h6>{{ implode(', ',$buyer_address) }}</h6>
                                                @endif
                                                @if($invoice->Country)
                                                    <h6>{{ $invoice->Country->name }}</h6>
                                                @endif
                                                @if(strlen($invoice->gst_number))
                                                    <p>
                                                        <span class="label">GST Number</span>
                                                        <span class="value">{{ $invoice->gst_number }}</span>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </section>
                                    <section class="date-section">
                                        <div class="row date-row">
                                            <div class="col-lg-6 col-12 issue-date-col">
                                                <h5>
                                                    <span class="label">Issue Date:</span>
                                                    <span class="value">
                                                    {{ $invoice->issued_date ? \Carbon\Carbon::createFromFormat('Y-m-d',$invoice->issued_date)->format('m/d/Y') : '' }}
                                                </span>
                                                </h5>
                                                <h5>
                                                    <span class="label">FULFILLMENT DATE:</span>
                                                    <span class="value">
                                                    {{ $invoice->fulfillment_date ? \Carbon\Carbon::createFromFormat('Y-m-d',$invoice->fulfillment_date)->format('m/d/Y') : '' }}
                                                </span>
                                                </h5>
                                            </div>
                                            <div class="col-lg-6 col-12 due-date-col">
                                                <h5>
                                                    <span class="label">DUE DATE:</span>
                                                    <span class="value">
                                                    {{ $invoice->due_date ? \Carbon\Carbon::createFromFormat('Y-m-d',$invoice->due_date)->format('m/d/Y') : '' }}
                                                </span>
                                                </h5>
                                                <h5>
                                                    <span class="label">Invoice Number:</span>
                                                    <span class="value">{{ $invoice->invoice_number_label }}</span>
                                                </h5>
                                            </div>
                                        </div>
                                    </section>
                                    <section class="total-section">
                                        <div class="row">
                                            <div class="col-12 d-flex">
                                                <div class="total-due ml-auto">
                                                    @if($invoice->has_paid)
                                                        <span class="label green-text">Paid</span>
                                                    @else
                                                        <span class="label green-text">total due:</span>
                                                        <span class="value">{{ $invoice_currency }}{{ number_format($invoice->total_gross_amount,2) }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <section class="info-section">
                                        <div class="row">
                                            <div class="col-12">
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th scope="col" class="item-number">&nbsp;</th>
                                                        <th scope="col" class="description">Description</th>
                                                        <th scope="col" class="quantity">Quantity</th>
                                                        <th scope="col" class="unit-price">Unit Price</th>
                                                        <th scope="col" class="tax">Tax</th>
                                                        <th scope="col" class="total">Amount</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    $total = 0;
                                                    $tax_rate_amount = 0;
                                                    ?>
                                                    @foreach($invoice->InvoiceItem as $key => $value)
                                                        <?php
                                                        $value->tax_rate = $value->tax_rate ? $value->tax_rate : 0;
                                                        $item_total = $value->unit_price * $value->qty;

                                                        $tax_rate_amount += ($value->tax_rate) ? $item_total * $value->tax_rate / 100 : 0;
                                                        $total += $item_total;
                                                        ?>
                                                        <tr>
                                                            <td scope="col" class="item-number">{{ $key + 1 }}</td>
                                                            <td scope="col" class="description">
                                                                <span class="text-bold">
                                                                    {{ $value->title }}
                                                                </span>
                                                                {{ $value->description }}
                                                            </td>
                                                            <td scope="col" class="quantity">{{ $value->qty }} unit</td>
                                                            <td scope="col" class="unit-price">{{ $invoice_currency }}{{ number_format($value->unit_price,2) }} </td>
                                                            <td scope="col" class="vat">{{ $value->tax_rate ? $value->tax_rate : '0' }}%</td>
                                                            <td scope="col" class="line-total">{{ $invoice_currency }}{{ ($value->unit_price && $value->qty) ? number_format($value->unit_price * $value->qty,2) : '0.00' }}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </section>
                                    <section class="net-total-section">
                                        <div class="row">
                                            <div class="col-12 d-flex">
                                                <div class="net-total ml-auto">
                                                    <p>
                                                        <span class="label">NET TOTAL:</span>
                                                        <span class="value">{{ $invoice_currency }}{{ number_format($total,2) }}</span>
                                                    </p>
                                                    <p>
                                                        <span class="label">{{ $tax_rate_amount ? ceil($tax_rate_amount * 100 / $total) : 0 }}% TAX:</span>
                                                        <span class="value">{{ $invoice_currency }}{{ number_format($tax_rate_amount,2) }}</span></p>
                                                    <p>
                                                        <span class="label">Total Due:</span>
                                                        <span class="value">{{ $invoice_currency }}{{ number_format($tax_rate_amount + $total,2) }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    @if(strlen($invoice->note))
                                        <section class="comment-section">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="comments">
                                                        <h3>Comments:</h3>
                                                        <p>{{ $invoice->note }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    @endif
                                </div>
                                <div class="invoice-footer">
                                    <div class="row">
                                        <div class="col-2 d-none d-lg-block number-of-page">
                                            <span>1/1 Page</span>
                                        </div>
                                        <div class="col-12 order-3 order-md-2 col-md-8 col-lg-6 footer-content">
                                            <p>The invoice was created with the “TradieFlow” billing program. Tradieflow.com</p>
                                        </div>
                                        <div class="col-12 order-1 order-md-3 col-md-4 footer-content">
                                            <img src="/images/main-logo.svg" alt="Main logo" class="footer-logo">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('view_script')
<script type="text/javascript">
$(document).ready(function(){

});
</script>
@endsection
