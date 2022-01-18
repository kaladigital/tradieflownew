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
                        <img src="/images/open-file.svg" alt="Open file icon">
                    </figure>
                    <h1>Verify <span class="green-text">Code</span></h1>
                    <p>Please type the 4-digit verification code that we have sent you in an email.</p>
                    <div class="form-wrap form-style2 verification-code-form">
                        @include('elements.alerts')
                        {!! Form::open(['url' => 'auth/forgot-password/verify', 'id' => 'verify_password_form', 'autocomplete' => 'off']) !!}
                            <div class="form-row">
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
                            <div id="loading_container" style="display: none;">
                                <img src="/images/loader.png" width="24px" class="float-left">
                                <span class="float-left ml-1 loader-text">Processing</span>
                            </div>
                            <div id="error_container" style="display: none;">
                                <div class="alert alert-danger" id="error_text"></div>
                            </div>
                            <button type="submit" class="btn btn-primary btn--sqr w-100" id="verify_password_btn">Next</button>
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
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}});
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
                $.post('/auth/forgot-password/verify',{ code: final_code },function(data){
                    $('#loading_container').hide();
                    if (data.status) {
                        location.href = '/auth/reset-password';
                    }
                    else{
                        if (data.reload) {
                            location.href = '/auth/login';
                        }
                        else{
                            $('#error_text').text(data.error);
                            $('#error_container').show();
                            $('#verify_password_btn').show();
                        }
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
