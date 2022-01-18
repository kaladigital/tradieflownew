@extends('layouts.master')
@section('view_css')
{{--<link rel="stylesheet" href="/js/jquery-ui/jquery-ui.css">--}}
<link rel="stylesheet" href="/js/upload_file/uploadfile.css">
@endsection
@section('content')
@if($auth_user->role == 'sales')
    @include('admin.left_sidebar_admin_menu',['active_page' => 'reviews'])
@else
    @include('dashboard.left_sidebar_full_menu',['active_page' => 'reviews'])
@endif
<div class="col-md-auto col-12 content-wrap reviews-setting">
    <div class="content-inner">
        <h2>Set Up Reviews</h2>
        <div class="profile-widget">
            <div class="row">
                <div class="col-12 col-lg-auto intro-col">
                    <h1 class="green-text">How to get More reviews</h1>
                    <div class="illustrator-row row">
                        <div class="col-12 col-sm-6 col-lg-12">
                            <div class="info-box">
                                <img src="/images/info-icon.svg" alt="Inco icon">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ullamcorper elementum
                                    nunc.
                                </p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-12">
                            <div class="illustration-wrap">
                                <img src="/images/setting-review-illustration.png" alt="Review illustration">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-auto mt-5 mt-lg-0 desc-col">
                    <h3>How it Works</h3>
                    <p>See how our referral process work and make the most out of it!</p>
                    {!! Form::model($auth_user,['action' => ['ReviewsController@setup'], 'method' => 'patch', 'autocomplete' => 'off', 'id' => 'reviews_setup_form']) !!}
                        <div class="process-steps">
                            <div class="row">
                                <div class="col-12 col-md-4 process-steps--item">
                                    <figure>
                                        <img src="/images/step-setting-icon.svg" alt="Setting icon">
                                    </figure>
                                    <h5>Set Up</h5>
                                    <p>Nullam fringilla dictum dolor, finibus dapibus mauris ultricies nec.</p>
                                </div>
                                <div class="col-12 col-md-4 process-steps--item">
                                    <figure>
                                        <img src="/images/step-complete-icon.svg" alt="Complete icon">
                                    </figure>
                                    <h5>Complete Jobs</h5>
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
                        <div class="inner-container">
                            <h6>Social Media Links</h6>
                            <p>Paste your Business page URLs to help you get more reviews on Social Media.</p>
                            <div class="input-link-row form-group">
                                {!! Form::url('facebook_reviews_url',null,['class' => 'form-control', 'id' => 'facebook_reviews_url', 'placeholder' => 'URL to Your Facebook Business Page']) !!}
                                {!! Form::label('facebook_reviews_url','URL to Your Facebook Business Page') !!}
                                <figure class="icon-wrap">
                                    <img class="icon" src="/images/social-icon-facebook.svg" alt="Facebook icon">
                                </figure>
                            </div>
                            <div class="url-form-wrap inner-container mb-3" id="facebook_wrong_link_container" style="display:none;">
                                <div class="card website-card-item">
                                    <img src="/images/error-icon.png" class="w32" alt="Refresh icon">
                                    <p><span class="red-text">Sorry, we cannot use this link! Please use a valid URL that has the following format: facebook.com/&lt;YourPage&gt;/reviews</span></p>
                                </div>
                            </div>
                            <div class="input-link-row form-group">
                                {!! Form::text('google_review_address',null,['class' => 'form-control', 'id' => 'google_review_address', 'placeholder' => 'Enter Your Business Address']) !!}
                                {!! Form::label('google_review_address','Enter Your Business Address') !!}
                                <figure class="icon-wrap">
                                    <img class="icon" src="/images/social-icon-google-map.svg" alt="Facebook icon">
                                </figure>
                            </div>
                            <h6>Business Logo</h6>
                            <p>
                                Upload the logo of your business so we can further personalize the review requests sent to your clients.
                            </p>
                            <div id="upload_logo"></div>
                        </div>
                        <div class="action-row">
                            <button type="submit" class="btn btn--round btn-primary">Save</button>
                        </div>
                        {!! Form::hidden('google_place_id',null,['id' => 'google_place_id']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('view_script')
<script type="text/javascript" src="/js/upload_file/jquery.form.min.js"></script>
<script type="text/javascript" src="/js/upload_file/jquery.uploadfile.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&libraries=places"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#upload_logo').uploadFile({
            url: '/reviews/business/logo',
            dragDrop: true,
            fileName: 'qqfile',
            multiple : false,
            returnType: 'json',
            showStatusAfterSuccess: false,
            showAbort: false,
            showDone: false,
            uploadButtonClass : '',
            dragDropStr: $('#upload_logo_box_template').html(),
            ajax_drag_drop_class: '',
            browse_btn_class: 'browse_image',
            showProgressBar: false,
            showLoading: false,
            onCancel: function(files,pd) {
                $('.ajax-file-upload-statusbar').remove();
            },
            onError: function(files,status,errMsg,pd)
            {
                $('.ajax-file-upload-statusbar').remove();
            },
            onSuccess:function(files,data,xhr){
                if (data.status) {
                    $('#review_logo_img').attr('src','/review-logo/' + data.file_name + '?t=' + (new Date()).getTime())
                    $('.upload-box').addClass('uploaded');
                    App.render_message('success','Logo successfully uploaded');
                }
                else{
                    App.render_message('error',data.error);
                }
            }
        });

        $(document).on('submit','#reviews_setup_form',function() {
            var facebook_url = $('#facebook_reviews_url').val();
            var facebook_added = facebook_url.length;
            var google_address = $('#google_review_address').val();
            if (!facebook_added && !google_address.length) {
                $('#facebook_reviews_url').focus();
                App.render_message('info', 'Please enter your Facebook Business page url or Google Address in order to receive reviews');
                return false;
            }

            if (facebook_added && !is_facebook_review_page_link_valid()) {
                return false;
            }
            return true;
        });

        $(document).on('keyup','#facebook_reviews_url',function(){
            var facebook_url = $.trim($(this).val());
            if (facebook_url.length && !is_facebook_review_page_link_valid()) {
                return false;
            }
            return false;
        });

        $(document).on('click','#delete_review_logo',function(){
            $('#review_logo_img').attr('src','/images/upload-img.svg?t=' + (new Date).getTime());
            $('.upload-box').removeClass('uploaded');
            $.post('/reviews/remove/logo',{});
            return false;
        });

        /**Default US country*/
        @if($auth_user->Country)
            var lat = 1 * '{{ $auth_user->Country->lat }}';
            var lng = 1 * '{{ $auth_user->Country->lng }}';
            var def_country = '{{ $auth_user->Country->code }}';
        @else
            var lat = 50.064192;
            var lng = -130.605469;
            var def_country = 'us';
        @endif

        const center = { lat: lat, lng: lng };
        const defaultBounds = {
            north: center.lat + 0.1,
            south: center.lat - 0.1,
            east: center.lng + 0.1,
            west: center.lng - 0.1,
        };
        const input = document.getElementById("google_review_address");
        const options = {
            bounds: defaultBounds,
            componentRestrictions: { country: def_country },
            fields: ["address_components", "geometry", "icon", "name", "place_id"],
            strictBounds: false,
            types: ["establishment"],
        };

        const autocomplete = new google.maps.places.Autocomplete(input, options);
        autocomplete.addListener("place_changed", () => {
            const place = autocomplete.getPlace();
            if (place && place.place_id) {
                $('#google_place_id').val(place.place_id);
            }
        });
    });

    var is_facebook_review_page_link_valid = function(){
        var facebook_url = $.trim($('#facebook_reviews_url').val());
        var facebook_obj = facebook_url.split('/');
        if (facebook_url.length && (!/^(https?:\/\/)?((w{3}\.)?)facebook.com\/.*/i.test(facebook_url) || facebook_obj[facebook_obj.length - 1] !== 'reviews')) {
            $('#facebook_wrong_link_container').show();
            $('#facebook_reviews_url').focus();
            return false;
        }
        else{
            $('#facebook_wrong_link_container').hide();
        }

        return true;
    }
</script>
<script type="text/template" id="upload_logo_box_template">
    <div class="upload-box {{ $auth_user->reviews_logo ? 'uploaded' : '' }}">
        <figure class="icon-wrap">
            <img src="{{ $auth_user->reviews_logo ? '/review-logo/'.$auth_user->reviews_logo : '/images/upload-img.svg' }}" alt="Image upload" class="icon single-img" id="review_logo_img">
            <img src="/images/upload-img-multiple.svg" alt="Multiple image upload" class="icon multiple-img">
        </figure>
        <h6>
            Drop your image here or
            <button type="button" class="btn browse-img-btn browse_image">browse</button>
        </h6>
        <span>Supports: JPG, PNG</span>
        <div class="action-row align-items-center">
            <button type="button" class="btn delete-btn" id="delete_review_logo">Delete Image</button>
            <button type="button" class="btn btn--round green-outline ml-auto browse_image">Replace Image</button>
        </div>
    </div>
</script>
@endsection
