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
                @include('settings.settings_menu',['active_page' => 'phone_numbers', 'user_onboaridng' => $user_onboarding])
                <div class="col-md-auto col-12 contents">
                    <div class="content-body">
                        <h3>Phone Numbers</h3>
                        <div class="visual-section">
                            <div class="inner-container d-flex align-items-center">
                                <div class="note-wrap order-md-2 order-lg-1 d-flex">
                                    <div class="icon">
                                        <img src="/images/info-icon.svg" alt="Info icon">
                                    </div>
                                    <p class="info">
                                        These numbers allow you to track every lead that comes through to your website and tradieflow platform, so you can get the best marketing data for your business.
                                    </p>
                                </div>
                                <div class="graphics-figure ml-auto order-md-1 order-lg-2">
                                    <img src="/images/phone-number-visual-figure.svg" alt="Phone number visual figure">
                                </div>
                            </div>
                        </div>
                        <h6>Your Purchased Numbers</h6>
                        <p>These are you purchased phone numbers here.</p>
                        <div class="added-items added-numbers">
                            <div class="added-items-inner">
                                <div class="item-row row items-heading no-gutters">
                                    <div class="col-auto info-col">
                                        <h6>Phone Number</h6>
                                    </div>
                                    <div class="col-auto redirected-col">
                                        <h6>Redirected to</h6>
                                    </div>
                                    <div class="col-auto action-col ml-auto">
                                        <h6>&nbsp;</h6>
                                    </div>
                                </div>
                                <div class="item-row row no-gutters">
                                    <div class="col-auto info-col d-flex align-items-center">
                                        <img src="/images/{{ strtolower($user_twilio_number->country_code) }}.svg" alt="Australia flag" class="flag">
                                        <h6>{{ $user_twilio_number->friendly_name }}</h6>
                                    </div>
                                    <section id="phone_number_container">
                                        @if($redirect_number_data['phone_numbers'])
                                            <div class="col-auto redirected-col d-flex align-items-center">
                                                @foreach($redirect_number_data['phone_numbers'] as $key => $item)
                                                    <span class="bubble {{ isset($phone_number_colors[$key]) ? $phone_number_colors[$key] : '' }} cursor-pointer edit_numbers">
                                                        {{ $item['name'] }}
                                                    </span>
                                                @endforeach
                                                @if($redirect_number_data['show_more'])
                                                    <span class="bubble orange cursor-pointer edit_numbers">+ {{ count($redirect_number_data['show_more']) }} more</span>
                                                @endif
                                            </div>
                                        @endif
                                    </section>
                                    <div class="col-auto action-col ml-auto">
                                        <button class="btn btn--round green-outline edit_numbers" id="edit_phone_number_btn">{{ $redirect_number_data['phone_numbers'] ? 'Edit' : 'Add Numbers' }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="add-number-modal modal-wrapper" id="add_phones_modal">
                            <div class="modal-inner card">
                                <div class="card-header d-flex align-items-center">
                                    <h5>Add Numbers</h5>
                                    <button class="btn close-modal ml-auto close_modal_btn">
                                        <img src="/images/Close.svg" alt="close icon">
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-wrap">
                                        <h6>Add Phone Number</h6>
                                        <form>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="name" placeholder="Title/Name">
                                                <label for="name">Title/Name</label>
                                            </div>
                                            <div class="form-group no-gutters d-flex phone-number-field">
                                                <div class="select-flag d-flex align-items-center col-auto">
                                                    <div class="dropdown-wrap position-relative">
                                                        <div class="selected-flag" data-code="{{ $countries['0']->code }}" data-country="{{ $countries['0']->country_id }}">
                                                            <img src="/images/{{ $countries['0']->code }}.svg" alt="">
                                                        </div>
                                                        <ul id="countryFlag" class="dropdown-items position-absolute">
                                                            @foreach($countries as $item)
                                                                <li class="select_country" data-code="{{ $item->code }}" data-country="{{ $item->country_id }}">
                                                                    <img src="/images/{{ $item->code }}.svg" alt=""> <span>{{ $item->number }}</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="form-group col-auto mb-0">
                                                    <input type="text" class="form-control" id="phone_number" placeholder="Phone Number">
                                                    <label for="phone_number">Phone Number</label>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn--round btn-secondary" id="cancel_phone_update" style="display:none;">Cancel</button>
                                            <button type="submit" class="btn btn--round btn-primary" id="add_phone_number" data-type="add">Add Number</button>
                                        </form>
                                    </div>
                                    <div class="added-numbers">
                                        <h6>Added Numbers</h6>
                                        <section id="added_numbers_container"></section>
                                    </div>
                                </div>
                                @if($user_onboarding->status == 'completed')
                                    <div class="card-footer">
                                        <div class="btn-row">
                                            <button class="btn btn--sqr btn-secondary close_modal_btn">Cancel</button>
                                            <button class="btn btn--sqr btn-primary" id="save_phone_numbers">Save Changes</button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($user_onboarding->status == 'pending')
                        <div class="action-row">
                            <a href="/settings/skip/phone-numbers" class="btn btn--round btn-secondary">Skip</a>
                            <button type="button" id="save_onboarding_phone_numbers" class="btn btn--round btn-primary">Continue</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
{!! Form::open(['url' => 'settings/phone-numbers', 'method' => 'POST', 'id' => 'phone_numbers_form']) !!}
{!! Form::hidden('phone_numbers',null,['id' => 'phone_numbers']) !!}
{!! Form::close() !!}
@endsection
@section('view_script')
<script type="text/javascript" src="/js/jquery.inputmask.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        window.phone_number_items = [];
        window.phone_number_colors = <?php echo json_encode($phone_number_colors) ?>;

        $(document).on('click','.edit_numbers',function(){
            set_country_mask('{{ $countries['0']->code }}');
            $('#name,#phone_number').val('');
            $('.select_country').first().trigger('click');
            $('#cancel_phone_update').hide();
            $('#add_phone_number').attr('data-type','add').text('Add Number');
            $('#add_phones_modal').addClass('expanded');
            return false;
        });

        $(document).on('click','.close_modal_btn',function(){
            $('#add_phones_modal').removeClass('expanded');
            return false;
        });

        $(document).on('click','.dropdown-wrap',function(){
            $('#cancel_phone_update').hide();
            $(this).toggleClass('expanded');
            return false;
        });

        $(document).on('click','.select_country',function(){
            $('.selected-flag').attr('data-country',$(this).attr('data-country'));
            set_country_mask($(this).attr('data-code'));
            return false;
        });

        $(document).on('click','.remove_phone_number',function(){
            $(this).closest('.phone_number_item').remove();
            if (!$('.phone_number_item').length) {
                $('#added_numbers_container').html($('#no_phone_numbers_template').html());
            }
            update_display_items();
            return false;
        });

        $(document).on('click','#add_phone_number',function(){
            var name = $.trim($('#name').val());
            if (!name.length) {
                $('#name').focus();
                return false;
            }

            var phone = $('#phone_number').val();
            if (!phone.length) {
                $('#phone_number').focus();
                return false;
            }

            if ($('#no_phone_numbers_alert').length) {
                $('#no_phone_numbers_alert').remove();
            }

            if ($(this).attr('data-type') == 'add' || !$('.active_phone_item').length) {
                $('#added_numbers_container').prepend(_.template($('#add_phone_number_template').html())({
                    item: {
                        id: '',
                        name: name,
                        phone: phone,
                        code: $('.selected-flag').attr('data-code'),
                        country_id: $('.selected-flag').attr('data-country')
                    }
                }));
            }
            else{
                if ($('.active_phone_item').length) {
                    var id = $('.active_phone_item').attr('data-id');
                    $('.active_phone_item').replaceWith(_.template($('#add_phone_number_template').html())({
                        item: {
                            id: id,
                            name: name,
                            phone: phone,
                            code: $('.selected-flag').attr('data-code'),
                            country_id: $('.selected-flag').attr('data-country')
                        }
                    }));
                }
                $('#cancel_phone_update').hide();
                $(this).attr('data-type','add').text('Add Number');
            }

            $('#name,#phone_number').val('');
            update_display_items();
            return false;
        });

        $(document).on('click','#save_phone_numbers',function(){
            @if($user_onboarding->status == 'completed')
                $('#phone_numbers').val(JSON.stringify(get_phone_numbers()));
                $('#phone_numbers_form').submit();
            @else
                $('#add_phones_modal').removeClass('expanded');
            @endif
            return false;
        });

        $(document).on('click','#save_onboarding_phone_numbers',function(){
            $('#phone_numbers').val(JSON.stringify(get_phone_numbers()));
            $('#phone_numbers_form').submit();
            return false;
        });

        $(document).on('click','.edit_phone_number',function(){
            var closest_obj = $(this).closest('.phone_number_item');
            $('#add_phone_number').attr('data-type','edit').text('Update Number');
            $('#name').val($.trim(closest_obj.find('.phone_name_item').text()));
            $('#phone_number').val($.trim(closest_obj.find('.phone_number_phone_item').text()));
            $('.select_country[data-country="' + closest_obj.attr('data-country') + '"]').trigger('click');
            $('#cancel_phone_update').show();
            $('.phone_number_item').removeClass('active_phone_item');
            closest_obj.addClass('active_phone_item');
            return false;
        });

        $(document).on('click','#cancel_phone_update',function(){
            $('#name,#phone_number').val('');
            $('.select_country').first().trigger('click');
            $(this).hide();
            $('#add_phone_number').attr('data-type','add').text('Add Number');
        });

        @if($redirect_numbers)
            @foreach($redirect_numbers as $item)
                $('#added_numbers_container').append(_.template($('#add_phone_number_template').html())({
                    item: <?php echo json_encode($item) ?>,
                }));
            @endforeach
        @else
            $('#added_numbers_container').html($('#no_phone_numbers_template').html());
        @endif
        set_country_mask('{{ $countries['0']->code }}');
    });

    var set_country_mask = function(country_code) {
        $('.dropdown-wrap').removeClass('expanded');
        $('.selected-flag').attr('data-code',country_code).find('img').attr('src','/images/' + country_code + '.svg');
        switch (country_code) {
            case 'au':
                $('#phone_number').inputmask("(99) 9999 9999",{ clearIncomplete: true });
            break;
            case 'us':
            case 'ca':
                $('#phone_number').inputmask('(999) 999-9999',{ clearIncomplete: true });
            break;
            case 'gb':
                $('#phone_number').inputmask('99 999 9999',{ clearIncomplete: true });
            break;
        }

        $('#phone_number').focus();
    }

    var get_phone_numbers = function() {
        var phone_number_items = [];
        var phone_numbers = $('.phone_number_item');
        if (phone_numbers.length) {
            $.each(phone_numbers,function(key,value){
                phone_number_items.push({
                    id: $(this).attr('data-id'),
                    country_id: $(this).attr('data-country'),
                    name: $.trim($(this).find('.phone_name_item').text()),
                    phone: $.trim($(this).find('.phone_number_phone_item').text())
                });
            });
        }

        return phone_number_items;
    }

    var update_display_items = function(){
        @if($user_onboarding->status == 'pending')
            var phone_numbers = get_phone_numbers();
            if (phone_numbers.length) {
                var phone_items = phone_numbers.slice(0,4);
                $('#phone_number_container').html(_.template($('#phone_number_display_template').html())({
                    phone_numbers: phone_items,
                    phone_number_colors: phone_number_colors,
                    has_more_items: phone_numbers.slice(4).length
                }));
                $('#edit_phone_number_btn').text('Edit');
            }
            else{
                $('#edit_phone_number_btn').text('Add Numbers');
                $('#phone_number_container').empty();
            }
        @endif
    }
