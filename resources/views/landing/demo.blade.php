@extends('layouts.landing')
@section('content')
    <section class="demo-sect second-demo-page">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-12">
                    <h2>Get a <span class="green">sneak-peak</span> at the world's first end-to-end <span class="green">trade business solution</span></h2>
                    <ul class="feature-list">
                        <li>Get a guided tour of the easiest way to manage your trade business</li>
                        <li>Save time and gain insights - manage your entire customer base on one singular place</li>
                        <li>Learn how to send quotes, book jobs and send invoices, and all directly from your phone!</li>
                    </ul>
                    <div class="main-img">
                        <div class="promo-text">Book Your FREE <span class="bg-green">30-Minute Demo</span></div>
                        <section id="calendar_container"></section>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="promo-section" id="promo">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-8 offset-lg-2">
                    <h2 class="text-center">How <span class="green-text">TradieFlow Helps Grow Your</span> Trade, Contracting Or Home Improvement <span class="green-text">Business</span></h2>
                    <div class="video-container">
                        <div class="check-intro-text">
                            Check Our <span class="green-bg">Intro Video</span>
                        </div>
                        <div class="circle"></div>
                        <div class="video-player">
                            <div class="video-thumb">
                                <img src="/landing_media/img/_src/png/video-thumb.png" alt="Video thumbnial">
                            </div>
                            <button class="btn play-video" id="play_video">
                                <img src="/landing_media/img/_src/svg/video-play-icon.svg" alt="Video play icon">
                            </button>
                            <video class="promo-video" controls id="video_player">
                                <source src="/landing_media/video/TradieFlow.mp4" type="video/mp4">
                                Sorry, your browser doesn't support embedded videos.
                            </video>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('elements.footer',['hide_footer' => false])
@endsection
@section('view_script')
<script type="text/javascript">
    $(document).ready(function(){
        $('#calendar_container').html('<iframe src="https://calendly.com/carltradieflowco/30min?hide_gdpr_banner=1&embed_domain=tradiedigital.co&amp;embed_type=Inline&amp;text_color=333332&amp;primary_color=ff9523&amp" scrolling="no" width="1000" height="810"></iframe>');
        $(document).on('click','#play_video',function(){
            $('.video-player').addClass('playing');
            $('#video_player')[0].play();
            return false;
        });
    });
    var header = document.getElementById("main-head");
    var sticky = header.offsetTop;
    window.onscroll = function() {
        if (window.pageYOffset > sticky) {

            header.classList.add("sticky");
        } else {
            header.classList.remove("sticky");
        }
    };
</script>
@endsection
