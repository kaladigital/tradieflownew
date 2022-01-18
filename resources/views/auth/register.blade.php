@extends('layouts.login')
@section('content')
    <main class="main onboarding-new">
        <header class="main-header row no-gutters align-items-center">
            <a href="/" class="logo-wrap mx-auto col-auto">
                <img src="/images/tradiereviews.svg" alt="TradieReviews logo">
            </a>
        </header>
        <section class="content-section create-account">
            <div class="container text-center">
                <h1>Create Your <span class="blue-text">Free Account</span></h1>
                <h3 class="lead-text">Get your reviews!</h3>
                <div class="form-style2 form-wrap mx-auto">
                    @include('elements.alerts')
                    {!! Form::open(['url' => '/free-trial', 'method' => 'post', 'id' => 'register_form', 'tabindex' => '500']) !!}
                        <div class="form-group">
                            <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                            <label for="email">Email</label>
                        </div>
                        <div id="loading_container" class="mb-5 clearfix" style="display:none;">
                            <img src="/images/loader.gif" width="24px" class="float-left">
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
    <script type="text/javascript">
        $(document).ready(function(){
            $(document).on('submit','#register_form',function(){
                $('#submit_btn').hide();
                $('#loading_container').show();
                $.post('/free-trial',{ email: $('#email').val() },function(data){
                    if (data.status) {
                        location.href = '/register/verify';
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
