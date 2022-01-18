@extends('layouts.landing')
@section('view_css')
<link rel="stylesheet" href="/js/noty/noty.css">
@endsection
@section('content')
<main class="main registration-page">
    <div class="container-fluid">
        <div class="row no-gutters">
            <div class="col-12 col-lg-auto form-section">
                <div class="inner-container">
                    <a href="/" class="main-logo">
                        <img src="/images/main-logo.svg" alt="">
                    </a>
                    <div class="login-content-wrapper">
                        <h1>Welcome to <span class="green-text">TradieFlow</span></h1>
                        <h6>Already registered? <a href="/auth/login">Log in</a></h6>
                        <div class="form-wrap login-form form-style2">
                            @include('elements.alerts')
                            {!! Form::open(['url' => 'free-trial', 'method' => 'post', 'id' => 'register_form', 'tabindex' => '500']) !!}
                                <div class="form-group">
                                    {!! Form::text('name', null, ['required' => 'required', 'class' => 'form-control', 'placeholder' => 'Full Name', 'id' => 'name']) !!}
                                    {!! Form::label('name','Full Name') !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::email('email', null, ['required' => 'required', 'class' => 'form-control', 'placeholder' => 'Email', 'id' => 'email']) !!}
                                    {!! Form::label('email','Email') !!}
                                </div>
                                <div class="form-group au_item" style="display:none;">
                                    {!! Form::text('address', null, ['class' => 'form-control', 'id' => 'address', 'placeholder' => 'Address']) !!}
                                    {!! Form::label('address','Address') !!}
                                </div>
                                <div class="form-group au_item" style="display:none;">
                                    {!! Form::text('state', null, ['class' => 'form-control', 'id' => 'state', 'placeholder' => 'State']) !!}
                                    {!! Form::label('state','State') !!}
                                </div>
                                <div class="form-group select-group">
                                    {!! Form::select('country_id', $all_countries, null, ['class' => 'form-control', 'id' => 'country_id', 'placeholder' => 'Select']) !!}
                                    {!! Form::label('country_id','Country') !!}
                                </div>
                                <div class="small-popup-box notification-box" id="signup_alert_container">
                                    <img class="icon" src="/images/phone-image.png" alt="Phone image">
                                    <h6>
                                        In case of trial accounts we only offer United States phone numbers. If you love TradieFlow and subscribe you will be able to use Australian, Canadian and British phone numbers too, to help your business grow locally.
                                    </h6>
                                    <button class="btn close-popup-box" id="close_alert">
                                        <img src="/images/close-gray.svg" alt="Close gray icon">
                                    </button>
                                </div>
                                @if($twilio_error)
                                    <div class="small-popup-box mb-3" id="registration_fail_error" style="display:block !important;">
                                        <img class="icon" src="/images/registration-small-box-img.png" alt="Small box img">
                                        <h6>
                                            <span class="red-text">Sorry,</span> {{ $twilio_error }}
                                        </h6>
                                        <button type="button" class="btn close-popup-box" id="close_fail_popup">
                                            <img src="/images/close-gray.svg" alt="Close gray icon">
                                        </button>
                                    </div>
                                @endif
                                <div id="register_loader" style="display:none;">
                                    <img src="/images/loader.png" width="24px" class="float-left">
                                    <span class="float-left ml-1 loader-text">Processing</span>
                                </div>
                                <button type="submit" class="btn submit-btn btn-primary btn--sqr" id="register_btn">Try for Free</button>
                                {!! Form::hidden('ref',$request['ref']) !!}
                            {!! Form::close() !!}
                            <p>
                                By registering you agree to our
                                <a href="/terms">Terms</a> and
                                <a href="/privacy-policy">Privacy Policy</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-auto thumb-section align-items-center">
                <div class="thumb-inner">
                    <img src="/images/register-image.png" alt="Registration background image"  class="figure-image">
                    <h1>The Easiest Way To Manage Your <span class="green-text">Trade Business</span></h1>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
@section('view_script')
<script type="text/javascript" src="/js/noty/noty.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        // var phone_country = $('#phone_country option:selected').text();
        // if (phone_country == 'Australia') {
        //     $('.au_item').show();
        // }

        // $(document).on('change','#phone_country',function(){
        //     if ($(this).val() == 'other') {
        //         $('.login-content-wrapper').addClass('not-available');
        //         $('.au_item').hide();
        //     }
        //     else{
        //         $('.login-content-wrapper').removeClass('not-available');
        //         var phone_country = $('#phone_country option:selected').text();
        //         if (phone_country == 'Australia') {
        //             $('.au_item').fadeIn();
        //         }
        //         else{
        //             $('.au_item').hide();
        //         }
        //     }
        //     return false;
        // });

        $(document).on('click','#close_alert',function(){
            $('#signup_alert_container').slideUp(function(){
                $(this).remove();
            });

            return false;
        });

        $(document).on('click','#close_fail_popup',function(){
            $('#registration_fail_error').remove();
            return false;
        });

        $(document).on('click','#close_other_popup',function(){
            $('.login-content-wrapper').removeClass('not-available');
            $('#phone_country').val('');
            return false;
        });

        $(document).on('click','#save_phone_location',function(){
            $('#prove_address_modal').modal('hide');
            $('#register_form').trigger('submit');
            return false;
        });

        $(document).on('submit','#register_form',function(){
            // var phone_country = $('#phone_country option:selected').text();
            // if (phone_country == 'Australia') {
            //     var address = $.trim($('#address').val());
            //     if (!address) {
            //         $('#address').focus();
            //         return false;
            //     }
            //
            //     var city = $('#city').val();
            //     if (!city) {
            //         $('#city').focus();
            //         return false;
            //     }
            //
            //     var zip = $('#zip_code').val();
            //     if (!zip) {
            //         $('#zip_code').focus();
            //         return false;
            //     }
            //
            //     var state = $('#state').val();
            //     if (!state) {
            //         $('#state').focus();
            //         return false;
            //     }
            //
            //     var country = $('#country_id').val();
            //     if (!country) {
            //         $('#country_id').focus();
            //         return false;
            //     }
            // }

            $('#register_btn').hide();
            $('#register_loader').show();
        });
    });
</script>
@endsection