</script>
<script type="text/template" id="add_phone_number_template">
    <div class="added-items no-gutters d-flex align-items-center phone_number_item" data-id="<%= item.id %>" data-country="<%= item.country_id %>">
        <div class="col-auto items info-col d-flex align-items-center">
            <img src="/images/<%= item.code %>.svg" alt="Australia flag" class="flag">
            <span class="phone_number_phone_item"><%= item.phone %></span>
        </div>
        <div class="col-auto items redirected phone_name_item">
            <%= item.name %>
        </div>
        <div class="ml-auto col-auto items actions d-flex align-items-center">
            <button class="btn edit-btn edit_phone_number">
                <img src="/images/edite-icon.svg" alt="Edit icon">
            </button>
            <button class="btn remove-btn remove_phone_number">
                <img src="/images/close-red.svg" alt="Close icon">
            </button>
        </div>
    </div>
</script>
<script type="text/template" id="no_phone_numbers_template">
    <div class="alert alert-warning" id="no_phone_numbers_alert">
        No phone numbers added
    </div>
</script>
<script type="text/template" id="phone_number_display_template">
    <div class="col-auto redirected-col d-flex align-items-center">
        <% for (let i = 0; i < phone_numbers.length; i++) { %>
            <span class="bubble <%= phone_number_colors[i] ? phone_number_colors[i] : '' %> cursor-pointer edit_numbers">
                <%= phone_numbers[i].name %>
            </span>
        <% } %>
        <% if (has_more_items) { %>
            <span class="bubble orange cursor-pointer edit_numbers">+ <%= has_more_items %> more</span>
        <% } %>
    </div>
</script>
@endsection
