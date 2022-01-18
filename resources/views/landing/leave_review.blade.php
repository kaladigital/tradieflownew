<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradieFlow | Set Up Reviews</title>
    <link rel="shortcut icon" href="/favicon-TradieFlow.png" type="image/x-icon">
    <link rel="stylesheet" href="/js/select2/css/select2.min.css">
    <link rel="stylesheet" href="/js/noty/noty.css">
    <link rel="stylesheet" href="/css/main.css?v={{ Carbon\Carbon::now()->timestamp }}">
</head>
<body>
<main class="main">
    <header class="review-write-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-12 col-lg-auto">
                    <div class="logo-wrap d-flex align-items-center">
                        @if($user->reviews_logo)
                            <img src="/review-logo/{{ $user->reviews_logo }}" alt="Company logo" class="company-logo">
                        @else
                            <img src="/images/company-logo.png" alt="Company logo" class="company-logo">
                        @endif
{{--                        @if($user->reviews_company_name || $user->invoice_company_name)--}}
{{--                            <h2 class="company-name">--}}
{{--                                {{ $user->reviews_company_name ? $user->reviews_company_name : $user->invoice_company_name }}--}}
{{--                            </h2>--}}
{{--                        @endif--}}
                    </div>
                </div>
                <div class="col-12 col-sm-6 mt-4 mt-lg-0 col-lg-auto info-col ml-auto text-lg-right">
                    <div class="info">
                        @if($user->Country || $user->zip_code)
                            <h5>
                                <?php
                                    $address_items = [];
                                    if ($user->Country) {
                                        $address_items[] = $user->Country->name;
                                    }

                                    if ($user->zip_code) {
                                        $address_items[] = $user->zip_code;
                                    }
                                ?>
                                {{ implode(', ',$address_items) }}
                            </h5>
                        @endif
                        <h5><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></h5>
                    </div>
                </div>
                <div class="col-12 col-sm-6 mt-4 mt-lg-0 col-lg-auto info-col text-lg-right">
                    <div class="info">
                        <h5>{{ $user->name }}</h5>
                        @if($user_twilio_phone)
                            <h5>
                                <a href="tel:+{{ $user_twilio_phone->phone }}">{{ $user_twilio_phone->friendly_name }}</a>
                            </h5>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="content-body review-write-content-body">
        @if(!$review_invite || ($review_invite && $review_invite->status == 'pending'))
            {!! Form::open(['url' => 'rate/job', 'id' => 'rate_form']) !!}
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="inner-container">
                                <h2>
                                    How did
                                    @if($client)
                                        @if($client->company)
                                            {{ $client->company }} ({{ $client->name }})
                                        @else
                                            {{ $client->name }}
                                        @endif
                                    @else
                                        {{ $review_invite ? $review_invite->User->name : '' }}
                                    @endif
                                    do?
                                </h2>
                                <h3>
                                    How would you rate your experience with
                                    @if($client)
                                        {{ $client->name }}
                                    @else
                                        {{ $review_invite ? $review_invite->User->name : '' }}
                                    @endif
                                    ?
                                </h3>
                                <p>1 star represents “Extremely Poor” and 5 stars represents “Extremely Positive”</p>
                                <div class="rating">
                                    {!! Form::radio('rating','5',$rate == 5 ? true : false,['id' => 'rate-5', 'autocomplete' => 'off']) !!}
                                    <label for="rate-5">
                                        <svg width="49" height="48" viewBox="0 0 49 48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="0.5" width="48" height="48" rx="8" fill="#EFF2F9" /><path d="M23.5489 11.9271C23.8483 11.0057 25.1517 11.0057 25.4511 11.9271L27.6432 18.6738C27.7771 19.0858 28.161 19.3647 28.5943 19.3647H35.6882C36.6569 19.3647 37.0597 20.6044 36.2759 21.1738L30.5369 25.3435C30.1864 25.5981 30.0397 26.0495 30.1736 26.4615L32.3657 33.2082C32.6651 34.1295 31.6106 34.8956 30.8269 34.3262L25.0878 30.1565C24.7373 29.9019 24.2627 29.9019 23.9122 30.1565L18.1731 34.3262C17.3894 34.8956 16.3349 34.1295 16.6343 33.2082L18.8264 26.4615C18.9603 26.0495 18.8136 25.5981 18.4631 25.3435L12.7241 21.1738C11.9403 20.6044 12.3431 19.3647 13.3118 19.3647H20.4057C20.839 19.3647 21.2229 19.0858 21.3568 18.6738L23.5489 11.9271Z" fill="white" /></svg>
                                    </label>
                                    {!! Form::radio('rating','4',$rate == 4 ? true : false,['id' => 'rate-4', 'autocomplete' => 'off']) !!}
                                    <label for="rate-4">
                                        <svg width="49" height="48" viewBox="0 0 49 48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="0.5" width="48" height="48" rx="8" fill="#EFF2F9" /><path d="M23.5489 11.9271C23.8483 11.0057 25.1517 11.0057 25.4511 11.9271L27.6432 18.6738C27.7771 19.0858 28.161 19.3647 28.5943 19.3647H35.6882C36.6569 19.3647 37.0597 20.6044 36.2759 21.1738L30.5369 25.3435C30.1864 25.5981 30.0397 26.0495 30.1736 26.4615L32.3657 33.2082C32.6651 34.1295 31.6106 34.8956 30.8269 34.3262L25.0878 30.1565C24.7373 29.9019 24.2627 29.9019 23.9122 30.1565L18.1731 34.3262C17.3894 34.8956 16.3349 34.1295 16.6343 33.2082L18.8264 26.4615C18.9603 26.0495 18.8136 25.5981 18.4631 25.3435L12.7241 21.1738C11.9403 20.6044 12.3431 19.3647 13.3118 19.3647H20.4057C20.839 19.3647 21.2229 19.0858 21.3568 18.6738L23.5489 11.9271Z" fill="white" /></svg>
                                    </label>
                                    {!! Form::radio('rating','3',$rate == 3 ? true : false,['id' => 'rate-3', 'autocomplete' => 'off']) !!}
                                    <label for="rate-3">
                                        <svg width="49" height="48" viewBox="0 0 49 48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="0.5" width="48" height="48" rx="8" fill="#EFF2F9" /><path d="M23.5489 11.9271C23.8483 11.0057 25.1517 11.0057 25.4511 11.9271L27.6432 18.6738C27.7771 19.0858 28.161 19.3647 28.5943 19.3647H35.6882C36.6569 19.3647 37.0597 20.6044 36.2759 21.1738L30.5369 25.3435C30.1864 25.5981 30.0397 26.0495 30.1736 26.4615L32.3657 33.2082C32.6651 34.1295 31.6106 34.8956 30.8269 34.3262L25.0878 30.1565C24.7373 29.9019 24.2627 29.9019 23.9122 30.1565L18.1731 34.3262C17.3894 34.8956 16.3349 34.1295 16.6343 33.2082L18.8264 26.4615C18.9603 26.0495 18.8136 25.5981 18.4631 25.3435L12.7241 21.1738C11.9403 20.6044 12.3431 19.3647 13.3118 19.3647H20.4057C20.839 19.3647 21.2229 19.0858 21.3568 18.6738L23.5489 11.9271Z" fill="white" /></svg>
                                    </label>
                                    {!! Form::radio('rating','2',$rate == 2 ? true : false,['id' => 'rate-2', 'autocomplete' => 'off']) !!}
                                    <label for="rate-2">
                                        <svg width="49" height="48" viewBox="0 0 49 48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="0.5" width="48" height="48" rx="8" fill="#EFF2F9" /><path d="M23.5489 11.9271C23.8483 11.0057 25.1517 11.0057 25.4511 11.9271L27.6432 18.6738C27.7771 19.0858 28.161 19.3647 28.5943 19.3647H35.6882C36.6569 19.3647 37.0597 20.6044 36.2759 21.1738L30.5369 25.3435C30.1864 25.5981 30.0397 26.0495 30.1736 26.4615L32.3657 33.2082C32.6651 34.1295 31.6106 34.8956 30.8269 34.3262L25.0878 30.1565C24.7373 29.9019 24.2627 29.9019 23.9122 30.1565L18.1731 34.3262C17.3894 34.8956 16.3349 34.1295 16.6343 33.2082L18.8264 26.4615C18.9603 26.0495 18.8136 25.5981 18.4631 25.3435L12.7241 21.1738C11.9403 20.6044 12.3431 19.3647 13.3118 19.3647H20.4057C20.839 19.3647 21.2229 19.0858 21.3568 18.6738L23.5489 11.9271Z" fill="white" /></svg>
                                    </label>
                                    {!! Form::radio('rating','1',$rate == 2 ? true : false,['id' => 'rate-1', 'autocomplete' => 'off']) !!}
                                    <label for="rate-1">
                                        <svg width="49" height="48" viewBox="0 0 49 48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="0.5" width="48" height="48" rx="8" fill="#EFF2F9" /><path d="M23.5489 11.9271C23.8483 11.0057 25.1517 11.0057 25.4511 11.9271L27.6432 18.6738C27.7771 19.0858 28.161 19.3647 28.5943 19.3647H35.6882C36.6569 19.3647 37.0597 20.6044 36.2759 21.1738L30.5369 25.3435C30.1864 25.5981 30.0397 26.0495 30.1736 26.4615L32.3657 33.2082C32.6651 34.1295 31.6106 34.8956 30.8269 34.3262L25.0878 30.1565C24.7373 29.9019 24.2627 29.9019 23.9122 30.1565L18.1731 34.3262C17.3894 34.8956 16.3349 34.1295 16.6343 33.2082L18.8264 26.4615C18.9603 26.0495 18.8136 25.5981 18.4631 25.3435L12.7241 21.1738C11.9403 20.6044 12.3431 19.3647 13.3118 19.3647H20.4057C20.839 19.3647 21.2229 19.0858 21.3568 18.6738L23.5489 11.9271Z" fill="white" /></svg>
                                    </label>
                                </div>
                                <h3>Share details of your experience</h3>
                                <p>You can add written details to your rating. </p>
                                <div class="form-group required">
                                    {!! Form::text('name',null,['class' => 'form-control', 'required' => 'required', 'id' => 'name', 'placeholder' => 'Full Name']) !!}
                                    {!! Form::label('name','Full Name') !!}
                                </div>
                                <div class="form-group required">
                                    {!! Form::email('email',null,['class' => 'form-control', 'required' => 'required', 'id' => 'email', 'placeholder' => 'Email']) !!}
                                    {!! Form::label('email','Email') !!}
                                </div>
                                <div class="phone-number-group d-flex">
                                    <div class="country-code">
                                        {!! Form::select('phone_country',$phone_countries,$user_twilio_phone ? $user_twilio_phone->country_code : null,['class' => 'form-control', 'id' => 'phone_country']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::text('phone',null,['class' => 'form-control', 'id' => 'phone', 'placeholder' => 'Phone Number', 'class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <h3>Share details of your experience</h3>
                                <p>You can add written details to your rating. </p>
                                <div class="form-group">
                                    {!! Form::textarea('review',null,['class' => 'form-control', 'height' => '147', 'maxlength' => '2048', 'id' => 'review', 'placeholder' => 'Start writing...', 'autocomplete' => 'off']) !!}
                                </div>
                                <div class="g-recaptcha mb-3" data-sitekey="{{ env('GOOGLE_RECAPTCHA_SITE_KEY') }}"></div>
                                <div id="loading_container" class="mb-5" style="display:none;">
                                    <img src="/images/loader.png" width="24px" class="float-left">
                                    <span class="float-left ml-1 loader-text">Processing</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row action-row">
                        <div class="col-12 action-col" id="action_btn_container">
                            <div class="inner-container">
                                <a href="/" class="btn btn-secondary btn--sqr">Cancel</a>
                                <button type="submit" class="btn btn-primary btn--sqr disabled" id="leave_review_btn">Submit Review</button>
                            </div>
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}
        @else
            <h4 class="text-center">You have already posted a review</h4>
        @endif
    </div>
</main>
<div class="modal fade share-review" id="share_review_modal" tabindex="-1" role="dialog" aria-labelledby="shareReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content text-center">
            <div class="modal-header">
                <span class="modal-title ml-auto" id="shareReviewModalLabel">Your help is needed</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="/images/in-call-close-icon.svg" alt="Close icon gray">
                </button>
            </div>
            <div class="modal-body">
                <h5>Would you like to share your experience on these social media sites?</h5>
                <div class="btn-row d-flex align-items-center">
                    @if($user->google_review_place_id)
                        <a class="btn google-btn btn-outline" href="http://search.google.com/local/writereview?placeid={{ $user->google_review_place_id }}" target="_blank">
                            <img src="/images/google-logo-large.png" alt="Google logo large transparent">
                        </a>
                    @endif
                    @if($user->facebook_reviews_url)
                        <a class="btn facebook-btn btn-outline" href="{{ $user->facebook_reviews_url }}" target="_blank">
                            <img src="/images/facebook-logo-large.png" alt="Facebook logo large transparent">
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/js/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/select2/js/select2.min.js"></script>
<script type="text/javascript" src="/js/jquery.inputmask.min.js"></script>
<script type="text/javascript" src="/js/noty/noty.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script type="text/javascript">
    $(document).ready(function(){
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}});
        $('#phone_country').select2({
            width: '100%',
            minimumResultsForSearch: -1,
            templateSelection: function(state){
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
            templateResult: function(state) {
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

        $(document).on('change','#phone_country',function(){
            set_country_mask($(this).val())
            return false;
        });

        $(document).on('change','input[name="rating"]',function(){
            $('#leave_review_btn').removeClass('disabled');
            return false;
        });

        $(document).on('submit','#rate_form',function(){
            var recaptcha_token = $('#g-recaptcha-response').val();
            if (recaptcha_token) {
                var rate = parseInt($('input[name="rating"]').filter(':checked').val());
                if (rate) {
                    $('#action_btn_container').hide();
                    $('#loading_container').show();
                    $.post('/rate/job',{code: '{{ $id }}', type: '{{ $review_invite ? 'invite' : ($client ? 'job' : 'public') }}', name: $('#name').val(), email: $('#email').val(), phone_country: $('#phone_country').val(), phone: $('#phone').val(), rate: rate, review: $('#review').val(), recaptcha_token: recaptcha_token},function(data){
                        if (data.status) {
                            $('.content-body').addClass('thank-you').html($('#thank_you_page_template').html());
                            if (rate > 3 && {{ $user->facebook_reviews_url || $user->google_review_place_id ? true : false }} ) {
                                $('#share_review_modal').modal('show');
                            }
                        }
                        else{
                            $('#loading_container').hide();
                            $('#action_btn_container').show();
                            App.render_message('error',data.error);
                            grecaptcha.reset();
                        }
                    },'json');
                }
                else{
                    new Noty({
                        type: 'info',
                        theme: 'metroui',
                        layout: 'topRight',
                        text: 'Please rate to continue',
                        timeout: 2500,
                        progressBar: false
                    }).show();
                }
            }
            else{
                new Noty({
                    type: 'info',
                    theme: 'metroui',
                    layout: 'topRight',
                    text: 'Please solve the captcha to continue',
                    timeout: 2500,
                    progressBar: false
                }).show();
            }

            return false;
        });

        @if($user_twilio_phone)
            set_country_mask('{{ $user_twilio_phone->country_code }}')
        @endif

        // $('.content-body').addClass('thank-you').html($('#thank_you_page_template').html());
    });

    var set_country_mask = function(country_code) {
        switch (country_code) {
            case 'au':
                $('#phone').inputmask("(99) 9999 9999",{ clearIncomplete: true });
            break;
            case 'us':
            case 'ca':
                $('#phone').inputmask('(999) 999-9999',{ clearIncomplete: true });
            break;
            case 'gb':
                $('#phone').inputmask('99 999 9999',{ clearIncomplete: true });
            break;
        }
    }
</script>
<script type="text/template" id="thank_you_page_template">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="figure">
                    <img src="/images/thank-you-figure.svg" alt="Thank you">
                </div>
                <h2 class="thank-you-text" data-title="Thank you!">Thank you For your Feedback!</h2>
            </div>
        </div>
    </div>
</script>
</body>
</html>
