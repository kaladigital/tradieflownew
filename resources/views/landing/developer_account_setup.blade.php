<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradieFlow | Developer Guide</title>
    <link rel="shortcut icon" href="/favicon-tradieflow.png" type="image/x-icon">
    <link rel="stylesheet" href="/js/noty/noty.css">
    <link rel="stylesheet" href="/js/select2/css/select2.min.css">
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/js/highlight/styles/atom-one-light.min.css">
</head>
<body>
<main class="main">
    <header class="secondary-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-12 col-lg-auto">
                    <div class="logo-wrap d-flex align-items-center">
                        <img src="/images/main-logo.svg" alt="Main logo" class="main-logo">
                    </div>
                </div>
                <div class="col-12 col-sm-6 mt-4 mt-lg-0 col-lg-auto info-col ml-auto text-lg-right">
                    <div class="info">
                        @if($user_address)
                            <h5>
                                {{ $user_address }}
                            </h5>
                        @endif
                        <h5>
                            <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                        </h5>
                    </div>
                </div>
                <div class="col-12 col-sm-6 mt-4 mt-lg-0 col-lg-auto info-col text-lg-right">
                    <div class="info">
                        <h5>{{ $user->name }}</h5>
                        @if($user_twilio_phone)
                            <h5>
                                <a href="tel:{{ $user_twilio_phone->phone }}">
                                    {{ $user_twilio_phone->friendly_name }}
                                </a>
                            </h5>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="content-body secondary-content-body developer-guid">
        <form>
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-auto navigation-col">
                        <nav id="contents-nav" class="navbar navbar-light flex-column align-items-stretch">
                            <nav class="nav nav-pills flex-column">
                                <a href="#" class="nav-link" data-page="overview">Overview</a>
                                <a href="#" class="nav-link" data-page="setup-section">Setup</a>
                                <nav class="nav-pills flex-column" id="setup_menu_links" style="display:none;">
                                    <a href="#" class="nav-link ms-3 my-1" data-page="phone-numbers">Phone Numbers</a>
                                    <a href="#" class="nav-link ms-3 my-1" data-page="setup-forms">Forms</a>
                                </nav>
                                <a href="#" class="nav-link" data-page="refer-earn">Refer & Earn $50</a>
                            </nav>
                        </nav>
                    </div>
                    <div class="col-12 col-md-auto description-col">
                        <div data-bs-spy="scroll" data-bs-target="#contents-nav" data-bs-offset="0" tabindex="0">
                            <h2 class="content-title" id="overview">Developer’s Guide to Set Up TradieFlow</h2>
                            <h3>
                                This guide helps you to configure {{ $user->name }}’s Website. TradieFlow is a job management
                                software for trades and contractor businesses. TradieFlow handles leads, schedules quotes,
                                books in jobs, sends invoices, and collects payment all from the very same app.
                            </h3>
                            <h2 class="content-title" id="setup-section">
                                Setup
                            </h2>
                            <h3>
                                We have created a guide dedicated to you. The first chapter will discuss how you can quickly
                                exchange the phone number on {{ $user->name }}’s website. Meanwhile, in the second section we will
                                introduce you a scrip by which {{ $user->name }} will be able to easily collect form results and
                                manage all the new customers in TradieFlow.</h3>
                            <h3 class="content-title" id="phone-numbers">Phone Numbers</h3>
                            <p>The following piece of script looks up all the phone numbers on {{ $user->name }}’s website and
                                then exchanges them to a new TradieFlow phone number. {{ $user->name }} will be able to handle all
                                the new customers from our application. You need to copy the code and place it on all pages
                                or if you are using Wordpress, please just add it to the main layout.</p>
                            <div class="scripting-code-section">
                                <div class="section-header d-flex align-items-center">
                                    <h6>Phone Number Script</h6>
                                    <button class="btn btn--round btn-outline bg-white ml-md-auto" id="copy_phone_number_script">Copy Code</button>
                                </div>
                                <div class="section-body">
                                    <div class="code-wrap">
                                        <pre id="phone_number_script_container">
                                            <code>&lt;script type=&quot;text/javascript&quot;&gt;{{ $phone_number_replace_script }}&lt/script&gt;</code>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                            <h2 class="content-title" id="setup-forms">FORMS</h2>
                            @if($completed_forms_list)
                                <p>
                                    The forms script helps {{ $user->name }} to collect and handle all form answers in TradieFlow. You
                                    need to copy the code and place it on all pages or if you are using Wordpress, please just
                                    add it to the main layout.
                                </p>
                                <div class="code-wrapper">
                                    <div class="form-group select-group">
                                        {!! Form::select('form_site',$completed_forms_list,null,['id' => 'select_website', 'class' => 'form-control', 'autocomplete' => 'off', 'id' => 'form_site']) !!}
                                        {!! Form::label('form_site','Select Website') !!}
                                    </div>
                                    <div class="scripting-code-section">
                                        <div class="section-header d-flex align-items-center">
                                            <h6>Form Script</h6>
                                            <button type="button" class="btn btn--round btn-outline bg-white ml-md-auto" id="copy_form_code">Copy Code</button>
                                        </div>
                                        <div class="section-body">
                                            <div class="code-wrap" id="form_tracking_container"></div>
                                        </div>
                                    </div>
                                </div>
                                <figure class="figure">
                                    <img src="/images/developer/forms-figure.png" alt="Forms figure">
                                </figure>
                            @else
                                @if($pending_forms)
                                    <p class="red-text">
                                        Forms are processing, once they are ready we will send you an email.
                                    </p>
                                @else
                                    <p class="red-text">
                                       {{ $user->name }} did not configure forms yet. Once he sets Forms up, we will send you an email.
                                    </p>
                                @endif
                            @endif
                            <h2 class="content-title" id="refer-earn">Refer & Earn $50 Per Every Successful Referral</h2>
                            <p>Love this technology? Share it with your other customers and get $50 per each customer. </p>
                            <figure class="figure">
                                <img src="/images/developer/refer-earn-figure.png" alt="Refer earn figure">
                            </figure>
                            <h3 class="content-title">Send your referral</h3>
                            <p>Please fill out the the form below!</p>
                            <div class="form-wrap">
                                <div class="form-group-row form-row">
                                    <div class="form-group col-12 col-lg-6 required">
                                        <input type="text" id="fullName" class="form-control" placeholder="Your Full Name">
                                        <label for="fullName">Your Full Name</label>
                                    </div>
                                    <div class="form-group col-12 col-lg-6 required">
                                        <input type="email" id="email" class="form-control" placeholder="Your Email">
                                        <label for="email">Your Email</label>
                                    </div>
                                </div>
                                <div class="form-group-row form-row">
                                    <div class="form-group col-12 col-lg-6 required">
                                        <input type="text" id="clientsFullName" class="form-control"
                                               placeholder="Your Client’s Full Name">
                                        <label for="clientsFullName">Your Client’s Full Name</label>
                                    </div>
                                    <div class="form-group col-12 col-lg-6 required">
                                        <input type="email" id="clientsEmail" class="form-control"
                                               placeholder="Your Client’s Email">
                                        <label for="clientsEmail">Your Client’s Email</label>
                                    </div>
                                </div>
                                <div class="form-group-row form-row">
                                    <div class="col-12 col-lg-6">
                                        <div class="phone-number-group d-flex">
                                            <div class="country-code">
                                                {!! Form::select('ref_phone_country',$phone_countries,$user_twilio_phone ? $user_twilio_phone->country_code : 'us',['class' => 'form-control phone_country']) !!}
                                            </div>
                                            <div class="form-group">
                                                {!! Form::text('ref_client_phone',null,['class' => 'form-control client_phone', 'placeholder' => 'Phone Number', 'id' => 'ref_client_phone']) !!}
                                                <label for="ref_client_phone">Phone Number</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>
