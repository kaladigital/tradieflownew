@extends('layouts.master')
@section('content')
    @if($auth_user->role == 'sales')
        @include('admin.left_sidebar_admin_menu',['active_page' => 'settings'])
    @else
        @include('dashboard.left_sidebar_menu',['active_page' => 'settings'])
    @endif
    <div class="col-md-auto col-12 content-wrap">
        <div class="content-inner">
            <div>
                <h2 class="page-title">Settings</h2>
                <div class="content-widget row no-gutters">
                    @include('settings.settings_menu',['active_page' => 'forms', 'user_onboarding' => $user_onboarding])
                    <div class="col-md-auto col-12 contents">
                        <div class="content-body">
                            <h3>Forms</h3>
                            <div class="visual-section">
                                <div class="inner-container d-flex align-items-center">
                                    <div class="note-wrap order-md-2 order-lg-1 d-flex">
                                        <div class="icon">
                                            <img src="/images/info-icon.svg" alt="Info icon">
                                        </div>
                                        <p class="info">
                                            Track every event that goes on, on your website.  All website forms will come directly into your tradieflow app, so you can handle them from one place.
                                        </p>
                                    </div>
                                    <div class="graphics-figure ml-auto order-md-1 order-lg-2">
                                        <img src="/images/forms-visual-figure.svg" alt="Forms visual figure">
                                    </div>
                                </div>
                            </div>
                            <h6>Automatic Form Tracking</h6>
                            <p>Our system automatically finds the forms on your website. Type your website’s URL to start the tracking process! </p>
                            <div class="url-form-wrap inner-container">
                                @include('elements.alerts')
                                {!! Form::open(['url' => '/settings/form/track']) !!}
                                    <div class="position-relative">
                                        <div class="form-group">
                                            {!! Form::text('website',null,['class' => 'form-control', 'id' => 'website', 'placeholder' => 'URL of Your Website']) !!}
                                            <label for="websiteUrl">URL of Your Website</label>
                                        </div>
                                        <button type="submit" class="btn btn--sqr btn-primary">Examine Forms</button>
                                    </div>
                                {!! Form::close() !!}
                                @if($pending_forms->count())
                                    @foreach($pending_forms as $item)
                                        <div class="card website-card-item">
                                            <img src="/images/refresh-icon.svg" alt="Refresh icon">
                                            <p><strong class="green-text">We are processing your request on {{ $item->website }}</strong> This might take anything between a few minutes to 15-20 minutes depending on the number of pages on your website.  Feel free to click on  the “Continue” button to continue filling out the other pages.</p>
                                        </div>
                                    @endforeach
                                    <div class="mt-2">
                                        @include('elements.pagination',['paginator' => $pending_forms])
                                    </div>
                                @endif
                            </div>
                            <div class="row form-tracking">
                                <div class="col-12">
                                    <h6>Manual Form Tracking</h6>
                                    <p>Add your forms to our system manually. To do this you might need some help from a developer.</p>
                                    <button class="btn btn-primary btn--sqr d-flex add-item-btn" id="add_manual_btn">
                                        <img src="/images/plus-Icons.svg" alt="Plus icon"> Add Item Manually
                                    </button>
                                    <div class="add-item-form" id="add_manual_container" style="display:none;">
                                        <form>
                                            <div class="form-row no-gutters align-items-center">
                                                <div class="col-12 col-lg-auto order-2 field-col">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="manual_page_url" placeholder="URL of Form’s Page">
                                                        <label for="manual_page_url">URL of Form’s Page</label>
                                                    </div>
                                                </div>
                                                <div class="col-auto order-3 save-col">
                                                    <button class="btn btn-primary btn--sqr" id="save_manual_url">Save</button>
                                                </div>
                                                <div class="col-auto order-1 delet-col">
                                                    <button class="btn" id="hide_manual_tracking">
                                                        <img src="/images/delete-icon.svg" alt="Delete icon">
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    @if($completed_forms_list)
                                        <br>
                                        <div class="select-website">
                                            <div class="form-group select-group">
                                                {!! Form::select('select_website',$completed_forms_list,null,['id' => 'select_website', 'class' => 'form-control', 'placeholder' => 'Select', 'autocomplete' => 'off']) !!}
                                                <label for="select_website">Select Website</label>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="added-items" id="site_header_container" style="display:none;">
                                        <div class="added-items-inner">
                                            <div class="item-row row items-heading no-gutters">
                                                <div class="col-auto info-col">
                                                    &nbsp;
                                                </div>
                                                <div class="col-auto remove-col">
                                                    <h6>Remove?</h6>
                                                </div>
                                                <div class="col-auto switch-col">
                                                    <h6>Allow Tracking</h6>
                                                </div>
                                            </div>
                                            <div class="item-row row no-gutters">
                                                <div class="col-auto info-col">
                                                    <h6><strong>Remove or Turn ON/OFF All</strong></h6>
                                                </div>
                                                <div class="col-auto remove-col">
                                                    <button class="btn" id="remove_website">
                                                        <img src="/images/delete-icon.svg" alt="Delet icon">
                                                    </button>
                                                </div>
                                                <div class="col-auto switch-col">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input" id="switch_all_toggle">
                                                        <label class="custom-control-label" for="switch_all_toggle"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="site_page_container"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row tracking-code" id="site_track_container" style="display:none;"></div>
                        </div>
                        <div class="action-row">
                            @if($user_onboarding->status == 'pending')
                                <a href="/settings/skip/forms" class="btn btn--round btn-secondary">Skip</a>
                                <a href="/settings/skip/forms" class="btn btn--round btn-primary">Continue</a>
                            @else
                                <button type="submit" class="btn btn--round btn-primary">Save</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{--    <div class="modal fade" id="form_title_edit_modal" tabindex="-1" role="dialog" aria-labelledby="inCallModalLabel" aria-hidden="true">--}}
{{--        <div class="modal-dialog" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-header">--}}
{{--                    <span class="modal-title" id="inCallModalLabel">Edit Form Title</span>--}}
{{--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                        <img src="/images/in-call-close-icon.svg" alt="Close icon gray">--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--                <div class="modal-body">--}}
{{--                    {!! Form::open(['url' => 'settings/forms', 'id' => 'user_title_form']) !!}--}}
{{--                        <div class="form-group">--}}
{{--                            {!! Form::text('form_title',null,['class' => 'form-control', 'id' => 'form_title']) !!}--}}
{{--                            {!! Form::label('form_title','Title') !!}--}}
{{--                        </div>--}}
{{--                        <button type="submit" class="btn btn-primary d-flex align-items-center">Update</button>--}}
{{--                    {!! Form::close() !!}--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
@endsection
@section('view_script')
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('change','#select_website',function(){
            var website_id = $(this).val();
            if (website_id) {
                $.post('/settings/forms/website',{ id: website_id },function(data){
                    if (data.status) {
                        var all_checked = true;
                        var tracking_code = '';
                        $('#site_page_container').empty();
                        $.each(data.user_form_pages,function(key,value){
                            if (!value.allow_track) {
                                all_checked = false;
                            }

                            $('#site_page_container').append(_.template($('#site_item_template').html())({
                                item : value
                            }));

                            tracking_code = value.tracking_code;
                        });

                        $('#switch_all_toggle').prop('checked',all_checked);
                        $('#site_header_container').show();

                        $('#site_page_container').show();
                        $('#site_track_container').html(_.template($('#site_track_template').html())({
                            tracking_code: tracking_code
                        })).show();
                    }
                    else{
                        location.reload();
                    }
                },'json');
            }
            else{
                $('#site_header_container').hide();
                $('#switch_all_toggle').prop('checked',false);
                $('#site_page_container').empty();
                $('#site_track_container').empty();
                $('#add_manual_container').hide();
            }
            return false;
        });

        $(document).on('change','#switch_all_toggle',function(){
            var is_checked = $(this).prop('checked');
            $('.allow_track_item').prop('checked',is_checked);
            $.post('/settings/forms/check/tracking',{ id: $('#select_website').val(), allow_track: is_checked ? '1' : '' },function(data){
                if (data.status) {
                    $('#site_track_container').html(_.template($('#site_track_template').html())({
                        tracking_code: data.tracking_code
                    })).show();
                }
            },'json');
            return false;
        });

        $(document).on('click','#remove_website',function(){
            $.post('/settings/forms/remove/form',{ id: $('#select_website').val() },function(data){
                location.reload();
            },'json');
        });

        $(document).on('click','.delete_site_item',function(){
            var closest_obj = $(this).closest('.site_item');
            closest_obj.remove();
            $.post('/settings/forms/remove/page',{ form_id: $('#select_website').val(), id: closest_obj.attr('data-id')}, function(data){
                if (data.status) {
                    $('#site_track_container').html(_.template($('#site_track_template').html())({
                        tracking_code: data.tracking_code
                    })).show();
                }
            },'json');
        });

        $(document).on('change','.allow_track_item',function(){
            var page_form_id = $(this).closest('.site_item').attr('data-id');
            var is_checked = $(this).prop('checked');
            $.post('/settings/forms/page/track',{ form_id: $('#select_website').val(), id: page_form_id, allow_track: (is_checked) ? '1' : '0' }, function(data){
                if (data.status) {
                    $('#site_track_container').html(_.template($('#site_track_template').html())({
                        tracking_code: data.tracking_code
                    })).show();
                }
            });
        });

        $(document).on('click','#copy_code',function(){
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($('#tracking_code_content').text()).select();
            document.execCommand("copy");
            $temp.remove();
            App.render_message('success','Successfully copied to clipboard');
            return false;
        });

        $(document).on('click','#add_manual_btn',function(){
            $('#manual_page_url').val('');
            $('#save_manual_url').attr('disabled','disabled');
            $('#add_manual_container').fadeIn();
            $(this).addClass('display-hidden');
            return false;
        });

        $(document).on('click','#hide_manual_tracking',function(){
            $('#add_manual_container').hide();
            $('#manual_page_url').val('');
            $('#add_manual_btn').removeClass('display-hidden');
            return false;
        });

        $(document).on('keyup','#manual_page_url',function(){
            if ($.trim($(this).val()).length) {
                $('#save_manual_url').removeAttr('disabled');
            }
            else{
                $('#save_manual_url').attr('disabled','disabled');
            }
            return false;
        });

        $(document).on('click','#save_manual_url',function(){
            var page_url = $.trim($('#manual_page_url').val());
            if (page_url.length) {
                $.post('/settings/forms/manual/tracking',{'url' : page_url },function(data){
                    if (data.status) {
                        location.reload();
                    }
                    else{
                        if (data.reload) {
                            location.reload();
                        }
                        else{
                            App.render_message('info',data.error);
                        }
                    }
                },'json');
            }
            else{
                $('#manual_page_url').focus();
            }
            return false;
        });

        $(document).on('click','.edit_form_item',function(){
            $('.site_item').removeClass('active-form-item');
            var closest_obj = $(this).closest('.site_item');
                closest_obj.addClass('active-form-item');
            $('#form_title').val($.trim(closest_obj.find('.form_title_text').text()));
            $('#form_title_edit_modal').modal('show');
            return false;
        });

        $(document).on('keyup','.form_title_input',function(){
            $(this).closest('.site_item').find('.form_title_text').text($(this).val());
        });

        // $(document).on('mouseenter','.site_item',function(){
        //     $(this).find('.form_title_text').hide();
        //     $(this).find('.form_title_input').show();
        //     return false;
        // });

        $(document).on('mouseleave','.site_item',function(){
            // $(this).find('.form_title_input').hide();
            // $(this).find('.form_title_text').show();

            var form_title = $.trim($(this).find('.form_title_input').val());
            if (form_title.length) {
                $(this).find('.form_title_text').text(form_title);
                $.post('/settings/forms/update/form/title', { id: $(this).attr('data-id'), title: form_title }, function (data) {}, 'json');
            }

            return false;
        });
    });
</script>
<script type="text/template" id="site_item_template">
    <div class="item-row row no-gutters site_item" data-id="<%= item.user_form_page_form_id %>">
        <div class="col-auto info-col">
            <h6>
                <span class="form_title_text"><%= item.display_name %></span>
                <input type="text" class="form_title_input" value="<%= item.display_name %>">
            </h6>
            <p>
                Form found on:
                <a href="<%= item.url %>"><%= item.url %></a>
            </p>
        </div>
        <div class="col-auto remove-col">
            <button type="button" class="btn delete_site_item">
                <img src="/images/delete-icon.svg" alt="Delete icon">
            </button>
        </div>
        <div class="col-auto switch-col">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input allow_track_item" id="custom_switch_<%= item.user_form_page_form_id %>" <%= item.allow_track ? 'checked' : '' %>>
                <label class="custom-control-label" for="custom_switch_<%= item.user_form_page_form_id %>"></label>
            </div>
        </div>
    </div>
</script>
<script type="text/template" id="site_track_template">
    <div class="col-12">
        <h6>Your Tracking Code</h6>
        <p>Please copy-paste and add the following code to the source code of your website. This will allow us to track your forms.</p>
        <div class="card tracking-code-card">
            <div class="code-wrap">
                <span>Tracking Code</span>
                <pre id="tracking_code_content">
                    &lt;script type=&quot;text/javascript&quot;&gt;
                      <%= tracking_code %>
                    &lt/script&gt;
                </pre>
            </div>
        </div>
        <button class="btn btn-primary d-flex align-items-center copy-code-btn" id="copy_code"><img class="icon" src="/images/copy-icon.svg" alt="Copy icon"> Copy Code</button>
    </div>
</script>
@endsection
