@extends('layouts.landing')
@section('view_css')
<link rel="stylesheet" href="/js/select2/css/select2.min.css">
@endsection
@section('content')
<script type="text/javascript">
    document.getElementsByTagName('body')['0'].classList.add('modal-open');
</script>
<main class="main">
    <img src="/images/signup-bg.png" alt="Page bg">
</main>
<div class="modal onboarding-new-modal version-b" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="modal_container">
            <div class="modal-body create-account">
                <div class="row">
                    <div class="col-12 col-md-auto content-col">
                        <div class="content-section">
                            <div class="content-inner">
                                <h3>
                                    Create Your
                                    <span class="green-text">Free Account</span>
                                </h3>
                                <p class="lead-text">Get all the features and support! No credit card is required.</p>
                                <div class="form-style2 form-wrap">
                                    {!! Form::open(['url' => 'register', 'id' => 'register_form']) !!}
                                        <div class="form-group">
                                            {!! Form::text('email',null,['class' => 'form-control', 'placeholder' => 'Email', 'required' => 'required', 'id' => 'email']) !!}
                                            {!! Form::label('email','Email') !!}
                                        </div>
                                        <div id="loading_container" class="mb-5 clearfix" style="display:none;">
                                            <img src="/images/loader.png" width="24px" class="float-left">
                                            <span class="float-left ml-1 loader-text">Processing</span>
                                        </div>
                                        <button type="submit" id="submit_btn" class="btn btn--sqr btn-primary">Next</button>
                                    {!! Form::close() !!}
                                    <p>
                                        By signing up, I agree to the TradieFlow
                                        <a href="/terms" target="_blank">Terms</a>
                                        and
                                        <a href="/privacy-policy">Privacy Policy.</a>
                                    </p>
                                </div>
                                <p>
                                    Already registered? <a href="/auth/login">Log in</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-center col-md-auto figure-wrap d-flex justify-content-center">
                        <div class="figure responsive d-none d-md-block">
                            <img src="/images/onboarding/version-b-create-account-bg.png" alt="Step page figure">
                        </div>
                        <div class="spacer"></div>
                        <h6>Join the community that uses the most effective tools!</h6>
                        <div class="figure responsive d-md-none pb-0">
                            <img src="/landing_media/images/create-account-bg-mobi.png" alt="Step page figure">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('view_script')
