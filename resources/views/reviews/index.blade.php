@extends('layouts.master')
@section('view_css')
<link rel="stylesheet" href="/css/font-awesome.min.css">
<link rel="stylesheet" href="/js/jquery-bar-rating/themes/fontawesome-stars.css">
<link rel="stylesheet" href="/js/select2/css/select2.min.css">
@endsection
@section('content')
    @if($auth_user->role == 'sales')
        @include('admin.left_sidebar_admin_menu',['active_page' => 'reviews'])
    @else
        @include('dashboard.left_sidebar_full_menu',['active_page' => 'reviews'])
    @endif
    <div class="col-md-auto col-12 content-wrap reviews-list-view">
        <div class="content-inner">
            <div class="float-right">
                <ul class="nav nav-tabs action-triger">
                    <li class="nav-item">
                        <a class="nav-link switch_reviews_tab" data-type="send" href="#">
                            Send
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link switch_reviews_tab active" data-type="feedback" href="/reviews">
                            Feedback
                        </a>
                    </li>
                </ul>
            </div>
            <h2>Customer Feedback</h2>
            <div class="profile-widget">
                <div class="row">
                    <div class="col-12 col-md-auto intro-col">
                        <div class="title-wrap">
                            <h2>{{ $auth_user->name }}</h2>
                            <p class="d-flex justify-content-center align-items-center mb-0">
                                <img src="/images/location-icon-green.svg" alt="Location icon" class="icon">
                                <span>{{ $auth_user->city ? $auth_user->city.', ' : '' }}{{ $auth_user->Country ? $auth_user->Country->name : '' }}</span>
                            </p>
                        </div>
                        <div class="review-hint">
                            <h3>Customer Reviews</h3>
                            <div class="rating-points d-flex align-items-center">
                                <select class="rating star-rating" data-current-rating="0" autocomplete="off">
                                    <option hidden="" value="0">0</option>
                                    <option value="1" {{ $avg_reviews_received_star == '1' ? 'selected="selected"' : '' }}>1</option>
                                    <option value="2" {{ $avg_reviews_received_star == '2' ? 'selected="selected"' : '' }}>2</option>
                                    <option value="3" {{ $avg_reviews_received_star == '3' ? 'selected="selected"' : '' }}>3</option>
                                    <option value="4" {{ $avg_reviews_received_star == '4' ? 'selected="selected"' : '' }}>4</option>
                                    <option value="5" {{ $avg_reviews_received_star == '5' ? 'selected="selected"' : '' }}>5</option>
                                </select>
                                <span class="points">{{ $avg_reviews_received }} out of 5</span>
                            </div>
                            <p>{{ $total_reviews_received }} customer ratings</p>
                            <div class="review-rating-slide-wrap">
                                <div class="slide-item d-flex align-items-center">
                                    <span>5 star</span>
                                    <div class="progress-slide" data-rating-progress="{{ $five_start_percentage_rounded }}">
                                        <span class="progress-slide--thumb"></span>
                                    </div>
                                    <span class="rating-percent">{{ sprintf('%.2f',$five_start_review_percentage) }}%</span>
                                </div>
                                <div class="slide-item d-flex align-items-center">
                                    <span>4 star</span>
                                    <div class="progress-slide" data-rating-progress="{{ $four_start_percentage_rounded }}">
                                        <span class="progress-slide--thumb"></span>
                                    </div>
                                    <span class="rating-percent">{{ sprintf('%.2f',$four_start_review_percentage) }}%</span>
                                </div>
                                <div class="slide-item d-flex align-items-center">
                                    <span>3 star</span>
                                    <div class="progress-slide" data-rating-progress="{{ $three_start_percentage_rounded }}">
                                        <span class="progress-slide--thumb"></span>
                                    </div>
                                    <span class="rating-percent">{{ sprintf('%.2f',$three_start_review_percentage) }}%</span>
                                </div>
                                <div class="slide-item d-flex align-items-center">
                                    <span>2 star</span>
                                    <div class="progress-slide" data-rating-progress="{{ $two_start_percentage_rounded }}">
                                        <span class="progress-slide--thumb"></span>
                                    </div>
                                    <span class="rating-percent">{{ sprintf('%.2f',$two_start_review_percentage) }}%</span>
                                </div>
                                <div class="slide-item d-flex align-items-center">
                                    <span>1 star</span>
                                    <div class="progress-slide" data-rating-progress="{{ $one_start_percentage_rounded }}">
                                        <span class="progress-slide--thumb"></span>
                                    </div>
                                    <span class="rating-percent">{{ sprintf('%.2f',$one_start_review_percentage) }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-auto des-col mt-4 mt-lg-0">
                        <div class="review-list-contents tab-pane fade show active" id="reviews_list_container">
                            <div class="rating-filter-row row">
                                <div class="col-12 col-lg-6 filter-rating-options">
                                    <h6>Reviews ({{ $total_reviews_received }})</h6>
                                    <div class="reviews-filter">
                                        <div class="reviews-filter--item slide-item d-flex align-items-center" data-rating-progress="{{ $five_start_percentage_rounded }}">
                                            <div class="reviews-filter--status">
                                                <div class="custom-control custom-checkbox">
                                                    {!! Form::checkbox('five_star_filter','1',true,['class' => 'custom-control-input filter_item', 'id' => 'five_star_filter', 'autocomplete' => 'off']) !!}
                                                    {!! Form::label('five_star_filter','5 (Excellent)',['class' => 'custom-control-label']) !!}
                                                </div>
                                            </div>
                                            <div class="progress-slide" data-rating-progress="{{ $five_start_percentage_rounded }}">
                                                <span class="progress-slide--thumb"></span>
                                            </div>
                                            <span>{{ intval($totals['2']->num) }}</span>
                                        </div>
                                        <div class="reviews-filter--item slide-item d-flex align-items-center" data-rating-progress="{{ $four_start_percentage_rounded }}">
                                            <div class="reviews-filter--status">
                                                <div class="custom-control custom-checkbox">
                                                    {!! Form::checkbox('four_star_filter','1',true,['class' => 'custom-control-input filter_item', 'id' => 'four_star_filter', 'autocomplete' => 'off']) !!}
                                                    {!! Form::label('four_star_filter','4 (Very good)',['class' => 'custom-control-label']) !!}
                                                </div>
                                            </div>
                                            <div class="progress-slide" data-rating-progress="{{ $four_start_percentage_rounded }}">
                                                <span class="progress-slide--thumb"></span>
                                            </div>
                                            <span>{{ intval($totals['3']->num) }}</span>
                                        </div>
                                        <div class="reviews-filter--item slide-item d-flex align-items-center" data-rating-progress="{{ $three_start_percentage_rounded }}">
                                            <div class="reviews-filter--status">
                                                <div class="custom-control custom-checkbox">
                                                    {!! Form::checkbox('three_star_filter','1',true,['class' => 'custom-control-input filter_item', 'id' => 'three_star_filter', 'autocomplete' => 'off']) !!}
                                                    {!! Form::label('three_star_filter','3 (Average)',['class' => 'custom-control-label']) !!}
                                                </div>
                                            </div>
                                            <div class="progress-slide" data-rating-progress="{{ $three_start_percentage_rounded }}">
                                                <span class="progress-slide--thumb"></span>
                                            </div>
                                            <span>{{ intval($totals['4']->num) }}</span>
                                        </div>
                                        <div class="reviews-filter--item slide-item d-flex align-items-center" data-rating-progress="{{ $two_start_percentage_rounded }}">
                                            <div class="reviews-filter--status">
                                                <div class="custom-control custom-checkbox">
                                                    {!! Form::checkbox('two_star_filter','1',true,['class' => 'custom-control-input filter_item', 'id' => 'two_star_filter', 'autocomplete' => 'off']) !!}
                                                    {!! Form::label('two_star_filter','2 (Poor)',['class' => 'custom-control-label']) !!}
                                                </div>
                                            </div>
                                            <div class="progress-slide" data-rating-progress="{{ $two_start_percentage_rounded }}">
                                                <span class="progress-slide--thumb"></span>
                                            </div>
                                            <span>{{ intval($totals['5']->num) }}</span>
                                        </div>
                                        <div class="reviews-filter--item slide-item d-flex align-items-center" data-rating-progress="{{ $one_start_percentage_rounded }}">
                                            <div class="reviews-filter--status">
                                                <div class="custom-control custom-checkbox">
                                                    {!! Form::checkbox('one_star_filter','1',true,['class' => 'custom-control-input filter_item', 'id' => 'one_star_filter', 'autocomplete' => 'off']) !!}
                                                    {!! Form::label('one_star_filter','1 (Terrible)',['class' => 'custom-control-label']) !!}
                                                </div>
                                            </div>
                                            <div class="progress-slide" data-rating-progress="{{ $one_start_percentage_rounded }}">
                                                <span class="progress-slide--thumb"></span>
                                            </div>
                                            <span>{{ intval($totals['6']->num) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 filter-radio-options">
                                    <div class="row">
                                        <div class="col-12 col-lg-6">
                                            <h6>Review Type</h6>
                                            <div class="custom-control custom-radio">
                                                {!! Form::radio('written_review_filter','reviews',false,['class' => 'custom-control-input filter_item', 'id' => 'written_review_filter_reviews', 'autocomplete' => 'off']) !!}
                                                {!! Form::label('written_review_filter_reviews','Include Written Reviews',['class' => 'custom-control-label']) !!}
                                            </div>
                                            <div class="custom-control custom-radio">
                                                {!! Form::radio('written_review_filter','stars_only',false,['class' => 'custom-control-input filter_item', 'id' => 'written_review_filter_stars', 'autocomplete' => 'off']) !!}
                                                {!! Form::label('written_review_filter_stars','Include Stars-only',['class' => 'custom-control-label']) !!}
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6 mt-4 mt-lg-0">
                                            <h6>Sort by</h6>
                                            <div class="custom-control custom-radio">
                                                {!! Form::radio('sort_by','latest',true,['class' => 'custom-control-input filter_item', 'id' => 'sort_by_latest', 'autocomplete' => 'off']) !!}
                                                {!! Form::label('sort_by_latest','Latest first',['class' => 'custom-control-label']) !!}
                                            </div>
                                            <div class="custom-control custom-radio">
                                                {!! Form::radio('sort_by','oldest',false,['class' => 'custom-control-input filter_item', 'id' => 'sort_by_oldest', 'autocomplete' => 'off']) !!}
                                                {!! Form::label('sort_by_oldest','Oldest first',['class' => 'custom-control-label']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="rating-reviews">
                                <div class="rating-reviews--wrap">
                                    @foreach($reviews as $item)
                                        <div class="rating-reviews--item">
                                            <div class="title-row d-flex align-items-center">
                                                {!! Form::select('star_rating',$rate_points,$item->rate,['class' => 'star-rating', 'style' => 'display:none;', 'autocomplete' => 'off']) !!}
                                                <span class="name">{{ $item->reviewer_name }}</span>
                                                <span class="time">{{ $item->created_at->format('j F, Y') }}</span>
                                                <div class="action-trigers ml-auto">
                                                    <button class="btn btn-call make_call {{ $item->reviewer_phone ? '' : 'not-added' }}" data-name="{{ $item->reviewer_name }}" data-phone="{{ $item->reviewer_phone }}" data-phone-format="{{ $item->reviewer_phone_format }}" data-client="{{ $item->client_id }}">
                                                        <img class="icon-green" src="/images/calendar-event-call.svg" alt="Call icon">
                                                        <img class="icon-gray" src="/images/calendar-event-call-gray.svg" alt="Call icon">
                                                    </button>
                                                    <button class="btn btn-message">
                                                        <img class="icon-green" src="/images/calendar-event-email.svg" alt="Email icon">
                                                        <img class="icon-gray" src="/images/calendar-event-email-gray.svg" alt="Email icon">
                                                    </button>
                                                    <button class="btn btn-message">
                                                        <img class="icon-green" src="/images/calendar-event-text-message.svg" alt="Message icon">
                                                        <img class="icon-gray" src="/images/calendar-event-text-message-gray.svg" alt="Message icon">
                                                    </button>
                                                </div>
                                            </div>
                                            <p>{{ $item->description }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="pagination-row d-flex">
                                <div class="page-count">Show 1 of {{ $reviews->lastPage() }} pages</div>
                                <div class="nav col-auto ml-auto pagination">
                                    <div id="pagination"></div>
                                </div>
                            </div>
                        </div>
                        <div id="reviews_send_container" role="tabpanel" class="review-invite-contents tab-pane fade active">
                            <h3>How it Works</h3>
                            <p>See how our referral process work and make the most out of it!</p>
                            <div class="process-steps">
                                <div class="row">
                                    <div class="col-12 col-md-4 process-steps--item">
                                        <figure>
                                            <img src="/images/step-complete-icon.svg" alt="Setting icon">
                                        </figure>
                                        <h5>Complete Jobs</h5>
                                        <p>Nullam fringilla dictum dolor, finibus dapibus mauris ultricies nec.</p>
                                    </div>
                                    <div class="col-12 col-md-4 process-steps--item">
                                        <figure>
                                            <img src="/images/step-send-invites-icon.svg" alt="Complete icon">
                                        </figure>
                                        <h5>Send Invites</h5>
                                        <p>Nullam fringilla dictum dolor, finibus dapibus mauris ultricies nec.</p>
                                    </div>
                                    <div class="col-12 col-md-4 process-steps--item">
                                        <figure>
                                            <img src="/images/step-review-icon.svg" alt="Review icon">
                                        </figure>
                                        <h5>Get Reviews</h5>
                                        <p>Nullam fringilla dictum dolor, finibus dapibus mauris ultricies nec.</p>
                                    </div>
                                </div>
                            </div>
                            <h3>Invite Your Clients</h3>
                            <p>Insert your clients’ email addresses and send them review requests!</p>
                            <div class="form-group">
                                {!! Form::text('review_emails',null,['class' => 'form-control', 'required' => 'required', 'id' => 'review_emails', 'placeholder' => 'Email addresses', 'multiple' => 'multiple', 'autocomplete' => 'off']) !!}
                                <button type="submit" class="btn btn--sqr btn-primary" id="send_email_invite">Send</button>
                                <div id="email_send_loading" class="mt-2" style="display:none;">
                                    <img src="/images/loader.png" width="24px" class="float-left">
                                    <span class="float-left ml-1 loader-text">Sending invitation(s), please stand by...</span>
                                </div>
                            </div>
                            <div class="devider"><span>Or send via Text</span></div>
                            <p>Insert one phone number at a time and send the review request!</p>
                            {!! Form::open(['url' => 'reviews/send', 'id' => 'send_txt_form']) !!}
                                <div class="phone-number-group-wrapper">
                                    <div class="phone-number-group d-flex">
                                        <div class="country-code">
                                            {!! Form::select('phone_country',$phone_countries,null,['class' => 'form-control', 'required' => 'required', 'id' => 'phone_country']) !!}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::text('phone',null,['class' => 'form-control', 'required' => 'required', 'id' => 'phone', 'placeholder' => 'Phone Number']) !!}
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn--sqr btn-primary" id="send_txt_invite">Send</button>
                                    <div id="txt_send_loading" class="mt-2" style="display:none;">
                                        <img src="/images/loader.png" width="24px" class="float-left">
                                        <span class="float-left ml-1 loader-text">Sending invitation, please stand by...</span>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                            <h3>Share Your Review Link</h3>
                            <p>You can share your review link by copying and sending.</p>
                            <div class="review-link-field d-flex">
                                <div class="link-wrap d-flex">
                                    <span class="ref-link">{{ env('APP_URL').'/review/'.$auth_user->public_reviews_code }}</span>
                                    <button type="button" class="btn copy-link green-text" id="copy_reviews_link">Copy Link</button>
                                </div>
                                <a class="btn mail-btn" href="mailto:?subject=Leave%20Review&amp;body={{ env('APP_URL').'/review/'.$auth_user->public_reviews_code }}">
                                    <img src="/images/outline-email-icon-green.svg" alt="Outline Email icon green">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('view_script')
<script type="text/javascript" src="/js/jquery-bar-rating/jquery.barrating.min.js"></script>
<script type="text/javascript" src="/js/jquery.twbsPagination.min.js"></script>
<script type="text/javascript" src="/js/select2/js/select2.min.js"></script>
<script type="text/javascript" src="/js/jquery.inputmask.min.js"></script>
<script type="text/javascript" src="/js/tagsinput/bootstrap-tagsinput.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        window.default_pagination_options = {
            totalPages: '{{ $reviews->lastPage() }}',
            initiateStartPageClick: false,
            visiblePages: 4,
            hideOnlyOnePage: true,
            lastClass: 'display-hidden',
            firstClass: 'display-hidden',
            paginationClass: 'pagination',
            startPage: 1,
            onPageClick: function (event, page) {
                $('body,html').animate({
                    scrollTop: $('.rating-reviews--wrap').offset().top,
                }, {
                    duration: 900
                });
                load_reviews(page);
            }
        }

        $('#pagination').twbsPagination(default_pagination_options);
        handle_star_ratings();

        $('#review_emails').tagsinput({
            allowDuplicates: false,
            tagClass: 'added-email-item',
            trimValue: true,
            maxTags: 100
        });

        $(document).on('change','.filter_item',function(){
            load_reviews(1);
            return false;
        });

        $(document).on('click','.make_call',function(){
            @if($user_twilio_phone)
                var phone = $(this).attr('data-phone');
                if (phone.length) {
                    App.call.process_outgoing_call(App.call.twilio_outgoing_obj,
                        phone,
                        '{{ $user_twilio_phone->phone }}',
                        'client',
                        $(this).attr('data-name'),
                        $(this).attr('data-client'),
                        $(this).attr('data-phone-format')
                    );
                }
                else{
                    App.render_message('info','No phone number found to make a call')
                }
            @else
                alert('Please purchase a new subscription to make calls')
            @endif
            return false;
        });

        $(document).on('click','.switch_reviews_tab',function(){
            if (!$(this).hasClass('active')) {
                $('.switch_reviews_tab').removeClass('active');
                $(this).addClass('active');
                var data_type = $(this).attr('data-type');
                if (data_type == 'feedback') {
                    $('#reviews_send_container').removeClass('show')
                    $('#reviews_list_container').addClass('show');
                }
                else{
                    $('#reviews_list_container').removeClass('show');
                    $('#reviews_send_container').addClass('show');
                }
                return false;
            }

            return false;
        });

        $(document).on('change','#phone_country',function(){
            set_country_mask($(this).val());
            return false;
        });

        $(document).on('submit','#send_email_form',function(e){
            e.preventDefault();
            return false;
        });

        $(document).on('click','#send_email_invite',function(){
            var emails = $('#review_emails').val();
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
                    App.render_message('warning','"' + non_valid_email + '" is not valid email address')
                }
                else{
                    $('#send_email_invite').hide();
                    $('#email_send_loading').show();
                    $.post('/reviews/send/invite',{ type: 'email', email: email_obj },function(data){
                        if (data.status) {
                            $('#email_send_loading').hide();
                            $('#send_email_invite').show();
                            $('#review_emails').tagsinput('removeAll');
                            $('#review_emails').val('');
                            App.render_message('success','Invitation sent successfully');
                        }
                        else{
                            App.render_message('info',data.error);
                            if (data.reload) {
                                location.reload();
                            }
                        }
                    },'json');
                }
            }
            else{
                App.render_message('info','Please enter at least one email')
            }

            return false;
        });

        $(document).on('submit','#send_txt_form',function(){
            var full_phone = $.trim($('#selected_phone_country_code').text()) + $('#phone').val().replace(/\D/g,'');
            location.href = 'sms:' + full_phone + '?body=' + 'How would you rate your experience with {{ $auth_user->name }}?';

            // $('#send_txt_invite').hide();
            // $('#txt_send_loading').show();
            // $.post('/reviews/send/invite',{ type: 'phone', phone: $('#phone').val(), country: $('#phone_country').val() },function(data){
            //     if (data.status) {
            //         $('#txt_send_loading').hide();
            //         $('#send_txt_invite').show();
            //         $('#phone').val('');
            //         App.render_message('success','Invitation sent successfully');
            //     }
            //     else{
            //         App.render_message('info',data.error);
            //     }
            // },'json');
            return false;
        });

        $(document).on('click','#copy_reviews_link',function(){
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val('{{ env('APP_URL').'/review/'.$auth_user->public_reviews_code }}').select();
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
                    '<span class="flag-text" id="selected_phone_country_code">' + state.text + ' ' + "</span>"
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

    var load_reviews = function(page){
        $.post('/reviews/filter', {
            five_star_filter: $('#five_star_filter').prop('checked') ? '1' : '0',
            four_star_filter: $('#four_star_filter').prop('checked') ? '1' : '0',
            three_star_filter: $('#three_star_filter').prop('checked') ? '1' : '0',
            two_star_filter: $('#two_star_filter').prop('checked') ? '1' : '0',
            one_star_filter: $('#one_star_filter').prop('checked') ? '1' : '0',
            written_reviews: $('#written_review_filter_reviews').prop('checked') ? '1' : '0',
            stars_only_reviews: $('#written_review_filter_stars').prop('checked') ? '1' : '0',
            sort_by_latest: $('#sort_by_latest').prop('checked') ? '1' : '0',
            sort_by_oldest: $('#sort_by_oldest').prop('checked') ? '1' : '0',
            page: page
        }, function(data) {
            if (data.status) {
                $('.rating-reviews--wrap').html(_.template($('#review_item_template').html())({
                    items: data.items
                }));

                handle_star_ratings();
                if (data.total_pages) {
                    $('#pagination').twbsPagination('destroy');
                    $('#pagination').twbsPagination($.extend({}, default_pagination_options, {
                        startPage: page,
                        totalPages: data.total_pages
                    }));
                }
            }
        },'json');
    }

    var handle_star_ratings = function(){
        $('.star-rating').barrating({
            theme: 'css-stars',
            showSelectedRating: false,
            readonly: true,
            allowEmpty: true,
            emptyValue: 0,
        });
    }

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
<script type="text/template" id="review_item_template">
    <% for (let i in items) { %>
    <div class="rating-reviews--item">
        <div class="title-row d-flex align-items-center">
            <select class="star-rating" style="display:none;" autocomplete="off">
                @foreach($rate_points as $item)
                    <option value="{{ $item }}" <%= items[i].rate == '{{ $item }}' ? 'selected="selected"' : '' %>>{{ $item }}</option>
                @endforeach
            </select>
            <span class="name"><%= items[i].reviewer_name %></span>
            <span class="time"><%= items[i].created_at %></span>
            <div class="action-trigers ml-auto">
                <button class="btn btn-call make_call <%= items[i].reviewer_phone ? '' : 'not-added' %>" data-name="<%= items[i].reviewer_name %>" data-phone="<%= items[i].reviewer_phone %>" data-phone-format="<%= items[i].reviewer_phone_format %>" data-client="<%= items[i].client_id %>">
                    <img class="icon-green" src="/images/calendar-event-call.svg" alt="Call icon">
                    <img class="icon-gray" src="/images/calendar-event-call-gray.svg" alt="Call icon">
                </button>
                <button class="btn btn-message">
                    <img class="icon-green" src="/images/calendar-event-email.svg" alt="Email icon">
                    <img class="icon-gray" src="/images/calendar-event-email-gray.svg" alt="Email icon">
                </button>
                <button class="btn btn-message">
                    <img class="icon-green" src="/images/calendar-event-text-message.svg" alt="Message icon">
                    <img class="icon-gray" src="/images/calendar-event-text-message-gray.svg" alt="Message icon">
                </button>
            </div>
        </div>
        <p><%= items[i].description %></p>
    </div>
    <% } %>
</script>
@endsection
