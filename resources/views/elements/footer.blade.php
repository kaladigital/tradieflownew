<footer class="main-footer" style="{{ $hide_footer ? 'display:none;' : '' }}">
    <div class="container">
        <div class="newsletter-row row no-gutters align-items-center">
            <div class="col-12 col-md-6">
                <h2>Signup Newsletter:</h2>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-wrap ml-auto">
                    <form id="newsletter_form" autocomplete="off">
                        <div class="form-group">
                            <input type="email" class="form-control" id="newsletter_email" placeholder="Your Email" required="required">
                            <label for="newsletter_email">Your Email</label>
                            <button type="submit" class="btn position-absolute showPassword align-items-center d-flex">
                                <span>Join</span>
                                <img src="/landing_media/images/arrow-right-white.png" alt="Arrow right white">
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="copyright-row row no-gutters">
            <div class="col-12 col-sm-4">
                <p class="text-center text-sm-left">© 2021 TradieFlow</p>
            </div>
            <div class="col-12 col-sm-8 text-center text-sm-right footer-links">
                <a href="/contact-us">Contact Us</a>
                <a href="/cookies">Cookies</a>
                <a href="/terms">Terms</a>
                <a href="/privacy-policy">Privacy Policy</a>
            </div>
        </div>
    </div>
</footer>
@section('view_script_ext')
    <script type="text/javascript">
        $(document).ready(function(){
            $(document).on('submit','#newsletter_form',function(){
                var email = $('#newsletter_email').val();
                $.post('/newsletter/subscribe',{ email: email },function(data){
                    if (data.status) {
                        new Noty({
                            type: 'success',
                            theme: 'metroui',
                            layout: 'topRight',
                            text: 'Successfully subscribed to newsletter',
                            timeout: 2500,
                            progressBar: false
                        }).show();
                        if (data.subscribed) {
                            @if(env('APP_ENV') != 'local')
                                dataLayer.push({'event': 'newsletter_subscribe'});
                            @endif
                        }
                        setTimeout(function(){
                            location.href = '/newsletter/subscribed';
                        },1000);
                    }
                    else{
                        new Noty({
                            type: 'info',
                            theme: 'metroui',
                            layout: 'topRight',
                            text: data.error,
                            timeout: 2500,
                            progressBar: false
                        }).show();
                    }
                });
                return false;
            });
        });
    </script>
@endsection
