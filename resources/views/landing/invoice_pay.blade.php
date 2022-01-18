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
        <div class="secondary-content-body invoices-checkout" id="secondary_content_body">
            <div class="container">
                <div class="row">
                    <div class="col-12" id="invoice_error_container" style="display:none;"></div>
                    <div class="col-12" id="invoice_success_container" style="display:none;"></div>
                    <div class="col-12" id="invoice_purchase_container">
                        <div class="invoice-wrapper">
                            <h3>Confirm Invoice & Pay</h3>
                            <div class="details-wrapper">
                                <div class="row">
                                    <div class="col-12 col-lg-7">
                                        <div class="payment-method-form-wrap form-shown widget-box card-container">
                                            <h6>Payment Details</h6>
                                            {!! Form::open(['url' => 'invoice/pay', 'id' => 'checkout_form', 'autocomplete' => 'off']) !!}
                                                <div class="form-group">
                                                    {!! Form::text('card_holder_name',null,['class' => 'form-control', 'id' => 'card_holder_name', 'placeholder' => 'Name on Card', 'required' => 'required']) !!}
                                                    {!! Form::label('card_holder_name','Name on Card') !!}
                                                </div>
                                                <div class="form-group">
                                                    <div id="card_number" class="form-control"></div>
                                                    <label for="card_number">Credit Card Number</label>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-lg-6">
                                                        <div id="expiry_date" class="form-control"></div>
                                                        <label for="expiry_date">Expiry Date (YY/MM)</label>
                                                    </div>
                                                    <div class="form-group col-lg-6">
                                                        <div id="cvv_code" class="form-control"></div>
                                                        <label for="cvv_code">CVC (Security Code)</label>
                                                    </div>
                                                </div>
                                                <div id="stripe_error"></div>
                                                <div id="checkout_loader" style="display:none;">
                                                    <img src="/images/loader.png" width="24px" class="float-left">
                                                    <span class="float-left ml-1 loader-text">Processing</span>
                                                </div>
                                                <button type="submit" id="process_checkout_btn" class="btn btn--sqr btn-primary w-100" id="addCardInfo">Proceed Payment</button>
                                                <div class="security-note-text d-flex align-items-center">
                                                    <img src="/images/lock-icon-gray.svg" alt="Loc icon" class="icon">
                                                    100% Secure Payments
                                                </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-5 mt-4 mt-lg-0">
                                        <div class="widget-box">
                                            <h6>Invoice Details</h6>
                                            <p><span>Brandon Baptista</span> 180 Fort Washington Ave, New York, NY 10032, USA</p>
                                            @if($invoice->InvoiceItem->count())
                                                <div class="details-table">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th class="item-name">Item</th>
                                                                <th class="item-qty">Qty</th>
                                                                <th class="item-price">Price</th>
                                                                <th class="item-total-price">Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($invoice->InvoiceItem as $item)
                                                                <tr>
                                                                    <td class="item-name">{{ $item->title }}</td>
                                                                    <td class="item-qty">{{ $item->qty }}</td>
                                                                    <td class="item-price">{{ $item->unit_price }} {{ $currency }}</td>
                                                                    <td class="item-total-price">{{ sprintf('%.2f',$item->qty * $item->unit_price) }} {{ $currency }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            <div class="taxes-row d-flex">
                                                <span class="label">Taxes</span>
                                                <span class="value ml-auto">{{ $invoice->tax_amount }} {{ $currency }}</span>
                                            </div>
                                            <div class="discount-row d-flex">
                                                <span class="label">Discount</span>
                                                <span class="value ml-auto">-{{ $invoice->discount_amount }} {{ $currency }}</span>
                                            </div>
                                            <div class="total-amount-row d-flex">
                                                <span class="label">Total Amount</span>
                                                <span class="value ml-auto">{{ number_format($invoice->total_gross_amount,2) }} {{ $currency }}</span>
                                            </div>
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
<script type="text/javascript" src="/js/underscore-min.js"></script>
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        var stripe = Stripe('{{ env('STRIPE_PUBLIC_KEY') }}');
        var elementStyles = {
            base: {
                iconColor: '#20283e',
                color: '#000000',
                fontWeight: 400,
                fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
                fontSize: '16px',
                fontSmoothing: 'antialiased',
                ':-webkit-autofill': {
                    color: '#fce883',
                },
                '::placeholder': {
                    opacity: 0,
                    color: '#86969E',
                },
                '.CardBrandIcon-container': {
                    left: 'auto',
                    right: 20
                }
            },
            invalid: {
                iconColor: '#4cb5f5',
                color: '#4cb5f5',
            },
        };

        var elements = stripe.elements({
            fonts: [
                {
                    cssSrc: 'https://fonts.googleapis.com/css?family=Poppins',
                },
            ]
        });

        var elementClasses = {
            focus: 'focused',
            empty: 'empty',
            invalid: 'invalid',
        };

        var cardNumber = elements.create('cardNumber', {
            showIcon: false,
            style: elementStyles,
            classes: elementClasses,
            placeholder: '',
        });

        cardNumber.mount('#card_number');
        cardNumber.on('change', function(event) {
            var displayError = document.getElementById('card_errors');
            if (event.error) {
                $('.error .message').text(event.error.message);
            } else {
                $('.error .message').text('');
            }
        });

        var cardExpiry = elements.create('cardExpiry', {
            style: elementStyles,
            classes: elementClasses,
            placeholder: ' ',
        });

        cardExpiry.mount('#expiry_date');

        var cardCvc = elements.create('cardCvc', {
            style: elementStyles,
            classes: elementClasses,
            placeholder: ' ',
        });
        cardCvc.mount('#cvv_code');
        registerElements([cardNumber, cardExpiry, cardCvc], 'card-container','default');

        function registerElements(elements, exampleName) {
            var formClass = '.' + exampleName;
            var example = document.querySelector(formClass);

            var form = example.querySelector('form');
            var error = document.getElementById('stripe_error');

            function enableInputs() {
                Array.prototype.forEach.call(
                    form.querySelectorAll(
                        "input[type='text'], input[type='email'], input[type='tel']"
                    ),
                    function(input) {
                        input.removeAttribute('disabled');
                    }
                );
            }

            function disableInputs() {
                Array.prototype.forEach.call(
                    form.querySelectorAll(
                        "input[type='text'], input[type='email'], input[type='tel']"
                    ),
                    function(input) {
                        input.setAttribute('disabled', 'true');
                    }
                );
            }

            function triggerBrowserValidation() {
                var submit = document.createElement('input');
                submit.type = 'submit';
                submit.style.display = 'none';
                form.appendChild(submit);
                submit.click();
                submit.remove();
            }

            // Listen for errors from each Element, and show error messages in the UI.
            var savedErrors = {};
            elements.forEach(function(element, idx) {
                element.on('change', function(event) {
                    if (event.error) {
                        error.classList.add('visible');
                        savedErrors[idx] = event.error.message;
                        error.innerText = event.error.message;
                    } else {
                        savedErrors[idx] = null;
                        error.innerText = '';
                        var nextError = Object.keys(savedErrors)
                            .sort()
                            .reduce(function(maybeFoundError, key) {
                                return maybeFoundError || savedErrors[key];
                            }, null);

                        if (nextError) {
                            error.innerText = nextError;
                        } else {
                            error.classList.remove('visible');
                        }
                    }
                });
            });
        }

        $(document).on('submit','#checkout_form',function(){
            $('#process_checkout_btn').hide();
            $('#checkout_loader').show();
            stripe.createToken(cardNumber).then(function(result) {
                if (result.error) {
                    $('#checkout_loader').hide();
                    $('#process_checkout_btn').show();
                    $('#stripe_error').text(result.error.message);
                }
                else {
                    $('#stripe_error').empty();
                    $.post('/invoice/pay',{ token: result.token.id, id: '{{ $invoice->invoice_unique_number }}' },function(data){
                        $('#secondary_content_body').addClass('status');
                        $('#invoice_purchase_container').css('opacity','0');
                        $('#checkout_loader').hide();
                        if (data.status) {
                            $('#invoice_success_container').html($('#payment_success_template').html()).show();
                        }
                        else{
                            $('#invoice_error_container').html(_.template($('#payment_failure_template').html())({
                                error_message: data.error
                            })).show();
                            $('#process_checkout_btn').show();
                        }
                    },'json');
                }
            });

            return false;
        });

        $(document).on('click','#try_again_btn',function(){
            $('#invoice_error_container').hide();
            $('#secondary_content_body').removeClass('status');
            $('#invoice_purchase_container').css('opacity','1');
            return false;
        });
    });
</script>
<script type="text/template" id="payment_failure_template">
    <div class="col-12 text-center">
        <div class="figure">
            <img src="/images/status-error-icon.svg" alt="Error">
        </div>
        <h2 class="status-text" data-title="Ooooops">Error</h2>
        <h3><%= error_message %></h3>
        <a href="#" class="btn btn--sqr btn-primary btn-try-again" id="try_again_btn">Try Again</a>
    </div>
</script>
<script type="text/template" id="payment_success_template">
    <div class="figure">
        <img src="/images/thank-you-figure.svg" alt="Thank you">
    </div>
    <h2 class="status-text" data-title="Thank you!">
        Successful Payment!
    </h2>
</script>
@endsection
