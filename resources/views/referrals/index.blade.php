@extends('layouts.master')
@section('view_css')
    <link rel="stylesheet" href="/js/select2/css/select2.min.css">
@endsection
@section('content')
    @if($auth_user->role == 'sales')
        @include('admin.left_sidebar_admin_menu',['active_page' => 'reviews'])
    @else
        @include('dashboard.left_sidebar_full_menu',['active_page' => 'reviews'])
    @endif
    <div class="col-md-auto col-12 content-wrap reviews-setting referrals-user">
        <div class="content-inner">
            <h2>Referrals</h2>
            <div class="profile-widget">
                <div class="row">
                    <div class="col-12 col-lg-auto intro-col">
                        <h1 class="green-text">How to get One month free for every person</h1>
                        <div class="illustrator-row row">
                            <div class="col-12 col-sm-6 col-lg-12">
                                <p>For every successful person you refer you receive 1 month free and each friend will receive one month free too.</p>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-12">
                                <div class="illustration-wrap">
                                    <img src="/images/referrals/referrals-illustrator.png" alt="Referrals illustration">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-auto mt-5 mt-lg-0 desc-col">
                        <div class="review-invite-contents">
                            <h3>99% of Our Business Comes From Referrals</h3>
                            <p>We would love to help you and your friends. Get a free month of TradieFlow for every person you refer.</p>
                            <div class="process-steps">
                                <div class="row">
                                    <div class="col-12 col-md-4 process-steps--item">
                                        <figure>
                                            <img src="/images/step-send-invites-icon.svg" alt="Setting icon">
                                        </figure>
                                        <h5>Send Invitation</h5>
                                        <p>Send your referral link to a friend and tell them how awesome TradieFlow is!</p>
                                    </div>
                                    <div class="col-12 col-md-4 process-steps--item">
                                        <figure>
                                            <img src="/images/referrals/step-registration-icon.svg" alt="Registration icon">
                                        </figure>
                                        <h5>Registration</h5>
                                        <p>Let them register to our services using your referral link.</p>
                                    </div>
                                    <div class="col-12 col-md-4 process-steps--item">
                                        <figure>
                                            <img src="/images/referrals/step-use-free-icon.svg" alt="Use free icon">
                                        </figure>
                                        <h5>Use of Free!</h5>
                                        <p>You and your friends get 1 month premium subscription for free!</p>
                                    </div>
                                </div>
                            </div>
                            <h3>Invite Your Friends</h3>
                            <p>Insert your friends’ email addresses and send them invitations to join TradieFlow!</p>
                            <div class="form-group">
                                {!! Form::email('email',null,['class' => 'form-control', 'required' => 'required', 'id' => 'email', 'placeholder' => 'Email addresses', 'multiple' => 'multiple', 'autocomplete' => 'off']) !!}
                                <button type="button" id="send_email_invite" class="btn btn--sqr btn-primary">Send</button>
                                <div id="email_send_loading" class="mt-2" style="display:none;">
                                    <img src="/images/loader.png" width="24px" class="float-left">
                                    <span class="float-left ml-1 loader-text">Processing</span>
                                </div>
                            </div>
                            <div class="devider"><span>Or send via Text</span></div>
                            <p>Insert one phone number at a time and send the invitation to your friend to join TradieFlow!</p>
                            {!! Form::open(['url' => 'reviews/send', 'id' => 'send_txt_form', 'autocomplete' => 'off']) !!}
                                <div class="phone-number-group-wrapper">
                                    <div class="phone-number-group d-flex">
                                        <div class="country-code">
                                            {!! Form::select('phone_country',$phone_countries,null,['class' => 'form-control', 'required' => 'required', 'id' => 'phone_country']) !!}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::text('phone',null,['class' => 'form-control', 'required' => 'required', 'id' => 'phone', 'placeholder' => 'Phone Number']) !!}
                                        </div>
                                    </div>
                                    <button type="submit" id="send_txt_invite" class="btn btn--sqr btn-primary">Send</button>
                                    <div id="txt_send_loading" class="mt-2" style="display:none;">
                                        <img src="/images/loader.png" width="24px" class="float-left">
                                        <span class="float-left ml-1 loader-text">Processing</span>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                            <h3>Share Your Unique Referral Link</h3>
                            <p>You can share your referral link by copying and sending it or sharing it on your social media.</p>
                            <div class="review-link-field d-flex">
                                <div class="link-wrap d-flex">
                                    <span class="ref-link">{{ $referral_share_url }}</span>
                                    <button type="button" id="copy_referral_link" class="btn copy-link green-text">Copy Link</button>
                                </div>
                                <a class="btn mail-btn" href="https://www.facebook.com/sharer/sharer.php?u={{ $referral_share_url }}">
                                    <img src="/images/referrals/facebook-green.svg" alt="Facebook share icon">
                                </a>
                                <a class="btn mail-btn" href="http://twitter.com/share?url={{ $referral_share_url }}">
                                    <img src="/images/referrals/twitter-green.svg" alt="Twitter share icon">
                                </a>
                                <a class="btn mail-btn" href="https://www.linkedin.com/sharing/share-offsite/?url={{ $referral_share_url }}">
                                    <img src="/images/referrals/linkedin-green.svg" alt="Linkedin share icon">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{--    <div class="col-md-auto col-12 content-wrap reviews-list-view">--}}
{{--            <h2>Referrals</h2>--}}
{{--            <div class="profile-widget">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-12 col-lg-auto des-col mt-4 mt-lg-0">--}}
{{--                        <h3>Invite Your Friends</h3>--}}
{{--                        {!! Form::open(['url' => 'referrals/send', 'id' => 'send_email_form']) !!}--}}
{{--                        <div class="form-group">--}}

{{--                        </div>--}}
{{--                        {!! Form::close() !!}--}}
{{--                        <div class="devider"><span>Or send via Text</span></div>--}}
{{--                        <p>Insert one phone number at a time and send the review request!</p>--}}

{{--                            <a class="btn mail-btn" href="mailto:?subject=Signup%20&amp;body={{ env('APP_URL').'/start-free-trial?ref='.$auth_user->signup_referral_code }}">--}}
{{--                                <img src="/images/outline-email-icon-green.svg" alt="Outline Email icon green">--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
@endsection
@section('view_script')
<script type="text/javascript" src="/js/select2/js/select2.min.js"></script>
<script type="text/javascript" src="/js/jquery.inputmask.min.js"></script>
<script type="text/javascript" src="/js/tagsinput/bootstrap-tagsinput.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#email').tagsinput({
            allowDuplicates: false,
            tagClass: 'added-email-item',
            trimValue: true,
            maxTags: 100
        });

        $(document).on('change','#phone_country',function(){
            set_country_mask($(this).val());
            return false;
        });

        $(document).on('click','#send_email_invite',function(){
            var emails = $('#email').val();
            if (emails.length) {
                var email_obj = emails.split(',');
                var non_valid_email = null;
                for (var i = 0; i < email_obj.length; i++) {
                    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                    if (!non_valid_email && !re.test(email_obj[i].toLowerCase())) {
                        non_valid_email = email_obj[i];
                    }
                }

                if (non_valid_email) {
                    App.render_message('warning', '"' + non_valid_email + '" is not valid email address')
                } else {
                    $('#send_email_invite').hide();
                    $('#email_send_loading').show();
                    $.post('/referrals/send/invite',{ type: 'email', email: email_obj },function(data){
                        if (data.status) {
                            $('#email_send_loading').hide();
                            $('#send_email_invite').show();
                            $('#email').tagsinput('removeAll');
                            $('#email').val('');
                            App.render_message('success','Invitation sent successfully');
                        }
                        else{
                            App.render_message('info',data.error);
                        }
                    },'json');
                }
            }
            return false;
        });

        $(document).on('submit','#send_txt_form',function(){
            $('#send_txt_invite').hide();
            $('#txt_send_loading').show();
            $.post('/referrals/send/invite',{ type: 'phone', phone: $('#phone').val(), country: $('#phone_country').val() },function(data){
                if (data.status) {
                    $('#txt_send_loading').hide();
                    $('#send_txt_invite').show();
                    $('#phone').val('');
                    App.render_message('success','Invitation sent successfully');
                }
                else{
                    App.render_message('info',data.error);
                }
            },'json');
            return false;
        });

        $(document).on('click','#copy_referral_link',function(){
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val('{{ $referral_share_url }}').select();
            document.execCommand("copy");
            $temp.remove();
            App.render_message('success','Successfully copied to clipboard');
            return false;
        });

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

        set_country_mask($('#phone_country').val());
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
@endsection