<script type="text/javascript" src="/js/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/underscore-min.js"></script>
<script type="text/javascript" src="/js/noty/noty.min.js"></script>
<script type="text/javascript" src="/js/select2/js/select2.min.js"></script>
<script type="text/javascript" src="/js/jquery.inputmask.min.js"></script>
<script type="text/javascript" src="/js/highlight/highlight.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        window.last_scroll_top = 0;
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}});
        $(document).on('change','#form_site',function(){
            load_site_details($(this).val());
            return false;
        });

        $(document).on('click','#copy_form_code',function(){
            var $temp = $('<input>');
            $('body').append($temp);
            $temp.val($('#form_script_container').text()).select();
            document.execCommand('copy');
            $temp.remove();
            new Noty({
                type: 'success',
                theme: 'metroui',
                layout: 'topRight',
                text: 'Successfully copied to clipboard',
                timeout: 2500,
                progressBar: false
            }).show();
            return false;
        });

        $(document).on('click','#copy_phone_number_script',function(){
            var $temp = $('<input>');
            $('body').append($temp);
            $temp.val($('#phone_number_script_container').text()).select();
            document.execCommand('copy');
            $temp.remove();
            new Noty({
                type: 'success',
                theme: 'metroui',
                layout: 'topRight',
                text: 'Successfully copied to clipboard',
                timeout: 2500,
                progressBar: false
            }).show();
            return false;
        });

        $(document).on('change','.phone_country',function(){
            set_country_mask();
            return false;
        });

        $(document).on('click','.nav-link',function(){
            var page_num = $(this).attr('data-page');
            var scroll_offset = 0;
            if (page_num == 'setup-section') {
                if ($('#setup_menu_links').is(':visible')) {
                    $('#setup_menu_links').slideUp();
                }
                else{
                    $('.nav-link[data-page="phone-numbers"]').removeClass('active');
                    $('.nav-link[data-page="setup-forms"]').removeClass('active');
                    $('#setup_menu_links').slideDown();
                }

                scroll_offset = -70;
            }
            else{
                switch (page_num) {
                    case 'phone-numbers':
                        $(this).addClass('active');
                        $('.nav-link[data-page="setup-forms"]').removeClass('active');
                    break;
                    case 'setup-forms':
                        $(this).addClass('active');
                        $('.nav-link[data-page="phone-numbers"]').removeClass('active');
                    break;
                    default:
                        $('#setup_menu_links').slideUp(function(){
                            $('.nav-link[data-page="phone-numbers"]').removeClass('active');
                            $('.nav-link[data-page="setup-forms"]').removeClass('active');
                        });
                    break;
                }
            }

            $('body,html').animate({
                scrollTop: $('#' + page_num).offset().top + scroll_offset,
            }, {
                duration: 900
            });
            return false;
        });

        $(window).on('scroll',function(){
            var st = window.pageYOffset || document.documentElement.scrollTop;
            if (st > last_scroll_top){
                var window_scroll_offset = st - 50;
                var overview_offset = $('#overview').offset().top;
                var setup_offset = $('#setup-section').offset().top;
                var phone_number_offset = $('#phone-numbers').offset().top;
                var forms_offset = $('#setup-forms').offset().top;
                var refer_offset = $('#refer-earn').offset().top;

                if (window_scroll_offset > overview_offset && window_scroll_offset < phone_number_offset) {
                    if (!$('#setup_menu_links').is(':visible')) {
                        $('.nav-link[data-page="phone-numbers"]').removeClass('active');
                        $('.nav-link[data-page="setup-forms"]').removeClass('active');
                        $('#setup_menu_links').slideDown();
                    }
                }
                else if (window_scroll_offset > setup_offset && window_scroll_offset < forms_offset) {
                    $('.nav-link[data-page="setup-forms"]').removeClass('active');
                    $('.nav-link[data-page="phone-numbers"]').addClass('active');
                }
                else if (window_scroll_offset > forms_offset && window_scroll_offset < refer_offset) {
                    $('.nav-link[data-page="phone-numbers"]').removeClass('active');
                    $('.nav-link[data-page="setup-forms"]').addClass('active');
                }
            }
            last_scroll_top = st <= 0 ? 0 : st;
        });

        $('#form_site').trigger('change');
        set_country_mask();
    })

    var load_site_details = function(site_id) {
        $.post('/developer/forms/tracking',{ website_id: site_id, code: '{{ $code }}' },function(data){
            if (data.status) {
                $('#form_tracking_container').html(_.template($('#form_tracking_template').html())({
                    tracking_code: data.tracking_code
                })).show();

                hljs.highlightAll();
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
        },'json');
    }

    var set_country_mask = function() {
        var country_code = $('.phone_country').val();
        switch (country_code) {
            case 'au':
                $('#ref_client_phone').inputmask("(99) 9999 9999", {clearIncomplete: true});
                break;
            case 'us':
            case 'ca':
                $('#ref_client_phone').inputmask('(999) 999-9999', {clearIncomplete: true});
                break;
            case 'gb':
                $('#ref_client_phone').inputmask('99 999 9999', {clearIncomplete: true});
                break;
        }

        $('.phone_country').select2({
            width: '100%',
            minimumResultsForSearch: -1,
            templateSelection: function (state) {
                if (!state.id) {
                    return state.text;
                }

                var state_lowcase = state.id.toLowerCase();

                return $(
                    '<span class="flag-icon flag-icon-' + state_lowcase + '">' +
                    '<img src="/images/flags/' + state.id + '.png"/>' +
                    '</span>' +
                    '<span class="flag-text">' + state.text + ' ' + "</span>"
                );
            },
            templateResult: function (state) {
                if (!state.id) {
                    return state.text;
                }
                var state_lowcase = state.id.toLowerCase();
                return $(
                    '<span class="flag-icon flag-icon-' + state_lowcase + '">' +
                    '<img src="/images/flags/' + state.id + '.png"/>' +
                    '</span>' +
                    '<span class="flag-text">' + state.text + "</span>"
                );
            },
        });
    }
</script>
<script type="text/template" id="form_tracking_template">
    <pre id="form_script_container"><code>&lt;script type=&quot;text/javascript&quot;&gt;<%= tracking_code %>&lt/script&gt;</code></pre>
</script>
</body>
</html>
