@extends('layouts.landing')
@section('view_css')
<link rel="stylesheet" href="/js/noty/noty.css">
@endsection
@section('content')
    <main class="main not-logedin contact-us-main">
        <header class="header">
            <div class="container d-flex align-items-center navbar-expand-md">
                <a href="/" class="main-logo navbar-brand">
                    <img src="/images/main-logo.svg" alt="TradieFlow logo">
                </a>
                <a href="/auth/login" class="login-link ml-auto d-flex align-items-center">
                    <img src="/images/user-icon-green-circle.svg" alt="User icon" class="icon">
                    <span>Login</span>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse text-center" id="navbarContent">
                    <a href="/free-trial" class="btn btn-primary btn--sqr start-free-trial-btn animate-pulse">Start Free Trial</a>
                </div>
            </div>
        </header>
        <div class="container contents-wrapper contact-us">
            <div class="row no-gutters">
                <div class="col-12 col-md-6 content-col">
                    <div class="inner-container">
                        <h1>We’d Love To <span class="green-text">Hear From You</span>!</h1>
                        <div class="form-wrap form-style2">
                            {!! Form::open(['url' => 'contact-us', 'id' => 'contact_us_form']) !!}
                                <div class="form-group">
                                    {!! Form::text('name',null,['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Your Name', 'required' => 'required']) !!}
                                    {!! Form::label('name','Your Name') !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::email('email',null,['class' => 'form-control', 'id' => 'email', 'placeholder' => 'Your Email', 'required' => 'required']) !!}
                                    {!! Form::label('email','Your Email') !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::textarea('message',null,['class' => 'form-control', 'id' => 'message', 'placeholder' => 'Start typing...', 'required' => 'required', 'required' => 'required']) !!}
                                    {!! Form::label('message','Your Message') !!}
                                </div>
                                <div class="form-group">
                                    <div class="g-recaptcha mb-3 mt-3" data-sitekey="{{ env('GOOGLE_RECAPTCHA_SITE_KEY') }}"></div>
                                </div>
                                <div id="loading_container" style="display:none;">
                                    <img src="/images/loader.gif" width="24px" class="float-left">
                                    <span class="float-left ml-1 loader-text">Processing</span>
                                </div>
                                <button type="submit" id="submit_btn" class="btn btn-primary btn--sqr">Get in Touch</button>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 figure-col">
                    <div class="inner-container ml-md-auto">
                        <figure class="figure text-center">
                            <img src="/images/contact-us-figure.png" alt="Contact us figure">
                        </figure>
                        <h2>Or Conact Us via Email:</h2>
                        <div class="mail-info-cart cart d-flex align-items-center">
                            <figure class="cart-figure">
                                <img src="/images/envelope-icon.svg" alt="">
                            </figure>
                            <div class="info">
                                <p>Send us an email:</p>
                                <a href="mailto:info@tradieflow.co">info@tradieflow.co</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('view_script')
<script type="text/javascript" src="/js/noty/noty.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('submit','#contact_us_form',function(){
            var recaptcha_token = $('#g-recaptcha-response').val();
            $('#submit_btn').hide();
            $('#loading_container').show();
            $.post('/contact-us',{ name: $('#name').val(), email: $('#email').val(), message: $('#message').val(), recaptcha_token: recaptcha_token },function(data){
                $('#loading_container').hide();
                $('#submit_btn').show();
                grecaptcha.reset();
                if (data.status) {
                    new Noty({
                        type: 'success',
                        theme: 'metroui',
                        layout: 'topRight',
                        text: "<b>Thank you!</b> <br>We've received your message and will get back to you within 24 hours.",
                        timeout: 4000,
                        progressBar: false
                    }).show();
                    @if(env('APP_ENV') != 'local')
                        dataLayer.push({'event': 'contact_us_request'});
                    @endif
                    $('#contact_us_form')['0'].reset();
                }
                else{
                    new Noty({
                        type: 'error',
                        theme: 'metroui',
                        layout: 'topRight',
                        text: data.error,
                        timeout: 4000,
                        progressBar: false
                    }).show();
                }
            },'json');
            return false;
        });
    });
</script>
@endsection
