@extends('layouts.landing')
@section('view_css')
<link rel="stylesheet" href="{{ $app_cdn_url }}/js/noty/noty.css">
@endsection
@section('content')
    <main class="main not-logedin forgot-password-page">
        @include('auth.header')
        <div class="container contents-wrapper">
            <div class="row no-gutters">
                <div class="col-12 col-md-auto text-center">
                    <figure class="loc-icon">
                        <img src="/images/lock-icon.svg" alt="Lock icon">
                    </figure>
                    <h1>Forgot <span class="green-text">Password</span>?</h1>
                    <p>Just enter the email address you registered with and we’ll send you a recovery code to reset your password.</p>
                    @include('elements.alerts')
                    <div class="form-wrap form-style2">
                        {!! Form::open(['url' => 'auth/forgot-password', 'method' => 'post', 'id' => 'forgot_password_form', 'tabindex' => '500']) !!}
                            <div class="form-group">
                                {!! Form::email('email', null, ['required' => 'required', 'class' => 'form-control', 'placeholder' => 'Email', 'id' => 'email']) !!}
                                {!! Form::label('email','Email') !!}
                            </div>
                            <div id="loading_container" style="display: none;">
                                <img src="/images/loader.png" width="24px" class="float-left">
                                <span class="float-left ml-1 loader-text">Processing</span>
                            </div>
                            <button type="submit" class="btn btn-primary btn--sqr w-100" id="forgot_password_btn">Send Verification Code</button>
                        {!! Form::close() !!}
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
        $(document).on('submit','#forgot_password_form',function(){
            $('#forgot_password_btn').hide();
            $('#loading_container').show();
            return true;
        });
    });
</script>
@endsection
