@extends('layouts.new_landing')
@section('content')
<main class="main not-logedin industries-main">
    <header class="main-header" id="main_header">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light">
                <a href="/" class="navbar-brand main-logo">
                    <img src="/images/main-logo.svg" alt="TradieReviews logo">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse ml-xl-auto md-md-affffff" id="navbarSupportedContent">
                    <div class="nav-inner d-flex align-items-center w-100">
                        <ul class="navbar-nav mx-auto top-nav">
                            <li class="nav-item active">
                                <a class="nav-link" href="/#home">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/#about">About</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/#features">Features</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/#faqs">FAQs</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/#pricing">Pricing</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/#integrations">Integrations</a>
                            </li>
                        </ul>
                        <div class="spacer d-lg-none"></div>
                        <div class="btn-wrap d-flex align-items-center ml-lg-auto">
                            @if($auth_user)
                                <a href="/auth/logout" class="btn btn-login d-flex align-items-center">
                                    <img src="/images/user-icon-green-circle.svg" alt="Login icon" class="icon">
                                    <span>Logout</span>
                                </a>
                                <a href="/settings/account" class="btn btn-primary btn--sqr">My Account</a>
                            @else
                                <a href="/auth/login" class="btn btn-login d-flex align-items-center">
                                    <img src="/images/user-icon-green-circle.svg" alt="Login icon" class="icon">
                                    <span>Login</span>
                                </a>
                                <a href="/free-trial" class="btn btn-primary btn--sqr animate-pulse">Start Free Trial</a>
                            @endif
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <section class="hero-section content-with-thumb thumb-right" id="home">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 figure-col">
                    <figure class="figure"><img src="/images/industries-page-hero-bg.png" alt="Section thumb" class="figure-img">
                    </figure>
                </div>
                <div class="col-12 col-md-6 content-col">
                    <!-- <h6 class="section-label">CONTRACTING SOFTWARE</h6> -->
                    <h1>Save Time, Drive Sales, and Grow Your Business with All-in-One <span class="green-text">Contractor Software</span></h1>
                    <p class="lead-text">TradieFlow handles your leads, schedules quote meetings, books jobs, and sends invoices all from one app. You do the work, TradieFlow does the rest. That???s job management made easy.</p>
                    <ul class="ul-list-items">
                        <li>Collect leads & close deals</li>
                        <li>Automate invoices & payments</li>
                        <li>Generate 5-star reviews</li>
                        <li>Book & schedule jobs</li>
                    </ul>
                    <a href="/free-trial" class="btn btn-primary btn--sqr">Start Free Trial</a>
                </div>
            </div>
        </div>
    </section>
    <!-- //End hero section -->

    <!-- Beginning about section -->
    <section class="about-section content-with-thumb thumb-left" id="about">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 figure-col">
                    <figure class="figure"><img src="/images/about-thumb.png" alt="Section thumb" class="figure-img">
                    </figure>
                </div>
                <div class="col-12 col-md-6 content-col">
                    <h6 class="section-label">About</h6>
                    <h2>What is <span class="green-text">TradieFlow?</span></h2>
                    <p>Your time is important, that???s why TradieFlow helps you (and hundreds of contractors) save hours on paperwork. Built to automate your most time-sucking tasks, TradieFlow gives you an all-in-one tool to manage your contracting business from, well...anywhere.</p>
                    <p>Automate your workflow through one user-friendly app, from the moment you receive a lead on your website, up until you finish a job and earn a 5-star review. With TradieFlow, it???s never been easier to run an effective, efficient contracting business.</p>
                    <h3>Save 20+ hours a week</h3>
                    <p>Say goodbye to manual data entry. With TradieFlow you spend less time staring at spreadsheets and more time growing your business. Streamline every part of the job cycle so you can focus on the big picture.</p>
                    <h3>Save thousands of dollars a year</h3>
                    <p>Whether you???re wasting juggling dozens of apps or losing customers through communication breakdown and human error, TradieFlow gives you a bird???s eye view of your business to ensure optimal results and maximum revenue.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- //End about section -->

    <!-- Beginning features section -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-auto features-nav d-none d-md-block">&nbsp;</div>
                <div class="col-12 col-md-auto features-content mt-0">
                    <h6 class="section-label">Features</h6>
                    <h2>Run Your <span class="green-text">Entire Business</span> From Your Mobile Phone At Home, Onsite, At The Beach... <span class="green-text">Anywhere</span></h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-auto features-nav">
                    <div class="nav-items">
                        <div class="nav-item active">
                            <h6>Collect Leads</h6>
                        </div>
                        <div class="nav-item">
                            <h6>Deliver Quotes</h6>
                        </div>
                        <div class="nav-item">
                            <h6>Book Jobs</h6>
                        </div>
                        <div class="nav-item">
                            <h6>Send Invoices</h6>
                        </div>
                        <div class="nav-item">
                            <h6>Chase Up Receivables</h6>
                        </div>
                        <div class="nav-item">
                            <h6>Get Reviews</h6>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-auto features-content">
                    <div class="content-wrap">
                        <div class="inner-container">
                            <div class="swiper contentSwiper">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="content-with-thumb thumb-right">
                                            <div class="row">
                                                <div class="col-12 col-lg-7 figure-col">
                                                    <figure class="figure">
                                                        <img src="/images/features-thumb/feature-thumb1.png" alt="Figure thumb"
                                                             class="figure-img">
                                                    </figure>
                                                </div>
                                                <div class="col-12 col-lg-5 mt-3 mt-md-4 mt-lg-0 content-col">
                                                    <h3>Collect Leads</h3>
                                                    <p>Finding leads used to be your challenge, now it???s finding time to service them all. TradieFlow connects directly with your CRM or WordPress site and sends leads to your phone. And since leads are 10x less likely to respond if you don???t contact them within 5 minutes, you???ll have ???ready to buy??? leads in your pocket, day and night.</p>
                                                    <a href="/free-trial" class="btn btn-primary btn--sqr">Start Free Trial</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="content-with-thumb thumb-right">
                                            <div class="row">
                                                <div class="col-12 col-lg-7 figure-col">
                                                    <figure class="figure">
                                                        <img src="/images/features-thumb/feature-thumb2.png" alt="Figure thumb"
                                                             class="figure-img">
                                                    </figure>
                                                </div>
                                                <div class="col-12 col-lg-5 mt-3 mt-md-4 mt-lg-0 content-col">
                                                    <h3>Deliver Quotes</h3>
                                                    <p>Your potential customers want a quote, that???s great! But they???re requesting quotes from dozens of other trade businesses in your industry too. TradieFlow gives you the advantage of precision and speed with beautiful quotes that land in your customer???s inbox and stand out from the crowd. When your quote stands out, you win more business. Simple.</p>
                                                    <a href="/free-trial" class="btn btn-primary btn--sqr">Start Free Trial</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="content-with-thumb thumb-right">
                                            <div class="row">
                                                <div class="col-12 col-lg-7 figure-col">
                                                    <figure class="figure">
                                                        <img src="/images/features-thumb/feature-thumb3.png" alt="Figure thumb"
                                                             class="figure-img">
                                                    </figure>
                                                </div>
                                                <div class="col-12 col-lg-5 mt-3 mt-md-4 mt-lg-0 content-col">
                                                    <h3>Book Jobs</h3>
                                                    <p>TradieFlow isn???t just about saving you time. It???s also engineered to help you generate more revenue. Try adding your team to job cards so everyone is clear on the tasks at hand. When your team is able to service leads efficiently, your profitability explodes. It???s not rocket science, it???s just damn good customer service, and that???s what TradieFlow does.</p>
                                                    <a href="/free-trial" class="btn btn-primary btn--sqr">Start Free Trial</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="content-with-thumb thumb-right">
                                            <div class="row">
                                                <div class="col-12 col-lg-7 figure-col">
                                                    <figure class="figure">
                                                        <img src="/images/features-thumb/feature-thumb4.png" alt="Figure thumb"
                                                             class="figure-img">
                                                    </figure>
                                                </div>
                                                <div class="col-12 col-lg-5 mt-3 mt-md-4 mt-lg-0 content-col">
                                                    <h3>Send Invoices</h3>
                                                    <p>Streamline your contracting business by cutting back on paperwork, and increasing your turnover. With TradieFlow, you can quickly and easily create invoices to send to your customers. With built-in invoice templates, tracking, and reminders, you can earn more by doing less.</p>
                                                    <a href="/free-trial" class="btn btn-primary btn--sqr">Start Free Trial</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="content-with-thumb thumb-right">
                                            <div class="row">
                                                <div class="col-12 col-lg-7 figure-col">
                                                    <figure class="figure">
                                                        <img src="/images/features-thumb/feature-thumb5.png" alt="Figure thumb"
                                                             class="figure-img">
                                                    </figure>
                                                </div>
                                                <div class="col-12 col-lg-5 mt-3 mt-md-4 mt-lg-0 content-col">
                                                    <h3>Chase Up Receivables</h3>
                                                    <p>You???ve got better things to do than chase up overdue invoices and late-paying customers (let???s face it, anything sounds better than that). That???s why TradieFlow automates your invoicing workflow to quickly chase up receivables and keep the cash coming in, while you focus on the bigger picture.</p>
                                                    <a href="/free-trial" class="btn btn-primary btn--sqr">Start Free Trial</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="content-with-thumb thumb-right">
                                            <div class="row">
                                                <div class="col-12 col-lg-7 figure-col">
                                                    <figure class="figure">
                                                        <img src="/images/features-thumb/feature-thumb6.png" alt="Figure thumb"
                                                             class="figure-img">
                                                    </figure>
                                                </div>
                                                <div class="col-12 col-lg-5 mt-3 mt-md-4 mt-lg-0 content-col">
                                                    <h3>Get Reviews</h3>
                                                    <p>Tired of logging in to different apps to try and land reviews? We get it. That???s why TradieFlow simplifies reputation management and lets you earn reviews on Google and Facebook from the second you close a job. Growing a 5-star reputation while you sleep? That???s the Tradie Digital advantage.</p>
                                                    <a href="/free-trial" class="btn btn-primary btn--sqr">Start Free Trial</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- //End features section -->

    <!-- Beginning Amplify word of mouth section -->
    <section class="manage-lead-drive-section content-with-thumb pb-0">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 figure-col">
                    <figure class="figure">
                        <img src="/images/manage-lead-thumb.png" alt="Amplify word thumb" class="figure-img">
                    </figure>
                </div>
                <div class="col-12 col-md-6 content-col">
                    <h6 class="section-label">MANAGE LEADS & DRIVE SALES</h6>
                    <h2>Turn Your Team into <span class="green-text">Sales & Service Legends</span></h2>
                    <p>TradieFlow provides powerful tools to not only manage your leads but drive bigger sales and supercharge your ROI. Profitability isn???t just about selling, but being efficient. And with your customer???s data in one place, your field service operations have the info they need to keep bookings rolling in.</p>
                    <a href="/free-trial" class="btn btn-primary btn--sqr">Start Free Trial</a>
                    <div class="content-row row">
                        <div class="col-12 col-lg-6 content-item">
                            <figure class="figure">
                                <img src="/images/real-time-insights-icon.svg" alt="Real time insights icon" class="icon">
                            </figure>
                            <h5>Real-Time Insights</h5>
                            <p>Follow each job from unscheduled to scheduled, in progress to complete. Use data to save time, cut costs, and make better sales.</p>
                        </div>
                        <div class="col-12 col-lg-6 content-item">
                            <figure class="figure">
                                <img src="/images/collect-leads-icon.svg" alt="Collect-leads icon" class="icon">
                            </figure>
                            <h5>Collect Leads</h5>
                            <p>TradieFlow connects directly with your WordPress website and sends leads directly to your app. Get in touch with hot leads and land more customers.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- //End Amplify word of mouth section -->

    <!-- Beginning ONLINE ESTIMATES & QUOTES section -->
    <section class="online-estimates-section content-with-thumb thumb-right pb-0">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 figure-col text-center">
                    <figure class="figure">
                        <img src="/images/online-estimates-thumb.png" alt="Automatic review thumb"
                             class="figure-img">
                    </figure>
                </div>
                <div class="col-12 col-md-6 content-col">
                    <h6 class="section-label">ONLINE ESTIMATES & QUOTES</h6>
                    <h2>Send Paperless & Professional <span class="green-text">Quotes Fast </span></h2>
                    <p>Create and send professional quotes from anywhere. When you manage everything from one place, you???ll land more customers. TradieFlow places industry-leading customer service in the palm of your hand - literally.</p>
                    <a href="/free-trial" class="btn btn-primary btn--sqr">Start Free Trial</a>
                    <div class="content-row row">
                        <div class="col-12 col-lg-6 content-item">
                            <figure class="figure">
                                <img src="/images/job-icon.svg" alt="job icon" class="icon">
                            </figure>
                            <h5>Win More Jobs</h5>
                            <p>Send beautiful quotes to your customers to turn ???I???m still thinking??? into ???I???m ready to make a booking???.</p>
                        </div>
                        <div class="col-12 col-lg-6 content-item">
                            <figure class="figure">
                                <img src="/images/dolar-icon.svg" alt="Reminder icon" class="icon">
                            </figure>
                            <h5>Grow Revenue</h5>
                            <p>Increase your conversion rate with fast, accurate, and eye-catching quotes that deliver ROI and improve your bottom line.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- //End ONLINE ESTIMATES & QUOTES section -->

    <!-- Beginning MOBILE CRM section -->
    <section class="mobile-crm-section content-with-thumb">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 figure-col">
                    <figure class="figure">
                        <img src="/images/mobile-crm-thumb.png" alt="MOBILE CRM thumb" class="figure-img">
                    </figure>
                </div>
                <div class="col-12 col-md-6 content-col">
                    <h6 class="section-label">MOBILE CRM</h6>
                    <h2>Organize All Job Details in <span class="green-text">One Place</span></h2>
                    <p>TradieFlow literally puts your entire business in your pocket. That???s how you save hours on unnecessary phone calls between the job site and the office, as well as give your team the client info they need to turn inquiries into sales.</p>
                    <a href="/free-trial" class="btn btn-primary btn--sqr">Start Free Trial</a>
                    <div class="content-row row">
                        <div class="col-12 col-lg-6 content-item">
                            <figure class="figure">
                                <img src="/images/message-icon.svg" alt="Reviews icon" class="icon">
                            </figure>
                            <h5>Streamline Communication</h5>
                            <p>Endless calls and emails are a thing of the past. Now your team has the info they need without poor operational processes slowing down (or costing you) work.</p>
                        </div>
                        <div class="col-12 col-lg-6 content-item">
                            <figure class="figure">
                                <img src="/images/success-form-icon.svg" alt="Success form icon" class="icon">
                            </figure>
                            <h5>Book Jobs</h5>
                            <p>Add your team to job cards so everyone is clear on the task at hand. Better communication = better customer service, and with TradieFlow you???ve got both.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- //End MOBILE CRM section -->

    <!-- Beginning free trail section -->
    <section class="free-trial-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-auto content-col">
                    <h6 class="section-label">TRY TRADIEFLOW FOR FREE</h6>
                    <h2>Unlock more time and revenue <span class="black-text">without paying a cent.</span></h2>
                </div>
                <div class="col-12 col-md-auto btn-col ml-md-auto  mt-4 mt-md-0">
                    <a href="/free-trial" class="btn btn--sqr bg-white">Start Free Trial</a>
                </div>
            </div>
        </div>
    </section>
    <!-- //End free trail section -->

    <!-- Beginning INVOICING FROM THE FIELD section -->
    <section class="invoicing-from-field-section content-with-thumb">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 figure-col text-center">
                    <figure class="figure">
                        <img src="/images/invoicing-from-field-figure.png" alt="INVOICING FROM THE FIELD thumb"
                             class="figure-img">
                    </figure>
                </div>
                <div class="col-12 col-md-6 content-col">
                    <h6 class="section-label">INVOICING FROM THE FIELD</h6>
                    <h2>Send Invoices and <span class="green-text">Get Paid Online</span></h2>
                    <p>Billing, payment, and processing have never been easier (or quicker). TradieFlow lets you create, view, track, and send invoices via email or text, all at the click of a button. That???s how you cut down your admin time and get paid faster. </p>
                    <a href="/free-trial" class="btn btn-primary btn--sqr">Start Free Trial</a>
                    <div class="content-row row">
                        <div class="col-12 col-lg-6 content-item">
                            <figure class="figure">
                                <img src="/images/cards-icon.svg" alt="Cards icon" class="icon">
                            </figure>
                            <h5>Get Paid Online</h5>
                            <p>Stop leaving money on the table. Credit card processing means instant payment and no chasing up invoices through confusing email chains and miscommunications.</p>
                        </div>
                        <div class="col-12 col-lg-6 content-item">
                            <figure class="figure">
                                <img src="/images/money-icon.svg" alt="money icon" class="icon">
                            </figure>
                            <h5>Simplified Payment Process</h5>
                            <p>Add your logo to invoices, search previous invoices, automate customer receipts, collect deposits online before work starts. Super simple, super effective.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- //End INVOICING FROM THE FIELD section -->

    <!-- Beginning testimonial section -->
    <section class="testimonial-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="testimonial-wrap">
                        <div class="swiper-container testimonial-slider">
                            <!-- Additional required wrapper -->
                            <div class="swiper-wrapper">
                                <!-- Slides -->
                                <div class="swiper-slide text-center">
                                    <h3>nteger efficitur eget magna vel mollis. Vivamus ultricies <span
                                                class="green-text">ultricies dui, a hendrerit nunc</span>
                                        lacinia vitae. Nam iaculis velit vel iaculis convallis. Fusce lectus purus, volutpat.
                                    </h3>
                                    <h5>??? Full Name, Co-Founder & CEO, Parvenu</h5>
                                </div>
                                <div class="swiper-slide text-center">
                                    <h3>nteger efficitur eget magna vel mollis. Vivamus ultricies <span
                                                class="green-text">ultricies dui, a hendrerit nunc</span>
                                        lacinia vitae. Nam iaculis velit vel iaculis convallis. Fusce lectus purus, volutpat.
                                    </h3>
                                    <h5>??? Full Name, Co-Founder & CEO, Parvenu</h5>
                                </div>
                                <div class="swiper-slide text-center">
                                    <h3>nteger efficitur eget magna vel mollis. Vivamus ultricies <span
                                                class="green-text">ultricies dui, a hendrerit nunc</span>
                                        lacinia vitae. Nam iaculis velit vel iaculis convallis. Fusce lectus purus, volutpat.
                                    </h3>
                                    <h5>??? Full Name, Co-Founder & CEO, Parvenu</h5>
                                </div>
                                <div class="swiper-slide text-center">
                                    <h3>nteger efficitur eget magna vel mollis. Vivamus ultricies <span
                                                class="green-text">ultricies dui, a hendrerit nunc</span>
                                        lacinia vitae. Nam iaculis velit vel iaculis convallis. Fusce lectus purus, volutpat.
                                    </h3>
                                    <h5>??? Full Name, Co-Founder & CEO, Parvenu</h5>
                                </div>
                            </div>
                            <!-- If we need pagination -->
                            <div class="swiper-pagination"></div>
                        </div>
                        <!-- If we need navigation buttons -->
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- //End testimonial section -->

    <!-- Beginning FAQs section -->
    <section class="faqs-section" id="faqs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="inner-container">
                        <h2>FAQs</h2>
                        <div class="accordion" id="faqsAccordion">
                            <div class="card">
                                <div class="card-header" id="heading-1">
                                    <h6 class="mb-0">
                                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                                data-target="#collapse-1" aria-expanded="true" aria-controls="collapse-1">
                                            What are the benefits of using contractor software?
                                        </button>
                                    </h6>
                                </div>

                                <div id="collapse-1" class="collapse show" aria-labelledby="heading-1"
                                     data-parent="#faqsAccordion">
                                    <div class="card-body">
                                        <p>Would you rather save time or money? With TradieFlow, you can have both.</p>
                                        <p>Between managing schedules and managing jobs, it???s easy to lose time and miss out on new business. Throw in hours of paperwork every week, chasing up unpaid invoices, and marketing your business, and it???s a surprise you even have time to read this.</p>
                                        <p>TradieFlow is the easiest way to manage your contractor business and grow ROI without the headaches or wasted time of juggling multiple contractor software.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="heading-2">
                                    <h6 class="mb-0">
                                        <button class="btn btn-link btn-block text-left collapsed" type="button"
                                                data-toggle="collapse" data-target="#collapse-2" aria-expanded="false"
                                                aria-controls="collapse-2">
                                            Will TradieFlow let me run my business from my phone?
                                        </button>
                                    </h6>
                                </div>
                                <div id="collapse-2" class="collapse" aria-labelledby="heading-2"
                                     data-parent="#faqsAccordion">
                                    <div class="card-body">
                                        <p>Yes!</p>
                                        <p>TradieFlow gives you (and your team) the tools you need to manage every aspect of your business from one app. Whether you???re automating your workflow to unlock more free time in your week, or using TradieFlow???s marketing intelligence to increase your revenue, you???ll have the tools you need to streamline, run, and grow your contracting business.</p>
                                        <p>Best of all, TradieFlow is specifically designed to run your business from anywhere, whether that???s on the road, at home, or at the beach. With user-friendly software accessible on laptops, tablets, desktop computers, and smartphones, you can literally run your business from anywhere in the world.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="heading-3">
                                    <h6 class="mb-0">
                                        <button class="btn btn-link btn-block text-left collapsed" type="button"
                                                data-toggle="collapse" data-target="#collapse-3" aria-expanded="false"
                                                aria-controls="collapse-3">
                                            What support is included in my TradieFlow subscription?
                                        </button>
                                    </h6>
                                </div>
                                <div id="collapse-3" class="collapse" aria-labelledby="heading-3"
                                     data-parent="#faqsAccordion">
                                    <div class="card-body">
                                        <p>Plenty.</p>
                                        <p>We know how challenging it is to run a small business (because we are one) so we???re here for you every step of the way. You???ll enjoy FREE TradieFlow demos and initial setup assistance, with ongoing support whether you have features requests or need a little troubleshooting help. It doesn???t matter whether you???re a one-man band or a 25 person contracting team, we???ve got you covered. </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="heading-4">
                                    <h6 class="mb-0">
                                        <button class="btn btn-link btn-block text-left collapsed" type="button"
                                                data-toggle="collapse" data-target="#collapse-4" aria-expanded="false"
                                                aria-controls="collapse-4">
                                            What software features are included in my TradieFlow subscription?
                                        </button>
                                    </h6>
                                </div>
                                <div id="collapse-4" class="collapse" aria-labelledby="heading-4"
                                     data-parent="#faqsAccordion">
                                    <div class="card-body">
                                        Phone calls, text messages, CRM, reviews, and much more are included for free with your TradieFlow subscription. <a href="#">You can read more about our plans here.</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="heading-5">
                                    <h6 class="mb-0">
                                        <button class="btn btn-link btn-block text-left collapsed" type="button"
                                                data-toggle="collapse" data-target="#collapse-5" aria-expanded="false"
                                                aria-controls="collapse-5">
                                            Does TradieFlow have any 3rd Party Integrations?
                                        </button>
                                    </h6>
                                </div>
                                <div id="collapse-5" class="collapse" aria-labelledby="heading-5"
                                     data-parent="#faqsAccordion">
                                    <div class="card-body">
                                        <p>You bet.</p>
                                        <p>We understand you have data coming from different apps and we wouldn???t expect you to get rid of *all* of them (just the ones associated with low ROI and wasted time), that???s why TradieFlow syncs with your favorite apps, like Xero accounting software. To request an additional app go here.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- //End FAQs section -->

    <!-- Beginning pricing section -->
    <section class="pricing-section" id="pricing">
        <div class="container text-center">
            <h6 class="section-label">Pricing</h6>
            <h2>Simple, transparent <span class="blue-text">Pricing</span></h2>
            <div class="inner-container mx-auto">
                <div class="row pricing-card-row text-left">
                    <div class="col-12 col-md-6 pricing-card-item">
                        <div class="widget-box">
                            <div class="title-wrap d-flex align-items-center">
                                <div class="figure d-flex">
                                    <img src="/images/starter-icon.png" alt="Ster icon" class="icon">
                                </div>
                                <h3>Monthly Starter</h3>
                            </div>
                            <h2 class="price">{{ $currency == 'usd' ? '$99.00' : 'AUD 97.00' }} <span>/ month</span></h2>
                            <ul class="list-items feature-list">
                                <li>Track all leads from one app</li>
                                <li>View all customer interactions in one place</li>
                                <li>Free SMS, Free Phone Calls</li>
                                <li>Book Meetings</li>
                                <li>Book Jobs</li>
                                <li>Send Invoices</li>
                                <li>Collect Payments</li>
                                <li>Account Integrations (Xero, Gmail)</li>
                                <li>Enterprise Level Data About Your Business</li>
                            </ul>
                            <a href="/early-access" class="btn btn-primary btn--sqr">Choose Plan</a>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 pricing-card-item">
                        <div class="widget-box">
                            <div class="title-wrap d-flex align-items-center">
                                <div class="figure d-flex">
                                    <img src="/images/pricing-prof-icon.svg" alt="Ster icon" class="icon">
                                </div>
                                <div class="title-col">
                                    <h3>Yearly Professional</h3>
                                    <span class="status">Save 20%</span>
                                </div>
                            </div>
                            <h2 class="price">{{ $currency == 'usd' ? '$950.40' : 'AUD 931.00' }}<span>/ year</span></h2>
                            <ul class="list-items feature-list">
                                <li>Track all leads from one app</li>
                                <li>View all customer interactions in one place</li>
                                <li>Free SMS, Free Phone Calls</li>
                                <li>Book Meetings</li>
                                <li>Book Jobs</li>
                                <li>Send Invoices</li>
                                <li>Collect Payments</li>
                                <li>Account Integrations (Xero, Gmail)</li>
                                <li>Enterprise Level Data About Your Business</li>
                            </ul>
                            <a href="/early-access/yearly" class="btn btn-primary btn--sqr">Choose Plan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- //End pricing section -->

    <!-- Beginning feature list section -->
    <section class="feature-list-section">
        <div class="container">
            <!-- <h6 class="section-label">FeatuRe list</h6> -->
            <h2>Browse <span class="green-text">Features</span></h2>
            <ul class="ul-list-items">
                <li>
                    <div class="list-content">
                        <figure class="figure">
                            <img src="/images/collect-lead-icon.svg" alt="Collect lead icon" class="icon">
                        </figure>
                        <span>Collect Leads</span>
                    </div>
                </li>
                <li>
                    <div class="list-content">
                        <figure class="figure">
                            <img src="/images/send-invoice-icon.svg" alt="list icon" class="icon">
                        </figure>
                        <span>Send Invoices</span>
                    </div>
                </li>
                <li>
                    <div class="list-content">
                        <figure class="figure">
                            <img src="/images/job-scheduling.svg" alt="list icon" class="icon">
                        </figure>
                        <span>Job Scheduling</span>
                    </div>
                </li>
                <li>
                    <div class="list-content">
                        <figure class="figure">
                            <img src="/images/marketing-strategy-icon.svg" alt="list icon" class="icon">
                        </figure>
                        <span>Marketing Strategy</span>
                    </div>
                </li>
                <li>
                    <div class="list-content">
                        <figure class="figure">
                            <img src="/images/deliver-quotes.svg" alt="list icon" class="icon">
                        </figure>
                        <span>Deliver Quotes</span>
                    </div>
                </li>
                <li>
                    <div class="list-content">
                        <figure class="figure">
                            <img src="/images/chase-up-receivable.svg" alt="list icon" class="icon">
                        </figure>
                        <span>Chase Up Receivables</span>
                    </div>
                </li>
                <li>
                    <div class="list-content">
                        <figure class="figure">
                            <img src="/images/client-communication.svg" alt="list icon" class="icon">
                        </figure>
                        <span>Client Communication</span>
                    </div>
                </li>
                <li>
                    <div class="list-content">
                        <figure class="figure">
                            <img src="/images/customer-service.svg" alt="list icon" class="icon">
                        </figure>
                        <span>Customer Service</span>
                    </div>
                </li>
                <li>
                    <div class="list-content">
                        <figure class="figure">
                            <img src="/images/book-jobs-icon.svg" alt="list icon" class="icon">
                        </figure>
                        <span>Book Jobs</span>
                    </div>
                </li>
                <li>
                    <div class="list-content">
                        <figure class="figure">
                            <img src="/images/get-reviews-icon.svg" alt="list icon" class="icon">
                        </figure>
                        <span>Get Reviews</span>
                    </div>
                </li>
                <li>
                    <div class="list-content">
                        <figure class="figure">
                            <img src="/images/financing-icon.svg" alt="list icon" class="icon">
                        </figure>
                        <span>Financing</span>
                    </div>
                </li>
                <li>
                    <div class="list-content">
                        <figure class="figure">
                            <img src="/images/time-tracking-icon.svg" alt="list icon" class="icon">
                        </figure>
                        <span>Time Tracking</span>
                    </div>
                </li>
            </ul>
        </div>
    </section>
    <!-- //End feature list section -->

    <!-- Beginning TradieReview section -->
    <section class="tradiereview-section" id="integrations">
        <div class="container">
            <div class="row sharing-row">
                <div class="col-12">
                    <h2>Your <span class="green-text">Favorite Integrations</span> to Unleash the Power of Reviews</h2>
                    <div class="review-icons-wrap owl-carousel d-md-flex align-items-center justify-content-center">
                        <div class="icon-item">
                            <img src="/images/gmail-icon.svg" alt="Gmail icon" class="icon">
                        </div>
                        <div class="icon-item">
                            <img src="/images/apple-icon.svg" alt="Apple icon" class="icon">
                        </div>
                        <div class="icon-item">
                            <img src="/images/facebook-icon.png" alt="Facebook icon" class="icon">
                        </div>
                        <div class="icon-item">
                            <img src="/images/google-poly-icon.svg" alt="Google icon" class="icon">
                        </div>
                        <div class="icon-item">
                            <img src="/images/xero-icon.svg" alt="Xero icon" class="icon">
                        </div>
                        <div class="icon-item twilio-icon">
                            <img src="/images/twilio-icon.png" alt="Twilio icon" class="icon">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- //End TradieReview section -->

    <!-- Beginning free trail section -->
    <section class="free-trial-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-auto content-col">
                    <h6 class="section-label">TRY TRADIEFLOW FOR FREE</h6>
                    <h2>Unlock more time and revenue <span class="black-text">without paying a cent.</span></h2>
                </div>
                <div class="col-12 col-md-auto btn-col ml-md-auto  mt-4 mt-md-0">
                    <a href="/free-trial" class="btn btn--sqr bg-white">Start Free Trial</a>
                </div>
            </div>
        </div>
    </section>
    <!-- //End free trail section -->
    @include('elements.footer',['hide_footer' => false])
