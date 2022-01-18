@extends('layouts.master')
@section('view_css')
<link rel="stylesheet" href="/js/jquery-confirm/jquery-confirm.min.css">
<link rel="stylesheet" href="/js/jquery-ui/jquery-ui.css">
<link rel="stylesheet" href="/js/jquery-datetimepicker/jquery.datetimepicker.min.css">
<link rel="stylesheet" href="/js/select2/css/select2.min.css">
@endsection
@section('content')
    @if($auth_user->role == 'sales')
        @include('admin.left_sidebar_admin_menu',['active_page' => 'history'])
    @else
        @include('dashboard.left_sidebar_full_menu',['active_page' => 'history'])
    @endif
    <div class="col-md-auto col-12 content-wrap history-page">
        <div class="content-inner">
            <h2>History</h2>
            <div class="row">
                <div class="col-12 col-lg-8 history-infos-column">
                    <div class="profile-widget history-log">
                        <div class="row align-items-center">
                            <div class="col-12 col-md-auto title-column">
                                <h6>Previous Calls</h6>
                            </div>
                            <div class="col-12 nav-tabs-column col-md-auto mt-4 mt-md-0 ml-md-auto">
                                <ul class="nav nav-tabs action-triger">
                                    <li class="nav-item">
                                        <a class="nav-link {{ !$request['type'] ? 'active' : ''  }}" href="/history">All ({{ $totals['0']->total + $totals['1']->total + $totals['2']->total + $totals['3']->total }})</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $request['type'] == 'missed' ? 'active' : ''  }}" href="/history?type=missed">Missed ({{ $totals['0']->total }})</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $request['type'] == 'leads' ? 'active' : ''  }}" href="/history?type=leads">Leads ({{ $totals['1']->total }})</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $request['type'] == 'forms' ? 'active' : ''  }}" href="/history?type=forms">Forms ({{ $totals['2']->total }})</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <ul class="call-logs">
                                    @foreach ($items as $item)
                                        <li class="log-item history_item cursor-pointer {{ $item->history_type.'-call' }}" data-id="{{ $item->item_id }}" data-type="{{ $item->history_type }}">
                                            <div class="row align-items-center">
                                                <div class="col-12 col-md-auto mb-4 mb-md-0 d-flex">
                                                    <figure class="icon">
                                                        @switch($item->history_type)
                                                            @case('missed')
                                                                <img src="/images/missed-call-icon.svg" alt="Missed call icon">
                                                            @break
                                                            @case('incoming')
                                                                <img src="/images/Incoming-call-icon.svg" alt="Incoming call icon">
                                                            @break
                                                            @case('outgoing')
                                                                <img src="/images/outgoing-call-icon.svg" alt="Outgoing call icon">
                                                            @break
                                                            @case('forms')
                                                                <img src="/images/form-icon-blue.svg" alt="Form icon">
                                                            @break
                                                        @endswitch
                                                    </figure>
                                                    <div class="info">
                                                        <h5>
                                                            @if($item->name)
                                                                {{ $item->name ? $item->name : 'N/A' }}
                                                                {{ $item->company ? '('.$item->company.')' : '' }}
                                                            @else
                                                                {{ $item->phone ? $item->phone : '' }}
                                                            @endif
                                                        </h5>
                                                        @if($item->ongoing_value || $item->upfront_value)
                                                            <div class="value">Value: ${{ number_format($item->ongoing_value ? $item->ongoing_value : $item->upfront_value,2) }}</div>
                                                        @else
                                                            <div class="value unknown">Value: Unknown</div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-auto ml-md-auto time" title="{{ $item->created_at->format('m/d/Y H:i') }}">
                                                    @if($today_date_format == $item->created_at->format('Y-m-d'))
                                                        {{ $item->created_at->format('H:i') }}
                                                    @else
                                                        {{ \App\Helpers\Helper::convertDateToFriendlyFormat($item->created_at->format('Y-m-d H:i:s')) }}
                                                    @endif
                                                </div>
                                                <div class="col-auto ml-auto ml-md-0">
                                                    @if($item->phone)
                                                        <button type="button" class="btn call-btn call_history_item" data-id="{{ $item->item_id }}" data-client="{{ $item->client_id }}" data-type="{{ $item->type }}" data-phone="{{ $item->phone }}" data-name="{{ $item->name ? $item->name : $item->phone }}">
                                                            <img src="/images/call-green-round.svg" alt="Call icon">
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="row pagination-row no-gutters">
                                    <div class="page-count col-auto">
                                        Show {{ $items->currentPage() }} of {{ $items->lastPage() }} pages
                                    </div>
                                    @include('elements.pagination',['paginator' => $items])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 widgets-column">
                    <div id="details_popup" class="profile-widget profile-details-widget" style="display:none;">
                        <div class="widget-header d-flex">
                            <h6 id="history_item_title">Client’s Profile</h6>
                            <button id="close_details_popup" class="btn close-profile-details ml-auto">
                                <img src="/images/close-icon-black.svg" alt="Close icon">
                            </button>
                        </div>
                        <div class="detail-widget" id="client_profile_widget_options"></div>
                        <section id="call_history_container"></section>
                        <div id="history_pagination"></div>
                        <div class="record-log" id="form_details_container" style="display:none;">
                            <h6>Form Details</h6>
                            <div class="info d-flex">
                                <div class="title">
                                    <h6>Contact form</h6>
                                    <p>Source:
                                        <span id="form_details_url"></span>
                                    </p>
                                </div>
                                <a href="#" class="btn view-button d-flex align-items-center ml-auto" id="view_form_details">
                                    <span>View</span>
                                    <img src="/images/history-view-icon.svg" alt="History view icon">
                                </a>
                            </div>
                        </div>
                        <div class="d-flex view-profile-btn-row" id="view_client_profile" style="display:none;">
                            <a href="" id="view_client_profile_btn" class="btn btn--round btn-outline mx-auto">View Profile</a>
                        </div>
                    </div>
                    <div class="profile-widget dialer-widget">
                        <h6>Search Contacts and Call</h6>
                        <div class="search-field-wrap d-flex align-items-center">
                            <button class="btn search-btn" id="search_client_btn">
                                <img src="/images/search-icon-green.svg" alt="Search icon green">
                            </button>
                            {!! Form::text('search_client',null,['class' => 'form-control', 'placeholder' => 'Start typing any name...', 'id' => 'search_client', 'autocomplete' => 'off']) !!}
                        </div>
                        <div class="devider text-center">
                            <span>Or</span>
                        </div>
                        <h6>Dial a Number</h6>
                        <div class="show-dial-number text-center dialed">
                            <div class="form-group">
                                {!! Form::text('dial_number_phone',null,['class' => 'form-control dialed-number', 'placeholder' => 'Dial Here', 'autocomplete' => 'off', 'id' => 'dial_number_phone']) !!}
                                <button type="button" class="btn clear-btn" id="handle_clear_btn" style="display:none;">
                                    <img src="/images/clear-btn-icon.svg" alt="Clear icon">
                                </button>
                            </div>
                            <div class="select-group select-country-code">
                                {!! Form::select('dial_country',$dial_countries,$user_twilio_phone ? $user_twilio_phone->country_code : null,['class' => 'form-control', 'id' => 'dial_country', 'autocomplete' => 'off']) !!}
                            </div>
                        </div>
                        <div class="dial-buttons mx-auto d-flex align-items-center justify-content-center">
                            <button type="button" class="btn dial_number_item" data-num="1">
                                <span class="number">1</span>
                            </button>
                            <button type="button" class="btn dial_number_item" data-num="2">
                                <span class="number">2</span>
                                <span class="alp">abc</span>
                            </button>
                            <button type="button" class="btn dial_number_item" data-num="3">
                                <span class="number">3</span>
                                <span class="alp">def</span>
                            </button>
                            <button type="button" class="btn dial_number_item" data-num="4">
                                <span class="number">4</span>
                                <span class="alp">ghi</span>
                            </button>
                            <button type="button" class="btn dial_number_item" data-num="5">
                                <span class="number">5</span>
                                <span class="alp">jkl</span>
                            </button>
                            <button type="button" class="btn dial_number_item" data-num="6">
                                <span class="number">6</span>
                                <span class="alp">mno</span>
                            </button>
                            <button type="button" class="btn dial_number_item" data-num="7">
                                <span class="number">7</span>
                                <span class="alp">pqrs</span>
                            </button>
                            <button type="button" class="btn dial_number_item" data-num="8">
                                <span class="number">8</span>
                                <span class="alp">tuv</span>
                            </button>
                            <button type="button" class="btn dial_number_item" data-num="9">
                                <span class="number">9</span>
                                <span class="alp">wxyz</span>
                            </button>
                            <button type="button" class="btn dial_number_item" data-num="*">
                                <span class="number">*</span>
                            </button>
                            <button type="button" class="btn" id="dial_number_zero_item">
                                <span class="number">0</span>
                                <span class="alp">+</span>
                            </button>
                            <button type="button" class="btn dial_number_item" data-num="#">
                                <span class="number">#</span>
                            </button>
                        </div>
                        <div class="dialer-actions-wrap d-flex align-items-center justify-content-center">
                            <button type="button" class="btn add-contact" id="add_contact">
                                <svg width="35" height="34" viewBox="0 0 35 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.1953 5.0931C13.7146 4.2881 16.4243 4.30291 18.9346 5.13538C21.4449 5.96786 23.6265 7.57511 25.1655 9.72594C25.5673 10.2874 26.3481 10.4168 26.9095 10.0151C27.4709 9.61335 27.6004 8.83255 27.1986 8.27113C25.3518 5.69013 22.7339 3.76144 19.7215 2.76246C16.7091 1.76349 13.4575 1.74572 10.4344 2.71171C7.41126 3.67771 4.77243 5.57769 2.89748 8.13834C1.02253 10.699 0.00807572 13.7884 4.80139e-05 16.9621C-0.00797969 20.1358 0.99083 23.2302 2.8528 25.8003C4.71478 28.3704 7.34396 30.2837 10.3622 31.265C13.3803 32.2463 16.632 32.245 19.6494 31.2612C22.5309 30.3218 25.057 28.5327 26.8984 26.1339C27.3188 25.5863 27.5 25.125 26.875 24.5C26.8166 24.4552 26.3131 23.908 26.25 23.875C25 22.7129 23.1901 21.5536 21.2185 20.737C19.247 19.9203 17.1339 19.5 14.9999 19.5C12.8659 19.5 10.7528 19.9203 8.78131 20.737C7.15309 21.4114 5.64775 22.3452 4.32289 23.5C3.1284 21.5379 2.4942 19.2783 2.50004 16.9684C2.50673 14.3236 3.35211 11.7492 4.91457 9.61528C6.47703 7.4814 8.67605 5.89809 11.1953 5.0931ZM5.8348 25.5C7.27827 27.0564 9.10064 28.2261 11.1351 28.8875C13.6503 29.7052 16.36 29.7041 18.8745 28.8844C20.9052 28.2223 22.724 27.0539 24.1651 25.5001C23.0127 24.4696 21.6934 23.6396 20.2618 23.0467C18.5936 22.3557 16.8056 22 14.9999 22C13.1942 22 11.4062 22.3557 9.73801 23.0467C8.30646 23.6396 6.98716 24.4696 5.8348 25.5ZM27.5 12C27.5 11.3096 28.0596 10.75 28.75 10.75C29.4404 10.75 30 11.3096 30 12V15.75H33.75C34.4404 15.75 35 16.3096 35 17C35 17.6904 34.4404 18.25 33.75 18.25H30V22C30 22.6904 29.4404 23.25 28.75 23.25C28.0596 23.25 27.5 22.6904 27.5 22V18.25H23.75C23.0596 18.25 22.5 17.6904 22.5 17C22.5 16.3096 23.0596 15.75 23.75 15.75H27.5V12ZM10 10.75C10 7.98858 12.2386 5.75 15 5.75C17.7614 5.75 20 7.98858 20 10.75V13.25C20 16.0114 17.7614 18.25 15 18.25C12.2386 18.25 10 16.0114 10 13.25V10.75ZM15 8.25C13.6193 8.25 12.5 9.36929 12.5 10.75V13.25C12.5 14.6307 13.6193 15.75 15 15.75C16.3807 15.75 17.5 14.6307 17.5 13.25V10.75C17.5 9.36929 16.3807 8.25 15 8.25Z" fill="#86969E" />
                                </svg>
                            </button>
                            <button type="button" class="btn call-btn" id="make_call">
                                <img src="/images/call-btn-large-green.svg" alt="Call icon large">
                            </button>
                            <button type="button" class="btn delete-number" id="clear_dial">
                                <svg width="35" height="34" viewBox="0 0 35 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2.16733 16.4872L6.38643 11.8904L11.1663 6.76155C12.0648 5.79748 13.3234 5.25 14.6412 5.25H30C32.0711 5.25 33.75 6.92893 33.75 9V25C33.75 27.0711 32.0711 28.75 30 28.75H14.6536C13.33 28.75 12.0665 28.1978 11.1675 27.2264L6.93303 22.651L2.16943 17.5038C1.90416 17.2172 1.90325 16.775 2.16733 16.4872L1.24642 15.642L2.16733 16.4872Z" stroke="#86969E" stroke-width="2.5" />
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M27.2426 14.526C27.7308 14.0379 27.7308 13.2464 27.2426 12.7583C26.7545 12.2701 25.963 12.2701 25.4749 12.7583L23 15.2331L20.5251 12.7583C20.037 12.2701 19.2455 12.2701 18.7574 12.7583C18.2692 13.2464 18.2692 14.0379 18.7574 14.526L21.2322 17.0009L18.7574 19.4758C18.2692 19.9639 18.2692 20.7554 18.7574 21.2435C19.2455 21.7317 20.037 21.7317 20.5251 21.2435L23 18.7687L25.4749 21.2435C25.963 21.7317 26.7545 21.7317 27.2426 21.2435C27.7308 20.7554 27.7308 19.9639 27.2426 19.4758L24.7678 17.0009L27.2426 14.526Z" fill="#86969E" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="add-new-popup add-calendar-event modal-wrapper" id="add_event_modal">
        <div class="modal-inner">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5>Add New Event</h5>
                    <button class="btn close-modal ml-auto close_modal" data-dismiss="modal" aria-label="Close">
                        <img src="/images/Close.svg" alt="close icon">
                    </button>
                </div>
                {!! Form::open(['url' => 'client', 'id' => 'add_event_form']) !!}
                <div class="modal-body">
                    <div class="form-wrap">
                        <h6>Client’s Data</h6>
                        <div class="form-group">
                            {!! Form::text('client_name','',['class' => 'form-control', 'id' => 'client_name', 'readonly' => 'readonly' ]) !!}
                            <label for="client_name">Full Name</label>
                        </div>
                        <div class="form-group-row form-row">
                            <div class="form-group col-12 col-sm-6">
                                {!! Form::text('event_upfront',null,['class' => 'form-control', 'id' => 'event_upfront']) !!}
                                {!! Form::label('event_upfront','Upfront') !!}
                            </div>
                            <div class="form-group col-12 col-sm-6">
                                {!! Form::text('event_ongoing',null,['class' => 'form-control', 'id' => 'event_ongoing']) !!}
                                {!! Form::label('event_ongoing','Ongoing') !!}
                            </div>
                        </div>
                        <h6>Event Details</h6>
                        <div class="form-group">
                            {!! Form::text('event_start_date',null,['class' => 'form-control', 'id' => 'event_start_date']) !!}
                            {!! Form::label('event_start_date','Starts') !!}
                        </div>
                        <div class="form-group">
                            {!! Form::text('event_end_date',null,['class' => 'form-control', 'id' => 'event_end_date']) !!}
                            {!! Form::label('event_end_date','Ends') !!}
                        </div>
                        <div class="form-group">
                            {!! Form::select('add_event_type',$event_types,null,['id' => 'add_event_type']) !!}
                            {!! Form::label('add_event_type','Event Type') !!}
                        </div>
                        <h6>Location</h6>
                        <section class="event_location_container"></section>
                        <button id="add_event_location" class="btn add-another d-flex align-items-center">
                            <img src="/images/add-icon-green.svg" alt="Pluse icon" class="icon">
                            Add Another
                        </button>
                    </div>
                </div>
                <div class="card-footer">
                    <div id="event_create_loader" style="display:none;">
                        <img src="/images/loader.png" width="24px" class="float-left">
                        <span class="float-left ml-1 loader-text">Processing</span>
                    </div>
                    <div class="btn-row">
                        <button type="button" class="btn btn--sqr btn-secondary close_modal">Cancel</button>
                        <button type="submit" class="btn btn--sqr btn-primary">Add Event</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="modal fade history-popup form-popup" id="form_details_modal" tabindex="-1" role="dialog" aria-labelledby="formPopupModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-12 header-col d-flex align-items-center">
                        <span class="modal-title" id="formPopupModalLabel">Form</span>
                        <button type="button" class="btn call-event-btn ml-auto call_history_item" id="form_modal_call_btn" data-client="" data-id="" data-client="" data-type="" data-phone="" data-name="">
                            <img src="/images/calendar-event-call.svg" alt="Call icon">
                        </button>
                        <button type="button" class="btn email-event-btn" id="form_modal_email_btn">
                            <img src="/images/calendar-event-email.svg" alt="Email icon">
                        </button>
                        <button type="button" class="btn text-message-event-btn" id="form_modal_message_btn">
                            <img src="/images/calendar-event-text-message.svg" alt="Text message icon">
                        </button>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <img src="/images/calendar-modal-close.svg" alt="Close icon black">
                        </button>
                    </div>
                </div>
                <div class="modal-body" id="form_details_modal_container"></div>
            </div>
        </div>
    </div>
    <div class="add-client-modal modal-wrapper modal_item" id="add_new_client_modal">
        <div class="modal-inner card">
            <div class="card-header d-flex align-items-center">
                <h5>Add New Client</h5>
                <button class="btn close-modal ml-auto cancel_add_client">
                    <img src="/images/Close.svg" alt="close icon">
                </button>
            </div>
            <div class="modal-body">
                <div class="form-wrap">
                    {!! Form::open(['url' => 'client','method' => 'post', 'id' => 'add_client_form']) !!}
                    <h6>Client’s Data</h6>
                    <div class="form-group">
                        {!! Form::text('add_client_name',null,['class' => 'form-control', 'required' => 'required', 'id' => 'add_client_name', 'placeholder' => 'Full Name']) !!}
                        {!! Form::label('add_client_name','Full Name') !!}
                    </div>
                    <div class="form-group-row form-row">
                        <div class="form-group col-12 col-lg-6">
                            {!! Form::text('client_upfront_value',null,['class' => 'form-control', 'id' => 'client_upfront_value', 'placeholder' => 'Upfront Value']) !!}
                            {!! Form::label('client_upfront_value','Upfront Value') !!}
                            <span class="currency">USD</span>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            {!! Form::text('client_ongoing_value',null,['class' => 'form-control', 'id' => 'client_ongoing_value', 'placeholder' => 'Ongoing Value']) !!}
                            {!! Form::label('client_ongoing_value','Ongoing Value') !!}
                            <span class="currency">USD</span>
                        </div>
                    </div>
                    <h6>Phone Number</h6>
                    <div class="form-group">
                        <div class="phone-number-group d-flex">
                            <div class="country-code">
                                {!! Form::select('phone_country',$dial_countries,$user_twilio_phone ? $user_twilio_phone->country_code : null,['class' => 'form-control', 'id' => 'phone_country']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::text('client_phone',null,['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Phone Number', 'id' => 'client_phone']) !!}
                                {!! Form::label('client_phone','Phone Number') !!}
                            </div>
                        </div>
                    </div>
                    <h6>Location</h6>
                    <div class="form-group-row form-row">
                        <div class="form-group col-12 col-lg-6">
                            {!! Form::text('client_city',null,['class' => 'form-control', 'placeholder' => 'City', 'id' => 'client_city']) !!}
                            {!! Form::label('client_city','City') !!}
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            {!! Form::text('client_zip',null,['class' => 'form-control', 'placeholder' => 'ZIP', 'id' => 'client_zip']) !!}
                            {!! Form::label('client_zip','Zip') !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::text('client_address',null,['class' => 'form-control', 'id' => 'client_address', 'placeholder' => 'Address Line']) !!}
                        {!! Form::label('client_address','Address') !!}
                    </div>
                    <h6>Additional Data</h6>
                    <div class="form-group">
                        {!! Form::text('client_company',null,['class' => 'form-control', 'id' => 'client_company', 'placeholder' => 'Company']) !!}
                        {!! Form::label('client_company','Company') !!}
                    </div>
                    <div class="form-group">
                        {!! Form::text('client_email',null,['class' => 'form-control', 'id' => 'client_email', 'placeholder' => 'Email']) !!}
                        {!! Form::label('client_email','Email') !!}
                    </div>
                    <h6>Client’s Stage</h6>
                    <div class="form-group stage-field not-listed">
                        {!! Form::select('client_status',$client_statuses,null,['class' => 'form-control', 'id' => 'client_status', 'placeholder' => 'All']) !!}
                        {!! Form::label('client_status', 'Client\'s stage') !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="card-footer">
                <div id="client_create_loader" style="display:none;">
                    <img src="/images/loader.png" width="24px" class="float-left">
                    <span class="float-left ml-1 loader-text">Processing</span>
                </div>
                <div class="btn-row modal_btn">
                    <button class="btn btn--sqr btn-secondary cancel_add_client">Cancel</button>
                    <button class="btn btn--sqr btn-primary" id="add_client_btn">Add Client</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('view_script')
<script src="https://unpkg.com/wavesurfer.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script type="text/javascript" src="/js/jquery.inputmask.min.js"></script>
<script type="text/javascript" src="/js/jquery-datetimepicker/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript" src="/js/select2/js/select2.min.js"></script>
<script type="text/javascript" src="/js/jquery.twbsPagination.min.js"></script>
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        window.dial_number_text = 'Dial Here';
        window.client_details = {};
        window.dial_hold_active = false;
        window.dial_hold_timeout = {};
        window.wavesurfer_objects = {};

        $('#event_start_date').datetimepicker({
            format: 'D, j F, Y H:i',
            lang:'en',
            validateOnBlur: false,
            closeOnTimeSelect: true
        });

        $('#event_end_date').datetimepicker({
            format: 'D, j F, Y H:i',
            lang:'en',
            validateOnBlur: false,
            closeOnTimeSelect: true
        });

        $('#event_upfront').inputmask('decimal',{
            rightAlign: false,
            placeholder: ''
        });
        $('#event_ongoing').inputmask('decimal',{
            rightAlign: false,
            placeholder: ''
        });

        $('#client_upfront_value').inputmask('decimal',{
            rightAlign: false,
            placeholder: ''
        });

        $('#client_ongoing_value').inputmask('decimal',{
            rightAlign: false,
            placeholder: ''
        });

        $('#dial_country').select2({
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

        $('#phone_country').select2({
            dropdownParent: $('#add_new_client_modal'),
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

        $('#client_status').select2({
            width: '100%',
            minimumResultsForSearch: -1,
            dropdownParent: $('#add_new_client_modal'),
            templateSelection: function(state){
                if (!state.id) {
                    return state.text;
                }

                return $('<span class="' + state.element.value.toLowerCase() + '">' + state.text + '</span>');
            },
            templateResult: function(state){
                if (!state.id) {
                    return state.text;
                }
                return $('<span class="' + state.element.value.toLowerCase() + '">' + state.text + '</span>');
            }
        });

        $(document).on('click','.play_audio_record_btn',function(){
            var closest_obj = $(this).closest('.audio_record_container');
            var key_num = closest_obj.attr('data-num');
            for (let i in wavesurfer_objects) {
                if ('waveform_' + key_num != i) {
                    if (wavesurfer_objects[i].isPlaying()) {
                        $('.audio_record_container[data-num="' + i.replace(/[^0-9]/g,'') +'"]').find('.pause_audio_record_btn').trigger('click');
                    }
                }
            }
            wavesurfer_objects['waveform_' + key_num].play();
            $(this).hide();
            closest_obj.find('.pause_audio_record_btn').show();
            $(this).parent().addClass('playing');
            return false;
        });

        $(document).on('click','.pause_audio_record_btn',function(){
            var closest_obj = $(this).closest('.audio_record_container');
            wavesurfer_objects['waveform_' + closest_obj.attr('data-num')].pause();
            $(this).hide();
            closest_obj.find('.play_audio_record_btn').show();
            $(this).parent().addClass('paused');
            return false;
        });

        $(document).on('click','.stop_audio_record_btn',function(){
            var closest_obj = $(this).closest('.audio_record_container');
            wavesurfer_objects['waveform_' + closest_obj.attr('data-num')].stop();
            closest_obj.find('.pause_audio_record_btn').hide();
            closest_obj.find('.play_audio_record_btn').show();
            $(this).parent().removeClass('playing paused');
            return false;
        });

        $(document).on('click','.history_item',function(){
            $('.history_item').not($(this)).removeClass('selected');
            $(this).addClass('selected');
            // $('.audio_record_container').css('opacity','0');
            var data_id = $(this).attr('data-id');
            var history_type = $(this).attr('data-type');
            $.post('/history/details',{ id: data_id, type: history_type, page: 1 }, function(data){
                if (data.status) {
                    var client_profile_title = 'N/A';
                    if (data.client.name) {
                        client_profile_title = data.client.name;
                    }
                    else if(data.client.phone) {
                        client_profile_title = data.client.phone;
                    }

                    /**Render new players*/
                    client_details = data.client;
                    client_details.client_profile_title = client_profile_title;
                    client_details.data_id = data_id;

                    $('#client_profile_widget_options').html(_.template($('#client_profile_widget_template').html())({
                        client_profile_title: client_profile_title,
                        name: data.client.name,
                        status: data.client.client_status,
                        status_label: data.client.client_status_label,
                        upfront_value: data.client.upfront_value,
                        ongoing_value: data.client.ongoing_value,
                        has_email: data.client.has_email,
                        phone: data.client.phone,
                        client_id: data.client.client_id,
                        type: history_type,
                        id: data_id
                    }));

                    if (data.client.client_id) {
                        $('#view_client_profile_btn').attr('href','/client/' + data.client.client_id);
                        $('#view_client_profile').addClass('d-flex').show();
                    }
                    else{
                        $('#view_client_profile').removeClass('d-flex').hide();
                    }

                    /**Handle Forms*/
                    if (history_type == 'forms') {
                        $('#form_details_url').text(data.client.form_data_url);
                        $('#form_details_container').show();
                    }
                    else{
                        $('#form_details_container').hide();
                    }

                    handle_new_call_history(data.call_history);
                    $('#details_popup').slideDown();

                    if (data.call_history.total_pages > 1) {
                        $('#history_pagination').twbsPagination({
                            totalPages: data.call_history.total_pages,
                            initiateStartPageClick: false,
                            visiblePages: 4,
                            hideOnlyOnePage: true,
                            lastClass: 'display-hidden',
                            firstClass: 'display-hidden',
                            paginationClass: 'pagination',
                            startPage: 1,
                            onPageClick: function (event, page) {
                                $('body,html').animate({
                                    scrollTop: $('#call_history_container').offset().top,
                                }, {
                                    duration: 900,
                                    complete:  function() {
                                        $.post('/history/details',{ id: data_id, type: history_type, page: page }, function(data){
                                            handle_new_call_history(data.call_history);
                                        });
                                    }
                                });
                            }
                        });
                    }
                }
                else{
                    App.render_message('error',data.error);
                }
            });
            return false;
        });

        $(document).on('click','#close_details_popup',function(){
            $('#stop_audio_record_btn').trigger('click');
            $('#details_popup').slideUp();
            return false;
        });

        $(document).on('click','.download_audio_record_btn',function(){
            location.href = '/history/download/recording/' + $(this).closest('.audio_record_container').attr('data-num');
            return false;
        });

        $('#search_client').autocomplete({
            autoFill: true,
            source: function( request, response ) {
                $.ajax( {
                    url: '/history/client/search',
                    type: 'POST',
                    dataType: 'json',
                    async: false,
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        var clients = [];
                        if (data.clients.length) {
                            $.each(data.clients,function(key,value){
                                if (value.company) {
                                    value.name += ' (' + value.company + ')';
                                }
                                clients.push({
                                    label : value.name + ' (' + value.phone + ')',
                                    value : value.client_id,
                                    phone: value.phone
                                });
                            });
                        }
                        return response(clients);
                    }
                } );
            },
            minLength: 0,
            select: function(event, ui) {
                $('#dial_number_phone').val(ui.item.phone);
                handle_dial_clear_btn();
                return false;
            }
        }).bind('focus',function(){ $(this).autocomplete("search"); } );

        $(document).on('click','#search_client_btn',function(){
            $('#search_client').autocomplete('search');
            return false;
        });

        $(document).on('click','.dial_number_item',function(){
            add_dial_number($(this).attr('data-num'));
            handle_dial_clear_btn();
            $('#dial_number_phone').trigger('input');
            return false;
        });

        $('#dial_number_zero_item').mousedown(function(){
            add_dial_number('0');
            dial_hold_timeout = setTimeout(function() {
                dial_hold_timeout = null;
                dial_hold_active = true;
                var old_value = $('#dial_number_phone').val();
                $('#dial_number_phone').val(old_value.slice(0,-1) + '+');
                handle_dial_clear_btn();
            }, 500);
        });

        $('#dial_number_zero_item').mouseup(function(){
            dial_number_hold_out();
        });

        $('#dial_number_zero_item').mouseout( function(){
            dial_number_hold_out();
        });

        $(document).on('click','#clear_dial',function(){
            var old_value = $('#dial_number_phone').val();
            if (old_value != dial_number_text) {
                var new_value = old_value.slice(0,-1);
                $('#dial_number_phone').val(new_value.length ? new_value : dial_number_text);
            }

            handle_dial_clear_btn();
            return false;
        });

        $(document).on('click','#handle_clear_btn',function(){
            $('#dial_number_phone').val('');
            $(this).hide();
            return false;
        });

        $(document).on('change','#dial_country',function(){
            set_country_mask('dial_number_phone', $(this).val());
            return false;
        });

        $(document).on('keyup','#dial_number_phone',function(){
            handle_dial_clear_btn();
            return false;
        });

        /**Add Event*/
        $(document).on('click','#add_event',function(){
            $('.event_location_container').html(_.template($('#event_location_item_template').html())({
                num: (new Date()).getTime() * 1000
            }));
            $('.event_location_item:last').slideDown();
            if ($('.upfront_value').length) {
                $('#event_upfront').val($('.upfront_value').first().val());
                $('#event_ongoing').val($('.ongoing_value').first().val());
            }

            $('#client_name').val($(this).attr('data-name'));
            $('#add_event_form').attr('data-client',$(this).attr('data-client'));
            var client_status = $(this).attr('data-status');
            var event_type;
            switch (client_status) {
                case 'not-listed':
                case 'lead':
                    event_type = 'quote-meeting';
                break;
                case 'quote-meeting':
                case 'work-in-progress':
                    event_type = 'work-in-progress';
                break;
                case 'completed':
                case 'cancelled':
                    var event_type = 'remind-me';
                break;
            }

            if (event_type) {
                $('#add_event_type').val(event_type).trigger('change');
            }

            $('#add_event_modal').addClass('expanded');
            $('#add_event_type').select2({
                width: '100%',
                dropdownParent: $('#add_event_modal'),
                minimumResultsForSearch: -1,
                templateSelection: function(state){
                    if (!state.id) {
                        return state.text;
                    }

                    return $('<span class="' + state.element.value.toLowerCase() + '">' + state.text + '</span>');
                },
                templateResult: function(state){
                    if (!state.id) {
                        return state.text;
                    }
                    return $('<span class="' + state.element.value.toLowerCase() + '">' + state.text + '</span>');
                }
            });
            return false;
        });

        $(document).on('click','#add_event_location',function(){
            $('.event_location_container').append(_.template($('#event_location_item_template').html())({
                num: (new Date()).getTime() * 1000
            }));
            $('.event_location_item:last').slideDown();
            return false;
        });

        $(document).on('click','.delete_event_location_item',function(){
            $(this).closest('.event_location_item').slideUp(function(){
                $(this).remove();
            });
            return false;
        });

        $(document).on('click','.close_modal',function(){
            $(this).closest('.modal-wrapper').removeClass('expanded');
            return false;
        });

        $(document).on('submit','#add_event_form',function(){
            var start_date = $('#event_start_date').val();
            if (!start_date) {
                $('#event_start_date').focus();
                return false;
            }

            var end_date = $('#event_end_date').val();
            if (!end_date) {
                $('#event_end_date').focus();
                return false;
            }

            var locations = [];
            $('.event_location_item').each(function(){
                var city = $(this).find('.event_city').val();
                var zip = $(this).find('.event_zip').val();
                var address = $(this).find('.event_address').val();
                if (city.length || zip.length || address.length) {
                    locations.push({
                        city: city,
                        zip: zip,
                        address: address
                    });
                }
            });

            var form_data = {
                client_id : $(this).attr('data-client'),
                start_date: start_date,
                end_date: end_date,
                upfront_value: $('#event_upfront').val(),
                ongoing_value: $('#event_ongoing').val(),
                status: $('#add_event_type').val(),
                other_text: $('#event_other').val(),
                locations: locations
            }

            $.post('/client/add/event',form_data,function(data){
                if (data.status) {
                    $('#add_event_modal').removeClass('expanded');
                    $('#add_event_form')['0'].reset();
                    App.render_message('success','New event added successfully');
                }
                else{
                    App.render_message('info',data.error);
                }
            },'json');
            return false;
        });

        /**Form Details*/
        $(document).on('click','#view_form_details',function(){
            if (client_details.phone) {
                $('#form_modal_call_btn').show();
                $('#form_modal_message_btn').show();
            }
            else{
                $('#form_modal_call_btn').hide();
                $('#form_modal_message_btn').hide();
            }

            $('#form_modal_call_btn').attr({
                'data-client' : client_details.client_id,
                'data-id' : client_details.data_id,
                'data-type' : 'forms',
                'data-phone' : client_details.phone,
                'data-name' : client_details.name
            });

            if (client_details.has_email) {
                $('#form_modal_email_btn').show();
            }
            else{
                $('#form_modal_email_btn').hide();
            }

            $('#form_details_modal_container').html(_.template($('#form_details_modal_template').html())({
                client: client_details,
                form_details: client_details.form_data ? JSON.parse(client_details.form_data) : []
            }))
            $('#form_details_modal').modal('show');
            return false;
        });

        /**Calls*/
        $(document).on('click','#make_call',function(){
            if (App.call.twilio_current_call) {
                App.render_message('Please end call before starting a new one');
                return false;
            }
            else{
                var phone_number = $.trim($('#dial_number_phone').val());
                if (!phone_number.length) {
                    App.render_message('Please dial number to make a call');
                    return false;
                }

                if (phone_number.length < 5) {
                    App.render_message('Please type a valid number');
                    return false;
                }

                var phone_country = $('#dial_country').val();
                if (phone_number.indexOf('+') !== 0) {
                    phone_number = $('#dial_country option[value="' + phone_country + '"]').text() + '' + phone_number.replace(/\D/g,'');
                }
                var client_type = client_details.client_id ? 'client' : 'no_client';
                App.call.process_outgoing_call(App.call.twilio_outgoing_obj, phone_number, '{{ $user_twilio_phone->phone }}', client_type, client_details.client_profile_title);
            }
            return false;
        });

        /**Call buttons*/
        $(document).on('click','.call_history_item',function(e){
            e.preventDefault();
            $('#form_details_modal').modal('hide');
            if ($(this).attr('data-client')) {
                location.href = '/history/pre/call/' + $(this).attr('data-id') + '/' + $(this).attr('data-type');
            }
            else{
                var phone = $(this).attr('data-phone');
                var title = $(this).attr('data-name');
                    title = title ? title : phone;
                App.call.process_outgoing_call(App.call.twilio_outgoing_obj, $(this).attr('data-phone'), '{{ $user_twilio_phone->phone }}', 'no_client', title);
            }
            return false;
        });

        /**Add Contact*/
        $(document).on('click','#add_contact',function(){
            $('#add_client_form')['0'].reset();
            $('#phone_country').val($('#dial_country').val()).trigger('change');
            var phone_number = $('#dial_number_phone').val();
            if (phone_number) {
                $('#client_phone').val(phone_number);
            }
            $('#add_new_client_modal').modal('show');
            return false;
        });

        $(document).on('change','#phone_country',function(){
            set_country_mask('client_phone',$(this).val());
            return false;
        });

        $(document).on('click','.cancel_add_client',function(){
            $('#add_new_client_modal').modal('hide');
            return false;
        });

        $(document).on('click','#add_client_btn',function(){
            $('#add_client_form').trigger('submit');
            return false;
        });

        $('#add_client_form').validate({
            submitHandler: function(form){
                $('.modal_btn').hide();
                $('#client_create_loader').fadeIn();
                var form_data = {
                    name : $('#add_client_name').val(),
                    upfront_value: $('#client_upfront_value').val(),
                    ongoing_value: $('#client_ongoing_value').val(),
                    phone_country: $('#phone_country').val(),
                    phone: $('#client_phone').val(),
                    city: $('#client_city').val(),
                    zip: $('#client_zip').val(),
                    address: $('#client_address').val(),
                    company: $('#client_company').val(),
                    email : $('#client_email').val(),
                    status: $('#client_status').val()
                }

                console.log(form_data);
                $.post('/client',form_data,function(data){
                    $('#client_create_loader').fadeOut();
                    $('.modal_btn').show();
                    if (data.status) {
                        App.render_message('success','Client created successfully');
                        $('#add_new_client_modal').modal('hide');
                    }
                    else{
                        App.render_message('info',data.error_message);
                    }
                    return false;
                },'json');
                return false;
            },
            focusInvalid: false,
            onkeyup: false,
            rules : {
                add_client_name: {
                    required: true,
                },
                client_upfront_value: {
                    number: true
                },
                client_ongoing_value: {
                    number: true
                },
                client_email: {
                    email: true
                },
                client_phone: {
                    required: true
                },
                client_status: {
                    required: true
                }
            },
            messages: {
                add_client_name: {
                    required : 'Client name is required'
                },
                client_upfront_value: {
                    number : 'Please specify valid number for Upfront amount'
                },
                client_ongoing_value: {
                    number : 'Please specify valid number for Ongoing amount'
                },
                client_email: {
                    number : 'Please specify valid email'
                },
                client_phone: {
                    required : 'Please specify phone number'
                },
                client_status: {
                    required : 'Client stage is required'
                }
            }
        });

        set_country_mask('dial_number_phone',$('#dial_country').val());
    });

    var dial_number_hold_out = function(){
        if (dial_hold_timeout) {
            clearTimeout(dial_hold_timeout);
        }
        else if (dial_hold_active) {
            dial_hold_active = false;
        }
    }

    var add_dial_number = function(num) {
        var old_value = $('#dial_number_phone').val();
        if (old_value == dial_number_text) {
            old_value = '';
        }
        $('#dial_number_phone').val(old_value + '' + num);
        handle_dial_clear_btn();
    }

    var handle_new_call_history = function(call_history) {
        /**Destroy player*/
        for (let i in wavesurfer_objects) {
            wavesurfer_objects[i].destroy();
        }

        wavesurfer_objects = {}

        $('#call_history_container').html(_.template($('#call_history_items_template').html())({
            call_history: call_history
        }));

        $('.waveform').each(function(){
            var $this = $(this);
            var closest_obj = $this.closest('.call_record_history_item');
                closest_obj.find('.audio_remaining_title').text($(this).attr('data-time-format'));
            var wavesurfer_key = $(this).attr('id');
            wavesurfer_objects[wavesurfer_key] = WaveSurfer.create({
                container: document.querySelector('#' + wavesurfer_key),
                waveColor: '#43D14F',
                progressColor: '#43d14f',
                cursorColor: '#d6d9e0',
                barWidth: 2.5,
                barRadius: 1.5,
                cursorWidth: 1,
                height: 30,
                barGap: 4,
                autocenter: true
            });

            wavesurfer_objects[wavesurfer_key].on('audioprocess', function() {
                var totalTime = wavesurfer_objects[wavesurfer_key].getDuration(),
                    currentTime = wavesurfer_objects[wavesurfer_key].getCurrentTime(),
                    remainingTime = totalTime - currentTime;

                var minutes = Math.floor(remainingTime / 60);
                var seconds = Math.ceil(remainingTime - minutes * 60);
                var minutes_format =  (minutes) ? (minutes < 10 ? '0' + minutes : minutes) : '00';
                var seconds_format =  (seconds) ? (seconds < 10 ? '0' + seconds : seconds) : '00';

                closest_obj.find('.audio_remaining_title').text(minutes_format + ':' + seconds_format);
            });

            wavesurfer_objects[wavesurfer_key].on('finish', function(){
                closest_obj.find('.audio-player').find('.controls').removeClass("playing paused");
                closest_obj.find('.pause_audio_record_btn').hide();
                closest_obj.find('.play_audio_record_btn').show();
            });

            wavesurfer_objects[wavesurfer_key].load('/records/' + $this.attr('data-file'));
        });
    }

    var handle_dial_clear_btn = function(){
        if ($.trim($('#dial_number_phone').val()).length) {
            $('#handle_clear_btn').show();
        }
        else{
            $('#handle_clear_btn').hide();
        }
    }

    var set_country_mask = function(id, country_code) {
        switch (country_code) {
            case 'au':
                $('#' + id).inputmask("(99) 9999 9999",{ clearIncomplete: true, autoUnmask: true });
            break;
            case 'us':
            case 'ca':
                $('#' + id).inputmask('(999) 999-9999',{ clearIncomplete: true, autoUnmask: true });
            break;
            case 'gb':
                $('#' + id).inputmask('99 999 9999',{ clearIncomplete: true, autoUnmask: true });
            break;
        }
    }
</script>
<script type="text/template" id="client_profile_widget_template">
    <div class="widget-head d-flex align-items-center">
        <div class="name-wrap">
            <h2 id="client_profile_title"><%= client_profile_title %></h2>
            <h6 class="status">
                <%
                   if (upfront_value || ongoing_value) {
                        if (ongoing_value){
                    %>
                            <span class="value">$<%= ongoing_value %> ·</span>
                    <%
                        }
                        else {
                    %>
                            <span class="value">$<%= upfront_value %> ·</span>
                    <%
                        }
                    %>

                <%
                    }
                    else {
                %>
                        <span class="no-value">No Value · </span>
                <% } %>
                <span class="<%= status %>"><%= status_label %></span>
            </h6>
        </div>
        <% if (phone) { %>
            <button class="btn call-btn ml-auto call_history_item" data-phone="<%= phone %>" data-client="<%= client_id %>" data-type="<%= type %>" data-id="<%= id %>" data-name="<%= name %>">
                <img src="/images/call-green-round.svg" alt="Call green icon">
            </button>
        <% } %>
    </div>
    <div class="widget-body actions d-flex">
        <button class="btn text-message <%= phone ? '' : 'not-added' %>">
            <img src="/images/text-message-icon-green.svg" alt="Text message icon" class="green">
            <img src="/images/text-message-icon-gray.svg" alt="Text message icon" class="gray">
            <span>Text Message</span>
        </button>
        <button class="btn add-event <%= client_id ? '' : 'not-added' %>" id="add_event" data-status="<%= status %>" data-name="<%= name %>" data-client="<%= client_id %>">
            <img src="/images/calendar-icon-green.svg" alt="Calendar icon" class="green">
            <img src="/images/calendar-icon-gray.svg" alt="Calendar icon" class="gray">
            <span>Add Event</span>
        </button>
        <button type="button" class="btn send-email <%= has_email ? '' : 'not-added' %>">
            <img src="/images/email-icon-green-large.svg" alt="Emain icon" class="green">
            <img src="/images/email-icon-gray-large.svg" alt="Emain icon" class="gray">
            <span>Send Email</span>
        </button>
        <button class="btn send-quote <%= client_id ? '' : 'not-added' %>"">
            <img src="/images/send-quote-green-icon.svg" alt="Send quote icon" class="green">
            <img src="/images/send-quote-gray-icon.svg" alt="Send quote icon" class="gray">
            <span>Send Quote</span>
        </button>
    </div>
</script>
<script type="text/template" id="event_location_item_template">
    <div class="row event_location_item" style="display:none;">
        <div class="col-md-5">
            <div class="form-group">
                <input type="text" name="event_city" class="form-control event_city" id="city_<%= num %>">
                <label for="city_<%= num %>">City</label>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <input type="text" name="event_zip" class="form-control event_zip" id="zip_<%= num %>">
                <label for="zip_<%= num %>">ZIP</label>
            </div>
        </div>
        <div class="col-md-2">
            <div class="mt-2 delete-button-wrap delete_value_item">
                <button class="btn remove-item remove_client_stage delete_event_location_item">
                    <img src="/images/delete-red.svg" alt="Delete icon red">
                </button>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <input type="text" name="event_address" class="form-control event_address" id="address_<%= num %>">
                <label for="address_<%= num %>">Address Line</label>
            </div>
        </div>
    </div>
</script>
<script type="text/template" id="form_details_modal_template">
    <div class="row">
        <div class="col-12">
            <h5 class="popup-status <%= client.client_status %>">
                <%= client.client_profile_title %> · <%= client.client_status_label %>
            </h5>
            <% if (Object.keys(form_details).length) { %>
                <ul>
                    <% for (let i in form_details) { %>
                        <li>
                            <div class="icon">
                                <img src="/images/popup-time-icon.svg" alt="Time icon">
                            </div>
                            <div class="info">
                                <span><%= i %></span>
                                <p><%= form_details[i] %></p>
                            </div>
                        </li>
                    <% } %>
                </ul>
            <% } %>
        </div>
    </div>
</script>
<script type="text/template" id="call_history_items_template">
    <% if (call_history.total_pages > 0) {
    %>
            <div class="call-log">
                <h6>history</h6>
            </div>
    <%
        for (let i in call_history.items) {
    %>
            <section class="call_record_history_item">
                <div class="call-log">
                    <span title="<%= call_history.items[i].full_format %>">
                        <%= call_history.items[i].user_friendly_format %>
                    </span>
                    <p title="<%= call_history.items[i].full_format %>">
                        <%= call_history.items[i].time_format %>
                        <span>
                            <%
                            switch(call_history.items[i].type) {
                                case 'incoming':
                            %>
                                    Incoming Call
                            <%
                                break;
                                case 'outgoing':
                            %>
                                    Outgoing Call
                            <%
                                break;
                                case 'missed':
                            %>
                                    Missed Call
                            <%
                                break;
                            }
                            %>
                        </span>
                    </p>
                </div>
                <% if (call_history.items[i].recorded_audio_file) { %>
                    <div class="record-log audio_record_container" data-status="pending" data-num="<%= call_history.items[i].id %>">
                        <h6>Recording</h6>
                        <div class="info d-flex align-items-center audio-player">
                            <div class="audio-wave">
                                <div class="waveform" id="waveform_<%= call_history.items[i].id %>" data-file="<%= call_history.items[i].recorded_audio_file %>" data-time-format="<%= call_history.items[i].recorded_playtime_format %>"></div>
                            </div>
                            <div class="controls d-flex align-items-center">
                                <div class="time-log audio_remaining_title"></div>
                                <button type="button" class="btn download-btn download_audio_record_btn">
                                    <img src="/images/download-icon-round-green.svg" alt="Download icon">
                                </button>
                                <button type="button" class="btn pause-btn pause_audio_record_btn">
                                    <img src="/images/pause-round-icon-green.svg" alt="Pause icon">
                                </button>
                                <button type="button" class="btn play-btn play_audio_record_btn">
                                    <img src="/images/play-round-icon-green.svg" alt="Play icon">
                                </button>
                                <button type="button" class="btn stop-btn stop_audio_record_btn">
                                    <img src="/images/stop-icon-round-green.svg" alt="Stop icon">
                                </button>
                            </div>
                        </div>
                    </div>
                <% } %>
            </section>
    <%
            }
        }
    %>
</script>
@endsection