<script type="text/javascript" src="/js/select2/js/select2.min.js"></script>
<script type="text/javascript" src="/js/underscore-min.js"></script>
<script type="text/javascript" src="/js/noty/noty.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        window.signup_user = {
            name: '',
            company: ''
        }
        /**Register*/
        $(document).on('submit','#register_form',function(){
            $('#submit_btn').hide();
            $('#loading_container').show();
            var email = $('#email').val();
            $.post('/free-trial',{ email: email },function(data){
                if (data.status) {
                    $('#modal_container').fadeOut(function() {
                        $(this).html(_.template($('#verify_account_template').html())({
                            email: email
                        })).fadeIn();
                    });
                }
                else{
                    $('#loading_container').hide();
                    $('#submit_btn').show();
                    new Noty({
                        type: 'error',
                        theme: 'metroui',
                        layout: 'topRight',
                        text: data.error,
                        timeout: 2500,
                        progressBar: false
                    }).show();
                }
            },'json');
            return false;
        });

        /**Verify Email*/
        $(document).on('keyup','.code_item',function(){
            if ($(this).val()) {
                var code_num = $(this).attr('data-num');
                var next_num = parseInt(code_num) + 1;
                if (next_num <= 4) {
                    $('.code_item[data-num="' + next_num + '"]').focus();
                }
            }

            var final_code = get_code_num();
            if (final_code) {
                $('#verify_email_form').trigger('submit');
            }

            return false;
        });

        $(document).on('submit','#verify_email_form',function(){
            var final_code = get_code_num();
            if (final_code) {
                $('#verify_password_btn').hide();
                $('#loading_container').show();
                $('#error_container').hide();
                $('#error_text').text('');
                $.post('/free-trial-2/verify',{ code: final_code },function(data){
                    $('#loading_container').hide();
                    if (data.status) {
                        $('#modal_container').fadeOut(function() {
                            $(this).html(_.template($('#set_password_template').html())({
                                name: signup_user.name
                            })).fadeIn();
                        });
                    }
                    else{
                        $('#error_text').text(data.error);
                        $('#error_container').show();
                        $('#verify_password_btn').show();
                    }
                },'json');
            }

            return false;
        });

        /**Set Password*/
        $(document).on('click','#show_password',function(){
            if ($('#password').attr('type') == 'password') {
                $('#password').attr('type','text');
                $(this).find('.eye_icon[data-type="gray"]').hide();
                $(this).find('.eye_icon[data-type="green"]').show();
            }
            else{
                $('#password').attr('type','password');
                $(this).find('.eye_icon[data-type="green"]').hide();
                $(this).find('.eye_icon[data-type="gray"]').show();
            }
            return false;
        });

        $(document).on('submit','#register_password_form',function(){
            var name = $('#name').val();
            var password = $('#password').val();
            if (password.length < 8) {
                new Noty({
                    type: 'error',
                    theme: 'metroui',
                    layout: 'topRight',
                    text: 'Please specify password with at least 8 character length',
                    timeout: 2500,
                    progressBar: false
                }).show();
            }
            else{
                $('#submit_btn').hide();
                $('#loading_container').show();
                $.post('/free-trial-2/password',{ password: password, name: name },function(data){
                    if (data.status) {
                        signup_user.name = name;
                        $('#modal_container').fadeOut(function() {
                            $(this).html(_.template($('#set_company_template').html())({
                                company: signup_user.company
                            })).fadeIn();
                        });
                    }
                    else{
                        if (data.redirect) {
                            location.href = '/free-trial-2';
                        }
                        else{
                            new Noty({
                                type: 'error',
                                theme: 'metroui',
                                layout: 'topRight',
                                text: data.error,
                                timeout: 2500,
                                progressBar: false
                            }).show();
                        }
                    }
                },'json');
            }
            return false;
        });

        /**Go Back logic*/
        $(document).on('click','.go-back',function(){
            var step = $(this).attr('data-step');
            switch (step) {
                case '1':
                    $('#modal_container').fadeOut(function() {
                        $(this).html(_.template($('#set_password_template').html())({
                            name: signup_user.name
                        })).fadeIn();
                    });
                break;
                case '2':
                    $('#modal_container').fadeOut(function() {
                        $(this).html(_.template($('#set_company_template').html())({
                            company: signup_user.company
                        })).fadeIn();
                    });
                break;
            }
            return false;
        });

        /**Set Company*/
        $(document).on('submit','#register_company_form',function(){
            var company = $.trim($('#company').val());
            if (company.length) {
                $('#submit_btn').hide();
                $('#loading_container').show();
                $.post('/free-trial-2/step/2',{ company: company },function(data){
                    if (data.status) {
                        signup_user.company = company;
                        $('#modal_container').fadeOut(function() {
                            $(this).html(_.template($('#set_country_template').html())).fadeIn();
                        });
                    }
                    else{
                        if (data.redirect) {
                            location.href = '/free-trial-2';
                        }
                        else{
                            new Noty({
                                type: 'error',
                                theme: 'metroui',
                                layout: 'topRight',
                                text: data.error,
                                timeout: 2500,
                                progressBar: false
                            }).show();
                        }
                    }
                },'json');
            }
            else{
                $('#company').focus();
            }
            return false;
        });

        /**Set Country*/
        $(document).on('click','.select-country-item',function(){
            $('.select-country-item').not($(this)).removeClass('active active-row-item');
            $(this).addClass('active active-row-item');
            return false;
        });

        $(document).on('submit','#register_country_form',function(){
            if ($('.active-row-item').length) {
                $('#submit_btn').hide();
                $('#loading_container').show();
                $.post('/free-trial-2/step/3',{ country_id: $('.active-row-item').attr('data-id') },function(data){
                    if (data.status) {
                        @if(env('APP_ENV') != 'local')
                            dataLayer.push({'event': 'signup'});
                        @endif
                        location.href = '/onboarding';
                    }
                    else{
                        if (data.redirect) {
                            location.href = '/free-trial-2';
                        }
                        else{
                            new Noty({
                                type: 'error',
                                theme: 'metroui',
                                layout: 'topRight',
                                text: data.error,
                                timeout: 2500,
                                progressBar: false
                            }).show();
                        }
                    }
                },'json');
            }
            else{
                new Noty({
                    type: 'error',
                    theme: 'metroui',
                    layout: 'topRight',
                    text: 'Please select one of options to continue',
                    timeout: 2500,
                    progressBar: false
                }).show();
            }
            return false;
        });

        $('#dashboard_filter_dropdown').select2();
    });

    var get_code_num = function(){
        var code1 = $.trim($('.code_item[data-num="1"]').val());
        var code2 = $.trim($('.code_item[data-num="2"]').val());
        var code3 = $.trim($('.code_item[data-num="3"]').val());
        var code4 = $.trim($('.code_item[data-num="4"]').val());
        if (code1.length && code2.length && code3.length && code4.length) {
            return code1 + '' + code2 + '' + code3 + '' + code4;
        }

        return null;
    }
