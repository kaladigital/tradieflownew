@extends('layouts.landing')
@section('content')
    <section class="top-sect" id="home">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <div class="descr">
                        <h1>{{ $pre_tagline }} <span class="green">{{ $tagline }}</span></h1>
                        <p>
                            Leave our after hours admin work behind. TradieFlow handles your leads, schedules quotes, books in your jobs, sends invoices, and collects payment all from the very same app.
                        </p>
                        <a href="/early-access" class="btn btn-default btn-md">Get Early Access</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="about-us-sect" id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12 col-12">
                    <div class="descr">
                        <span class="sect-title">About Us</span>
                        <h2>Our Aim Is To Help <br> <span class="green">Millions Of Trade</span>,<br> Contracting and Home <br>
                            Improvement <span class="green">Business Grow</span></h2>
                        <a href="/free-demo" class="btn btn-default btn-md hidden-xs hidden-sm hidden-md">Free Demo</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 col-12">
                    <blockquote>
                        <p class="info">
                            TradieFlow helps hundreds of Trade, contracting and home improvement business save time on after hours
                            paperwork.
                        </p>
                        <p>
                            We built TradieFlow to automate your most time-sucking tasks. Like handling leads, booking in quotes,
                            scheduling jobs and chasing up invoices.
                        </p>
                        <span class="name">Carl Allan</span>
                        <span class="position">CEO TradieFlow</span>
                        <picture>
                            <img src="/landing_media/img/_src/png/allan-img.png" alt="allan">
                        </picture>
                    </blockquote>
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
                        <div class="circle"></div>
                        <div class="video-player">
                            <div class="video-thumb">
                                <img src="/landing_media/img/_src/png/video-thumb.png" alt="Video thumbnial">
                            </div>
                            <button class="btn play-video" id="play_video">
                                <img src="/landing_media/img/_src/svg/video-play-icon.svg" alt="Video play icon">
                            </button>
                            <section class="landing-video-container" style="display:none;">
                                <div class="wistia_responsive_padding"><div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;"><div class="wistia_embed wistia_async_t4wgpn4saq videoFoam=true" style="height:100%;position:relative;width:100%"><div class="wistia_swatch" style="height:100%;left:0;opacity:0;overflow:hidden;position:absolute;top:0;transition:opacity 200ms;width:100%;"><img src="https://fast.wistia.com/embed/medias/t4wgpn4saq/swatch" style="filter:blur(5px);height:100%;object-fit:contain;width:100%;" alt="" aria-hidden="true" onload="this.parentNode.style.opacity=1;" /></div></div></div></div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="features-sect gray-bg no-overflow-sect" id="features">
        <div class="container">
            <div class="row">
                <div class="features-column col-lg-auto">
                    <div class="descr">
                        <span class="sect-title">Features</span>
                        <h2>More <span class="green">Free Time</span>,<br> Less Paperwork.</h2>
                    </div>
                    <div class="features-tabs">
                        <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <button class="nav-link active" id="v-pills-get-paid-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-get-paid" type="button" role="tab" aria-controls="v-pills-get-paid"
                                    aria-selected="false">
                                <picture class="visible-md">
                                    <img src="/landing_media/img/_src/png/feature-img/mobile/feature-img-mobi-1.png" alt="get-paid">
                                </picture>
                                <span class="title">Run Your Entire Business From Your Mobile Phone.</span>
                                <p>Tradie Flow is designed for you to run your business from absolutely anywhere.</p>
                            </button>
                            <button class="nav-link" id="v-pills-more-efficient-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-more-efficient" type="button" role="tab"
                                    aria-controls="v-pills-more-efficient" aria-selected="false">
                                <picture class="visible-md">
                                    <img src="/landing_media/img/_src/png/feature-img/mobile/feature-img-mobi-2.png" alt="Be more efficient">
                                </picture>
                                <span class="title">Collect Leads</span>
                                <p>Tradieflow connects directly with your wordpress website and sends your leads directly
                                    Into yourTtradie Flow app.
                                </p>
                            </button>
                            <button class="nav-link" id="v-pills-win-quotes-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-win-quotes" type="button" role="tab" aria-controls="v-pills-win-quotes"
                                    aria-selected="false">
                                <picture class="visible-md">
                                    <img src="/landing_media/img/_src/png/feature-img/mobile/feature-img-mobi-3.png" alt="more-quotes">
                                </picture>
                                <span class="title">Deliver Quotes</span>
                                <p>Send beautiful quotes to your customers. Win more jobs. Grow revenue.</p>
                            </button>
                            <button class="nav-link" id="v-pills-customers-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-customers" type="button" role="tab" aria-controls="v-pills-customers"
                                    aria-selected="false">
                                <picture class="visible-md">
                                    <img src="/landing_media/img/_src/png/feature-img/mobile/feature-img-mobi-4.png" alt="be-more">
                                </picture>
                                <span class="title">Books Job.</span>
                                <p>Add your team to job cards, so everyone is clear on the tasks at hand.
                                    Better communication = better customer service.
                                </p>
                            </button>
                            <button class="nav-link" id="v-pills-send-invoices-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-send-invoices" type="button" role="tab" aria-controls="v-pills-send-invoices"
                                    aria-selected="false">
                                <picture class="visible-md">
                                    <img src="/landing_media/img/_src/png/feature-img/mobile/feature-img-mobi-5.png" alt="Your pocket">
                                </picture>
                                <span class="title">Send Invoices</span>
                                <p>Quickly and easily create invoices to send to your customers inside of Tradie Flow.</p>
                            </button>
                            <button class="nav-link" id="v-pills-chase-up-receivables-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-chase-up-receivables" type="button" role="tab"
                                    aria-controls="v-pills-chase-up-receivables" aria-selected="false">
                                <picture class="visible-md">
                                    <img src="/landing_media/img/_src/png/feature-img/mobile/feature-img-mobi-6.png" alt="Your pocket">
                                </picture>
                                <span class="title">Chase Up Receivables</span>
                                <p>Tired of late payers? Quickly chase up receivables with text or email from mobile or desktop.</p>
                            </button>
                            <button class="nav-link" id="v-pills-get-reviews-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-get-reviews" type="button" role="tab" aria-controls="v-pills-get-reviews"
                                    aria-selected="false">
                                <picture class="visible-md">
                                    <img src="/landing_media/img/_src/png/feature-img/mobile/feature-img-mobi-7.png" alt="Your pocket">
                                </picture>
                                <span class="title">Get Reviews</span>
                                <p>Tired of going into different apps for getting Reviews? Generate more reviews on Google and
                                    Facebook as soon as you close off a job - without even thinking!</p>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="tab-content tab-content-img col" id="v-pills-tabContent">
                    <div class="tab-pane v-pills-get-paid show active expanded-img" id="v-pills-get-paid" role="tabpanel" aria-labelledby="v-pills-get-paid">
                        <img src="/landing_media/img/_src/png/feature-img/feature-img-1.png" alt="Get Paid Quicker Illustration" width="auto" height="471">
                    </div>
                    <div class="tab-pane v-pills-more-efficient" id="v-pills-more-efficient" role="tabpanel" aria-labelledby="v-pills-more-efficient">
                        <img src="/landing_media/img/_src/png/feature-img/feature-img-2.png" alt="Be More Efficient Illustration" width="715" height="471">
                    </div>
                    <div class="tab-pane v-pills-win-quotes" id="v-pills-win-quotes" role="tabpanel" aria-labelledby="v-pills-win-quotes">
                        <img src="/landing_media/img/_src/png/feature-img/feature-img-3.png" alt="Win More Quotes Illustration" width="847" height="">
                    </div>
                    <div class="tab-pane v-pills-customers" id="v-pills-customers" role="tabpanel" aria-labelledby="v-pills-win-quotes-tab">
                        <img src="/landing_media/img/_src/png/feature-img/feature-img-4.png" alt="Understand Your Customers Illustration" width="792" height="531">
                    </div>
                    <div class="tab-pane v-pills-send-invoices" id="v-pills-send-invoices" role="tabpanel" aria-labelledby="v-pills-send-invoices-tab">
                        <img src="/landing_media/img/_src/png/feature-img/feature-img-5.png" alt="An Executive Team in Your Pocket Illustration" width="752" height="561">
                    </div>
                    <div class="tab-pane v-pills-chase-up-receivables" id="v-pills-chase-up-receivables" role="tabpanel" aria-labelledby="v-pills-chase-up-receivables-tab">
                        <img src="/landing_media/img/_src/png/feature-img/feature-img-6.png" alt="An Executive Team in Your Pocket Illustration" width="752" height="561">
                    </div>
                    <div class="tab-pane v-pills-get-reviews" id="v-pills-get-reviews" role="tabpanel" aria-labelledby="v-pills-get-reviews-tab">
                        <img src="/landing_media/img/_src/png/feature-img/feature-img-7.png" alt="An Executive Team in Your Pocket Illustration" width="752" height="561">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="saved-hours-sect white-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <h2 class="hidden-md">Diamond Finish Concrete saved
                        <span class="green">20 hours of paperwork per week.</span>
                    </h2>
                    <div class="preview-wrap">
                        <picture class="preview-img">
                            <img src="/landing_media/img/_src/jpg/preview-img.jpg" alt="preview">
                        </picture>
                        <div class="preview-descr">
                            <p>
                                TradieFlow has been instrumental in freeing up my time, and helping me spend more time on the things that matter.
                            </p>
                            <span class="name">
                              <svg width="12" height="1" viewBox="0 0 12 1" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <line y1="0.5" x2="12" y2="0.5" stroke="black" />
                              </svg> Tommo
                            </span>
                            <picture>
                                <img src="/landing_media/img/_src/png/diamond-finish-concrete-logo.png" alt="ecolawns-logo">
                            </picture>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="descr">
                        <h2 class="hidden-xs">
                            Diamond Finish Concrete saved <span class="green">20 hours of paperwork per week.</span>
                        </h2>
                        <div class="info-item">
                            <span class="title">Handles all of my leads and jobs.</span>
                            <p>
                                I used TradieFlow to handle all of my leads from when they call or fill in a form on my website.
                                Most job management softwares only handle jobs send invoices. But TradieFlow, also gives me
                                marketing intelligence on how to run my trades business more effectively. </p>
                        </div>
                        <div class="info-item">
                            <span class="title">Changed my life.</span>
                            <p>
                                TradieFlow has literally changed my life. By freeing up my time to focus on what’s most important -
                                Automating and systemising my business.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="pricing-sect gray-bg" id="pricing">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="descr">
                        <span class="sect-title">Pricing</span>
                        <h2>Simple, transparent <span class="green">Pricing</span></h2>
                    </div>
                    <div class="plan-item">
                        <div class="top-info">
                            <picture class="avatar avatar-inside">
                                <img src="/landing_media/img/_src/png/starter-icon.png" alt="starter">
                            </picture>
                            <span class="title">Monthly Starter</span>
                        </div>
                        <div class="price">
                            {{ $currency == 'usd' ? '$99.00' : 'AUD 97.00' }}
                            <span class="duration">/ month</span>
                        </div>
                        <div class="pros-list">
                            <div class="pros-item checked">
                                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="10.5" cy="10.5" r="10" fill="#43D14F" stroke="white"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1922 7.82843C14.8017 7.4379 14.1685 7.4379 13.778 7.82843L9.53534 12.0711L8.12113 10.6569C7.7306 10.2663 7.09744 10.2663 6.70692 10.6569C6.31639 11.0474 6.31639 11.6805 6.70692 12.0711L8.82824 14.1924C9.21876 14.5829 9.85192 14.5829 10.2424 14.1924L15.1922 9.24264C15.5827 8.85212 15.5827 8.21895 15.1922 7.82843Z" fill="white"/>
                                </svg>
                                <span class="item-title">Track all leads from one app</span>
                            </div>
                            <div class="pros-item checked">
                                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="10.5" cy="10.5" r="10" fill="#43D14F" stroke="white"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1922 7.82843C14.8017 7.4379 14.1685 7.4379 13.778 7.82843L9.53534 12.0711L8.12113 10.6569C7.7306 10.2663 7.09744 10.2663 6.70692 10.6569C6.31639 11.0474 6.31639 11.6805 6.70692 12.0711L8.82824 14.1924C9.21876 14.5829 9.85192 14.5829 10.2424 14.1924L15.1922 9.24264C15.5827 8.85212 15.5827 8.21895 15.1922 7.82843Z" fill="white"/>
                                </svg>
                                <span class="item-title">View all customer interactions in one place</span>
                            </div>
                            <div class="pros-item checked">
                                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="10.5" cy="10.5" r="10" fill="#43D14F" stroke="white"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1922 7.82843C14.8017 7.4379 14.1685 7.4379 13.778 7.82843L9.53534 12.0711L8.12113 10.6569C7.7306 10.2663 7.09744 10.2663 6.70692 10.6569C6.31639 11.0474 6.31639 11.6805 6.70692 12.0711L8.82824 14.1924C9.21876 14.5829 9.85192 14.5829 10.2424 14.1924L15.1922 9.24264C15.5827 8.85212 15.5827 8.21895 15.1922 7.82843Z" fill="white"/>
                                </svg>
                                <span class="item-title">Free SMS, Free Phone Calls</span>
                            </div>
                            <div class="pros-item checked">
                                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="10.5" cy="10.5" r="10" fill="#43D14F" stroke="white"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1922 7.82843C14.8017 7.4379 14.1685 7.4379 13.778 7.82843L9.53534 12.0711L8.12113 10.6569C7.7306 10.2663 7.09744 10.2663 6.70692 10.6569C6.31639 11.0474 6.31639 11.6805 6.70692 12.0711L8.82824 14.1924C9.21876 14.5829 9.85192 14.5829 10.2424 14.1924L15.1922 9.24264C15.5827 8.85212 15.5827 8.21895 15.1922 7.82843Z" fill="white"/>
                                </svg>
                                <span class="item-title">Book Meetings</span>
                            </div>
                            <div class="pros-item checked">
                                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="10.5" cy="10.5" r="10" fill="#43D14F" stroke="white"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1922 7.82843C14.8017 7.4379 14.1685 7.4379 13.778 7.82843L9.53534 12.0711L8.12113 10.6569C7.7306 10.2663 7.09744 10.2663 6.70692 10.6569C6.31639 11.0474 6.31639 11.6805 6.70692 12.0711L8.82824 14.1924C9.21876 14.5829 9.85192 14.5829 10.2424 14.1924L15.1922 9.24264C15.5827 8.85212 15.5827 8.21895 15.1922 7.82843Z" fill="white"/>
                                </svg>
                                <span class="item-title">Book Jobs</span>
                            </div>
                            <div class="pros-item checked">
                                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="10.5" cy="10.5" r="10" fill="#43D14F" stroke="white"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1922 7.82843C14.8017 7.4379 14.1685 7.4379 13.778 7.82843L9.53534 12.0711L8.12113 10.6569C7.7306 10.2663 7.09744 10.2663 6.70692 10.6569C6.31639 11.0474 6.31639 11.6805 6.70692 12.0711L8.82824 14.1924C9.21876 14.5829 9.85192 14.5829 10.2424 14.1924L15.1922 9.24264C15.5827 8.85212 15.5827 8.21895 15.1922 7.82843Z" fill="white"/>
                                </svg>
                                <span class="item-title">Send Invoices</span>
                            </div>
                            <div class="pros-item checked">
                                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="10.5" cy="10.5" r="10" fill="#43D14F" stroke="white"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1922 7.82843C14.8017 7.4379 14.1685 7.4379 13.778 7.82843L9.53534 12.0711L8.12113 10.6569C7.7306 10.2663 7.09744 10.2663 6.70692 10.6569C6.31639 11.0474 6.31639 11.6805 6.70692 12.0711L8.82824 14.1924C9.21876 14.5829 9.85192 14.5829 10.2424 14.1924L15.1922 9.24264C15.5827 8.85212 15.5827 8.21895 15.1922 7.82843Z" fill="white"/>
                                </svg>
                                <span class="item-title">Collect Payments</span>
                            </div>
                            <div class="pros-item checked">
                                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="10.5" cy="10.5" r="10" fill="#43D14F" stroke="white"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1922 7.82843C14.8017 7.4379 14.1685 7.4379 13.778 7.82843L9.53534 12.0711L8.12113 10.6569C7.7306 10.2663 7.09744 10.2663 6.70692 10.6569C6.31639 11.0474 6.31639 11.6805 6.70692 12.0711L8.82824 14.1924C9.21876 14.5829 9.85192 14.5829 10.2424 14.1924L15.1922 9.24264C15.5827 8.85212 15.5827 8.21895 15.1922 7.82843Z" fill="white"/>
                                </svg>
                                <span class="item-title">Account Integrations (Xero, Gmail)</span>
                            </div>
                            <div class="pros-item checked">
                                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="10.5" cy="10.5" r="10" fill="#43D14F" stroke="white"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1922 7.82843C14.8017 7.4379 14.1685 7.4379 13.778 7.82843L9.53534 12.0711L8.12113 10.6569C7.7306 10.2663 7.09744 10.2663 6.70692 10.6569C6.31639 11.0474 6.31639 11.6805 6.70692 12.0711L8.82824 14.1924C9.21876 14.5829 9.85192 14.5829 10.2424 14.1924L15.1922 9.24264C15.5827 8.85212 15.5827 8.21895 15.1922 7.82843Z" fill="white"/>
                                </svg>
                                <span class="item-title">Enterprise Level Data About Your Business</span>
                            </div>
                        </div>
                        <a href="/early-access" class="btn btn-default btn-md btn-coming">Choose Plan</a>
                    </div>
                    <div class="plan-item">
                        <div class="top-info">
                            <picture class="avatar">
                                <img src="/landing_media/img/_src/svg/pricing-prof-icon.svg" alt="starter">
                            </picture>
                            <span class="title">Yearly Professional
                                <span class="status">Save 20%</span>
                            </span>
                        </div>
                        <div class="price">
                            {{ $currency == 'usd' ? '$950.40' : 'AUD 931.00' }}
                            <span class="duration">/ year</span>
                        </div>
                        <div class="pros-list">
                            <div class="pros-item checked">
                                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="10.5" cy="10.5" r="10" fill="#43D14F" stroke="white"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1922 7.82843C14.8017 7.4379 14.1685 7.4379 13.778 7.82843L9.53534 12.0711L8.12113 10.6569C7.7306 10.2663 7.09744 10.2663 6.70692 10.6569C6.31639 11.0474 6.31639 11.6805 6.70692 12.0711L8.82824 14.1924C9.21876 14.5829 9.85192 14.5829 10.2424 14.1924L15.1922 9.24264C15.5827 8.85212 15.5827 8.21895 15.1922 7.82843Z" fill="white"/>
                                </svg>
                                <span class="item-title">Track all leads from one app</span>
                            </div>
                            <div class="pros-item checked">
                                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="10.5" cy="10.5" r="10" fill="#43D14F" stroke="white"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1922 7.82843C14.8017 7.4379 14.1685 7.4379 13.778 7.82843L9.53534 12.0711L8.12113 10.6569C7.7306 10.2663 7.09744 10.2663 6.70692 10.6569C6.31639 11.0474 6.31639 11.6805 6.70692 12.0711L8.82824 14.1924C9.21876 14.5829 9.85192 14.5829 10.2424 14.1924L15.1922 9.24264C15.5827 8.85212 15.5827 8.21895 15.1922 7.82843Z" fill="white"/>
                                </svg>
                                <span class="item-title">View all customer interactions in one place</span>
                            </div>
                            <div class="pros-item checked">
                                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="10.5" cy="10.5" r="10" fill="#43D14F" stroke="white"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1922 7.82843C14.8017 7.4379 14.1685 7.4379 13.778 7.82843L9.53534 12.0711L8.12113 10.6569C7.7306 10.2663 7.09744 10.2663 6.70692 10.6569C6.31639 11.0474 6.31639 11.6805 6.70692 12.0711L8.82824 14.1924C9.21876 14.5829 9.85192 14.5829 10.2424 14.1924L15.1922 9.24264C15.5827 8.85212 15.5827 8.21895 15.1922 7.82843Z" fill="white"/>
                                </svg>
                                <span class="item-title">Free SMS, Free Phone Calls</span>
                            </div>
                            <div class="pros-item checked">
                                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="10.5" cy="10.5" r="10" fill="#43D14F" stroke="white"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1922 7.82843C14.8017 7.4379 14.1685 7.4379 13.778 7.82843L9.53534 12.0711L8.12113 10.6569C7.7306 10.2663 7.09744 10.2663 6.70692 10.6569C6.31639 11.0474 6.31639 11.6805 6.70692 12.0711L8.82824 14.1924C9.21876 14.5829 9.85192 14.5829 10.2424 14.1924L15.1922 9.24264C15.5827 8.85212 15.5827 8.21895 15.1922 7.82843Z" fill="white"/>
                                </svg>
                                <span class="item-title">Book Meetings</span>
                            </div>
                            <div class="pros-item checked">
                                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="10.5" cy="10.5" r="10" fill="#43D14F" stroke="white"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1922 7.82843C14.8017 7.4379 14.1685 7.4379 13.778 7.82843L9.53534 12.0711L8.12113 10.6569C7.7306 10.2663 7.09744 10.2663 6.70692 10.6569C6.31639 11.0474 6.31639 11.6805 6.70692 12.0711L8.82824 14.1924C9.21876 14.5829 9.85192 14.5829 10.2424 14.1924L15.1922 9.24264C15.5827 8.85212 15.5827 8.21895 15.1922 7.82843Z" fill="white"/>
                                </svg>
                                <span class="item-title">Book Jobs</span>
                            </div>
                            <div class="pros-item checked">
                                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="10.5" cy="10.5" r="10" fill="#43D14F" stroke="white"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1922 7.82843C14.8017 7.4379 14.1685 7.4379 13.778 7.82843L9.53534 12.0711L8.12113 10.6569C7.7306 10.2663 7.09744 10.2663 6.70692 10.6569C6.31639 11.0474 6.31639 11.6805 6.70692 12.0711L8.82824 14.1924C9.21876 14.5829 9.85192 14.5829 10.2424 14.1924L15.1922 9.24264C15.5827 8.85212 15.5827 8.21895 15.1922 7.82843Z" fill="white"/>
                                </svg>
                                <span class="item-title">Send Invoices</span>
                            </div>
                            <div class="pros-item checked">
                                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="10.5" cy="10.5" r="10" fill="#43D14F" stroke="white"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1922 7.82843C14.8017 7.4379 14.1685 7.4379 13.778 7.82843L9.53534 12.0711L8.12113 10.6569C7.7306 10.2663 7.09744 10.2663 6.70692 10.6569C6.31639 11.0474 6.31639 11.6805 6.70692 12.0711L8.82824 14.1924C9.21876 14.5829 9.85192 14.5829 10.2424 14.1924L15.1922 9.24264C15.5827 8.85212 15.5827 8.21895 15.1922 7.82843Z" fill="white"/>
                                </svg>
                                <span class="item-title">Collect Payments</span>
                            </div>
                            <div class="pros-item checked">
                                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="10.5" cy="10.5" r="10" fill="#43D14F" stroke="white"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1922 7.82843C14.8017 7.4379 14.1685 7.4379 13.778 7.82843L9.53534 12.0711L8.12113 10.6569C7.7306 10.2663 7.09744 10.2663 6.70692 10.6569C6.31639 11.0474 6.31639 11.6805 6.70692 12.0711L8.82824 14.1924C9.21876 14.5829 9.85192 14.5829 10.2424 14.1924L15.1922 9.24264C15.5827 8.85212 15.5827 8.21895 15.1922 7.82843Z" fill="white"/>
                                </svg>
                                <span class="item-title">Account Integrations (Xero, Gmail)</span>
                            </div>
                            <div class="pros-item checked">
                                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="10.5" cy="10.5" r="10" fill="#43D14F" stroke="white"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1922 7.82843C14.8017 7.4379 14.1685 7.4379 13.778 7.82843L9.53534 12.0711L8.12113 10.6569C7.7306 10.2663 7.09744 10.2663 6.70692 10.6569C6.31639 11.0474 6.31639 11.6805 6.70692 12.0711L8.82824 14.1924C9.21876 14.5829 9.85192 14.5829 10.2424 14.1924L15.1922 9.24264C15.5827 8.85212 15.5827 8.21895 15.1922 7.82843Z" fill="white"/>
                                </svg>
                                <span class="item-title">Enterprise Level Data About Your Business</span>
                            </div>
                        </div>
                        <a href="/early-access/yearly" class="btn btn-default btn-md btn-coming">Choose Plan</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="why-us-sect" id="industries">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <span class="sect-title">Why Us?</span>
                    <div class="number">100+</div>
                    <div class="number-info">Industries, we serve</div>
                </div>
                <div class="col-lg-9 col-md-9">
                    <h2>We Serve Over 100 Trade, Contractor and Home Improvement Industries</h2>
                    <div class="items-wrap">
                        <div class="item">
                            <picture>
                                <img src="/landing_media/img/_src/svg/air-icon.svg" alt="air">
                            </picture>
                            <div class="title">Air Conditioning</div>
                            <p>Automate lead flow, book jobs, send invoices, get paid on the spot.</p>
                        </div>
                        <div class="item">
                            <picture>
                                <img src="/landing_media/img/_src/svg/bathroom-icon.svg" alt="bathroom-icon">
                            </picture>
                            <div class="title">Bathroom Renovations</div>
                            <p>Handle incoming phone calls and web forms, book in quotes, send professional invoices, collect payment and get sales performance data.</p>
                        </div>
                        <div class="item">
                            <picture>
                                <img src="/landing_media/img/_src/svg/building-icon.svg" alt="building-icon">
                            </picture>
                            <div class="title">Building</div>
                            <p>The easiest way to handle your lead flow and payments, so you can spend more time growing your business.</p>
                        </div>
                        <div class="item">
                            <picture>
                                <img src="/landing_media/img/_src/svg/cleaning-icon.svg" alt="cleaning-icon">
                            </picture>
                            <div class="title">Cleaning</div>
                            <p>Keep track of all your customers in one singular place. Learn the effectiveness of your sales and marketing data all from the same app. Invoice and get paid as you go.</p>
                        </div>
                        <div class="item">
                            <picture>
                                <img src="/landing_media/img/_src/svg/floor-icon.svg" alt="floor-icon">
                            </picture>
                            <div class="title">Flooring</div>
                            <p>The fastest and easiest way to expand your flooring business. Everything in one place.  Save hours of time from doing after hours paperwork! </p>
                        </div>
                        <div class="item green-item">
                            <div class="title">Can't Find Yours?</div>
                            <p>Take a look at Our full <br> list and select your <br> industry now</p>
                            <button href="#" class="btn btn-default btn-white btn-md-lg btn-full show-modal"
                                    data-bs-toggle="modal" data-bs-target="#staticBackdrop">View
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="grow-up">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-5">
                    <picture class="mobile-img">
                        <img src="/landing_media/img/_src/svg/iPhoneX.svg" alt="iphone">
                    </picture>
                </div>
                <div class="col-lg-7 col-md-7">
                    <h2>Grow your <span class="white">Trade or Contracting Business</span> today, Get your Free Demo</h2>
                    <a href="/free-demo" class="btn btn-default btn-md btn-white btn-md">Free Demo</a>
                </div>
            </div>
        </div>
    </section>
    <section class="contact-sect" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="descr">
                        <span class="sect-title">Contact</span>
                        <h2>Want to talk <br> about your <span class="green">Trade, Contractor or Home Improvement <br> business</span>?</h2>
                        <p>When growing your business, you have to wear a lot of hats. Tradieflow is designed to take those hats off you, so you can get on with doing more of what you love.</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="contact-form">
                        {!! Form::open(['url' => 'contact-us', 'id' => 'contact_form', 'autocomplete' => 'off']) !!}
                        <div class="form-group">
                            {{ Form::text('name', null, ['id' => 'name', 'class' => 'form-control', 'required' => 'required', 'placeholder' => 'Your Name']) }}
                            {{ Form::label('name', 'Name') }}
                        </div>
                        <div class="form-group">
                            {{ Form::email('email', null, ['id' => 'email', 'class' => 'form-control', 'required' => 'required', 'placeholder' => 'Your Email']) }}
                            {{ Form::label('email', 'Email') }}
                        </div>
                        <div class="form-group">
                            {{ Form::textarea('message', null, ['id' => 'message', 'class' => 'form-control', 'required' => 'required', 'placeholder' => 'Start typing...', 'rows' => '0']) }}
                            {{ Form::label('message', 'Your Message') }}
                        </div>
                        <div id="contact_us_loader" style="display:none;">
                            <img src="/images/loader.png" width="24px" class="float-left">
                            <span class="float-left ml-1 loader-text">Processing</span>
                        </div>
                        <div class="form-group mb-0" id="message_sent_container" style="display:none;">
                            <div class="alert alert-success">
                                <h4>
                                    We have received your enquiry and will respond to you within 24 hours
                                </h4>
                            </div>
                        </div>
                        <div class="form-group mb-0" id="message_error_container" style="display:none;">
                            <div class="alert alert-danger">
                                <h4 id="message_error_text"></h4>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-default btn-md" id="contact_us_btn">Get in Touch</button>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('elements.footer',['hide_footer' => false])
    <div class="modal" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="search-form contact-search-form">
                        <form>
                            <div class="form-group">
                                <input id="dial-users" type="search" class="form-control" aria-describedby="search"
                                       placeholder="Start Typing any name...">
                                <button type="submit" class="btn">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M0 8.80756C0 3.9512 3.9512 0 8.80756 0C13.6642 0 17.6151 3.9512 17.6151 8.80756C17.6151 10.9438 16.8507 12.9048 15.581 14.4314L19.7618 18.6122C20.0794 18.9295 20.0794 19.4444 19.7618 19.7618C19.603 19.9206 19.3949 20 19.187 20C18.9789 20 18.771 19.9206 18.6122 19.7618L14.4314 15.581C12.9048 16.8507 10.9438 17.6151 8.80756 17.6151C3.9512 17.6151 0 13.6642 0 8.80756ZM1.62601 8.8076C1.62601 12.7675 4.84769 15.9892 8.80756 15.9892C12.7674 15.9892 15.9891 12.7674 15.9891 8.80756C15.9891 4.84769 12.7674 1.62601 8.80756 1.62601C4.84769 1.62601 1.62601 4.84773 1.62601 8.8076Z"
                                              fill="#43D14F"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="users-list">
                                <input type="hidden" id="project-id">
                            </div>
                        </form>
                    </div>
                    <div class="right-side">
                        <h2>Which One Is <span class="green">Your Industry?</span></h2>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.681547 0.49497C0.291023 0.885494 0.288902 1.52076 0.676812 1.91389L5.54643 6.84893L0.401082 11.9261C0.00795979 12.314 0.00585405 12.945 0.396379 13.3355C0.786903 13.7261 1.42217 13.7282 1.81529 13.3403L6.95114 8.27251L12.1126 13.5034C12.5006 13.8965 13.1316 13.8986 13.5221 13.5081C13.9126 13.1175 13.9148 12.4823 13.5269 12.0891L8.37473 6.8678L13.4048 1.90445C13.7979 1.51654 13.8 0.885494 13.4095 0.494969C13.0189 0.104445 12.3837 0.102325 11.9906 0.490234L6.97002 5.44421L2.09103 0.499672C1.70312 0.106551 1.07207 0.104446 0.681547 0.49497Z" fill="black"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
