@extends('layouts.landing')
@section('view_css')
<link rel="stylesheet" href="/js/noty/noty.css">
@endsection
@section('content')
    <main class="main onboarding-new">
        <header class="main-header row no-gutters align-items-center">
            <a href="/" class="logo-wrap mx-auto col-auto">
                <img src="/images/main-logo.svg" alt="TradieFlow logo">
            </a>
        </header>
        <section class="content-section secure-account">
            <div class="container text-center">
                <h1>
                    Secure your Account <br>
                    <span class="green-text">With a Password</span>
                </h1>
                <h3 class="lead-text">Password must be 8 characters or longer.</h3>
                <div class="form-style2 form-wrap mx-auto">
                    {!! Form::open(['url' => 'register/v/password', 'id' => 'register_password_form']) !!}
                        <div class="form-group">
                            {!! Form::password('password', ['required' => 'required', 'class' => 'form-control', 'placeholder' => 'Password', 'id' => 'password']) !!}
                            {!! Form::label('password','Password') !!}
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
        </section>
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

        $(document).on('submit','#register_password_form',function(){
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
                $.post('/free-trial-2/password',{ password: password },function(data){
                    if (data.status) {
                        location.href = '/free-trial-2/step/1';
                    }
                    else{
                        if (data.redirect) {
                            location.href = data.redirect;
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
    });
</script>
@endsection