</main>
@endsection
@section('view_script')
<script type="text/javascript" src="/js/owl.carousel.min.js"></script>
<script type="text/javascript" src="/js/swiper-bundle.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        var header = document.getElementById("main_header");
        var sticky = header.offsetTop;

        if (window.pageYOffset - 50 > sticky) {
            $('#main_header').addClass('sticky-header fixedTop');
        } else {
            $('#main_header').removeClass('sticky-header fixedTop');
        }

        window.onscroll = function () {
            var takeOffset = window.pageYOffset - 50;
            if (takeOffset > 50) {
                $('#main_header').addClass('sticky-header fixedTop');
            } else {
                $('#main_header').removeClass('sticky-header fixedTop');
            }

            // $('#main_header').removeClass('scrolledAbout').removeClass('scrolledFeatures').removeClass('scrolledPrices').removeClass('scrolledIndustries').removeClass('scrolledContact')
            $('.top-nav a').removeClass('active');
            takeOffset+= 180;
            if (takeOffset > $('#about').offset().top && !(takeOffset > $('#features').offset().top)) {
                $('.main-head').addClass('scrolledAbout')
                $('.top-nav a[href="/#about"]').addClass('active')
            } else if (takeOffset > $('#features').offset().top && !(takeOffset > $('#pricing').offset().top)) {
                $('.main-head').addClass('scrolledFeatures')
                $('.top-nav a[href="/#features"]').addClass('active')
            } else {
                $('.main-head').removeClass('scrolledAbout').removeClass('features').removeClass('pricing').removeClass('industries').removeClass('contact')
                $('.top-nav a[href="/#home"]').addClass('active')
            }
        };

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
        var owl = $('.review-icons-wrap');
        if ($(window).width() < 768) {
            owl.owlCarousel({
                loop: false,
                margin: 15,
                smartSpeed: 700,
                dots: false,
                nav: false,
                items: 4,
            });
        }
        else{
            owl.addClass("off");
        }

        var testimonialSwiper = new Swiper(".testimonial-slider", {
            loop: false,
            spaceBetween: 100,
            slidesPerView: 1,
            pagination: {
                el: '.testimonial-slider .swiper-pagination',
                clickable: true
            },
            navigation: {
                nextEl: '.testimonial-wrap .swiper-button-next',
                prevEl: '.testimonial-wrap .swiper-button-prev',
            },
        });

        // Take nav for feature items
        var menu = ['Collect Leads', 'Deliver Quotes', 'Book Jobs', 'Send Invoices', 'Chase Up Receivables', 'Get Reviews'];
        var contentSwiper = new Swiper(".contentSwiper", {
            loop: true,
            spaceBetween: 10,
            autoHeight: true,
            pagination: {
                el: '.features-nav .nav-items',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<div class="nav-item ' + className + '"><h6>' + (menu[index]) +
                        '</h6></div>';
                },
            }
        });

    });
</script>
@endsection
