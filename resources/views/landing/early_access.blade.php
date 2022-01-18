@extends('layouts.landing')
@section('view_css')
<link rel="stylesheet" href="/js/noty/noty.css">
@endsection
@section('content')
    <div class="not-logedin early-access version2">
        <section class="offer-section" id="offer">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-lg-8 offset-lg-2">
                        <h2 class="text-center"> <span class="decorated-text">
                            get 50% off</span> for your 1st year if you sign up before launch.
                        </h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 d-none d-sm-block col-sm-2">
                        <img src="/landing_media/img/_src/svg/illustration-1.svg" alt="Illustration 1 " class="illustration illustration-1">
                    </div>
                    <div class="col-12 col-sm-8">
                        <p class="lead-text">
                            This launch special allows you to get 50% off for your 1st year if you sign up before launch. At launch date TradieFlow will be full price
                        </p>
                        <h2 class="text-center comming-soon">Coming Soon</h2>
                        <div class="time-counter" id="time_counter" style="display:none;">
                            <div id="days_left" class="hours" data-name="days"></div> <span>:</span>
                            <div id="hours_left" class="hours" data-name="hours"></div> <span>:</span>
                            <div id="minutes_left" class="minutes" data-name="minutes"></div> <span>:</span>
                            <div id="seconds_left" class="seconds" data-name="seconds"></div>
                        </div>
                    </div>
                    <div class="col-12 d-none d-sm-block col-sm-2">
                        <img src="/landing_media/img/_src/svg/illustration-2.svg" alt="Illustration 2" class="illustration illustration-2">
                    </div>
                </div>
                <!-- <div class="row">
                  <div class="col-12">
                    <div class="figure-thumb">
                      <img src="/landing_media/img/_src/svg/early-access-IMG.svg" alt="Early access">
                    </div>
                  </div>
                </div> -->
            </div>
        </section>
        <section class="pricing-sect" id="prices">
            <div class="container">
                {!! Form::open(['url' => 'early-access', 'id' => 'early_access_form']) !!}
                    <div class="row switch-row">
                        <div class="col-12">
                            <div class="monthly-yearly-switch d-flex justify-content-center align-items-center">
                                <span class="monthly {{ $subscription_type == 'monthly' ? 'active' : '' }}">Monthly</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="subscription_switch_item" {{ $subscription_type == 'yearly' ? 'checked="checked"' : '' }} autocomplete="off">
                                    <label class="custom-control-label" for="subscription_switch_item"></label>
                                </div>
                                <span class="yearly {{ $subscription_type == 'yearly' ? 'active' : '' }}">Yearly</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="endless-possibilities">
                                <div class="plan-item">
                                    <h4>Endless Possibilities</h4>
                                    <p>Professional Features for Professional Tradies</p>
                                    <ul class="features-list">
                                        <li class="list-item">
                                            <span class="item-title">Track all leads from one app</span>
                                            <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="10.5" cy="10.5" r="10" fill="#43D14F" stroke="white" /><path fill-rule="evenodd" clip-rule="evenodd" d="M15.1922 7.82843C14.8017 7.4379 14.1685 7.4379 13.778 7.82843L9.53534 12.0711L8.12113 10.6569C7.7306 10.2663 7.09744 10.2663 6.70692 10.6569C6.31639 11.0474 6.31639 11.6805 6.70692 12.0711L8.82824 14.1924C9.21876 14.5829 9.85192 14.5829 10.2424 14.1924L15.1922 9.24264C15.5827 8.85212 15.5827 8.21895 15.1922 7.82843Z" fill="white" /></svg>
                                        </li>
                                        <li class="list-item">
                                            <span class="item-title">View all customer interactions in one place</span>
                                            <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="10.5" cy="10.5" r="10" fill="#43D14F" stroke="white" /><path fill-rule="evenodd" clip-rule="evenodd" d="M15.1922 7.82843C14.8017 7.4379 14.1685 7.4379 13.778 7.82843L9.53534 12.0711L8.12113 10.6569C7.7306 10.2663 7.09744 10.2663 6.70692 10.6569C6.31639 11.0474 6.31639 11.6805 6.70692 12.0711L8.82824 14.1924C9.21876 14.5829 9.85192 14.5829 10.2424 14.1924L15.1922 9.24264C15.5827 8.85212 15.5827 8.21895 15.1922 7.82843Z" fill="white" /></svg>
                                        </li>
                                        <li class="list-item">
                                            <span class="item-title">Free SMS, Free Phone Calls</span>
                                            <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="10.5" cy="10.5" r="10" fill="#43D14F" stroke="white" /><path fill-rule="evenodd" clip-rule="evenodd" d="M15.1922 7.82843C14.8017 7.4379 14.1685 7.4379 13.778 7.82843L9.53534 12.0711L8.12113 10.6569C7.7306 10.2663 7.09744 10.2663 6.70692 10.6569C6.31639 11.0474 6.31639 11.6805 6.70692 12.0711L8.82824 14.1924C9.21876 14.5829 9.85192 14.5829 10.2424 14.1924L15.1922 9.24264C15.5827 8.85212 15.5827 8.21895 15.1922 7.82843Z" fill="white" /></svg>
                                        </li>
                                        <li class="list-item">
                                            <span class="item-title">Book Meetings & Jobs</span>
                                            <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="10.5" cy="10.5" r="10" fill="#43D14F" stroke="white" /><path fill-rule="evenodd" clip-rule="evenodd" d="M15.1922 7.82843C14.8017 7.4379 14.1685 7.4379 13.778 7.82843L9.53534 12.0711L8.12113 10.6569C7.7306 10.2663 7.09744 10.2663 6.70692 10.6569C6.31639 11.0474 6.31639 11.6805 6.70692 12.0711L8.82824 14.1924C9.21876 14.5829 9.85192 14.5829 10.2424 14.1924L15.1922 9.24264C15.5827 8.85212 15.5827 8.21895 15.1922 7.82843Z" fill="white" /></svg>
                                        </li>
                                        <li class="list-item">
                                            <span class="item-title">Send Invoices & Collect Payments</span>
                                            <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="10.5" cy="10.5" r="10" fill="#43D14F" stroke="white" /><path fill-rule="evenodd" clip-rule="evenodd" d="M15.1922 7.82843C14.8017 7.4379 14.1685 7.4379 13.778 7.82843L9.53534 12.0711L8.12113 10.6569C7.7306 10.2663 7.09744 10.2663 6.70692 10.6569C6.31639 11.0474 6.31639 11.6805 6.70692 12.0711L8.82824 14.1924C9.21876 14.5829 9.85192 14.5829 10.2424 14.1924L15.1922 9.24264C15.5827 8.85212 15.5827 8.21895 15.1922 7.82843Z" fill="white" /></svg>
                                        </li>
                                    </ul>
                                </div>
                                <div class="plan-item">
                                    <div class="top-info subscription_box_item" data-type="yearly" style="{{ $subscription_type == 'yearly' ? '' : 'display:none;' }}">
                                        <picture class="avatar">
                                            <img class="yearly-avatar" src="/landing_media/images/pricing-prof-icon.png" alt="starter">
                                        </picture>
                                        <div class="title">
                                            Yearly Professional
                                            <div class="price">
                                                <span id="yearly_price_format">
                                                    {{ $currency_label.$subscription_plan_data['yearly']->price }}
                                                </span>
                                                <span class="duration">/ year</span>
                                                <span class="status bg-green discount_box_item">-50% OFF</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="top-info subscription_box_item" data-type="monthly" style="{{ $subscription_type == 'monthly' ? '' : 'display:none;' }}">
                                        <picture class="avatar">
                                            <img class="monthly-avatar" src="/landing_media/images/monthly-badge.png" alt="starter">
                                        </picture>
                                        <div class="title">
                                            Monthly Starter
                                            <div class="price">
                                                <span id="monthly_price_format">
                                                    {{ $currency_label.$selected_plan->price }}
                                                </span>
                                                <span class="duration">/ month</span>
                                                <span class="status bg-green discount_box_item">-50% OFF</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-8">
                                            <h4>
                                                <span id="subscription_plan_text">{{ $selected_plan->plan_code == 'pro' ? 'Monthly' : 'Yearly' }}</span>
                                                Subscription fee
                                            </h4>
                                        </div>
                                        <div class="col-12 col-sm-4 text-md-right">
                                            <h4 id="subscription_price_text">
                                                {{ $currency_label.$selected_plan->discounted_price }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-8">
                                            <h4>GST (10%)</h4>
                                        </div>
                                        <div class="col-12 col-sm-4 text-md-right">
                                            <h4 id="gst_total">{{ $currency_label.$selected_plan->gst_discount_price }}</h4>
                                        </div>
                                    </div>
                                    <div class="row total-row">
                                        <div class="col-12 col-sm-8">
                                            <h4>Total</h4>
                                        </div>
                                        <div class="col-12 col-sm-4 text-md-right">
                                            <h4 id="total_subscription_text">{{ $currency_label.$selected_plan->total_price }}</h4>
                                        </div>
                                    </div>
                                    <div class="billing-information">
                                        <h6>Billing Information</h6>
                                        <div class="payment-method-form-wrap form-shown card-container">
                                            <div class="form-group">
                                                {!! Form::text('email',null,['class' => 'form-control empty early-access-email', 'placeholder' => 'Email', 'required' => 'required', 'id' => 'email']) !!}
                                                {!! Form::label('email','Email') !!}
                                            </div>
                                            <div class="form-group">
                                                <div id="card_number" class="form-control"></div>
                                                <label for="card_number">Credit Card Number</label>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-lg-6">
                                                    <div id="expiry_date" class="form-control"></div>
                                                    <label for="expiry_date">Expiry Date</label>
                                                </div>
                                                <div class="form-group col-lg-6">
                                                    <div id="cvv_code" class="form-control"></div>
                                                    <label for="cvv_code">CVC</label>
                                                </div>
                                            </div>
                                            <div id="stripe_error" class="alert alert-warning line-height-20" style="display:none;"></div>
                                            <div class="subscription_loader" style="display:none;">
                                                <img src="/images/loader.png" width="24px" class="float-left">
                                                <span class="float-left ml-1 loader-text">Processing</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="checkout-wrap" id="processing_container">
                        <div class="row">
                            <div class="col-12 col-sm-8">
                                <div class="switch-wrap">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="discount_switch" checked="checked" autocomplete="off">
                                        <label class="custom-control-label" for="discount_switch"></label>
                                    </div>
                                    <div class="info">
                                        <h5>I Accept the Discount</h5>
                                        <span class="status">Save 50%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4 d-flex">
                                <button type="submit" id="checkout_btn" class="btn btn-primary btn--sqr checkout-btn mt-5 mt-sm-0 ml-sm-auto">
                                    Checkout
                                </button>
                                <div id="processing_payment_container" style="display:none;">
                                    <img src="/images/loader.png" width="24px" class="float-left">
                                    <span class="float-left ml-1 loader-text">Processing</span>
                                </div>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </section>
        <section class="promo-section" id="promo">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-lg-8 offset-lg-2">
                        <h2 class="text-center">
                            How
                            <span class="green-text">TradieFlow Helps Grow Your</span>
                            Trade, Contracting Or Home Improvement
                            <span class="green-text">Business</span>
                        </h2>
                        <div class="video-container">
                            <div class="circle"></div>
                            <div class="video-player">
                                <div class="video-thumb">
                                    <img src="/landing_media/img/_src/png/video-thumb.png" alt="Video thumbnial">
                                </div>
                                <button class="btn play-video" id="play_video">
                                    <img src="/landing_media/img/_src/svg/video-play-icon.svg" alt="Video play icon">
                                </button>
                                <section class="landing-video-container" style="display:none;">
                                    <div class="wistia_responsive_padding"><div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;"><div class="wistia_embed wistia_async_t4wgpn4saq videoFoam=true" style="height:100%;position:relative;width:100%"><div class="wistia_swatch" style="height:100%;left:0;opacity:0;overflow:hidden;position:absolute;top:0;transition:opacity 200ms;width:100%;"><img src="https://fast.wistia.com/embed/medias/t4wgpn4saq/swatch" style="filter:blur(5px);height:100%;object-fit:contain;width:100%;" alt="" aria-hidden="true" onload="this.parentNode.style.opacity=1;" /></div></div></div></div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @include('elements.footer',['hide_footer' => false])
    </div>
@endsection
@section('view_script')
<script src="https://js.stripe.com/v3/"></script>
<script src="https://fast.wistia.com/embed/medias/t4wgpn4saq.jsonp" async></script>
<script src="https://fast.wistia.com/assets/external/E-v1.js" async></script>
<script type="text/javascript" src="/js/noty/noty.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        window.dataLayer = window.dataLayer || [];
        window.currency = '{{ $currency }}';
        window.subscription_prices = {
            'monthly_usd' : '{{ $subscription_plan_data['pro']->price_usd }}',
            'monthly_usd_format' : '{{ number_format($subscription_plan_data['pro']->price_usd,2) }}',
            'yearly_usd' : '{{ $subscription_plan_data['yearly']->price_usd }}',
            'yearly_usd_format' : '{{ number_format($subscription_plan_data['yearly']->price_usd,2) }}',
            'monthly_aud' : '{{ $subscription_plan_data['pro']->price_aud }}',
            'monthly_aud_format' : '{{ number_format($subscription_plan_data['pro']->price_aud,2) }}',
            'yearly_aud' : '{{ $subscription_plan_data['yearly']->price_aud }}',
            'yearly_aud_format' : '{{ number_format($subscription_plan_data['yearly']->price_aud,2) }}',
        }
        // window.onscroll = function () {
        //     header_scroll();
        // }
        //
        // header_scroll();

        $(document).on('click','#play_video',function(){
            var video = Wistia.api("t4wgpn4saq");
            $('.landing-video-container').show();
            video.play();
            $('.video-player').addClass('playing');
            return false;
        });

        $(document).on('change','#subscription_switch_item',function(){
            if ($(this).prop('checked')) {
                $('.monthly').removeClass('active');
                $('.yearly').addClass('active');
                $('.subscription_box_item[data-type="monthly"]').hide();
                $('.subscription_box_item[data-type="yearly"]').show();
                $('#yearly_price_format').text(currency == 'usd' ? '$' + subscription_prices.yearly_usd_format : 'AUD ' + subscription_prices.yearly_aud_format);
            }
            else{
                $('.yearly').removeClass('active');
                $('.monthly').addClass('active');
                $('.subscription_box_item[data-type="yearly"]').hide();
                $('.subscription_box_item[data-type="monthly"]').show();
                $('#monthly_price_format').text(currency == 'usd' ? '$' + subscription_prices.monthly_usd_format : 'AUD ' + subscription_prices.monthly_aud_format);
            }

            $('#discount_switch').trigger('change');
            return false;
        });

        $(document).on('click','#get_discount',function(){
            let access_form_offset = $('#early_access_form').offset().top;
            $('html, body').animate({
                scrollTop: $('#time_counter').is(':visible') ? access_form_offset - 150 : access_form_offset - 80
            }, 2000);
            return false;
        });

        $(document).on('change','#discount_switch',function(){
            var has_checked = $(this).prop('checked');
            if (has_checked) {
                $('#discount_label').text('-50% OFF');
                $('.discount_box_item').show();
            }
            else{
                $('#discount_label').text('0% OFF');
                $('.discount_box_item').hide();
            }

            /**If yearly*/
            if ($('#subscription_switch_item').prop('checked')) {
                var price = (currency == 'usd') ? subscription_prices.yearly_usd : subscription_prices.yearly_aud;
            }
            else{
                var price = (currency == 'usd') ? subscription_prices.monthly_usd : subscription_prices.monthly_aud;
            }

            var currency_label = currency == 'usd' ? '$' : 'AUD ';

            var subscription_price = has_checked ? parseFloat(price / 2) : parseFloat(price);
            var gst_price = subscription_price / 10;
            $('#subscription_price_text').text(currency_label + format_number(subscription_price))
            $('#gst_total').text(currency_label + format_number(gst_price));
            $('#total_subscription_text').text(currency_label + format_number(subscription_price + gst_price));
            return false;
        });

        $(document).on('submit','#early_access_form',function(){
            $('#checkout_btn').hide()
            $('#processing_payment_container').show();
            stripe.createToken(cardNumber).then(function(result) {
                if (result.error) {
                    $('#stripe_error').text(result.error.message).show();
                    $('#checkout_btn').show()
                    $('#processing_payment_container').hide();
                }
                else {
                    $.post('/early-access/purchase',{
                        currency: currency,
                        email : $('#email').val(),
                        token: result.token.id,
                        discount: $('#discount_switch').prop('checked') ? 1 : 0,
                        plan: $('.yearly').hasClass('active') ? 'yearly' : 'monthly'
                    }, function(data) {
                        if (data.status) {
                            $('#processing_container').remove();
                            $('.billing-information').html($('#success_payment_template').html());
                            /**GA Push*/
                            @if(env('APP_ENV') != 'local')
                                dataLayer.push({'event': 'earlyaccess'});
                            @endif
                            if (data.login_redirect) {
                                setTimeout(function(){
                                    location.href = '/auth/login';
                                },1500);
                            }
                        }
                        else{
                            if (data.currency) {
                                currency = data.currency;
                                /**Show new prices*/
                                $('#subscription_switch_item').trigger('change');
                            }

                            $('#stripe_error').text(data.error).show();
                            $('#checkout_btn').show()
                            $('#processing_payment_container').hide();
                        }
                    },'json');
                }
            });
            return false;
        });

        var countDownDate = new Date('2021-11-10T19:31:04').getTime();
        var x = setInterval(function() {
            var now = (new Date()).getTime();
            var distance = countDownDate - now;
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            if (days > 0) {
                $('#days_left').text(days).show();
            }
            else{
                $('#days_left').hide();
            }

            $('#hours_left').text(hours).show();
            $('#minutes_left').text(minutes).show();
            $('#seconds_left').text(seconds).show();
            $('#time_counter').slideDown();

            if (distance < 0) {
                clearInterval(x);
                $('#time_counter').hide();
            }
            else{
                $('#time_counter').fadeIn();
            }
        }, 1000);

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
                        savedErrors[idx] = event.error.message;
                        $('#stripe_error').text(event.error.message).show();
                    } else {
                        savedErrors[idx] = null;
                        $('#stripe_error').hide().text('');
                        var nextError = Object.keys(savedErrors)
                            .sort()
                            .reduce(function(maybeFoundError, key) {
                                return maybeFoundError || savedErrors[key];
                            }, null);

                        if (nextError) {
                            error.innerText = nextError;
                            $('#stripe_error').text(nextError).show();
                        } else {
                            $('#stripe_error').hide().text('');
                        }
                    }
                });
            });
        }
    });

    var format_number = function(price){
        return price.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }
</script>
<script type="text/template" id="success_payment_template">
    <div class="alert alert-success">
        <h3>
            Your payment was successfully processed. You will receive a confirmation email shortly.
        </h3>
    </div>
</script>
@endsection
