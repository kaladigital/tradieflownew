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
        <section class="content-section verify-code">
            <div class="container text-center">
                <h1>Check Your <span class="green-text">Email for a Code</span></h1>
                <h3 class="lead-text">
                    We’ve sent a 4-digit code to <strong>{{ $signup_user['email'] }}</strong>. The code expires shortly, so please enter it soon.
                </h3>
                <div class="form-style2 form-wrap mx-auto">
                    {!! Form::open(['url' => '/free-trial-2/verify', 'id' => 'verify_password_form']) !!}
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
        </section>
    </main>
@endsection
@section('view_script')
<script type="text/javascript" src="/js/noty/noty.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
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
                $('#verify_password_form').trigger('submit');
            }

            return false;
        });

        $(document).on('submit','#verify_password_form',function(){
            var final_code = get_code_num();
            if (final_code) {
                $('#verify_password_btn').hide();
                $('#loading_container').show();
                $('#error_container').hide();
                $('#error_text').text('');
                $.post('/free-trial-2/verify',{ code: final_code },function(data){
                    $('#loading_container').hide();
                    if (data.status) {
                        location.href = '/free-trial-2/password';
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
@endsection
