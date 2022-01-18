@extends('layouts.master')
@section('view_css')
<link rel="stylesheet" href="/js/jquery-confirm/jquery-confirm.min.css">
<link rel="stylesheet" href="/js/jquery-ui/jquery-ui.css">
<link rel="stylesheet" href="/js/select2/css/select2.min.css">
@endsection
@section('content')
@if($auth_user->role == 'sales')
    @include('admin.left_sidebar_admin_menu',['active_page' => 'client'])
@else
    @include('dashboard.left_sidebar_full_menu',['active_page' => 'client'])
@endif
<div class="col-md-auto col-12 content-wrap client-main {{ $view_mode == 'list' ? 'clients-list-view' : 'clients-funnel-view' }}">
    <div class="content-inner">
        <div class="row align-items-center heading-row">
            <div class="col-auto">
                <h2>Clients</h2>
            </div>
            <div class="col-auto action-col ml-xl-auto d-flex align-items-center">
                <button class="btn btn-outline btn--round d-flex delete-btn action-triger" id="delete_clients">
                    <img src="/images/delete-red.svg" alt="Delete icon red" class="icon">
                    <span id="delete_btn_text"></span>
                </button>
                <button type="button" id="add_new_client" class="btn btn-primary btn--round d-flex add-btn action-triger">
                    <img src="/images/plus-Icons.svg" alt="Pluse icon white" class="icon">
                    Add New
                </button>
                <ul class="nav nav-tabs action-triger">
                    <li class="nav-item">
                        <a class="nav-link {{ !$request['recurring'] ? 'active' : '' }} type_of_recurring_filter" data-type="" aria-current="all" href="#">
                            All
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $request['recurring'] == 'upfront' ? 'active' : '' }} type_of_recurring_filter" data-type="upfront" href="#">
                            One-time
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $request['recurring'] == 'recurring' ? 'active' : '' }} type_of_recurring_filter" data-type="recurring" href="#">
                            Recurring
                        </a>
                    </li>
                </ul>
                <div class="dropdown-wrap view-switcher action-triger focus" id="view_mode_options">
                    <button class="btn btn--round btn-outline d-flex" id="view_mode_change" data-mode="{{ $view_mode }}">
                        @if($view_mode == 'list')
                            <img src="/images/list-view-icon.svg" alt="List view icon" class="icon">
                            <span>List View</span>
                        @else
                            <img src="/images/funnel-view-icon.svg" alt="Funnel view icon" class="icon">
                            <span>Funnel View</span>
                        @endif
                    </button>
                    <ul class="dropdown-items with-icons">
                        <li data-label="list-view" class="change_mode" data-selected="{{ $view_mode == 'list' ? 'selected' : '' }}">
                            <div class="icon">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect y="1" width="16" height="2" rx="1" fill="#86969E"/>
                                    <rect y="7" width="16" height="2" rx="1" fill="#86969E"/>
                                    <rect y="13" width="16" height="2" rx="1" fill="#86969E"/>
                                </svg>
                            </div>
                            <span>List View</span>
                        </li>
                        <li data-label="funnel-view" class="change_mode" data-selected="{{ $view_mode == 'funnel' ? 'selected' : '' }}">
                            <div class="icon">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect y="1" width="5" height="2" rx="1" fill="#86969E"/>
                                    <rect y="7" width="10" height="2" rx="1" fill="#86969E"/>
                                    <rect y="13" width="16" height="2" rx="1" fill="#86969E"/>
                                </svg>
                            </div>
                            <span>Funnel View</span>
                        </li>
                    </ul>
                </div>
                <button class="btn btn--round btn-outline d-flex action-triger filter-btn" id="filter_clients">
                    <img src="/images/filter-icon.svg" alt="Filter icon" class="icon">
                    Filter
                </button>
            </div>
        </div>
        @if($view_mode == 'list')
            <div class="list-items-wrapper">
                <div class="list-items list-table">
                    <div class="row items-heading no-gutters">
                        <div class="col-auto lead-name">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="check_all_clients" autocomplete="off">
                                <label class="custom-control-label" for="check_all_clients"></label>
                            </div>
                            <button class="btn sort-btn {{ in_array($request['sort_by'],['lead_asc','lead_desc']) ? ($request['sort_by'] == 'lead_asc' ? 'sorted-asc' : 'sorted-desc') : '' }}">
                                <span>Lead Name</span>
                                <a class="sort_by" data-type="{{ $request['sort_by'] == 'lead_asc' ? 'lead_desc' : 'lead_asc' }}" href="#">
                                    <svg width="10" height="9" viewBox="0 0 10 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.3" d="M5.43301 8.25C5.24056 8.58333 4.75944 8.58333 4.56699 8.25L0.23686 0.75C0.0444095 0.416667 0.284973 1.69368e-08 0.669873 5.05859e-08L9.33013 8.07689e-07C9.71503 8.41338e-07 9.95559 0.416668 9.76314 0.750001L5.43301 8.25Z" fill="#86969E"/>
                                    </svg>
                                </a>
                            </button>
                        </div>
                        <div class="col-auto value">
                            <button class="btn sort-btn {{ in_array($request['sort_by'],['value_asc','value_desc']) ? ($request['sort_by'] == 'value_asc' ? 'sorted-asc' : 'sorted-desc') : '' }}">
                                <span>Value</span>
                                <a class="sort_by" data-type="{{ $request['sort_by'] == 'value_asc' ? 'value_desc' : 'value_asc' }}">
                                    <svg width="10" height="9" viewBox="0 0 10 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.3" d="M5.43301 8.25C5.24056 8.58333 4.75944 8.58333 4.56699 8.25L0.23686 0.75C0.0444095 0.416667 0.284973 1.69368e-08 0.669873 5.05859e-08L9.33013 8.07689e-07C9.71503 8.41338e-07 9.95559 0.416668 9.76314 0.750001L5.43301 8.25Z" fill="#86969E"/>
                                    </svg>
                                </a>
                            </button>
                        </div>
                        <div class="col-auto source">
                            <button class="btn sort-btn {{ in_array($request['sort_by'],['source_asc','source_desc']) ? ($request['sort_by'] == 'source_asc' ? 'sorted-asc' : 'sorted-desc') : '' }}">
                                <span>Source</span>
                                <a class="sort_by" data-type="{{ $request['sort_by'] == 'source_asc' ? 'source_desc' : 'source_asc' }}">
                                    <svg width="10" height="9" viewBox="0 0 10 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.3" d="M5.43301 8.25C5.24056 8.58333 4.75944 8.58333 4.56699 8.25L0.23686 0.75C0.0444095 0.416667 0.284973 1.69368e-08 0.669873 5.05859e-08L9.33013 8.07689e-07C9.71503 8.41338e-07 9.95559 0.416668 9.76314 0.750001L5.43301 8.25Z" fill="#86969E"/>
                                    </svg>
                                </a>
                            </button>
                        </div>
                        <div class="col-auto lead-page">
                            <button class="btn sort-btn {{ in_array($request['sort_by'],['page_asc','page_desc']) ? ($request['sort_by'] == 'page_asc' ? 'page_desc' : 'sorted-desc') : '' }}">
                                <span>Lead Page</span>
                                <a class="sort_by" data-type="{{ $request['sort_by'] == 'page_asc' ? 'page_desc' : 'page_asc' }}">
                                    <svg width="10" height="9" viewBox="0 0 10 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.3" d="M5.43301 8.25C5.24056 8.58333 4.75944 8.58333 4.56699 8.25L0.23686 0.75C0.0444095 0.416667 0.284973 1.69368e-08 0.669873 5.05859e-08L9.33013 8.07689e-07C9.71503 8.41338e-07 9.95559 0.416668 9.76314 0.750001L5.43301 8.25Z" fill="#86969E"/>
                                    </svg>
                                </a>
                            </button>
                        </div>
                        <div class="col-auto status">
                            <button class="btn sort-btn {{ in_array($request['sort_by'],['status_asc','status_desc']) ? ($request['sort_by'] == 'status_asc' ? 'sorted-asc' : 'sorted-desc') : '' }}">
                                <span>Status</span>
                                <a class="sort_by" data-type="{{ $request['sort_by'] == 'status_asc' ? 'status_desc' : 'status_asc' }}">
                                    <svg width="10" height="9" viewBox="0 0 10 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.3" d="M5.43301 8.25C5.24056 8.58333 4.75944 8.58333 4.56699 8.25L0.23686 0.75C0.0444095 0.416667 0.284973 1.69368e-08 0.669873 5.05859e-08L9.33013 8.07689e-07C9.71503 8.41338e-07 9.95559 0.416668 9.76314 0.750001L5.43301 8.25Z" fill="#86969E"/>
                                    </svg>
                                </a>
                            </button>
                        </div>
                        <div class="col-auto rec">
                            <span>Rec</span>
                        </div>
                        <div class="col-auto duration">
                            <button class="btn sort-btn {{ in_array($request['sort_by'],['duration_asc','duration_desc']) ? ($request['sort_by'] == 'duration_asc' ? 'sorted-asc' : 'sorted-desc') : '' }}">
                                <span>Duration</span>
                                <a class="sort_by" data-type="{{ $request['sort_by'] == 'duration_asc' ? 'duration_desc' : 'duration_asc' }}">
                                    <svg width="10" height="9" viewBox="0 0 10 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.3" d="M5.43301 8.25C5.24056 8.58333 4.75944 8.58333 4.56699 8.25L0.23686 0.75C0.0444095 0.416667 0.284973 1.69368e-08 0.669873 5.05859e-08L9.33013 8.07689e-07C9.71503 8.41338e-07 9.95559 0.416668 9.76314 0.750001L5.43301 8.25Z" fill="#86969E"/>
                                    </svg>
                                </a>
                            </button>
                        </div>
                        <div class="col-auto more">
                            <span>More</span>
                        </div>
                    </div>
                    @foreach($clients as $item)
                        <div class="row items-body no-gutters client_row_item" data-id="{{ $item->client_id }}">
                            <div class="col-auto lead-name">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input client_check_item" name="client_{{ $item->client_id }}" id="client_{{ $item->client_id }}">
                                    <label class="custom-control-label" for="client_{{ $item->client_id }}"></label>
                                </div>
                                <a href="/client/{{ $item->client_id }}">{{ $item->name }} {{ $item->company ? '('.$item->company.')' : '' }}</a>
                            </div>
                            <div class="col-auto value">
                                @if($item->ClientLastValue)
                                    @foreach($item->ClientValue as $value_item)
                                        <span>
                                            @if($request['recurring'])
                                                @if($request['recurring'] == 'upfront')
                                                    {{ '$'.number_format($value_item->upfront_value,2) }}
                                                @else
                                                    {{ '$'.number_format($value_item->ongoing_value,2) }}
                                                @endif
                                            @else
                                                @if($value_item->ongoing_value)
                                                    {{ '$'.number_format($value_item->ongoing_value,2) }}
                                                @else
                                                    {{ '$'.number_format($value_item->upfront_value,2) }}
                                                @endif
                                            @endif
                                        </span>
                                    @endforeach
                                @else
                                    <span class="red-text">
                                        No Value
                                    </span>
                                @endif
                            </div>
                            @if($item->source_type)
                                <div class="col-auto source">
                                    @switch($item->source_type)
                                        @case('google_ads')

                                        @break
                                        @case('phone')
                                            <button class="btn phone-btn">
                                                <img src="/images/call.svg" alt="Phone icon">
                                            </button>
                                        @break
                                        @case('form')
                                            <button class="btn form-btn">
                                                <img src="/images/form-icon-green.svg" alt="Form">
                                            </button>
                                        @break
                                        @case('message')
                                            <button class="btn message-btn">
                                                <img src="/images/chat-icon.svg" alt="Messages">
                                            </button>
                                        @break
                                    @endswitch
                                </div>
                                <div class="col-auto lead-page">
                                    <span>{{ $item->source_text }}</span>
                                </div>
                            @else
                                <div class="col-auto source"></div>
                                <div class="col-auto lead-page"></div>
                            @endif

                            <div class="col-auto status">
                                <div class="dropdown-wrap select-dropdown {{ $item->status }}">
                                    <button class="btn select-btn d-flex align-items-center client_status">
                                        <span class="client_status_text">
                                            {{ isset($client_statuses[$item->status]) ? $client_statuses[$item->status] : '' }}
                                        </span>
                                        <svg width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.7042 0.295188C11.3106 -0.0983958 10.6725 -0.0983963 10.2789 0.295188L6.00274 4.57135L1.72152 0.295263C1.32769 -0.098085 0.689186 -0.0980849 0.295365 0.295263C-0.0984549 0.688611 -0.0984553 1.32635 0.295365 1.7197L5.2761 6.69446C5.2808 6.69935 5.28555 6.7042 5.29037 6.70901C5.68395 7.1026 6.32208 7.1026 6.71566 6.70902L11.7042 1.72048C12.0978 1.3269 12.0978 0.688773 11.7042 0.295188Z" fill="#43D14F"/>
                                        </svg>
                                    </button>
                                    <ul class="dropdown-items">
                                        @foreach($client_statuses as $k => $v)
                                            <li data-label="{{ $k }}" class="client_status_item" data-selected="{{ $item->status == $k ? 'selected' : '' }}">
                                                {{ $v }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @if($item->recorded_audio_file)
                                <div class="col-auto rec">
                                    <a class="btn play-pause-btn play_record" data-status="pending" data-record="{{ $item->recorded_audio_file }}">
                                        <img src="/images/play-icon-green.svg" alt="Play icon" class="play-icon">
                                        <img class="pause-icon" src="/images/pause-icon-green.svg" alt="Pause icon">
                                    </a>
                                </div>
                                <div class="col-auto duration">
                                    <span>{{ $item->recorded_playtime_format }}</span>
                                </div>
                            @else
                                <div class="col-auto rec"></div>
                                <div class="col-auto duration"></div>
                            @endif
                            <div class="col-auto more">
                                <div class="dropdown-wrap">
                                    <button type="button" class="btn more-btn dropdown-toggle" data-toggle="dropdown">
                                        <span></span>
                                    </button>
                                    <ul class="dropdown-items dropdown-menu">
                                        <li data-label="view-profile">
                                            <a href="/client/{{ $item->client_id }}">
                                                View Profile
                                            </a>
                                        </li>
                                        <li data-label="call">
                                            <a href="/client/{{ $item->client_id }}/pre/call">
                                                Call
                                            </a>
                                        </li>
                                        <li data-label="send-message">
                                            <a href="#">Send Message</a>
                                        </li>
                                        <li data-label="send-email">
                                            <a href="#">Send Email</a>
                                        </li>
                                        <li data-label="send-quote">
                                            <a href="#">Send Quote</a>
                                        </li>
                                        <li data-label="delete-from-database">
                                            <a href="#" class="delete_single_item">
                                                Delete from Database
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="row pagination-row no-gutters">
                        <div class="page-count col-auto">
                            Show {{ $clients->currentPage() }} of {{ $clients->lastPage() }} pages
                        </div>
                        @include('elements.pagination',['paginator' => $clients])
                    </div>
                </div>
            </div>
        @else
            <div class="funnel-items-wrapper">
                <div class="list-items">
                    <div class="row items-heading no-gutters">
                        <div class="col-auto not-listed">
                            <h6>
                                <span> Not listed</span>
                            </h6>
                        </div>
                        <div class="col-auto leads">
                            <h6>
                                <span> Leads</span>
                            </h6>
                        </div>
                        <div class="col-auto quote-meetings">
                            <h6>
                                <span> Quote Meetings</span>
                            </h6>
                        </div>
                        <div class="col-auto work-in-progress">
                            <h6>
                                <span> Work in Progress</span>
                            </h6>
                        </div>
                        <div class="col-auto job-completed">
                            <h6>
                                <span> Job Completed</span>
                            </h6>
                        </div>
                    </div>
                    <div class="row items-body no-gutters">
                        <div class="col-auto not-listed">
                            <div class="scrollable-y">
                                <ul class="dragable-items-wrap" data-type="not-listed" id="not_listed_container"></ul>
                            </div>
                        </div>
                        <div class="col-auto leads">
                            <div class="scrollable-y">
                                <ul class="dragable-items-wrap" data-type="lead" id="lead_container"></ul>
                            </div>
                        </div>
                        <div class="col-auto quote-meetings">
                            <div class="scrollable-y">
                                <ul class="dragable-items-wrap" data-type="quote-meeting" id="quote_container"></ul>
                            </div>
                        </div>
                        <div class="col-auto work-in-progress">
                            <div class="scrollable-y">
                                <ul class="dragable-items-wrap" data-type="work-in-progress" id="work_in_progress_container"></ul>
                            </div>
                        </div>
                        <div class="col-auto job-completed">
                            <div class="scrollable-y">
                                <ul class="dragable-items-wrap" data-type="completed" id="completed_container"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="filter-modal modal-wrapper modal_item" id="filter_modal">
            <div class="modal-inner card">
                <div class="card-header d-flex align-items-center">
                    <div>
                        <h5>Filter Clients</h5>
                        <button class="btn clear-filter-btn">Clear All Filters</button>
                    </div>
                    <button class="btn close-modal ml-auto close_modal">
                        <img src="/images/Close.svg" alt="close icon">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-wrap">
                        {!! Form::model($request,['url' => 'client','method' => 'get', 'id' => 'filter_form']) !!}
                        <h6>Client’s Stage</h6>
                        <div class="form-group custom-select-group dropdown-wrap">
                            <div class="select-toggler" id="filter_client_stage">
                                <span class="label">Client’s Stage</span>
                                <span class="selected all" id="client_stage_text">{{ $request['status'] && isset($client_statuses[$request['status']]) ? $client_statuses[$request['status']] : 'All' }}</span>
                            </div>
                            <ul class="dropdown-items" id="client_stage">
                                <li data-label="all" class="filter_client_stage_item" data-selected="{{ !$request['status'] ? 'selected' : '' }}">
                                    <span>All</span>
                                </li>
                                @foreach($client_statuses as $key => $item)
                                    <li data-label="{{ $key }}" class="filter_client_stage_item" data-selected="{{ $request['status'] == $key ? 'selected' : '' }}">
                                        <span>{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <h6>Type of Client</h6>
                        <ul class="nav nav-tabs action-triger">
                            <li class="nav-item">
                                <a class="nav-link type_of_client_filter {{ !$request['type'] ? 'active' : '' }}" data-type="" aria-current="all" href="#">All</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link type_of_client_filter {{ $request['type'] == 'individual' ? 'active' : '' }}" data-type="individual" href="#">Individual</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link type_of_client_filter {{ $request['type'] == 'company' ? 'active' : '' }}" data-type="company" href="#">Company</a>
                            </li>
                        </ul>
                        <h6>Type of Value </h6>
                        <ul class="nav nav-tabs action-triger">
                            <li class="nav-item">
                                <a class="nav-link type_of_value_filter {{ !$request['recurring'] ? 'active' : '' }}" data-type="" aria-current="all" href="#">All</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link type_of_value_filter {{ $request['recurring'] == 'upfront' ? 'active' : '' }}" data-type="upfront" href="#">Upfront</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link type_of_value_filter {{ $request['recurring'] == 'recurring' ? 'active' : '' }}" data-type="recurring" href="#">Recurring</a>
                            </li>
                        </ul>
                        <h6>Amount of Value</h6>
                        <div class="form-group-row form-row">
                            <div class="form-group col-12 col-lg-6">
                                {!! Form::text('from_value',null,['class' => 'form-control', 'placeholder' => 'From']) !!}
                                <label for="amountFrom">From</label>
                                <span class="currency">USD</span>
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                {!! Form::text('to_value',null,['class' => 'form-control', 'placeholder' => 'To']) !!}
                                <label for="amountTo">To</label>
                                <span class="currency">USD</span>
                            </div>
                        </div>
                        <h6>Amount of Score</h6>
                        <div class="form-group-row form-row">
                            <div class="form-group col-12 col-lg-6">
                                <input type="text" class="form-control" id="amountFrom" placeholder="From" value="0">
                                <label for="amountFrom">From</label>
                                <span class="currency">pts</span>
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <input type="text" class="form-control" id="amountTo" placeholder="To" value="1,000,000.00">
                                <label for="amountTo">To</label>
                                <span class="currency">pts</span>
                            </div>
                        </div>
                        {!! Form::hidden('status',null,['id' => 'filter_status']) !!}
                        {!! Form::hidden('type',null,['id' => 'filter_type']) !!}
                        {!! Form::hidden('recurring',null,['id' => 'recurring']) !!}
                        {!! Form::hidden('page',null,['id' => 'page']) !!}
                        {!! Form::hidden('mode',null,['id' => 'view_mode']) !!}
                        {!! Form::hidden('sort_by',null,['id' => 'sort_by']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-row">
                        <button class="btn btn--sqr btn-secondary close_modal">Cancel</button>
                        <button class="btn btn--sqr btn-primary" id="filter_btn">Apply Filters</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="add-client-modal modal-wrapper modal_item" id="add_new_client_modal">
            <div class="modal-inner card">
                <div class="card-header d-flex align-items-center">
                    <h5>Add New Client</h5>
                    <button class="btn close-modal ml-auto close_modal">
                        <img src="/images/Close.svg" alt="close icon">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-wrap">
                        {!! Form::model($request,['url' => 'client','method' => 'post', 'id' => 'add_client_form']) !!}
                        <h6>Client’s Data</h6>
                        <div class="form-group">
                            {!! Form::text('client_name',null,['class' => 'form-control', 'required' => 'required', 'id' => 'client_name', 'placeholder' => 'Full Name']) !!}
                            {!! Form::label('client_name','Full Name') !!}
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
                                    {!! Form::select('phone_country',$phone_countries,$user_twilio_phone ? $user_twilio_phone->country_code : null,['class' => 'form-control', 'id' => 'phone_country']) !!}
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
                        <div class="form-group custom-select-group dropdown-wrap">
                            <div class="select-toggler" id="add_client_stage">
                                <span class="label">Client’s Stage</span>
                                <span class="selected all" id="add_client_stage_text">All</span>
                            </div>
                            <ul class="dropdown-items" id="add_client_stage">
                                <li data-label="all" class="add_client_stage_item" data-selected="selected">
                                    <span>All</span>
                                </li>
                                @foreach($client_statuses as $key => $item)
                                    <li data-label="{{ $key }}" class="add_client_stage_item" data-selected="">
                                        <span>{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
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
                        <button class="btn btn--sqr btn-secondary close_modal">Cancel</button>
                        <button class="btn btn--sqr btn-primary" id="add_client_btn">Add Client</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('view_script')
<script src="/libs/select2-develop/dist/js/select2.min.js"></script>
<script src="/js/jquery-confirm/jquery-confirm.min.js"></script>
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/js/noty/noty.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script type="text/javascript" src="/js/jquery.inputmask.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        window.AudioObj = {};
        window.AudioPlayingObj = {};
        window.FunnelViewPages = {
            'not-listed' : '1',
            'lead' : '1',
            'quote-meeting' : '1',
            'work-in-progress' : '1',
            'completed' : '1'
        };
        window.process_ajax_load = false;

        $(document).on('click','#view_mode_change',function(){
            $('#view_mode_options').toggleClass('expanded');
            return false;
        });

        $(document).on('click','.change_mode',function(){
            $('#view_mode').val($(this).attr('data-label') == 'list-view' ? 'list' : 'funnel');
            $('#filter_form').submit();
            return false;
        });

        $(document).on('click','.type_of_recurring_filter',function(){
            $('.type_of_recurring_filter').not($(this)).removeClass('active');
            $(this).addClass('active');
            $('#recurring').val($(this).attr('data-type'));
            $('#page').val('1');
            $('#filter_form').submit();
            return false;
        });

        $(document).on('click','#filter_clients',function(){
            $('#filter_modal').addClass('expanded');
            return false;
        });

        $(document).on('click','.close_modal',function(){
            $('.modal_item').removeClass('expanded');
            return false;
        });

        $(document).on('click','#filter_client_stage',function(){
            if ($(this).hasClass('focus')) {
                $(this).parent().removeClass('expanded');
                $(this).removeClass('focus');
            }
            else{
                $(this).parent().addClass('expanded');
                $(this).addClass('focus');
            }
            return false;
        });

        $(document).on('click','.filter_client_stage_item',function(){
            $('.filter_client_stage_item').not($(this)).removeAttr('data-selected');
            $(this).attr('data-selected','selected');
            $('#client_stage_text').text($.trim($(this).text()));
            $('#filter_client_stage').parent().removeClass('expanded');
            $('#filter_client_stage').removeClass('focus');
            $('#client_stage_text').attr('class','selected ' + $(this).attr('data-label'));
            $('#filter_status').val($(this).attr('data-label'));
            return false;
        });

        $(document).on('click','.type_of_client_filter',function(){
            $('.type_of_client_filter').not($(this)).removeClass('active');
            $(this).addClass('active');
            $('#filter_type').val($(this).attr('data-type'));
            return false;
        });

        $(document).on('click','.type_of_value_filter',function(){
            $('.type_of_value_filter').not($(this)).removeClass('active');
            $(this).addClass('active');
            $('#recurring').val($(this).attr('data-type'));
            return false;
        });

        $(document).on('click','#filter_btn',function(){
            $('#page').val('1');
            $('#filter_form').submit();
            return false;
        });

        $(document).on('click','.play_record',function(){
            var $this = $(this);

            if ($(this).attr('data-status') == 'playing') {
                $(this).attr('data-status','pending').removeClass('paused');
                if (!$.isEmptyObject(AudioObj)) {
                    AudioObj.pause();
                    AudioObj.currentTime = 0;
                    AudioPlayingObj = {};
                }
            }
            else{
                $(this).attr('data-status','playing').addClass('paused');

                if (!$.isEmptyObject(AudioObj)) {
                    AudioObj.pause();
                    AudioObj.currentTime = 0;
                    if (!$.isEmptyObject(AudioPlayingObj)) {
                        AudioPlayingObj.attr('data-status','pending').removeClass('paused');
                        AudioPlayingObj = {};
                    }
                }

                AudioObj = new Audio('/records/' + $(this).attr('data-record'));
                AudioObj.play();
                var $this = $(this);
                AudioObj.onended = function(){
                    $this.attr('data-status','pending').removeClass('paused');
                }

                AudioPlayingObj = $this;
            }

            return false;
        });

        $(document).on('click','.client_status',function(){
            $(this).parent().toggleClass('expanded');
            return false;
        });

        $(document).on('click','.client_status_item',function(){
            var label = $(this).attr('data-label');
            var closest_obj = $(this).closest('.select-dropdown');
                closest_obj.removeClass('not-listed lead quote-meeting work-in-progress completed cancelled')
                    .addClass(label)
                    .find('.client_status_text').text($.trim($(this).text()));

                closest_obj.find('.client_status_item').not($(this)).removeAttr('data-selected');
                $(this).attr('data-selected','selected');
                closest_obj.removeClass('expanded');
            $.post('/client/update/status',{ id: $(this).closest('.client_row_item').attr('data-id'), status: label });
            return false;
        });

        $(document).on('click','.action_more',function(){
            $(this).parent().toggleClass('expanded');
            return false;
        });

        $(document).on('click','.pagination a',function(){
            var page_ref = $(this).attr('href');
            $('#page').val(page_ref.split('page=')['1']);
            $('#filter_form').trigger('submit');
            return false;
        });

        $(document).on('change','#check_all_clients',function(){
            if ($(this).prop('checked')) {
                $('.client_check_item').prop('checked',true);
                var total_delete_items = $('.client_check_item').filter(':checked').length;
                if (total_delete_items) {
                    $('#delete_btn_text').text('Delete (' + total_delete_items + ')');
                    $('#delete_clients').removeClass('delete-btn');
                }
            }
            else{
                $('.client_check_item').prop('checked',false);
                $('#delete_clients').addClass('delete-btn');
            }
            return false;
        });

        $(document).on('change','.client_check_item',function(){
            if (!$(this).prop('checked')) {
                $('#check_all_clients').prop('checked',false);
            }

            var total_delete_items = $('.client_check_item').filter(':checked').length;
            if (total_delete_items) {
                $('#delete_btn_text').text('Delete (' + total_delete_items + ')');
                $('#delete_clients').removeClass('delete-btn');
            }
            else{
                $('#delete_clients').addClass('delete-btn');
            }
            return false;
        });

        $(document).on('click','#delete_clients',function(){
            $.confirm({
                title: 'Confirmation required',
                content: 'Are you sure you want to delete selected client(s)',
                buttons: {
                    confirm: function () {
                        var client_ids = [];
                        $('.client_check_item').filter(':checked').each(function(){
                            client_ids.push($(this).closest('.client_row_item').attr('data-id'))
                        });

                        $.post('/client/delete',{ client_ids: client_ids },function(data){
                            location.reload();
                        },'json');
                    },
                    cancel: function () {

                    }
                }
            });
            return false;
        });

        $(document).on('click','.delete_single_item',function(){
            var $this = $(this);
            @if($view_mode == 'list')
                var client_id = $(this).closest('.client_row_item').attr('data-id');
            @else
                var client_id = $(this).closest('.dragable-item').attr('data-id');
            @endif
            $.confirm({
                title: 'Confirmation required',
                content: 'Are you sure you want to delete selected client(s)',
                buttons: {
                    confirm: function () {
                        $.post('/client/delete',{ client_ids: [client_id] },function(data){
                            @if($view_mode == 'list')
                                location.reload();
                            @else
                                $this.closest('.dragable-item').remove();
                            @endif
                        },'json');
                    },
                    cancel: function () {

                    }
                }
            });
            return false;
        });

        $(document).on('click','#add_new_client',function(){
            $('.modal_btn').show();
            set_country_mask($('#phone_country').val());
            $('#add_new_client_modal').addClass('expanded');
            return false;
        });

        $(document).on('click','#add_client_stage',function(){
            if ($(this).hasClass('focus')) {
                $(this).parent().removeClass('expanded');
                $(this).removeClass('focus');
            }
            else{
                $(this).parent().addClass('expanded');
                $(this).addClass('focus');
            }
            return false;
        });

        $(document).on('click','.add_client_stage_item',function(){
            $('.add_client_stage_item').not($(this)).removeAttr('data-selected');
            $(this).attr('data-selected','selected');
            $('#add_client_stage_text').text($.trim($(this).text()));
            $('#add_client_stage').parent().removeClass('expanded');
            $('#add_client_stage').removeClass('focus');
            $('#add_client_stage_text').attr('class','selected ' + $(this).attr('data-label'));
            return false;
        });

        $(document).on('click','#add_client_btn',function(){
            $('#add_client_form').trigger('submit');
            return false;
        });

        $('#add_client_form').validate({
            submitHandler: function(form){
                var status = $('.add_client_stage_item[data-selected="selected"]').attr('data-label');
                if (status == 'all') {
                    $('#client_create_loader').fadeOut();
                    App.render_message('info','Please select status to continue');
                }
                else{
                    $('.modal_btn').hide();
                    $('#client_create_loader').fadeIn();
                    var form_data = {
                        name : $('#client_name').val(),
                        upfront_value: $('#client_upfront_value').val(),
                        ongoing_value: $('#client_ongoing_value').val(),
                        phone_country: $('#phone_country').val(),
                        phone: $('#client_phone').val(),
                        city: $('#client_city').val(),
                        zip: $('#client_zip').val(),
                        address: $('#client_address').val(),
                        company: $('#client_company').val(),
                        email : $('#client_email').val(),
                        status: status
                    }

                    $.post('/client',form_data,function(data){
                        if (data.status) {
                            App.render_message('success','Client created successfully');
                            setTimeout(function(){
                                location.reload();
                            },1000)
                        }
                        else{
                            $('#client_create_loader').fadeOut();
                            $('.modal_btn').show();
                            App.render_message('info',data.error_message);
                        }
                        return false;
                    },'json');
                }
                return false;
            },
            focusInvalid: false,
            onkeyup: false,
            rules : {
                client_name: {
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
                client_name: {
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

        $(document).on('click','.sort_by',function(){
            $('#sort_by').val($(this).attr('data-type'));
            $('#filter_form').submit();
            return false;
        });

        $('#phone_country').select2({
            width: '100%',
            dropdownParent: $('#add_new_client_modal'),
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
            set_country_mask($(this).val());
            return false;
        });

        @if($view_mode == 'funnel')
            $(document).on('click','.funnel_more_btn',function(){
                $(this).parent().toggleClass('expanded');
                return false;
            });

            for (let i in FunnelViewPages){
                load_funnel_view_items(i,FunnelViewPages[i]);
            }

            $('#not_listed_container, #lead_container, #quote_container, #work_in_progress_container, #completed_container').sortable({
                connectWith: '.dragable-items-wrap',
                stop: function(event, ui) {
                    var status = $(ui.item).closest('.dragable-items-wrap').attr('data-type');
                    var progress_obj = $(ui.item).find('.progress');
                    switch (status) {
                        case 'work-in-progress':
                            $(progress_obj).attr('data-progress','75%').find('.processing').css('width','75%').show();
                            $(progress_obj).show();
                            // $(ui.item).find('.current-val').text('75%');
                            // $(ui.item).find('.progress').show();
                        break;
                        case 'completed':
                            $(progress_obj).attr('data-progress','100%').find('.processing').css('width','100%').show();
                            $(progress_obj).show();
                        break;
                        default:
                            $(progress_obj).hide();
                            // $(ui.item).find('.progress').hide();
                        break;
                    }

                    $.post('/client/update/status',{ id: $(ui.item).attr('data-id'), status: status });
                }
            }).disableSelection();
        @endif
    });

    var load_funnel_view_items = function(status, page){
        var position = $('.dragable-items-wrap[data-type="' + status + '"]').scrollTop();
        var bottom = $(document).height() - $('.dragable-items-wrap[data-type="' + status + '"]').height();

        $.post('/client/load/funnel',{status: status, page: page, filter: $('#filter_form').serializeArray()}, function(data){
            if (data.clients) {
                for (let i in data.clients) {
                    $('.dragable-items-wrap[data-type="' + status + '"]').append(_.template($('#client_funnel_item_template').html())({
                        item : data.clients[i]
                    }));
                }
            }
        },'json');
    };

    var set_country_mask = function(country_code) {
        switch (country_code) {
            case 'au':
                $('#client_phone').inputmask("(99) 9999 9999", {clearIncomplete: true});
                break;
            case 'us':
            case 'ca':
                $('#client_phone').inputmask('(999) 999-9999', {clearIncomplete: true});
                break;
            case 'gb':
                $('#client_phone').inputmask('99 999 9999', {clearIncomplete: true});
                break;
        }
    };
</script>
<script type="text/template" id="client_funnel_item_template">
    <li class="dragable-item" data-id="<%= item.client_id %>">
        <div class="info">
            <strong><%= item.name %></strong>
            <%
                var progress = 0;
                if (item.status == 'work-in-progress' || item.status == 'completed') {
                    progress = (item.status == 'work-in-progress') ? 75 : 100;
                }
            %>
            <div class="progress" data-progress="<%= progress %>%" style="<%= progress > 0 ? '' : 'display:none;' %>">
                <div class="progress-bar">
                    <div class="processing" style="width: <%= progress %>%;"></div>
                </div>
            </div>
            <% if (item.upfront_value || item.ongoing_value) { %>
                <span class="small-text">
                    Value: $<%= item.upfront_value ? item.upfront_value : item.ongoing_value %>
                </span>
            <% } else { %>
                <span class="small-text no-value">
                    No Value
                </span>
            <% } %>

            <div class="options-wrap dropdown-wrap">
                <button class="btn options-btn funnel_more_btn">
                    <span></span>
                </button>
                <ul class="dropdown-items">
                    <li data-label="view-profile">
                        <a href="/client/<%= item.client_id %>">
                            View Profile
                        </a>
                    </li>
                    <li data-label="call">Call</li>
                    <li data-label="send-message">Send Message</li>
                    <li data-label="send-email">Send Email</li>
                    <li data-label="send-quote">Send Quote</li>
                    <li data-label="delete-from-database" class="delete_single_item">Delete from Database</li>
                </ul>
            </div>
        </div>
    </li>
</script>
@endsection
