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
        <section class="content-section">
            <div class="container text-center">
                <h1>
                    Create Your
                    <span class="green-text">Free Account</span>
                </h1>
                <h3 class="lead-text">Get all the features and support! No credit card is required.</h3>
                <div class="form-style2 form-wrap mx-auto">
                    @include('elements.alerts')
                    {!! Form::open(['url' => 'register', 'id' => 'register_form']) !!}
                        <div class="form-group">
                            {!! Form::text('email',null,['class' => 'form-control', 'placeholder' => 'Email', 'required' => 'required', 'id' => 'email']) !!}
                            {!! Form::label('email','Email') !!}
                        </div>
                        <div id="loading_container" class="mb-5 clearfix" style="display:none;">
                            <img src="/images/loader.png" width="24px" class="float-left">
                            <span class="float-left ml-1 loader-text">Processing</span>
                        </div>
                        <button type="submit" id="submit_btn" class="btn btn--sqr btn-primary">Next</button>
                    {!! Form::close() !!}
                    <p>
                        By signing up, I agree to the TradieFlow
                        <a href="/terms">Terms</a> and
                        <a href="/privacy-policy">Privacy Policy</a>.
                    </p>
                </div>
                <p>
                    Already registered?
                    <a href="/auth/login">Log in</a>
                </p>
            </div>
        </section>
    </main>
@endsection
@section('view_script')
<script type="text/javascript" src="/js/noty/noty.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('submit','#register_form',function(){
            $('#submit_btn').hide();
            $('#loading_container').show();
            $.post('/free-trial',{ email: $('#email').val() },function(data){
                if (data.status) {
                    location.href = '/free-trial-2/verify';
                }
                else{
                    $('#loading_container').hide();
                    $('#submit_btn').show();
                    new Noty({
                        type: 'error',
                        theme: 'metroui',
                        layout: 'topRight',
                        text: data.error,
                        timeout: 2500,
                        progressBar: false
                    }).show();
                }
            },'json');
            return false;
        });
    });
</script>
@endsection
