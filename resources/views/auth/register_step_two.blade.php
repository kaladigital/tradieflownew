@extends('layouts.login')
@section('content')
    <main class="main onboarding-new setup-step step-2">
        <div class="container-fluid">
            <div class="row no-gutters">
                <div class="col-12 col-lg-7 col-md-6 content-col">
                    <a href="/" class="logo-wrap">
                        <img src="/images/tradiereviews.svg" alt="TradieReviews logo">
                    </a>
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
                            <h1>
                                What is the
                                <span class="blue-text">name of your Company?</span>
                            </h1>
                            <h6 class="lead-text">
                                We will automatically place your company name in documents such as quotes and invoices.
                            </h6>
                            <div class="form-style2 form-wrap">
                                {!! Form::model($user, ['url' => 'free-trial/step/2', 'id' => 'register_step_form']) !!}
                                    <div class="form-group">
                                        {!! Form::text('company', null, ['required' => 'required', 'class' => 'form-control', 'placeholder' => 'Company Name', 'id' => 'company']) !!}
                                        {!! Form::label('company','Company Name') !!}
                                    </div>
                                    <div id="loading_container" class="mb-5 clearfix" style="display:none;">
                                        <img src="/images/loader.gif" width="24px" class="float-left">
                                        <span class="float-left ml-1 loader-text">Processing</span>
                                    </div>
                                    <button type="submit" id="submit_btn" class="btn btn--sqr btn-primary">Next</button>
                                    <div class="text-center">
                                        <a href="/free-trial/step/1" class="go-back">Go Back</a>
                                    </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-5 col-md-6 figure-wrap d-flex align-items-center justify-content-center">
                    <div class="figure responsive">
                        <img src="/images/figure-step-2.png" alt="Step page figure">
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
            var company = $.trim($('#company').val());
            if (company.length) {
                $('#submit_btn').hide();
                $('#loading_container').show();
                $.post('/free-trial/step/2',{ company: company },function(data){
                    if (data.status) {
                        location.href = '/free-trial/step/3';
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
                $('#company').focus();
            }
            return false;
        });
    });
</script>
@endsection