@endsection
@section('view_script')
<script src="https://fast.wistia.com/embed/medias/t4wgpn4saq.jsonp" async></script>
<script src="https://fast.wistia.com/assets/external/E-v1.js" async></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}});
        window.features_hover = true;
        window.is_main_scrolling = false;
        $('.users-list').scroll(function () {
            if ($(this).scrollTop() > 0 && $('.users-list').find('.ui-menu').height() > 400) {
                $(this).closest('.modal-content').addClass('scrolled')
            } else {
                $(this).closest('.modal-content').removeClass('scrolled')
            }
        });

        $('.btn-close').click(function () {
            $(this).closest('.modal').hide();
        })

        $('.show-modal').click(function () {
            var takeModalId = $(this).attr('data-bs-target');
            $(takeModalId).show();
            $('#dial-users').focus();
        })

        $(document).on('submit','#contact_form',function(){
            $('#message_sent_container').hide();
            $('#message_error_container').hide();
            $('#contact_us_btn').hide();
            $('#contact_us_loader').show();
            $.post('/contact-us',{ name: $('#name').val(), email : $('#email').val(), message: $('#message').val() }, function(data){
                $('#contact_us_loader').hide();
                $('#contact_us_btn').show();
                if (data.status) {
                    $('#contact_form')['0'].reset();
                    $('#message_sent_container').show();
                }
                else{
                    $('#message_error_text').text(data.error);
                    $('#message_error_container').show();
                }
            },'json');
            return false;
        });

        //circle start

        var projects = [
            {label: "Air Conditioning", value: "Air Conditioning"},
            {label: "Antenna Services", value: "Antenna Services"},
            {label: "Appliance Installation", value: "Appliance Installation"},
            {label: "Appliance Repairs", value: "Appliance Repairs"},
            {label: "Arborists", value: "Arborists"},
            {label: "Architects", value: "Architects"},
            {label: "Artificial Turf", value: "Artificial Turf"},
            {label: "Asbestos Removal", value: "Asbestos Removal"},
            {label: "Auto Electricians", value: "Auto Electricians"},
            {label: "Awnings", value: "Awnings"},
            {label: "Balustrades", value: "Balustrades"},
            {label: "Bamboo Flooring", value: "Bamboo Flooring"},
            {label: "Bath Resurfacing", value: "Bath Resurfacing"},
            {label: "Bathroom Accessories", value: "Bathroom Accessories"},
            {label: "Bathroom Renovations", value: "Bathroom Renovations"},
            {label: "Blinds", value: "Blinds"},
            {label: "Builders,", value: "Builders,"},
            {label: "Building Certifiers", value: "Building Certifiers"},
            {label: "Building Consultants", value: "Building Consultants"},
            {label: "Building Designers", value: "Building Designers"},
            {label: "Building Inspections", value: "Building Inspections"},
            {label: "Building Suppliers", value: "Building Suppliers"},
            {label: "Building Surveyors", value: "Building Surveyors"},
            {label: "Cabinet Makers", value: "Cabinet Makers"},
            {label: "Cabinet Makers", value: "Cabinet Makers"},
            {label: "Carpentry", value: "Carpentry"},
            {label: "Carpentry", value: "Carpentry"},
            {label: "Carpet Cleaning", value: "Carpet Cleaning"},
            {label: "Ceilings", value: "Ceilings"},
            {label: "Chimney Sweepers", value: "Chimney Sweepers"},
            {label: "Cladding", value: "Cladding"},
            {label: "Commercial Cleaning", value: "Commercial Cleaning"},
            {label: "Concrete Kerbs", value: "Concrete Kerbs"},
            {label: "Concrete Resurfacing", value: "Concrete Resurfacing"},
            {label: "Concreters", value: "Concreters"},
            {label: "Concreters", value: "Concreters"},
            {label: "Construction Project Management", value: "Construction Project Management"},
            {label: "Curtains", value: "Curtains"},
            {label: "Custom Furniture", value: "Custom Furniture"},
            {label: "Damp Proofing", value: "Damp Proofing"},
            {label: "Deck Builders", value: "Deck Builders"},
            {label: "Demolition", value: "Demolition"},
            {label: "Doors", value: "Doors"},
            {label: "Drafting", value: "Drafting"},
            {label: "Drilling & Boring", value: "Drilling & Boring"},
            {label: "Earthmoving", value: "Earthmoving"},
            {label: "Electricians", value: "Electricians"},
            {label: "Epoxy Flooring", value: "Epoxy Flooring"},
            {label: "Equipment Hire", value: "Equipment Hire"},
            {label: "Excavators", value: "Excavators"},
            {label: "Excavators", value: "Excavators"},
            {label: "Extension Design", value: "Extension Design"},
            {label: "Fencing", value: "Fencing"},
            {label: "Floor Coatings", value: "Floor Coatings"},
            {label: "Floor Sanding", value: "Floor Sanding"},
            {label: "Fly Screens", value: "Fly Screens"},
            {label: "Garage Doors", value: "Garage Doors"},
            {label: "Garden Clean Up", value: "Garden Clean Up"},
            {label: "Garden Designers", value: "Garden Designers"},
            {label: "Gardeners", value: "Gardeners"},
            {label: "Gas Fitters", value: "Gas Fitters"},
            {label: "Gazebo", value: "Gazebo"},
            {label: "Glaziers", value: "Glaziers"},
            {label: "Graffiti Protection", value: "Graffiti Protection"},
            {label: "Granite Benchtops", value: "Granite Benchtops"},
            {label: "Granny Flat Builders", value: "Granny Flat Builders"},
            {label: "Gutter Cleaning", value: "Gutter Cleaning"},
            {label: "Gutter Protection", value: "Gutter Protection"},
            {label: "Guttering", value: "Guttering"},
            {label: "Handrails", value: "Handrails"},
            {label: "Handyman", value: "Handyman"},
            {label: "Heaters", value: "Heaters"},
            {label: "Heating Systems", value: "Heating Systems"},
            {label: "Home Automation", value: "Home Automation"},
            {label: "Home Builders", value: "Home Builders"},
            {label: "Home Renovations", value: "Home Renovations"},
            {label: "Home Security", value: "Home Security"},
            {label: "Home Security Products", value: "Home Security Products"},
            {label: "Home Theatre", value: "Home Theatre"},
            {label: "Homewares", value: "Homewares"},
            {label: "Hot Water Systems", value: "Hot Water Systems"},
            {label: "House Cleaning", value: "House Cleaning"},
            {label: "Insulation", value: "Insulation"},
            {label: "Interior Decorators", value: "Interior Decorators"},
            {label: "Irrigation Systems", value: "Irrigation Systems"},
            {label: "Joiners", value: "Joiners"},
            {label: "Kitchen Designers", value: "Kitchen Designers"},
            {label: "Kitchen Renovations", value: "Kitchen Renovations"},
            {label: "Landscape Architecture", value: "Landscape Architecture"},
            {label: "Landscaping", value: "Landscaping"},
            {label: "Lawn Mowing", value: "Lawn Mowing"},
            {label: "Lighting", value: "Lighting"},
            {label: "Locksmiths", value: "Locksmiths"},
            {label: "Painters", value: "Painters"},
            {label: "Patio", value: "Patio"},
            {label: "Pavers", value: "Pavers"},
            {label: "Pergolas", value: "Pergolas"},
            {label: "Pest Control", value: "Pest Control"},
            {label: "Plastering", value: "Plastering"},
            {label: "Plumbers", value: "Plumbers"},
            {label: "Pool Builders", value: "Pool Builders"},
            {label: "Pool Fencing", value: "Pool Fencing"},
            {label: "Pool Heating", value: "Pool Heating"},
            {label: "Pool Maintenance", value: "Pool Maintenance"},
            {label: "Pressure Cleaner", value: "Pressure Cleaner"},
            {label: "Privacy Screens", value: "Privacy Screens"},
            {label: "Rain Water Tanks", value: "Rain Water Tanks"},
            {label: "Removalists", value: "Removalists"},
            {label: "Rendering", value: "Rendering"},
            {label: "Renovations", value: "Renovations"},
            {label: "Retaining Walls", value: "Retaining Walls"},
            {label: "Roof Repairs", value: "Roof Repairs"},
            {label: "Roofing", value: "Roofing"},
            {label: "Rubbish Removal", value: "Rubbish Removal"},
            {label: "Sandblasting", value: "Sandblasting"},
            {label: "Security Doors", value: "Security Doors"},
            {label: "Shade Sails", value: "Shade Sails"},
            {label: "Shed Builders", value: "Shed Builders"},
            {label: "Shopfitters", value: "Shopfitters"},
            {label: "Shower Screens", value: "Shower Screens"},
            {label: "Shutters", value: "Shutters"},
            {label: "Skip & Truck Hire", value: "Skip & Truck Hire"},
            {label: "Skip Bins", value: "Skip Bins"},
            {label: "Skylights", value: "Skylights"},
            {label: "Solar", value: "Solar"},
            {label: "Splashbacks", value: "Splashbacks"},
            {label: "Stained Glass", value: "Stained Glass"},
            {label: "Staircases", value: "Staircases"},
            {label: "Stone Cleaning", value: "Stone Cleaning"},
            {label: "Stone Masonry", value: "Stone Masonry"},
            {label: "Surveyor", value: "Surveyor"},
            {label: "Swimming Pool Builders", value: "Swimming Pool Builders"},
            {label: "Synthetic Turf", value: "Synthetic Turf"},
            {label: "Tiling", value: "Tiling"},
            {label: "Timber Flooring", value: "Timber Flooring"},
            {label: "Town Planning Services", value: "Town Planning Services"},
            {label: "Tree Loppers", value: "Tree Loppers"},
            {label: "Turf", value: "Turf"},
            {label: "Underfloor Heating Systems", value: "Underfloor Heating Systems"},
            {label: "Underpinning", value: "Underpinning"},
            {label: "Upholstery Repair", value: "Upholstery Repair"},
            {label: "Ventilation", value: "Ventilation"},
            {label: "Verandah Builders", value: "Verandah Builders"},
            {label: "Vinyl & Laminate", value: "Vinyl & Laminate"},
            {label: "Wallpapering", value: "Wallpapering"},
            {label: "Wardrobe Builders", value: "Wardrobe Builders"},
            {label: "Waterproofing", value: "Waterproofing"},
            {label: "Window Cleaning", value: "Window Cleaning"},
            {label: "Window Installers", value: "Window Installers"},
            {label: "Window Repair", value: "Window Repair"},
            {label: "Window Tinting", value: "Window Tinting"},
            {label: "Windows Replacement", value: "Windows Replacement"}
        ];

        $('#dial-users').autocomplete({
            minLength: 0,
            appendTo: '.users-list',
            source: projects,
            focus: function (event, ui) {
                //$( "#dial-users" ).val( ui.item.label );
                return false;
            },
            select: function (event, ui) {
                //$( "#dial-users" ).val( ui.item.label );
                return false;
            }
        }).bind('focus', function () {
            $(this).autocomplete("search");
        })
            .autocomplete("instance")._renderItem = function (ul, item) {
            return $("<li>")
                .append("<a href='/free-demo'>" +
                    "<div class='title'>" + item.label + "</div>" +
                    "</a>"
                )
                .appendTo(ul);
        }

        $(document).on('click','#play_video',function(){
            var video = Wistia.api("t4wgpn4saq");
            $('.landing-video-container').show();
            video.play();
            $('.video-player').addClass('playing');
            return false;
        });

        var header = document.getElementById("main-head");
        var sticky = header.offsetTop;

        if (window.pageYOffset - 50 > sticky) {
            header.classList.add("sticky");
        } else {
            header.classList.remove("sticky");
        }

        window.onscroll = function () {
            var takeOffset = window.pageYOffset - 50;
            if (takeOffset > sticky) {
                header.classList.add("sticky");
            } else {
                header.classList.remove("sticky");
            }

            $('.main-head').removeClass('scrolledAbout').removeClass('scrolledFeatures').removeClass('scrolledPrices').removeClass('scrolledIndustries').removeClass('scrolledContact')
            $('.top-nav a').removeClass('active');
            takeOffset+= 180;
            if (takeOffset > $('#about').offset().top && !(takeOffset > $('#features').offset().top)) {
                $('.main-head').addClass('scrolledAbout')
                $('.top-nav a[href="/#about"]').addClass('active')
            } else if (takeOffset > $('#features').offset().top && !(takeOffset > $('#pricing').offset().top)) {
                $('.main-head').addClass('scrolledFeatures')
                $('.top-nav a[href="/#features"]').addClass('active')
            } else if (takeOffset > $('#pricing').offset().top && !(takeOffset > $('#industries').offset().top)) {
                $('.main-head').addClass('scrolledPrices')
                $('.top-nav a[href="/#pricing"]').addClass('active')
            } else if (takeOffset > $('#industries').offset().top && !(takeOffset > $('#contact').offset().top)) {
                $('.main-head').addClass('scrolledIndustries')
                $('.top-nav a[href="/#industries"]').addClass('active')
            } else if (takeOffset > $('#contact').offset().top) {
                $('.main-head').addClass('scrolledContact')
                $('.top-nav a[href="/contact-us"]').addClass('active')
            } else {
                $('.main-head').removeClass('scrolledAbout').removeClass('features').removeClass('pricing').removeClass('industries').removeClass('contact')
                $('.top-nav a[href="/#home"]').addClass('active')
            }
        };

        if ($(window).width() > 767) {
            $('.features-tabs .nav-link').click(function () {
                var getTab = $(this).attr('aria-controls');
                $('.features-tabs .nav-link').not($(this)).removeClass('active');
                $('.features-tabs .nav-link p').hide();
                $(this).addClass('active');
                $(this).find('p').fadeIn(300);
                $('.tab-content-img .tab-pane').fadeOut(0).removeClass('show').removeClass('active')
                $('#' + getTab).fadeIn(300).addClass('active');
            });

            $('.features-tabs .nav-link').hover(function () {
                    if (is_main_scrolling) {
                        features_hover = false;
                    }
                    else{
                        features_hover = true;
                        $(this).trigger('click');
                    }
                }, function () {
                    features_hover = true;
                }
            );

            function changeTab() {
                if (features_hover) {
                    if ($('.features-tabs .nav-link.active').next().text() == '') {
                        $('.features-tabs .nav-link:nth-of-type(1)').click()
                    } else {
                        $('.features-tabs .nav-link.active').next().click()
                    }
                }
            }

            setInterval(changeTab, 8000);
            $(".features-tabs .nav-link.active").click();
        }
        else{
            $('.top-nav .main-nav a').click(function () {
                $('.top-nav .close').click();
            })
        }

        $('.top-nav').on('click', 'a', function (event) {
            var id = $(this).attr('href').substring(1);
            var top = $(id).offset().top;
            $('.top-nav').not($(this)).removeClass('active');
            $(this).addClass('active');
            $('body,html').animate({
                scrollTop: top
            }, 1500);
            event.preventDefault();
        });
    });
</script>
@endsection