</script>
<script type="text/template" id="verify_account_template">
    <div class="modal-body verify-code">
        <div class="row">
            <div class="col-12 col-md-auto content-col">
                <div class="content-section">
                    <div class="content-inner">
                        <h3>Check Your <span class="green-text">Email for a Code</span></h3>
                        <p class="lead-text">
                            We’ve sent a 4-digit code to <strong><%= email %></strong>. The code expires shortly, so please enter it soon.
                        </p>
                        <div class="form-style2 form-wrap">
                            {!! Form::open(['url' => '/free-trial-2/verify', 'id' => 'verify_email_form']) !!}
                                <div class="verify-code-wrap">
                                    <div class="form-group-row form-row">
                                        <div class="form-group col-3">
                                            {!! Form::text('code1',null,['class' => 'form-control code_item', 'data-num' => '1', 'maxlength' => '1', 'required' => 'required']) !!}
                                        </div>
                                        <div class="form-group col-3">
                                            {!! Form::text('code2',null,['class' => 'form-control code_item', 'data-num' => '2', 'maxlength' => '1', 'required' => 'required']) !!}
                                        </div>
                                        <div class="form-group col-3">
                                            {!! Form::text('code3',null,['class' => 'form-control code_item', 'data-num' => '3', 'maxlength' => '1', 'required' => 'required']) !!}
                                        </div>
                                        <div class="form-group col-3">
                                            {!! Form::text('code4',null,['class' => 'form-control code_item', 'data-num' => '4', 'maxlength' => '1', 'required' => 'required']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div id="loading_container" style="display: none;">
                                    <img src="/images/loader.png" width="24px" class="float-left">
                                    <span class="float-left ml-1 loader-text">Processing</span>
                                </div>
                                <div id="error_container" style="display: none;">
                                    <div class="alert alert-danger" id="error_text"></div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                        <div class="action-row">
                            <div class="link-wrap d-flex justify-content-center align-items-center">
                                <a href="https://mail.google.com/mail/u/0/?ogbl" class="open-with-gmail d-flex align-items-center" target="_blank">
                                    <img src="/images/onboarding/gmail.svg" alt="Gmail icon" class="icon">
                                    <span>Open Gmail</span>
                                </a>
                                <a href="https://outlook.live.com/mail/0/inbox" class="open-with-outlook d-flex align-items-center" target="_blank">
                                    <img src="/images/onboarding/outlook.svg" alt="Outlook icon" class="icon">
                                    <span>Open Outlook</span>
                                </a>
                            </div>
                            <p>Can’t find your code? Check your spam folder!</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 text-center col-md-auto figure-wrap d-flex justify-content-center">
                <div class="figure responsive">
                    <img src="/images/onboarding/version-b-verify-code-bg.png" alt="Verify code figure">
                </div>
                <div class="spacer"></div>
                <h6>100% secure communication and transactions!</h6>
            </div>
        </div>
    </div>
</script>
<script type="text/template" id="set_password_template">
    <div class="modal-body">
        <div class="row">
            <div class="col-12 col-md-auto content-col">
                <div class="content-section">
                    <div class="step-section d-flex">
                        <span>Step 1 of 3</span>
                        <div class="step-progress d-flex align-items-center">
                            <button class="btn btn-step step-1 active"></button>
                            <button class="btn btn-step step-2"></button>
                            <button class="btn btn-step step-3"></button>
                        </div>
                    </div>
                    <div class="content-inner">
                        <h3>Set Up <span class="green-text">Your Account</span></h3>
                        <p class="lead-text">We will show your full name when you communicate with your clients.</p>
                        <div class="form-style2 form-wrap">
                            {!! Form::open(['url' => 'register/v/password', 'id' => 'register_password_form']) !!}
                                <div class="form-group">
                                    <input type="text" class="form-control" required="required" placeholder="Full Name" id="name" value="<%= name %>">
                                    <label for="name">Full Name</label>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" required="required" placeholder="Password" id="password">
                                    <label for="password">Password</label>
                                    <button type="button" class="btn position-absolute showPassword" id="show_password">
                                        <img src="/images/eye-icon.svg" class="eye_icon" data-type="gray" alt="Eye icon">
                                        <img src="/images/eye-green-icon.svg" class="eye_icon" data-type="green" alt="Eye icon" style="display:none;">
                                    </button>
                                </div>
                                <div id="loading_container" class="mb-5 clearfix" style="display:none;">
                                    <img src="/images/loader.png" width="24px" class="float-left">
                                    <span class="float-left ml-1 loader-text">Processing</span>
                                </div>
                                <button class="btn btn--sqr btn-primary" id="submit_btn" type="submit">Next</button>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 text-center col-md-auto figure-wrap d-flex justify-content-center">
                <div class="figure responsive">
                    <img src="/images/onboarding/modal-figure-step-1.png" alt="Step page figure">
                </div>
                <h6>Manage all communication through TradieFlow and get more leads!</h6>
            </div>
        </div>
    </div>
</script>
<script type="text/template" id="set_company_template">
    <div class="modal-body">
        <div class="row">
            <div class="col-12 col-md-auto content-col">
                <div class="content-section">
                    <div class="step-section d-flex">
                        <span>Step 2 of 3</span>
                        <div class="step-progress d-flex align-items-center">
                            <button class="btn btn-step step-1 completed"></button>
                            <button class="btn btn-step step-2 active"></button>
                            <button class="btn btn-step step-3"></button>
                        </div>
                    </div>
                    <div class="content-inner">
                        <h3>What is the <span class="green-text">name of your Company?</span></h3>
                        <p class="lead-text">We will automatically place your company name in documents such as
                            quotes and invoices.</p>
                        <div class="form-style2 form-wrap">
                            {!! Form::open(['url' => 'register/v/step/2', 'id' => 'register_company_form']) !!}
                                <div class="form-group">
                                    <input type="text" class="form-control" id="company" placeholder="Company Name" required="required" value="<%= company %>">
                                    <label for="company">Company Name</label>
                                </div>
                                <div id="loading_container" class="mb-5 clearfix" style="display:none;">
                                    <img src="/images/loader.png" width="24px" class="float-left">
                                    <span class="float-left ml-1 loader-text">Processing</span>
                                </div>
                                <button type="submit" id="submit_btn" class="btn btn--sqr btn-primary">Next</button>
                                <div class="text-center">
                                    <a href="#" class="go-back" data-step="1">Go Back</a>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 text-center col-md-auto figure-wrap d-flex justify-content-center">
                <div class="figure responsive">
                    <img src="/images/onboarding/modal-figure-step-2.png" alt="Step page figure">
                </div>
                <h6>Automatically generate documents!</h6>
            </div>
        </div>
    </div>
</script>
<script type="text/template" id="set_country_template">
    <div class="modal-body">
        <div class="row">
            <div class="col-12 col-md-auto content-col">
                <div class="content-section">
                    <div class="step-section d-flex">
                        <span>Step 3 of 3</span>
                        <div class="step-progress d-flex align-items-center">
                            <button class="btn btn-step step-1 completed"></button>
                            <button class="btn btn-step step-2 completed"></button>
                            <button class="btn btn-step step-3 active"></button>
                        </div>
                    </div>
                    <div class="content-inner">
                        <h3>Where do <span class="green-text">you work?</span></h3>
                        <p class="lead-text">In case of trial accounts we can only give you an United States phone
                            number. Later on you will be able to customize your phone number to American, Australian,
                            British and Canadian ones.</p>
                        <div class="form-style2 form-wrap">
                            {!! Form::open(['url' => 'register/v/step/3', 'id' => 'register_country_form']) !!}
                                <div class="country-select-wrap row">
                                    @foreach($countries as $item)
                                        <div class="col-12 col-sm-6">
                                            <a href="" class="btn select-country-item d-flex align-items-center" data-id="{{ $item->country_id }}">
                                                <img src="/images/flags/svg/{{ $item->code }}.svg" alt="{{ $item->name }} flag" class="icon">
                                                <span>{{ $item->name }}</span>
                                            </a>
                                        </div>
                                    @endforeach
                                    <div class="col-12">
                                        <a class="btn select-country-item d-flex align-items-center" data-id="">
                                            <img src="/images/onboarding/oth.svg" alt="OTH icon" class="icon">
                                            <span>Other</span>
                                        </a>
                                    </div>
                                </div>
                                <div id="loading_container" class="mt-4 mb-5 clearfix" style="display:none;">
                                    <img src="/images/loader.png" width="24px" class="float-left">
                                    <span class="float-left ml-1 loader-text">Processing</span>
                                </div>
                                <button type="submit" class="btn btn--sqr btn-primary" id="submit_btn">Take me to my workspace</button>
                                <div class="text-center">
                                    <a href="" class="go-back" data-step="2">Go Back</a>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 text-center col-md-auto figure-wrap d-flex justify-content-center">
                <div class="figure responsive">
                    <img src="/images/onboarding/modal-figure-step-3.png" alt="Step page figure">
                </div>
                <h6>Configure your client communication channels!</h6>
            </div>
        </div>
    </div>
</script>
@endsection
