@extends('layouts.login')
@section('content')
    <main class="main onboarding-new setup-step step-1">
        <div class="container-fluid">
            <div class="row no-gutters">
                <div class="col-12 col-lg-7 col-md-6 content-col">
                    <a href="/" class="logo-wrap">
                        <img src="/images/tradiereviews.svg" alt="TradieReviews logo">
                    </a>
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
                            <h1>What is your <span class="blue-text">Full Name?</span></h1>
                            <h6 class="lead-text">We will show your full name when you communicate with your clients.</h6>
                            <div class="form-style2 form-wrap">
                                {!! Form::model($user, ['url' => 'register/v/step/1', 'id' => 'register_step_form']) !!}
                                    <div class="form-group">
                                        {!! Form::text('name',null,['id' => 'name', 'class' => 'form-control', 'placeholder' => 'Full Name']) !!}
                                        {!! Form::label('name','Full Name') !!}
                                    </div>
                                    <div id="loading_container" class="mb-5 clearfix" style="display:none;">
                                        <img src="/images/loader.gif" width="24px" class="float-left">
                                        <span class="float-left ml-1 loader-text">Processing</span>
                                    </div>
                                    <button type="submit" id="submit_btn" class="btn btn--sqr btn-primary">Next</button>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-5 col-md-6 figure-wrap d-flex align-items-center justify-content-center">
                    <div class="figure responsive">
                        <img src="/images/figure-step-1.png" alt="Step page figure">
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('view_script')
    <script type="text/javascript">
        $(document).ready(function(){
            $(document).on('submit','#register_step_form',function(){
                var name = $.trim($('#name').val());
                if (name.length) {
                    $('#submit_btn').hide();
                    $('#loading_container').show();
                    $.post('/free-trial/step/1',{ name: name },function(data){
                        if (data.status) {
                            location.href = '/free-trial/step/2';
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
                else{
                    $('#name').focus();
                }
                return false;
            });
        });
    </script>
@endsection
