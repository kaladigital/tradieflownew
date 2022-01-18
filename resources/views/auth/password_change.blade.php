@extends('layouts.landing')
@section('view_css')
<link rel="stylesheet" href="/js/noty/noty.css">
@endsection
@section('content')
    <main class="main not-logedin forgot-password-page">
        @include('auth.header')
        <div class="container contents-wrapper">
            <div class="row no-gutters">
                <div class="col-12 col-md-auto text-center">
                    <figure class="loc-icon">
                        <img src="/images/check-in-circle.svg" alt="Check in circle icon">
                    </figure>
                    <h1>Reset Your <span class="green-text">Password</span></h1>
                    <p>Please enter your new password into the following fields.</p>
                    <div class="form-wrap form-style2">
                        @include('elements.alerts')
                        {!! Form::open(['action' => 'Auth\AuthController@saveNewPassword', 'method' => 'post', 'id' => 'save_new_password_form', 'autocomplete' => 'off']) !!}
                            <div class="form-group">
                                {!! Form::password('password', ['required' => 'required', 'class' => 'form-control', 'placeholder' => 'New Password', 'id' => 'password']) !!}
                                {!! Form::label('password','Password') !!}
                                <button type="button" class="btn position-absolute showPassword show_password">
                                    <img src="/images/eye-icon.svg" class="eye_icon" data-type="gray" alt="View Password">
                                    <img src="/images/eye-green-icon.svg" class="eye_icon" data-type="green" alt="Hide Password" style="display:none;">
                                </button>
                            </div>
                            <div class="form-group">
                                {!! Form::password('new_password', ['required' => 'required', 'class' => 'form-control', 'placeholder' => 'Repeat Password', 'id' => 'new_password']) !!}
                                {!! Form::label('new_password','Repeat Password') !!}
                                <button type="button" class="btn position-absolute showPassword show_password">
                                    <img src="/images/eye-icon.svg" class="eye_icon" data-type="gray" alt="View Password">
                                    <img src="/images/eye-green-icon.svg" class="eye_icon" data-type="green" alt="Hide Password" style="display:none;">
                                </button>
                            </div>
                            <div id="loading_container" style="display: none;">
                                <img src="/images/loader.png" width="24px" class="float-left">
                                <span class="float-left ml-1 loader-text">Processing</span>
                            </div>
                            <div id="error_container" style="display: none;">
                                <div class="alert alert-danger" id="error_text"></div>
                            </div>
                            <button type="submit" class="btn btn-primary btn--sqr w-100" id="save_password_btn">Save New Password</button>
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
        $(document).on('click','.show_password',function(){
            var input_obj = $(this).closest('.form-group').find('.form-control');
            if (input_obj.attr('type') == 'password') {
                input_obj.attr('type','text');
                $(this).find('.eye_icon[data-type="gray"]').hide();
                $(this).find('.eye_icon[data-type="green"]').show();
            }
            else{
                input_obj.attr('type','password');
                $(this).find('.eye_icon[data-type="green"]').hide();
                $(this).find('.eye_icon[data-type="gray"]').show();
            }

            return false;
        });

        $(document).on('keyup','#password,#new_password',function(){
            $('#error_container').hide();
            $('#error_text').text();
            return false;
        });

        $(document).on('submit','#save_new_password_form',function(){
            if ($('#password').val() == $('#new_password').val()) {
                $('#save_password_btn').hide();
                $('#loading_container').show();
                return true;
            }
            else{
                $('#error_text').text('Passwords do not match');
                $('#error_container').show();
                return false;
            }
        });
    });
</script>
@endsection
