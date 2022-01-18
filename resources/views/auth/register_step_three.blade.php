@extends('layouts.login')
@section('content')
    <main class="main onboarding-new setup-step step-3">
        <div class="container-fluid">
            <div class="row no-gutters">
                <div class="col-12 col-lg-7 col-md-6 content-col">
                    <a href="/" class="logo-wrap">
                        <img src="/images/tradiereviews.svg" alt="TradieReviews logo">
                    </a>
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
                            <h1>
                                In which
                                <span class="blue-text">country do you work?</span>
                            </h1>
                            <h6 class="lead-text">
                                In case of trial accounts we can only give you an United States phone number. Later on you will be able to customize your phone number to American, Australian, British and Canadian ones.
                            </h6>
                            <div class="form-wrap form-style2">
                                {!! Form::model($user, ['url' => 'register/v/step/3', 'id' => 'register_step_form']) !!}
                                    <div class="country-select-wrap row">
                                        @foreach($countries as $item)
                                            <div class="col-12 col-sm-6">
                                                <a href="" class="btn select-country-item d-flex align-items-center {{ $country_code == $item->code ? 'active active-row-item' : '' }}" data-id="{{ $item->country_id }}">
                                                    <img src="/images/flags/png/{{ $item->code }}.png" alt="{{ $item->name }} flag" class="icon">
                                                    <span>{{ $item->name }}</span>
                                                </a>
                                            </div>
                                        @endforeach
                                        <div class="col-12">
                                            <a class="btn select-country-item d-flex align-items-center" data-id="">
                                                <img src="/images/oth.svg" alt="OTH icon" class="icon">
                                                <span>Other</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div id="loading_container" class="mb-5 clearfix" style="display:none;">
                                        <img src="/images/loader.gif" width="24px" class="float-left">
                                        <span class="float-left ml-1 loader-text">Processing</span>
                                    </div>
                                    <button type="submit" class="btn btn--sqr btn-primary" id="submit_btn">Take me to my workspace</button>
                                    <div class="text-center">
                                        <a href="/free-trial/step/2" class="go-back">Go Back</a>
                                    </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-5 col-md-6 figure-wrap d-flex">
                    <div class="testimonial-wrap text-center mx-auto">
                        <h3>“TradieFlow collects leads from your website and places customer information all into a singular
                            location, all the way to generating reviews and collecting payments.”</h3>
                        <div class="author"><cite>Joe Martin</cite><span>CEO of PlumbersHome</span></div>
                    </div>
                    <div class="spacer"></div>
                    <div class="figure responsive">
                        <img src="/images/figure-step-3.png" alt="Step page figure">
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('view_script')
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click','.select-country-item',function(){
            $('.select-country-item').not($(this)).removeClass('active active-row-item');
            $(this).addClass('active active-row-item');
            return false;
        });

        $(document).on('submit','#register_step_form',function(){
            if ($('.active-row-item').length) {
                $('#submit_btn').hide();
                $('#loading_container').show();
                $.post('/free-trial/step/3',{ country_id: $('.active-row-item').attr('data-id') },function(data){
                    if (data.status) {
                        @if(env('APP_ENV') != 'local')
                            dataLayer.push({'event': 'signup'});
                        @endif
                        location.href = '/send-review/setup';
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
    });
</script>
@endsection
