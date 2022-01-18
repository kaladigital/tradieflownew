@extends('layouts.landing')
@section('view_css')
<link rel="stylesheet" href="/js/noty/noty.css">
@endsection
@section('content')
<main class="main login-page">
    <div class="container-fluid">
        <div class="row no-gutters">
            <div class="col-12 col-lg-auto form-section">
                <div class="inner-container">
                    <a href="/" class="main-logo">
                        <img src="/images/main-logo.svg" alt="">
                    </a>
                    <div class="login-content-wrapper">
                        <h1>Log <span class="green-text">In</span>.</h1>
                        <h6>New to TradieFlow? <a href="/free-trial">Register Now</a></h6>
                        <div class="form-wrap login-form form-style2">
                            @include('elements.alerts')
                            {!! Form::open(['action' => 'Auth\AuthController@postLogin', 'method' => 'post', 'id' => 'login_form']) !!}
                                <div class="form-group">
                                    {!! Form::email('email', null, ['required' => 'required', 'class' => 'form-control', 'placeholder' => 'Email', 'id' => 'email']) !!}
                                    {!! Form::label('email','Email') !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::password('password', ['required' => 'required', 'class' => 'form-control', 'placeholder' => 'Password', 'id' => 'password']) !!}
                                    {!! Form::label('password','Password') !!}
                                    <button type="button" class="btn position-absolute showPassword" id="show_password">
                                        <img src="/images/eye-icon.svg" class="eye_icon" data-type="gray" alt="Eye icon">
                                        <img src="/images/eye-green-icon.svg" class="eye_icon" data-type="green" alt="Eye icon" style="display:none;">
                                    </button>
                                </div>

                                <button type="submit" class="btn btn-primary btn--sqr">Log In</button>
                            {!! Form::close() !!}
                            <p>
                                <a href="/auth/forgot-password">Forgot password?</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-auto thumb-section">
                <div class="action-buttons">
                    <div class="container d-flex justify-contents-center">
                        <div class="button-item">
                            <button class="btn">
                                <img src="/images/calendar-polygon.svg" alt="">
                            </button>
                        </div>
                        <div class="button-item">
                            <button class="btn">
                                <img src="/images/user-polygon.svg" alt="">
                            </button>
                        </div>
                        <div class="button-item">
                            <button class="btn">
                                <img src="/images/message-polygon.svg" alt="">
                            </button>
                        </div>
                        <div class="button-item">
                            <button class="btn">
                                <img src="/images/phone-polygon.svg" alt="">
                            </button>
                        </div>
                        <div class="button-item">
                            <button class="btn">
                                <img src="/images/chat-polygon.svg" alt="">
                            </button>
                        </div>
                        <div class="button-item">
                            <button class="btn">
                                <img src="/images/dolar-polygon.svg" alt="">
                            </button>
                        </div>
                        <div class="button-item">
                            <button class="btn">
                                <img src="/images/form-polygon.svg" alt="">
                            </button>
                        </div>
                        <div class="button-item">
                            <button class="btn">
                                <img src="/images/money-polygon.svg" alt="">
                            </button>
                        </div>
                        <div class="button-item">
                            <button class="btn">
                                <img src="/images/timer-polygon.svg" alt="">
                            </button></div>
                        <div class="button-item">
                            <button class="btn">
                                <img src="/images/payment-polygon.svg" alt="">
                            </button>
                        </div>
                        <div class="button-item">
                            <button class="btn">
                                <img src="/images/camera-polygon.svg" alt="">
                            </button>
                        </div>
                        <div class="button-item">
                            <button class="btn">
                                <img src="/images/tradieflow-fvicon-polygon.svg" alt="">
                            </button>
                        </div>
                        <div class="button-item">
                            <button class="btn">
                                <img src="/images/group-polygon.svg" alt="">
                            </button>
                        </div>
                        <div class="button-item">
                            <button class="btn">
                                <img src="/images/profile-polygon.svg" alt="">
                            </button>
                        </div>
                        <div class="button-item">
                            <button class="btn">
                                <img src="/images/group-in-mobile-polygon.svg" alt="">
                            </button>
                        </div>
                    </div>
                </div>
                <img src="/images/login-bg.webp" alt="Login background image" class="figure-image">
            </div>
        </div>
    </div>
</main>
@endsection
@section('view_script')
<script type="text/javascript" src="/js/noty/noty.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
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
    });
</script>
@endsection
