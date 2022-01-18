@extends('layouts.master')
@section('view_css')
<link rel="stylesheet" href="/js/jquery-confirm/jquery-confirm.min.css">
<link rel="stylesheet" href="/js/jquery-ui/jquery-ui.min.css">
<link rel="stylesheet" href="/js/jquery-datetimepicker/jquery.datetimepicker.min.css">
<link rel="stylesheet" href="/js/select2/css/select2.min.css">
@endsection
@section('content')
    @if($auth_user->role == 'sales')
        @include('admin.left_sidebar_admin_menu',['active_page' => 'client'])
    @else
        @include('dashboard.left_sidebar_full_menu',['active_page' => 'client'])
    @endif
    <div class="col-md-auto col-12 content-wrap client-profile">
        <div class="content-inner">
            <div class="client-name d-flex align-items-center">
                <h2>
                    @if($client->company)
                        {{ $client->company }} ({{ $client->name }})
                    @else
                        {{ $client->name }}
                    @endif
                </h2>
                <?php
                    $total_phones = $client->ClientPhone->count();
                ?>
                @if($total_phones)
                    <div class="start-call dropdown-wrap d-flex align-items-center ml-sm-auto">
                        <button class="btn start-call-btn ml-auto" id="start_call_btn" data-phones="{{ $total_phones }}" data-phone-format="{{ $client->ClientPhone['0']->phone_format }}" data-phone="{{ $client->ClientPhone['0']->phone }}">
                            <span>Start Call</span>
                            <img class="icon" src="/images/call-icon-white.svg" alt="Call icon">
                        </button>
                        @if($total_phones > 1)
                            <div class="dropdown-items">
                                <h6>Select Number</h6>
                                <ul>
                                    @foreach($client->ClientPhone as $item)
                                        <li class="call_client" data-phone="{{ $item->phone }}" data-phone-format="{{ $item->phone_format }}">
                                            <img src="/images/outgoing-call-icon-gray.svg" alt="Outgoing call icon" class="icon">
                                            <span>
                                                @if($item->phone_format)
                                                    {{ $item->country_number }} {{ $item->phone_format }}
                                                @else
                                                    {{ $item->phone }}
                                                @endif
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
            <div class="statistical-boxes">
                @include('elements.alerts')
                <div class="row">
                    @switch($client->status)
                        @case('not-listed')
                            <div class="col-12 col-sm-6 col-lg-auto box-item">
                                <div class="statistical-boxe-item stage not-listed">
                                    <figure>
                                        <img src="/images/not-listed-icon.svg" alt="Not listed icon">
                                    </figure>
                                    <div class="info">
                                        <span>Stage</span>
                                        <h2>Not Listed</h2>
                                    </div>
                                </div>
                            </div>
                        @break
                        @case('lead')
                            <div class="col-12 col-sm-6 col-lg-auto box-item">
                                <div class="statistical-boxe-item stage lead">
                                    <figure>
                                        <img src="/images/lead-icon.svg" alt="Lead icon">
                                    </figure>
                                    <div class="info">
                                        <span>Stage</span>
                                        <h2>Lead</h2>
                                    </div>
                                </div>
                            </div>
                        @break
                        @case('quote-meeting')
                            <div class="col-12 col-sm-6 col-lg-auto box-item">
                                <div class="statistical-boxe-item stage quote-meeting">
                                    <figure>
                                        <img src="/images/quote-meeting-icon.svg" alt="Quote meeting icon">
                                    </figure>
                                    <div class="info">
                                        <span>Stage</span>
                                        <h2>Quote <small>meeting</small></h2>
                                    </div>
                                </div>
                            </div>
                        @break
                        @case('work-in-progress')
                            <div class="col-12 col-sm-6 col-lg-auto box-item">
                                <div class="statistical-boxe-item stage in-progress">
                                    <figure>
                                        <img src="/images/work-in-progress.svg" alt="Work in progress icon">
                                    </figure>
                                    <div class="info">
                                        <span>Stage</span>
                                        <h2>Work <small>in progress</small></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-auto box-item">
                                <div class="statistical-boxe-item payment-type outstanding-payments">
                                    <div class="figure">
                                        <div class="circle-progress" data-progress="{{ $outstanding_payments_percentage }}">
                                            <svg>
                                                <circle cx="22.5" cy="22.5" r="22.5" fill="none" stroke="#eff2f9" stroke-width="2"></circle>
                                                <circle cx="22.5" cy="22.5" r="22.5" fill="none" stroke="#43d14f" stroke-width="2"></circle>
                                            </svg>
                                            <h6>{{ $outstanding_payments_percentage }}<span>%</span></h6>
                                        </div>
                                    </div>
                                    <div class="info">
                                        <span>Outstanding Payments</span>
                                        <h2>$ {{ $outstanding_payments }} <small>{{ $today_day_format }}</small></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-auto box-item">
                                <div class="statistical-boxe-item">
                                    <div class="figure">
                                        <img src="/images/money-icon-green.svg" alt="Money icon green">
                                    </div>
                                    <div class="info">
                                        <span>Total Earned</span>
                                        <h2>$ {{ number_format($total_earned,2) }}</h2>
                                    </div>
                                </div>
                            </div>
                        @break
                        @case('completed')
                            <div class="col-12 col-sm-6 col-lg-auto box-item">
                                <div class="statistical-boxe-item stage completed">
                                    <figure>
                                        <img src="/images/completed-icon.svg" alt="Completed icon">
                                    </figure>
                                    <div class="info">
                                        <span>Stage</span>
                                        <h2>Completed</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-auto box-item">
                                <div class="statistical-boxe-item payment-type">
                                    <div class="figure">
                                        <div class="circle-progress" data-progress="{{ $outstanding_payments_percentage }}">
                                            <svg>
                                                <circle cx="22.5" cy="22.5" r="22.5" fill="none" stroke="#eff2f9" stroke-width="2"></circle>
                                                <circle cx="22.5" cy="22.5" r="22.5" fill="none" stroke="#43d14f" stroke-width="2"></circle>
                                            </svg>
                                            <h6>{{ $outstanding_payments_percentage }}<span>%</span></h6>
                                        </div>
                                    </div>
                                    <div class="info">
                                        <span>Outstanding Payments</span>
                                        <h2>$ {{ $outstanding_payments }} <small>{{ $today_day_format }}</small></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-auto box-item">
                                <div class="statistical-boxe-item">
                                    <div class="figure">
                                        <img src="/images/money-icon-green.svg" alt="Money icon green">
                                    </div>
                                    <div class="info">
                                        <span>Total Earned</span>
                                        <h2>$ {{ number_format($total_earned,2) }}</h2>
                                    </div>
                                </div>
                            </div>
                        @break
                        @case('cancelled')
                            <div class="col-12 col-sm-6 col-lg-auto box-item">
                                <div class="statistical-boxe-item stage cancelled">
                                    <figure>
                                        <img src="/images/cancelled-icon.svg" alt="Cancelled icon">
                                    </figure>
                                    <div class="info">
                                        <span>Stage</span>
                                        <h2>Cancelled</h2>
                                    </div>
                                </div>
                            </div>
                        @break
                    @endswitch
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-xl-3 actions-widget-wrapper order-xl-2">
                    <div class="quick-action profile-widget">
                        <h6>Quick Actions</h6>
                        <div class="action-wrap">
                            <div class="row box-row">
                                <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item">
                                    <a href="#" id="add_event" class="action-box d-flex align-items-center event">
                                        <img src="/images/action-calendar.svg" alt="Calendar icon orange" class="icon">
                                        <div class="info">Add Calendar Event</div>
                                    </a>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item">
                                    <a href="#" class="action-box d-flex align-items-center send-message">
                                        <img src="/images/action-message.svg" alt="Message icon" class="icon">
                                        <div class="info">Send Text Message</div>
                                    </a>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item">
                                    <a href="#" id="add_follow_up" class="action-box d-flex align-items-center follow-up">
                                        <img src="/images/action-reminder.svg" alt="Reminder icon" class="icon">
                                        <div class="info">Set a Follow-up</div>
                                    </a>
                                </div>
                                @if($client->status == 'work-in-progress')
                                    <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item">
                                        <a href="#" class="action-box d-flex align-items-center send-invoice">
                                            <img src="/images/action-invoicevia-text.svg" alt="Invoice text icon" class="icon">
                                            <div class="info">Send Invoice via Text</div>
                                        </a>
                                    </div>
                                    <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item">
                                        <a href="#" class="action-box d-flex align-items-center send-invoice">
                                            <img src="/images/action-invoicevia-email.svg" alt="Invoice email icon" class="icon">
                                            <div class="info">Send Invoice via Email</div>
                                        </a>
                                    </div>
                                @else
                                    <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item">
                                        <a href="#" class="action-box d-flex align-items-center send-quote">
                                            <img src="/images/action-quote.svg" alt="Quote icon" class="icon">
                                            <div class="info">Send Quote</div>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="progress-boxes profile-widget">
                        <h6>What is your Progress?</h6>
                        <div class="boxes-wrap">
                            <div class="row box-row">
                                @if($client->status == 'work-in-progress')
                                    <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item">
                                        <a href="#" class="action-box d-flex align-items-center completed">
                                            <figure class="icon">
                                                <img src="/images/payment-received-icon.svg" alt="Invoice email icon">
                                            </figure>
                                            <div class="info">Payment Received</div>
                                        </a>
                                    </div>
                                    <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item">
                                        <a href="#" class="action-box d-flex align-items-center cancelled">
                                            <figure class="icon">
                                                <img src="/images/payment-not-received-icon.svg" alt="Invoice email icon">
                                            </figure>
                                            <div class="info">Payment Not Received Yet</div>
                                        </a>
                                    </div>
                                @endif
                                @if($client->status == 'not-listed')
                                    <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item">
                                        <a href="/client/{{ $client->client_id }}/set/lead" class="action-box d-flex align-items-center lead">
                                            <figure class="icon">
                                                <img src="/images/progress-lead-icon.svg" alt="Lead icon">
                                            </figure>
                                            <div class="info">This became a Lead</div>
                                        </a>
                                    </div>
                                    <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item">
                                        <a href="/client/{{ $client->client_id }}/set/cancelled" class="action-box d-flex align-items-center not-lead">
                                            <figure class="icon">
                                                <img src="/images/progress-no-lead-icon.svg" alt="Not lead icon">
                                            </figure>
                                            <div class="info">This is not a Lead</div>
                                        </a>
                                    </div>
                                @endif
                                @if($client->status == 'lead')
                                    <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item">
                                        <a href="/client/{{ $client->client_id }}/set/cancelled" class="action-box d-flex align-items-center cancelled">
                                            <figure class="icon">
                                                <img src="/images/cancelled-icon.svg" alt="Cancelled icon">
                                            </figure>
                                            <div class="info">Cancelled</div>
                                        </a>
                                    </div>
                                    <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item">
                                        <a href="/client/{{ $client->client_id }}/set/work-in-progress" class="action-box d-flex align-items-center work-in-progress">
                                            <figure class="icon">
                                                <img src="/images/work-in-progress.svg" alt="Invoice text icon">
                                            </figure>
                                            <div class="info">Work in Progress</div>
                                        </a>
                                    </div>
                                    <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item dont_know_container">
                                        <a href="" id="dont_know_yet_btn" class="action-box d-flex align-items-center unknown">
                                            <figure class="icon">
                                                <img src="/images/progress-unknown-icon.svg" alt="Progress unknown icon">
                                            </figure>
                                            <div class="info">Don’t know yet</div>
                                        </a>
                                    </div>
                                @endif
                                @if($client->status == 'completed' || $client->status == 'cancelled')
                                    <div class="col-12 col-sm-6 col-lg-4 col-xl-12 box-item">
                                        <a href="#" class="action-box d-flex align-items-center add-new-project">
                                            <figure class="icon">
                                                <img src="/images/add-new-project-icon.svg" alt="Add new project icon">
                                            </figure>
                                            <div class="info">Add New Project</div>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-9 sections-wrapper order-xl-1">
                    <div class="adding-note profile-widget form-active">
                        <div class="widget-heading d-flex align-items-center">
                            <h6>Notes</h6>
                            <button type="button" id="add_new_note" class="btn add-note-btn btn-primary btn--round d-flex align-items-center ml-auto">
                                <img src="/images/plus-Icons.svg" alt="Plus icon" class="icon">
                                <span>Add New</span>
                            </button>
                        </div>
                        <div class="widget-bdoy">
                            <div class="add-note-form add-new add_note_container" style="display:none;">
                                <form autocomplete="off">
                                    <label for="newNote">Add Note</label>
                                    <div class="form-group">
                                        {!! Form::textarea('new_note',null,['class' => 'form-control', 'id' => 'new_note', 'placeholder' => 'Start typing...', 'maxlength' => '2048']) !!}
                                        {!! Form::label('new_note','Start typing...') !!}
                                    </div>
                                    <div class="btn-row d-flex" id="save_note_container">
                                        <button type="button" class="btn btn-secondary btn--sqr ml-auto cancel-btn" id="cancel_note_create">Cancel</button>
                                        <button type="button" class="btn btn-primary btn--sqr submit-btn" id="save_new_note">Send</button>
                                    </div>
                                    <div id="add_note_loading" style="display: none;">
                                        <img src="/images/loader.png" width="24px" class="float-left">
                                        <span class="float-left ml-1 loader-text">Processing</span>
                                    </div>
                                </form>
                            </div>
                            <div class="note-wrapper scrollable-contents">
                                <ul class="note-items note_container">
                                    @foreach($client->ClientNote as $item)
                                        <li class="note-item row no-gutters">
                                            <div class="col-auto time">
                                                <span title="{{ $item->created_at->format('m/d/Y H:i') }}">
                                                    {{ $item->created_at->format('H:i') }}
                                                </span>
                                            </div>
                                            <div class="col-auto details">
                                                <p>
                                                    {{ $item->note }}
                                                </p>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @if($client_history)
                        <div class="history profile-widget">
                            <div class="widget-heading d-flex align-items-center">
                                <h6>History</h6>
                            </div>
                            <div class="widget-bdoy history-items">
                                <div class="history-item">
                                    <span>Today</span>
                                </div>
                                <section id="history_container" class="mt-3">
                                    @foreach($client_history as $item)
                                        @switch($item['type'])
                                            @case('email_added')
                                                <div class="history-item">
                                                    <div class="icon">
                                                        <img src="/images/history-email.svg" alt="Profile icon green">
                                                    </div>
                                                    <div class="info">
                                                        <p>
                                                            Email Address Added
                                                            <span class="time">{{ \App\Helpers\Helper::convertDateToFriendlyFormat($item['created_at']) }}</span>
                                                        </p>
                                                        <span>{{ $item['description'] }}</span>
                                                    </div>
                                                </div>
                                            @break
                                            @case('form')
                                                <div class="history-item">
                                                    <div class="icon">
                                                        <img src="/images/history-forms-icon.svg" alt="Forms">
                                                    </div>
                                                    <div class="info">
                                                        <p>Form Received<span class="time">{{ \App\Helpers\Helper::convertDateToFriendlyFormat($item['form_created_date']) }}</span></p>
                                                        <?php $form_date_format = Carbon\Carbon::parse($item['form_created_date'])->format('F j, Y H:i'); ?>
                                                        <a href="#" class="open_form_details" data-date-format="{{ $form_date_format }}" data-id="{{ $item['user_form_data_id'] }}">Open · {{ $form_date_format }}</a>
                                                    </div>
                                                </div>
                                            @break
                                            @case('event')
                                                <div class="history-item">
                                                    <div class="icon">
                                                        <img src="/images/history-calendar.svg" alt="Calendar">
                                                    </div>
                                                    <div class="info">
                                                        <p>
                                                            {{ $item['title'] }}
                                                            <span class="time">{{ \App\Helpers\Helper::convertDateToFriendlyFormat($item['created_at']) }}</span>
                                                        </p>
                                                        <?php
                                                            $event_start_date_time = \App\Helpers\Helper::clientHistoryEventDateFormat($item['start_date_time'], $item['end_date_time']);
                                                        ?>
                                                        <a href="#" class="open_event_details" data-date="{{ $item['created_at'] }}" data-time-format="{{ $event_start_date_time }}" data-status="{{ $item['description'] }}" data-event="{{ $item['event_id'] }}">
                                                            {{ $item['description'] }} · {{ $event_start_date_time }}
                                                        </a>
                                                    </div>
                                                </div>
                                            @break
                                            @case('call')
                                                <div class="history-item data-{{ $item['client_history_id'] }}">
                                                    <div class="icon">
                                                        <img src="/images/history-phone-icon.svg" alt="Phone icon green">
                                                    </div>
                                                    <div class="info">
                                                        <p>Outgoing Call<span class="time">{{ \App\Helpers\Helper::convertDateToFriendlyFormat($item['created_at']) }}</span></p>
                                                        <a href="#" class="open_recording" data-audio="{{ $item['recorded_audio_file'] }}" data-format="{{ $item['recorded_playtime_format'] }}" data-call-date="{{ $item['call_start_date'] }}" data-call-start-time="{{ $item['call_start_time'] }}" data-call-end-time="{{ $item['call_end_time'] }}" data-related-id="{{ $item['related_id'] }}">Listen back</a>
                                                    </div>
                                                </div>
                                            @break
                                            @case('client_status')
                                                <div class="history-item">
                                                    <div class="icon">
                                                        <img src="/images/history-profile-icon.svg" alt="Profile icon green">
                                                    </div>
                                                    <div class="info">
                                                        <p>Status Changed<span class="time">{{ \App\Helpers\Helper::convertDateToFriendlyFormat($item['created_at']) }}</span></p>
                                                        <span>{{ $item['description'] }}</span>
                                                    </div>
                                                </div>
                                            @break
                                            @case('upfront_value')
                                                <div class="history-item">
                                                    <div class="icon">
                                                        <img src="/images/history-money-icon.svg" alt="Money icon green">
                                                    </div>
                                                    <div class="info">
                                                        <p>Upfront Value Added<span class="time">{{ \App\Helpers\Helper::convertDateToFriendlyFormat($item['created_at']) }}</span></p>
                                                        <span>{{ $item['description'] }}</span>
                                                    </div>
                                                </div>
                                            @break
                                            @case('ongoing_value')
                                                <div class="history-item">
                                                    <div class="icon">
                                                        <img src="/images/history-money-icon.svg" alt="Money icon green">
                                                    </div>
                                                    <div class="info">
                                                        <p>
                                                            Ongoing Value Added
                                                            <span class="time">{{ \App\Helpers\Helper::convertDateToFriendlyFormat($item['created_at']) }}</span>
                                                        </p>
                                                        <span>{{ $item['description'] }}</span>
                                                    </div>
                                                </div>
                                            @break
                                            @case('invoice_added')
                                                <div class="history-item">
                                                    <div class="icon">
                                                        <img src="/images/history-money-icon.svg" alt="Money">
                                                    </div>
                                                    <div class="info">
                                                        <p>
                                                            Invoice Sent
                                                            <span class="time">{{ \App\Helpers\Helper::convertDateToFriendlyFormat($item['created_at']) }}</span>
                                                        </p>
                                                        <?php
                                                            $invoice_sent_format = \Carbon\Carbon::parse($item['created_at'])->format('M j, Y H:i');
                                                        ?>
                                                        <a href="" class="open_invoice" data-id="{{ $item['related_id'] }}" data-sent-date="{{ $invoice_sent_format }}">Open · {{ $invoice_sent_format }}</a>
                                                    </div>
                                                </div>
                                            @break
                                        @endswitch
                                    @endforeach
                                </section>
                                @if($has_more_history)
                                    <div class="history-item">
                                        <a href="#" class="view-all" id="history_load_more_btn" data-page="1">Show More</a>
                                        <a href="#" class="view-all" id="history_load_less_btn" data-page="1" style="display:none;">Show Less</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                    <div class="client-data profile-widget">
                        <div class="widget-heading d-flex align-items-center">
                            <h6>Client Data</h6>
                        </div>
                        <div class="widget-bdoy">
                            {{ Form::model($client,['action' => ['ClientController@update',$client->client_id],'method' => 'patch', 'autocomplete' => 'off', 'id' => 'client_update_form']) }}
                                <div class="row client-data-row multiple-items-row">
                                    <div class="col-12 col-lg-6">
                                        <h6>Basic Information</h6>
                                        <div class="form-group">
                                            {!! Form::text('name',null,['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Full Name']) !!}
                                            <label for="name">Full Name</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        &nbsp;
                                    </div>
                                    <section class="client_value_container w-100 d-flex">
                                        @foreach ($client->ClientValue as $key => $item)
                                            <div class="col-12 col-lg-6 project-item client_value_item" data-id="{{ $item->client_value_id }}">
                                                <h6 class="project_title">Project {{ $key + 1 }}</h6>
                                                <div class="form-group-row form-row align-items-center">
                                                    <div class="form-group col-auto info-field-wrap project_name_container">
                                                        {!! Form::text('project_'.$key,$item->project_name,['class' => 'form-control project_name', 'placeholder' => 'Project Name']) !!}
                                                        <label for="{{ 'project_'.$key }}">Project Name</label>
                                                    </div>
                                                    <div class="form-group col-auto delete-button-wrap delete_value_item">
                                                        <button class="btn remove-item remove_client_stage">
                                                            <img src="/images/delete-red.svg" alt="Delete icon red">
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="form-group custom-select-group dropdown-wrap">
                                                    {!! Form::select('client_stage_'.$key,$client_statuses,$item->status,['class' => 'client_stage_item', 'id' => 'client_stage_'.$key]) !!}
                                                    {!! Form::label('client_stage_'.$key,'Client’s Stage') !!}
                                                </div>
                                                <h6>Progress</h6>
                                                <div class="form-group progress-slider-wrap">
                                                    <div class="progress-slider-inner">
                                                        <div class="progress-slider" data-progress="0">
                                                            <div class="progress-label"></div>
                                                            <div class="slider-range"></div>
                                                        </div>
                                                        <ul class="steps-wrap d-flex align-items-center" data-steps="5">
                                                            <li class="step-item">0</li>
                                                            <li class="step-item">25</li>
                                                            <li class="step-item">50</li>
                                                            <li class="step-item">75</li>
                                                            <li class="step-item">100</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="form-group-row form-row">
                                                    <div class="form-group col-12 col-sm-6">
                                                        {!! Form::text('upfront_value_'.$key,$item->upfront_value,['class' => 'form-control upfront_value', 'placeholder' => 'Upfront Value', 'id' => 'upfront_value_'.$key]) !!}
                                                        <label for="upfront_value_{{ $key }}">Upfront Value</label>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-6">
                                                        {!! Form::text('ongoing_value_'.$key,$item->ongoing_value,['class' => 'form-control ongoing_value', 'placeholder' => 'Ongoing Value', 'id' => 'ongoing_value_'.$key]) !!}
                                                        <label for="ongoing_value_{{ $key }}">Ongoing Value</label>
                                                    </div>
                                                </div>
                                                <button class="btn add-another add-another-project d-flex align-items-center add_value_item">
                                                    <img src="/images/add-icon-green.svg" alt="Plus icon" class="icon">
                                                    Add Another Project
                                                </button>
                                            </div>
                                        @endforeach
                                    </section>
                                    <div class="col-12 clearfix"></div>
                                    <section class="client_location_container w-100 d-flex">
                                        @foreach($client->ClientLocation as $key => $item)
                                            <div class="col-12 col-lg-6 location-item client_location_item" data-id="{{ $item->client_location_id }}">
                                                <h6 class="location_title">Location #{{ $key + 1 }}</h6>
                                                <div class="form-group-row form-row align-items-center">
                                                    <div class="form-group col-auto info-field-wrap client_address_container">
                                                        <div class="form-group-row form-row">
                                                            <div class="form-group col-12 col-sm-6">
                                                                {!! Form::text('client_city_'.$key,$item->city,['class' => 'form-control client_city', 'placeholder' => 'City']) !!}
                                                                <label for="client_city_{{ $key }}">City</label>
                                                            </div>
                                                            <div class="form-group col-12 col-sm-6">
                                                                {!! Form::text('client_zip_'.$key,$item->zip,['class' => 'form-control client_zip', 'placeholder' => 'ZIP']) !!}
                                                                <label for="client_zip_{{ $key }}">ZIP</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-auto delete-button-wrap delete_location_item">
                                                        <button type="button" class="btn remove-item">
                                                            <img src="/images/delete-red.svg" alt="Delete icon red">
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    {!! Form::text('client_address_'.$key,$item->address,['class' => 'form-control address_line', 'placeholder' => 'Address Line']) !!}
                                                    <label for="client_address_{{ $key }}">Address Line</label>
                                                </div>
                                                <button class="btn add-another d-flex align-items-center add_new_address">
                                                    <img src="/images/add-icon-green.svg" alt="Pluse icon" class="icon">
                                                    Add Another
                                                </button>
                                            </div>
                                        @endforeach
                                    </section>
                                    <div class="col-12 clearfix"></div>
                                    <div class="col-12 col-lg-6">
                                        <h6>Additional Data</h6>
                                        <div class="form-group">
                                            {!! Form::text('company',null,['class' => 'form-control', 'placeholder' => 'Company', 'id' => 'company']) !!}
                                            <label for="company">Company</label>
                                        </div>
                                        <div class="form-group">
                                            {!! Form::email('email',null,['class' => 'form-control', 'placeholder' => 'Email']) !!}
                                            <label for="email">Email</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <h6>Phone Number</h6>
                                        <section class="phone_number_container">
                                            @foreach($client->ClientPhone as $key => $item)
                                                <div class="form-group-row form-row align-items-center phone-item" data-id="{{ $item->client_phone_id }}">
                                                    <div class="phone-number-group d-flex">
                                                        <div class="country-code">
                                                            {!! Form::select('phone_country',$phone_countries,$item->country_code,['class' => 'form-control phone_country']) !!}
                                                        </div>
                                                        <div class="form-group">
                                                            {!! Form::text('client_phone_'.$key,$item->phone_format,['class' => 'form-control client_phone', 'placeholder' => 'Phone Number', 'id' => 'client_phone_'.$key]) !!}
                                                            <label for="client_phone_{{ $key }}">Phone Number</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-auto delete-button-wrap delete_phone_item">
                                                        <button class="btn remove-item">
                                                            <img src="/images/delete-red.svg" alt="Delete icon red">
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </section>
                                        <button type="button" class="btn add-another d-flex align-items-center" id="add_new_phone">
                                            <img src="/images/add-icon-green.svg" alt="Plus icon" class="icon">
                                            <span id="add_phone_text"Add Another</span>
                                        </button>
                                    </div>
                                    <div class="col-12 col-lg-6"></div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button class="btn save-change-btn btn-primary btn--sqr">Save Changes</button>
                                    </div>
                                </div>
                            {!! Form::hidden('client_values',null,['id' => 'client_values']) !!}
                            {!! Form::hidden('client_addresses',null,['id' => 'client_addresses']) !!}
                            {!! Form::hidden('client_phones',null,['id' => 'client_phones']) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div class="tasks profile-widget">
                        <div class="widget-heading d-flex align-items-center">
                            <h6>
                                Tasks <span id="tasks_remaining_title"></span>
                            </h6>
                            <button type="button" id="add_new_task" class="btn add-task-btn btn-primary btn--round d-flex align-items-center ml-auto">
                                <img src="/images/plus-Icons.svg" alt="Plus icon" class="icon">
                                <span>Add New</span>
                            </button>
                        </div>
                        <div class="widget-bdoy">
                            <div class="add-task-form add-new add_task_container">
                                <form autocomplete="off">
                                    <label>Add New Task</label>
                                    <div class="form-group">
                                        {!! Form::select('task_client',[],null,['class' => 'form-control', 'id' => 'task_client']) !!}
                                        <label for="task_title">Type</label>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::text('task_title',null,['class' => 'form-control', 'placeholder' => 'Title of Task', 'id' => 'task_title']) !!}
                                        <label for="task_title">Title of Task</label>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::text('task_description',null,['class' => 'form-control', 'placeholder' => 'Description of Task', 'id' => 'task_description']) !!}
                                        <label for="task_description">Description of Task</label>
                                    </div>
                                    <div class="btn-row d-flex" id="save_task_container">
                                        <button type="button" class="btn btn-secondary btn--sqr ml-auto cancel-btn" id="cancel_add_task">Cancel</button>
                                        <button type="button" class="btn btn-primary btn--sqr submit-btn" id="save_new_task">Add</button>
                                    </div>
                                    <div id="add_task_loading" style="display: none;">
                                        <img src="/images/loader.png" width="24px" class="float-left">
                                        <span class="float-left ml-1 loader-text">Processing</span>
                                    </div>
                                </form>
                            </div>
                            <div class="task-wrapper scrollable-contents">
                                <ul class="task-items" id="task_items_container">
                                    @foreach($client_tasks as $item)
                                        <li class="task-item row no-gutters client-dependent {{ $item->status == 'completed' ? 'done' : '' }}">
                                            <div class="col-auto select-task">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input task_check_item" data-id="{{ $item->user_task_id }}" id="task_{{ $item->user_task_id }}" autocomplete="off" {{ $item->status == 'pending' ? '' : 'checked="checked"' }}>
                                                    <label class="custom-control-label" for="task_{{ $item->user_task_id }}"></label>
                                                </div>
                                            </div>
                                            <div class="col-auto details">
                                                <h6>{{ $item->title }}</h6>
                                                <p>
                                                    @if($item->client_id)
                                                        <span class="green-text">{{ $client->name }}</span>
                                                    @endif
                                                    {{ $item->description }}
                                                </p>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="lead-information profile-widget" id="lead_information_container" style="display:none;">
                        <div class="widget-heading d-flex align-items-center">
                            <h6>Lead Information</h6>
                        </div>
                        <div class="widget-bdoy">
                            <div class="row lead-information-row">
                                <div class="col-12 col-sm-6 col-lg-4 information-item" id="lead_page_container" style="display:none;">
                                    <div class="information">
                                        <figure class="icon">
                                            <img src="/images/lead-page-icon.svg" alt="Lead PAge icon">
                                        </figure>
                                        <div class="info">
                                            <span>Lead Page</span>
                                            <p id="lead_page_url"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4 information-item" id="first_call_container" style="display:none;">
                                    <div class="information">
                                        <figure class="icon">
                                            <img src="/images/interaction-icon.svg" alt="First Interaction icon">
                                        </figure>
                                        <div class="info">
                                            <span>First Interaction</span>
                                            <p id="first_call_title"></p>
                                        </div>
                                    </div>
                                </div>
                                @if($client->quote_meeting_date_time)
                                    <div class="col-12 col-sm-6 col-lg-4 information-item">
                                        <div class="information">
                                            <figure class="icon">
                                                <img src="/images/meeting-icon.svg" alt="Quote Meeting icon">
                                            </figure>
                                            <div class="info">
                                                <span>Quote Meeting</span>
                                                <p>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$client->quote_meeting_date_time)->format('M j, Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if($client->work_started_date_time)
                                    <div class="col-12 col-sm-6 col-lg-4 information-item">
                                        <div class="information">
                                            <figure class="icon">
                                                <img src="/images/work-icon.svg" alt="Work Started icon">
                                            </figure>
                                            <div class="info">
                                                <span>Work Started</span>
                                                <p>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$client->work_started_date_time)->format('M j, Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
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
                    <button class="btn close-modal ml-auto close_modal" data-dismiss="modal" aria-label="Close"><img src="/images/Close.svg" alt="close icon"></button>
                </div>
                {!! Form::open(['url' => 'client', 'id' => 'add_event_form']) !!}
                    <div class="modal-body">
                        <div class="form-wrap">
                            <h6>Client’s Data</h6>
                            <div class="form-group">
                                {!! Form::text('client_name',$client->name,['class' => 'form-control', 'id' => 'client_name', 'readonly' => 'readonly' ]) !!}
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
    <div class="add-new-popup add-calendar-event modal-wrapper" id="add_follow_up_modal">
        <div class="modal-inner">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5>Set Follow-up</h5>
                    <button class="btn close-modal ml-auto close_modal" data-dismiss="modal" aria-label="Close"><img src="/images/Close.svg" alt="close icon"></button>
                </div>
                {!! Form::open(['url' => 'client', 'id' => 'add_follow_up_form']) !!}
                <div class="modal-body">
                    <div class="form-wrap">
                        <h6>Client’s Data</h6>
                        <div class="form-group">
                            {!! Form::text('client_name',$client->name,['class' => 'form-control', 'id' => 'client_name', 'readonly' => 'readonly' ]) !!}
                            <label for="client_name">Full Name</label>
                        </div>
                        <div class="form-group-row form-row">
                            <div class="form-group col-12 col-sm-6">
                                {!! Form::text('event_follow_up_upfront',null,['class' => 'form-control', 'id' => 'event_follow_up_upfront']) !!}
                                {!! Form::label('event_follow_up_upfront','Upfront') !!}
                            </div>
                            <div class="form-group col-12 col-sm-6">
                                {!! Form::text('event_follow_up_ongoing',null,['class' => 'form-control', 'id' => 'event_follow_up_ongoing']) !!}
                                {!! Form::label('event_follow_up_ongoing','Ongoing') !!}
                            </div>
                        </div>
                        <h6>Event Details</h6>
                        <div class="form-group">
                            {!! Form::text('event_follow_up_start_date',null,['class' => 'form-control', 'id' => 'event_follow_up_start_date']) !!}
                            {!! Form::label('event_follow_up_start_date','Starts') !!}
                        </div>
                        <div class="form-group">
                            {!! Form::text('event_follow_up_end_date',null,['class' => 'form-control', 'id' => 'event_follow_up_end_date']) !!}
                            {!! Form::label('event_follow_up_end_date','Ends') !!}
                        </div>
                        <h6>Location</h6>
                        <section class="event_follow_up_location_container"></section>
                        <button id="add_event_follow_up_location" class="btn add-another d-flex align-items-center">
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
                        <button type="submit" class="btn btn--sqr btn-primary">Save Follow-Up</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="modal fade history-popup phone-call-popup" id="phone_call_history_modal" tabindex="-1" role="dialog" aria-labelledby="phoneCallPopupModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-12 header-col d-flex align-items-center">
                        <span class="modal-title" id="phoneCallPopupModalLabel">Phone Call</span>
                        @if($total_client_phones)
                            <button type="button" class="btn call-event-btn ml-auto popup_call_btn">
                                <img src="/images/calendar-event-call.svg" alt="Call icon">
                            </button>
                        @endif
                        <button type="button" class="btn email-event-btn">
                            <img src="/images/calendar-event-email.svg" alt="Email icon">
                        </button>
                        <button type="button" class="btn text-message-event-btn">
                            <img src="/images/calendar-event-text-message.svg" alt="Text message icon">
                        </button>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <img src="/images/calendar-modal-close.svg" alt="Close icon black">
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="popup-status {{ $client->status }}">{{ $client->name }} · {{ $status_label }}</h5>
                            <ul id="call_history_recording_container"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade history-popup calendar-event-popup" id="event_details_modal" tabindex="-1" role="dialog" aria-labelledby="calendarPopupModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-12 col-sm-auto header-col order-2 order-sm-1 main-bg d-flex align-items-center justify-content-center">
                        <span class="modal-title" id="calendarPopupModalLabel">Calendar Event</span>
                    </div>
                    <div class="col-12 col-sm-auto header-col order-1 order-sm-2 d-flex align-items-center">
                        @if($total_client_phones)
                            <button type="button" class="btn call-event-btn popup_call_btn">
                                <img src="/images/calendar-event-call.svg" alt="Call icon">
                            </button>
                        @endif
                        <button type="button" class="btn email-event-btn">
                            <img src="/images/calendar-event-email.svg" alt="Email icon">
                        </button>
                        <button type="button" class="btn text-message-event-btn mr-3">
                            <img src="/images/calendar-event-text-message.svg" alt="Text message icon">
                        </button>

                        <a href="" class="btn edit-event-btn ml-auto" id="edit_event_btn">
                            <img src="/images/calendar-event-edit.svg" alt="Edit icon">
                        </a>
                        <a href="#" class="btn delete-event-btn" id="delete_event_btn" data-event="">
                            <img src="/images/calendar-event-delete.svg" alt="Delete icon">
                        </a>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="event_close_btn">
                            <img src="/images/calendar-modal-close.svg" alt="Close icon black">
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-sm-auto main-bg text-center" id="event_details_container"></div>
                        <div class="col-12 col-sm-auto">
                            <h5 class="popup-status {{ $client->status }}">{{ $client->name }} · {{ $status_label }}</h5>
                            <ul>
                                <li>
                                    <div class="icon">
                                        <img src="/images/popup-time-icon.svg" alt="Time icon">
                                    </div>
                                    <div class="info">
                                        <span>Time</span>
                                        <p id="event_details_time_title"></p>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon">
                                        <img src="/images/popup-category-icon.svg" alt="Category icon">
                                    </div>
                                    <div class="info">
                                        <span>Category</span>
                                        <p id="event_details_quote_meeting_title">Quote Meeting</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade history-popup form-popup" id="form_details_modal" tabindex="-1" role="dialog" aria-labelledby="formPopupModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-12 header-col d-flex align-items-center">
                        <span class="modal-title" id="formPopupModalLabel">Form</span>
                        @if($total_client_phones)
                            <button class="btn call-event-btn popup_call_btn ml-auto">
                                <img src="/images/calendar-event-call.svg" alt="Call icon">
                            </button>
                        @endif
                        <button class="btn email-event-btn {{ $total_client_phones ? '' : 'ml-auto' }}">
                            <img src="/images/calendar-event-email.svg" alt="Email icon">
                        </button>
                        <button class="btn text-message-event-btn">
                            <img src="/images/calendar-event-text-message.svg" alt="Text message icon">
                        </button>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <img src="/images/calendar-modal-close.svg" alt="Close icon black">
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="popup-status {{ $client->status }}">{{ $client->name }} · {{ $status_label }}</h5>
                            <ul id="form_modal_details_container">
                                <li>
                                    <div class="icon">
                                        <img src="/images/popup-time-icon.svg" alt="Time icon">
                                    </div>
                                    <div class="info">
                                        <span>Form Received</span>
                                        <p id="form_received_date"></p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade history-popup invoice-popup" id="invoice_details_modal" tabindex="-1" role="dialog" aria-labelledby="invoicePopupModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-12 header-col d-flex align-items-center">
                        <span class="modal-title" id="invoicePopupModalLabel">Invoice</span>
                        @if($total_client_phones)
                            <button class="btn call-event-btn popup_call_btn ml-auto">
                                <img src="/images/calendar-event-call.svg" alt="Call icon">
                            </button>
                        @endif
                        <button class="btn email-event-btn {{ $total_client_phones ? '' : 'ml-auto' }}">
                            <img src="/images/calendar-event-email.svg" alt="Email icon">
                        </button>
                        <button class="btn text-message-event-btn">
                            <img src="/images/calendar-event-text-message.svg" alt="Text message icon">
                        </button>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <img src="/images/calendar-modal-close.svg" alt="Close icon black">
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="popup-status work-in-progress">{{ $client->name }} · {{ $status_label }}</h5>
                            <ul id="invoice_details_container">
                                <li>
                                    <div class="icon">
                                        <img src="/images/popup-time-icon.svg" alt="Time icon">
                                    </div>
                                    <div class="info">
                                        <span>Invoice Sent</span>
                                        <p id="invoice_sent_date"></p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('view_script')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script type="text/javascript" src="/js/jquery.inputmask.min.js"></script>
<script type="text/javascript" src="/js/jquery-datetimepicker/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript" src="/js/select2/js/select2.min.js"></script>
<script src="https://unpkg.com/wavesurfer.js"></script>
<script src="/js/jquery-confirm/jquery-confirm.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click','.open_form_details',function(){
            $('#form_received_date').text($(this).attr('data-date-format'));
            $('#form_modal_details_container').find('li').not(':first').remove();
            $('#form_details_modal').modal('show');
            $.post('/client/form/details',{ id: $(this).attr('data-id') },function(data){
                if (data.form_data) {
                    var form_data = JSON.parse(data.form_data);
                    $.each(form_data,function(key,value){
                        $('#form_modal_details_container').append(_.template($('#form_detail_item_template').html())({
                            label: key,
                            value: value
                        }))
                    });
                }
            },'json');
            return false;
        });

        window.wavesurfer_object = {};
        window.client_status_progress = {
            'not-listed': 0,
            'lead': 25,
            'quote-meeting': 50,
            'work-in-progress': 75,
            'completed': 100,
            'cancelled': 0
        }

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

        $('#event_follow_up_start_date').datetimepicker({
            format: 'D, j F, Y H:i',
            lang:'en',
            validateOnBlur: false,
            closeOnTimeSelect: true
        });

        $('#event_follow_up_end_date').datetimepicker({
            format: 'D, j F, Y H:i',
            lang:'en',
            validateOnBlur: false,
            closeOnTimeSelect: true
        });

        if (!$('.client_location_item').length) {
            add_new_address();
        }

        @if($client->quote_meeting_date_time || $client->work_started_date_time)
            $('#lead_information_container').show();
        @endif

        $.post('/client/lead/information',{ client_id: '{{ $client->client_id }}' },function(data){
            if (data.user_form_date || data.first_call_date) {
                if (data.user_form_date) {
                    $('#lead_page_url').text(data.user_form_date);
                    $('#lead_page_container').show();
                }

                if (data.first_call_date) {
                    $('#first_call_title').text('Phone Call · ' + data.first_call_date);
                    $('#first_call_container').show();
                }

                $('#lead_information_container').show();
            }
        },'json');

        $(document).on('click','#add_new_note',function(){
            if ($('.add_note_container').is(':visible')) {
                $('.add_note_container').slideUp(function(){
                    $('#new_note').val('');
                });
            }
            else{
                $('.add_note_container').slideDown(function(){
                    $('#new_note').focus();
                });
            }
            return false;
        });

        $(document).on('click','#save_new_note',function(){
            var note = $.trim($('#new_note').val());
            if (note.length) {
                $('#save_note_container').hide();
                $('#add_note_loading').fadeIn();
                $.post('/client/note/create',{ client_id: '{{ $client->client_id }}', note: note },function(data){
                    $('#save_note_container').show();
                    $('#add_note_loading').hide();
                    if (data.status) {
                        $('#new_note').val('');
                        $('.note_container').append(_.template($('#new_note_template').html())({
                            note : note,
                            time_format : data.time_format,
                            date_format : data.date_format
                        }));

                        $('.note_item:last').fadeIn();
                    }
                    else{

                    }
                },'json');
            }
            else{
                $('#new_note').focus();
            }
            return false;
        });

        $(document).on('click','#cancel_note_create',function(){
            $('.add_note_container').slideUp(function(){
                $('#new_note').val('');
            });
            return false;
        });

        $('#phone_call_history_modal').on('hidden.bs.modal', function () {
            if (Object.keys(wavesurfer_object).length) {
                wavesurfer_object.destroy();
                wavesurfer_object = {};
            }

            return false;
        });

        $(document).on('click','.open_recording',function(){
            var audio_file = $(this).attr('data-audio');
            var audio_format = $(this).attr('data-format');
            if (Object.keys(wavesurfer_object).length) {
                wavesurfer_object.destroy();
                wavesurfer_object = {};
            }

            $('#call_history_recording_container').html(_.template($('#call_recording_template').html())({
                id: $(this).attr('data-related-id'),
                call_date: $(this).attr('data-call-date'),
                start_time: $(this).attr('data-call-start-time'),
                end_time: $(this).attr('data-call-end-time'),
                audio_file: audio_file,
                audio_format: audio_format
            }));

            if (audio_file) {
                wavesurfer_object = WaveSurfer.create({
                    container: document.querySelector('#waveform'),
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

                wavesurfer_object.on('audioprocess', function() {
                    var totalTime = wavesurfer_object.getDuration(),
                        currentTime = wavesurfer_object.getCurrentTime(),
                        remainingTime = totalTime - currentTime;

                    var minutes = Math.floor(remainingTime / 60);
                    var seconds = Math.ceil(remainingTime - minutes * 60);
                    var minutes_format =  (minutes) ? (minutes < 10 ? '0' + minutes : minutes) : '00';
                    var seconds_format =  (seconds) ? (seconds < 10 ? '0' + seconds : seconds) : '00';

                    $('.audio_remaining_title').text(minutes_format + ':' + seconds_format);
                });

                wavesurfer_object.on('finish', function(){
                    $('.audio-player').find('.controls').removeClass("playing paused");
                    $('.pause_audio_record_btn').hide();
                    $('.play_audio_record_btn').show();
                });

                wavesurfer_object.load('/records/' + audio_file);
            }

            $('#phone_call_history_modal').modal('show');
            return false;
        });

        $(document).on('click','.play_audio_record_btn',function(){
            var closest_obj = $(this).closest('.audio-player');
            var key_num = closest_obj.attr('data-num');
            wavesurfer_object.play();
            $(this).hide();
            closest_obj.find('.pause_audio_record_btn').show();
            $(this).parent().addClass('playing');
            return false;
        });

        $(document).on('click','.pause_audio_record_btn',function(){
            var closest_obj = $(this).closest('.audio-player');
            wavesurfer_object.pause();
            $(this).hide();
            closest_obj.find('.play_audio_record_btn').show();
            $(this).parent().addClass('paused');
            return false;
        });

        $(document).on('click','.stop_audio_record_btn',function(){
            var closest_obj = $(this).closest('.audio-player');
            wavesurfer_object.stop();
            closest_obj.find('.pause_audio_record_btn').hide();
            closest_obj.find('.play_audio_record_btn').show();
            $(this).parent().removeClass('playing paused');
            return false;
        });

        $(document).on('click','.download_audio_record_btn',function(){
            location.href = '/history/download/recording/' + $(this).attr('data-num');
            return false;
        });

        $(document).on('change','.client_stage_item',function(){
            handle_client_value();
            return false;
        });

        $(document).on('click','.add_value_item',function(){
            $('.client_value_container').append(_.template($('#add_client_value_template').html())({
                num: (new Date()).getTime() * 1000
            }));
            $('.client_value_item:last').slideDown();
            set_masks();
            handle_client_value();
            return false;
        });

        $(document).on('click','.remove_client_stage',function(){
            $(this).closest('.client_value_item').slideUp(function(){
                $(this).remove();
                handle_client_value();
            });
            return false;
        });

        $(document).on('click','.add_new_address',function(){
            add_new_address();
            return false;
        });

        $(document).on('click','.delete_location_item',function(){
            $(this).closest('.client_location_item').slideUp(function(){
                $(this).remove();
                handle_client_location();
            });
            return false;
        });

        $(document).on('click','#add_new_phone',function(){
            $('.phone_number_container').append(_.template($('#phone_number_item_template').html())({
                num: (new Date()).getTime() * 1000
            }));
            $('.phone-item:last').slideDown();
            handle_client_phones();
            return false;
        });

        $(document).on('click','.delete_phone_item',function(){
            $(this).closest('.phone-item').slideUp(function(){
                $(this).remove();
                handle_client_phones();
            });
            return false;
        });

        $(document).on('submit','#client_update_form',function(){
            /**Client Values*/
            var client_values = [];
            $('.client_value_item').each(function(){
                var project_name = $(this).find('.project_name').val();
                var upfront_value = parseFloat($(this).find('.upfront_value').val());
                var ongoing_value = parseFloat($(this).find('.ongoing_value').val());
                var status = $(this).find('.client_stage_item').val();
                if (status) {
                    client_values.push({
                        id: $(this).attr('data-id'),
                        project_name: project_name,
                        status: status,
                        upfront_value: upfront_value,
                        ongoing_value: ongoing_value,
                    });
                }
            });

            if (!client_values.length) {
                App.render_message('error','Please add at least one project');
                return false;
            }

            $('#client_values').val(JSON.stringify(client_values));

            /**Client Addresses*/
            var client_addresses = [];
            $('.client_location_item').each(function(){
                var city = $(this).find('.client_city').val();
                var zip = $(this).find('.client_zip').val();
                var address = $(this).find('.address_line').val();
                if (city.length || zip.length || address.length) {
                    client_addresses.push({
                        id: $(this).attr('data-id'),
                        city: city,
                        zip: zip,
                        address: address,
                    });
                }
            });

            $('#client_addresses').val(JSON.stringify(client_addresses));

            /**Client Phones*/
            var client_phones = [];
            $('.phone-item').each(function(){
                var phone = $(this).find('.client_phone').val();
                if (phone.length) {
                    client_phones.push({
                        phone: phone,
                        country: $(this).find('.phone_country').val(),
                        id: $(this).attr('data-id')
                    });
                }
            });

            if (!client_phones.length) {
                App.render_message('error','Please add at least one phone number');
                return false;
            }

            $('#client_phones').val(JSON.stringify(client_phones));
            return true;
        });

        $(document).on('click','#add_new_task',function(){
            if ($('.add_task_container').is(':visible')) {
                $('.add_task_container').slideUp(function(){
                    $('#task_client').val('global');
                    $('#task_title').val('');
                    $('#task_description').val('');
                });
            }
            else{
                $('.add_task_container').slideDown(function(){
                    setTaskClientDropdown();
                });
            }
            return false;
        });

        $(document).on('click','#cancel_add_task',function(){
            $('.add_task_container').slideUp(function(){
                $('#task_title').val('');
                $('#task_description').val('');
            });
            return false;
        });

        $(document).on('click','#save_new_task',function(){
            var client_id = $('#task_client').val();
                client_id = (client_id) ? client_id : 'global';
            var title = $.trim($('#task_title').val());
            if (!title.length) {
                $('#task_title').focus();
                return false;
            }

            var description = $.trim($('#task_description').val());
            if (!description.length) {
                $('#task_description').focus();
                return false;
            }

            $('#save_task_container').hide();
            $('#add_task_loading').fadeIn();
            var global_task = $('#is_global_task').prop('checked');
            $.post('/client/task/create',{ client_id: client_id, title: title, description: description},function(data){
                $('#save_task_container').show();
                $('#add_task_loading').hide();
                if (data.status) {
                    $('#task_title,#task_description').val('');
                    if (client_id == 'global' || client_id == '{{ $client->client_id }}') {
                        $('#task_items_container').append(_.template($('#task_item_template').html())({
                            id : data.id,
                            title : title,
                            description : description,
                            global_task: global_task
                        }));
                        claculate_not_completed_tasks();
                        $('.task-item:last').fadeIn();
                    }
                    else{
                        App.render_message('success','Task added successfully');
                    }
                }
                else{
                    App.render_message('info',data.error);
                }
            },'json');
            return false;
        });

        $(document).on('change','.task_check_item',function(){
            var is_checked = $(this).prop('checked');
            $(this).closest('.task-item').toggleClass('done');
            claculate_not_completed_tasks();
            $.post('/client/task/check',{ client_id: '{{ $client->client_id }}', id: $(this).attr('data-id'), checked: is_checked ? '1' : '0' });
            return false;
        });

        $(document).on('click','#dont_know_yet_btn',function(){
            $('.dont_know_container').hide();
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

            @switch($client->status)
                @case('not-listed')
                @case('lead')
                    var event_type = 'quote-meeting';
                @break
                @case('quote-meeting')
                @case('work-in-progress')
                    var event_type = 'work-in-progress';
                @break
                @case('completed')
                @case('cancelled')
                    var event_type = 'remind-me';
                @break
            @endswitch

            $('#add_event_type').val(event_type).trigger('change');
            $('#add_event_modal').addClass('expanded');
            $('#add_event_type').select2({
                width: '100%',
                minimumResultsForSearch: -1,
                dropdownParent: $('#add_event_modal'),
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
            })
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
                client_id : '{{ $client->client_id }}',
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

        /**Add Follow Up*/
        $(document).on('click','#add_event_follow_up_location',function(){
            $('.event_follow_up_location_container').append(_.template($('#event_follow_up_location_item_template').html())({
                num: (new Date()).getTime() * 1000
            }));

            $('.event_follow_up_location_item:last').slideDown();
            return false;
        });

        $(document).on('click','#add_follow_up',function(){
            $('.event_follow_up_location_container').html(_.template($('#event_follow_up_location_item_template').html())({
                num: (new Date()).getTime() * 1000
            }));
            $('.event_follow_up_location_item:last').slideDown();
            if ($('.upfront_value').length) {
                $('#event_follow_up_upfront').val($('.upfront_value').first().val());
                $('#event_follow_up_ongoing').val($('.ongoing_value').first().val());
            }
            $('#add_follow_up_modal').addClass('expanded');
            return false;
        });

        $(document).on('click','.delete_event_follow_up_location_item',function(){
            $(this).closest('.event_follow_up_location_item').slideUp(function(){
                $(this).remove();
            });
            return false;
        });

        $(document).on('submit','#add_follow_up_form',function(){
            var start_date = $('#event_follow_up_start_date').val();
            if (!start_date) {
                $('#event_follow_up_start_date').focus();
                return false;
            }

            var end_date = $('#event_follow_up_end_date').val();
            if (!end_date) {
                $('#event_follow_up_end_date').focus();
                return false;
            }

            var locations = [];
            $('.event_follow_up_location_item').each(function(){
                var city = $(this).find('.event_follow_up_city').val();
                var zip = $(this).find('.event_follow_up_zip').val();
                var address = $(this).find('.event_follow_up_address').val();
                if (city.length || zip.length || address.length) {
                    locations.push({
                        city: city,
                        zip: zip,
                        address: address
                    });
                }
            });

            var form_data = {
                client_id : '{{ $client->client_id }}',
                start_date: start_date,
                end_date: end_date,
                upfront_value: $('#event_follow_up_upfront').val(),
                ongoing_value: $('#event_follow_up_ongoing').val(),
                locations: locations
            }

            $.post('/client/add/follow-up',form_data,function(data){
                if (data.status) {
                    $('#add_follow_up_modal').removeClass('expanded');
                    $('#add_follow_up_form')['0'].reset();
                    App.render_message('success','Follow-up created successfully');
                }
                else{
                    App.render_message('info',data.error);
                }
            },'json');
            return false;
        });

        /**History*/
        $(document).on('click','#history_load_more_btn',function(){
            var page = $(this).attr('data-page');
            $.post('/client/load/history',{ client_id: '{{ $client->client_id }}', page: page },function(data){
                if (data.status) {
                    $.each(data.items,function(key,value){
                        $('#history_container').append(_.template($('#history_item_template').html())({
                            item: value
                        }));
                    });

                    if (data.has_more_items) {
                        $('#history_load_more_btn').attr('data-page',parseInt(page) + 1);
                    }
                    else{
                        $('#history_load_more_btn').hide();
                        $('#history_load_less_btn').show();
                    }
                }
                else{
                    App.render_message('error',data.error);
                }
            },'json');
            return false;
        });

        $(document).on('click','#history_load_less_btn',function(){
            $('#history_load_more_btn').attr('data-page',1);
            $(this).hide();
            $('#history_load_more_btn').show();
            $('#history_container > .history-item:gt({{ $history_page_limit - 1 }})').remove();
            return false;
        });

        $(document).on('change','.phone_country',function(){
            set_country_mask();
            return false;
        });

        $(document).on('click','#start_call_btn',function(){
            @if($user_twilio_phone)
                if (parseInt($(this).attr('data-phones')) > 1) {
                    $('.start-call').toggleClass('expanded');
                }
                else{
                    var phone = $(this).attr('data-phone');
                    var phone_format = $(this).attr('data-phone-format');

                    App.call.process_outgoing_call(App.call.twilio_outgoing_obj,
                        phone,
                        '{{ $user_twilio_phone->phone }}',
                        'client',
                        '{{ $client->name }}',
                        '{{ $client->client_id }}',
                        phone_format ? phone_format : phone
                    );
                }
            @else
                App.render_message('info','Please upgrade your subscription to get a new phone number to make calls');
            @endif
            return false;
        });

        $(document).on('click','.call_client',function(){
            @if($user_twilio_phone)
                $('.start-call').removeClass('expanded');
                var phone = $(this).attr('data-phone');
                var phone_format = $(this).attr('data-phone-format');
                App.call.process_outgoing_call(App.call.twilio_outgoing_obj,
                    phone,
                    '{{ $user_twilio_phone->phone }}',
                    'client',
                    '{{ $client->name }}',
                    '{{ $client->client_id }}',
                    phone_format ? phone_format : phone
                );
            @else
                App.render_message('info','Please upgrade your subscription to get a new phone number to make calls');
            @endif
            return false;
        });

        $(document).on('click','.open_event_details',function(){
            var event_id = $(this).attr('data-event');
            $('#event_details_time_title').text($(this).attr('data-time-format'))
            $('#event_details_quote_meeting_title').text($(this).attr('data-status'));
            if (event_id) {
                $('#edit_event_btn').attr('href','/calendar/' + event_id).show();
                $('#delete_event_btn').attr('data-event',event_id).show();
                $('#event_close_btn').removeClass('ml-auto');
            }
            else{
                $('#edit_event_btn,#delete_event_btn').hide();
                $('#event_close_btn').addClass('ml-auto');
            }
            $('#event_details_modal').modal('show');
            $.post('/client/history/events',{ start_date: $(this).attr('data-date') },function(data){
                $('#event_details_container').html(_.template($('#event_details_template').html())({
                    item: data
                }));
            },'json');
            return false;
        });

        $(document).on('click','#delete_event_btn',function(){
            var event_id = $(this).attr('data-event');
            var $this = $(this);
            $.confirm({
                scrollToPreviousElement: false,
                scrollToPreviousElementAnimate: false,
                title: 'Confirmation required',
                content: 'Are you sure you want to delete this event?',
                buttons: {
                    confirm: function () {
                        $('#edit_event_btn,#delete_event_btn').hide();
                        $('#event_close_btn').addClass('ml-auto');
                        $('.open_event_details[data-event="' + event_id + '"]').attr('data-event','');
                        $.post('/client/event/delete',{ event_id: event_id },function(data){

                        },'json');
                    },
                    cancel: function () {

                    }
                }
            });
            return false;
        });

        $(document).on('click','.popup_call_btn',function(){
            $('.modal').modal('hide');
            $('.modal-backdrop').remove();
            window.scrollTo(0,0);
            $('#start_call_btn').trigger('click');
            return false;
        });

        $(document).on('click','.open_invoice',function(){
            $('#invoice_sent_date').text($(this).attr('data-sent-date'));
            $('#invoice_details_container').find('li').not(':first').remove();
            $('#invoice_details_modal').modal('show');
            $.post('/client/invoice/details',{ client_id: '{{ $client->client_id }}', invoice_id : $(this).attr('data-id') },function(data){
                $('#invoice_details_container').append(_.template($('#invoice_details_template').html())({
                    invoice: data
                }));
            },'json');
            return false;
        });

        @if($call_client)
            if ($('#start_call_btn').length) {
                $('#start_call_btn').trigger('click');
            }
        @endif

        set_masks();
        handle_client_value();
        handle_client_location();
        handle_client_phones();
        claculate_not_completed_tasks();
        setTaskClientDropdown();
    });

    var set_masks = function(){
        $('.upfront_value').inputmask('decimal',{
            rightAlign: false,
            placeholder: ''
        });
        $('.ongoing_value').inputmask('decimal',{
            rightAlign: false,
            placeholder: ''
        });
        $('.event_upfront').inputmask('decimal',{
            rightAlign: false,
            placeholder: ''
        });
        $('.event_ongoing').inputmask('decimal',{
            rightAlign: false,
            placeholder: ''
        });
        $('#follow_up_upfront').inputmask('decimal',{
            rightAlign: false,
            placeholder: ''
        });
        $('#follow_up_upfront').inputmask('decimal',{
            rightAlign: false,
            placeholder: ''
        });
    }

    var handle_client_value = function(){
        $('.project_title').each(function(key,value){
            $(this).text('Project #' + (key + 1))
        });

        var last_obj = $('.add_value_item:last');
        $('.add_value_item').not(last_obj).attr('style','display:none !important');
        last_obj.removeAttr('style');

        if ($('.client_value_item').length == 1) {
            $('.project_name_container').removeClass('info-field-wrap').addClass('w-100');
            $('.delete_value_item').hide();
            $('.add_value_item').show();
        }
        else{
            $('.project_name_container').removeClass('w-100').addClass('info-field-wrap');
            $('.delete_value_item').show();
        }

        $('.client_stage_item').select2({
            width: '100%',
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

        /**Handle Progress Bar*/
        $('.progress-slider').each(function(){
            var closest_client_stage = $(this).closest('.client_value_item').find('.client_stage_item');
            var value = client_status_progress[closest_client_stage.val()];
                value = value ? value : 0;
            var slider_range_obj = $(this).find('.slider-range');
            var progress_label_obj = $(this).find('.progress-label');
            slider_range_obj.slider({
                range: "min",
                min: 0,
                max: 100,
                step: 25,
                value: value,
                slide: function (event, ui) {
                    for (let i in client_status_progress) {
                        if (client_status_progress[i] == ui.value) {
                            closest_client_stage.val(i).trigger('change');
                        }
                    }

                    var delay = function () {
                        progress_label_obj.html(ui.value + '%').position({
                            my: 'center bottom',
                            at: 'center top',
                            of: ui.handle,
                            offset: "0, 10"
                        });
                    };

                    setTimeout(delay, 5);
                }
            });

            $(this).find('.progress-label').html(value + '%').position({
                my: 'center bottom',
                at: 'center top',
                of: slider_range_obj.find('.ui-slider-handle'),
                offset: "0, 10"
            });
        });
    }

    var handle_client_location = function(){
        $('.location_title').each(function(key,value){
            $(this).text('Location #' + (key + 1))
        });

        var last_obj = $('.add_location_item:last');
        $('.add_location_item').not(last_obj).attr('style','display:none !important');
        last_obj.removeAttr('style');

        if ($('.client_location_item').length == 1) {
            $('.client_address_container').removeClass('info-field-wrap').addClass('w-100');
            $('.delete_location_item').hide();
            $('.add_location_item').show();
        }
        else{
            $('.client_address_container').removeClass('w-100').addClass('info-field-wrap');
            $('.delete_location_item').show();
        }

        var last_item = $('.add_new_address').last();
        $('.add_new_address').not(last_item).removeClass('d-flex').hide();
        last_item.addClass('d-flex').show();
    }

    var handle_client_phones = function(){
        $('#add_phone_text').text($('.phone-item').length ? 'Add Another' : 'Add New');
        set_country_mask();
    }

    var add_new_address = function(){
        $('.client_location_container').append(_.template($('#add_client_location_template').html())({
            num: (new Date()).getTime() * 1000
        }));
        $('.client_location_item:last').slideDown();
        set_masks();
        handle_client_location();
        $('.add_new_address').not($('.add_new_address').last()).removeClass('d-flex').hide();
    }

    var claculate_not_completed_tasks = function(){
        var total_remaining = 0;
        $('.task-item').each(function(){
            if (!$(this).hasClass('done')) {
                total_remaining++;
            }
        });

        $('#tasks_remaining_title').text(total_remaining);
    }

    var setTaskClientDropdown = function(){
        $('#task_client').select2({
            ajax: {
                url: '/client/task/search',
                type: 'POST',
                data: function (params) {
                    return {
                        term: params.term,
                        client: '{{ $client->client_id }}'
                    }
                },
                dataType: 'json',
                processResults: function (data) {
                    var task_data = [
                        {
                            text: 'No Client (Global)',
                            id: 'global'
                        },
                        {
                            text: '{{ $client->name }}',
                            id: '{{ $client->client_id }}'
                        }
                    ];
                    $.each(data.clients,function(key,value){
                        task_data.push({
                            text: value.name,
                            id: value.client_id
                        });
                    });
                    return {
                        results: task_data
                    };
                }
            }
        });
    }

    var set_country_mask = function() {
        $('.phone_country').each(function(){
            var country_code = $(this).val();
            var phone_number_obj = $(this).closest('.phone-item').find('.client_phone');
            switch (country_code) {
                case 'au':
                    phone_number_obj.inputmask("(99) 9999 9999",{ clearIncomplete: true });
                break;
                case 'us':
                case 'ca':
                    phone_number_obj.inputmask('(999) 999-9999',{ clearIncomplete: true });
                break;
                case 'gb':
                    phone_number_obj.inputmask('99 999 9999',{ clearIncomplete: true });
                break;
            }

            $(this).select2({
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
        });
    }
</script>
<script type="text/template" id="add_client_value_template">
    <div class="col-12 col-lg-6 project-item client_value_item" data-id="" style="display:none;">
        <h6 class="project_title"></h6>
        <div class="form-group-row form-row align-items-center">
            <div class="form-group col-auto info-field-wrap project_name_container">
                <input type="text" class="form-control project_name" placeholder="Project Name" id="project_<%= num %>">
                <label for="project_<%= num %>">Project Name</label>
            </div>
            <div class="form-group col-auto delete-button-wrap delete_value_item">
                <button class="btn remove-item remove_client_stage">
                    <img src="/images/delete-red.svg" alt="Delete icon red">
                </button>
            </div>
        </div>
        <div class="form-group custom-select-group dropdown-wrap">
            <select class="client_stage_item" id="client_stage_<%= num %>">
                @foreach($client_statuses as $key => $item)
                    <option value="{{ $key }}">{{ $item }}</option>
                @endforeach
            </select>
            <label for="client_stage_<%= num %>">Client’s Stage</label>
        </div>
        <h6>Progress</h6>
        <div class="form-group progress-slider-wrap">
            <div class="progress-slider-inner">
                <div class="progress-slider" data-progress="0">
                    <div class="progress-label"></div>
                    <div class="slider-range"></div>
                </div>
                <ul class="steps-wrap d-flex align-items-center" data-steps="5">
                    <li class="step-item">0</li>
                    <li class="step-item">25</li>
                    <li class="step-item">50</li>
                    <li class="step-item">75</li>
                    <li class="step-item">100</li>
                </ul>
            </div>
        </div>
        <div class="form-group-row form-row">
            <div class="form-group col-12 col-sm-6">
                <input type="text" class="form-control upfront_value" placeholder="Upfront Value" id="upfront_value_<%= num %>">
                <label for="upfront_value_<%= num %>">Upfront Value</label>
            </div>
            <div class="form-group col-12 col-sm-6">
                <input type="text" class="form-control ongoing_value" placeholder="Ongoing Value" id="ongoing_value_<%= num %>">
                <label for="ongoing_value_<%= num %>">Ongoing Value</label>
            </div>
        </div>
        <button class="btn add-another add-another-project d-flex align-items-center add_value_item">
            <img src="/images/add-icon-green.svg" alt="Pluse icon" class="icon">
            Add Another Project
        </button>
    </div>
</script>
<script type="text/template" id="add_client_location_template">
    <div class="col-12 col-lg-6 location-item client_location_item" data-id="" style="display:none;">
        <h6 class="location_title"></h6>
        <div class="form-group-row form-row align-items-center">
            <div class="form-group col-auto info-field-wrap client_address_container">
                <div class="form-group-row form-row">
                    <div class="form-group col-12 col-sm-6">
                        <input type="text" class="form-control client_city" id="client_city_<%= num %>" placeholder="City">
                        <label for="client_city_<%= num %>">City</label>
                    </div>
                    <div class="form-group col-12 col-sm-6">
                        <input type="text" class="form-control client_zip" id="client_zip_<%= num %>" placeholder="ZIP">
                        <label for="client_zip_<%= num %>">ZIP</label>
                    </div>
                </div>
            </div>
            <div class="form-group col-auto delete-button-wrap delete_location_item">
                <button type="button" class="btn remove-item">
                    <img src="/images/delete-red.svg" alt="Delete icon red">
                </button>
            </div>
        </div>
        <div class="form-group">
            <input type="text" class="form-control address_line" id="address_line_<%= num %>" placeholder="Address Line">
            <label for="address_line_<%= num %>">Address Line</label>
        </div>
        <button class="btn add-another d-flex align-items-center add_new_address">
            <img src="/images/add-icon-green.svg" alt="Plus icon" class="icon">
            Add Another
        </button>
    </div>
</script>
<script type="text/template" id="phone_number_item_template">
    <div class="form-group-row form-row align-items-center phone-item" data-id="" style="display:none;">
        <div class="phone-number-group d-flex">
            <div class="country-code">
                {!! Form::select('phone_country',$phone_countries,$user_twilio_phone ? $user_twilio_phone->country_code : null,['class' => 'form-control phone_country']) !!}
            </div>
            <div class="form-group">
                <input type="text" id="client_phone_<%= num %>" class="form-control client_phone" placeholder="Phone Number">
                <label for="client_phone_<%= num %>">Phone Number</label>
            </div>
        </div>
        <div class="form-group col-auto delete-button-wrap delete_phone_item">
            <button class="btn remove-item">
                <img src="/images/delete-red.svg" alt="Delete icon red">
            </button>
        </div>
    </div>
</script>
<script type="text/template" id="new_note_template">
    <li class="note-item row no-gutters note_item" style="display:none;">
        <div class="col-auto time">
            <span title="<%= date_format %>">
                <%= time_format %>
            </span>
        </div>
        <div class="col-auto details">
            <p>
                <%= note %>
            </p>
        </div>
    </li>
</script>
<script type="text/template" id="task_item_template">
    <li class="task-item row no-gutters client-dependent" style="display: none;">
        <div class="col-auto select-task">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input task_check_item" data-id="<%= id %>" id="task_<%= id %>" autocomplete="off">
                <label class="custom-control-label" for="task_<%= id %>"></label>
            </div>
        </div>
        <div class="col-auto details">
            <h6><%= title %></h6>
            <p>
                <% if (!global_task) { %>
                    <span class="green-text">{{ $client->name }}</span>
                <% } %>
                <%= description %>
            </p>
        </div>
    </li>
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
<script type="text/template" id="event_follow_up_location_item_template">
    <div class="row event_follow_up_location_item" style="display:none;">
        <div class="col-md-5">
            <div class="form-group">
                <input type="text" name="event_city" class="form-control event_follow_up_city" id="city_<%= num %>">
                <label for="city_<%= num %>">City</label>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <input type="text" name="event_zip" class="form-control event_follow_up_zip" id="zip_<%= num %>">
                <label for="zip_<%= num %>">ZIP</label>
            </div>
        </div>
        <div class="col-md-2">
            <div class="mt-2 delete-button-wrap delete_value_item">
                <button class="btn remove-item remove_client_stage delete_event_follow_up_location_item">
                    <img src="/images/delete-red.svg" alt="Delete icon red">
                </button>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <input type="text" name="event_address" class="form-control event_follow_up_address" id="address_<%= num %>">
                <label for="address_<%= num %>">Address Line</label>
            </div>
        </div>
    </div>
</script>
<script type="text/template" id="history_item_template">
    <% switch(item.type) {
        case 'event':
    %>
            <div class="history-item">
                <div class="icon">
                    <img src="/images/history-calendar.svg" alt="Calendar">
                </div>
                <div class="info">
                    <p><%= item.title %><span class="time"><%= item.time_ago_format %></span></p>
                    <a href="#" class="open_event_details" data-date="<%= item.created_at %>" data-time-format="<%= item.date_time_format %>" data-status="<%= item.description %>" data-event="<%= item.event_id %>">
                        <%= item.description %> · <%= item.date_time_format %>
                    </a>
                </div>
            </div>
    <%
        break;
        case 'form':
    %>
            <div class="history-item">
                <div class="icon">
                    <img src="/images/history-forms-icon.svg" alt="Forms">
                </div>
                <div class="info">
                    <p>Form Received<span class="time"><%= item.time_ago_format %></span></p>
                    <a href="#" class="open_form_details" data-date-format="<%= item.form_created_date_format %>" data-id="<%= item.user_form_data_id %>">Open · <%= item.form_created_date_format %></a>
                </div>
            </div>
    <%
        break;
        case 'email_added':
    %>
            <div class="history-item">
                <div class="icon">
                    <img src="/images/history-email.svg" alt="Profile icon green">
                </div>
                <div class="info">
                    <p>
                        Email Address Added
                        <span class="time"><%= item.time_ago_format %></span>
                    </p>
                    <span><%= item.description %></span>
                </div>
            </div>
    <%
        break;
        case 'call':
    %>
            <div class="history-item">
                <div class="icon">
                    <img src="/images/history-phone-icon.svg" alt="Phone icon green">
                </div>
                <div class="info">
                    <p>Outgoing Call<span class="time"><%= item.time_ago_format %></span></p>
                    <a href="#" class="open_recording" data-audio="<%= item.recorded_audio_file %>" data-format="<%= item.recorded_playtime_format %>" data-call-date="<%= item.call_start_date %>" data-call-start-time="<%= item.call_start_time %>" data-call-end-time="<%= item.call_end_time %>" data-related-id="<%= item.related_id %>">Listen back</a>
                </div>
            </div>
    <%
        break;
        case 'client_status':
    %>
            <div class="history-item">
                <div class="icon">
                    <img src="/images/history-profile-icon.svg" alt="Profile icon green">
                </div>
                <div class="info">
                    <p>Status Changed<span class="time"><%= item.time_ago_format %></span></p>
                    <span><%= item.description %></span>
                </div>
            </div>
    <%
        break;
        case 'upfront_value':
    %>
            <div class="history-item">
                <div class="icon">
                    <img src="/images/history-money-icon.svg" alt="Money icon green">
                </div>
                <div class="info">
                    <p>Upfront Value Added<span class="time"><%= item.time_ago_format %></span></p>
                    <span><%= item.description %></span>
                </div>
            </div>
    <%
        break;
        case 'ongoing_value':
    %>
            <div class="history-item">
                <div class="icon">
                    <img src="/images/history-money-icon.svg" alt="Money icon green">
                </div>
                <div class="info">
                    <p>Ongoing Value Added<span class="time"><%= item.time_ago_format %></span></p>
                    <span><%= item.description %></span>
                </div>
            </div>
    <%
        break;
        case 'invoice_added':
    %>
            <div class="history-item">
                <div class="icon">
                    <img src="/images/history-money-icon.svg" alt="Money">
                </div>
                <div class="info">
                    <p>
                        Invoice Sent
                        <span class="time"><%= item.time_ago_format %></span>
                    </p>
                    <a href="" class="open_invoice" data-id="<%= item.related_id %>" data-sent-date="<%= item.form_created_date_format %>">Open · <%= item.form_created_date_format %></a>
                </div>
            </div>
    <%
        break;
    } %>
</script>
<script type="text/template" id="call_recording_template">
    <li>
        <div class="icon">
            <img src="/images/popup-time-icon.svg" alt="Time icon">
        </div>
        <div class="info">
            <span>Phone Call Recorded</span>
            <p>
                <%= call_date %> · <%= start_time %> - <%= end_time %>
            </p>
        </div>
    </li>
    <li style="<%= audio_file ? '' : 'display:none !important;' %>">
        <div class="icon">
            <img src="/images/history-popup-recording.svg" alt="Location icon">
        </div>
        <div class="info">
            <span>Recording</span>
        </div>
        <div class="info d-flex align-items-center audio-player">
            <div class="audio-wave">
                <div class="waveform" id="waveform" data-file="<%= audio_file %>" data-time-format="<%= audio_format %>"></div>
            </div>
            <div class="controls d-flex align-items-center">
                <div class="time-log audio_remaining_title"></div>
                <button type="button" class="btn download-btn download_audio_record_btn" data-num="<%= id %>">
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
    </li>
</script>
<script type="text/template" id="event_details_template">
    <span><%= item.date_week_name %></span>
    <h2><%= item.date_format %></h2>
    <% if (item.events.length) { %>
        <ul class="history-wrap">
            <% for (let i in item.events) { %>
                <li>
                    <a href="/calendar/<%= item.events[i].event_id %>" class="link-default-color">
                        <%= item.events[i].start_time %> <%= item.events[i].name %>
                    </a>
                </li>
            <% } %>
        </ul>
    <% } else { %>
        <div class="history-wrap text-center">
            No events found
        </div>
    <% } %>
</script>
<script type="text/template" id="form_detail_item_template">
    <li>
        <div class="icon">
            <img src="/images/popup-form-question.svg" alt="Form question icon">
        </div>
        <div class="info">
            <span class="text-capitalize"><%= label %></span>
            <p><%= value %></p>
        </div>
    </li>
</script>
<script type="text/template" id="invoice_details_template">
    <% if (invoice.has_paid == 1) { %>
        <li>
            <div class="icon">
                <img src="/images/popup-form-question.svg" alt="Form question icon">
            </div>
            <div class="info">
                <span>Status</span>
                <div class="status success">
                    <img src="/images/paid-check-green.svg" alt="Check green">
                    <span>Paid</span>
                </div>
            </div>
        </li>
    <% } else {
        if (invoice.is_overdue) {
    %>
        <li>
            <div class="icon">
                <img src="/images/popup-form-question.svg" alt="Form question icon">
            </div>
            <div class="info">
                <span>Status</span>
                <div class="status overdue">
                    <img src="../images/popup-close-red.svg" alt="Close red">
                    <span><%= invoice.overdue_days %> Days Overdue</span>
                </div>
            </div>
        </li>
    <%
        }
        else {
    %>
        <li>
            <div class="icon">
                <img src="/images/popup-form-question.svg" alt="Form question icon">
            </div>
            <div class="info">
                <span>Status</span>
                <div class="status pending">
                    <img src="/images/question-mark-orange.svg" alt="Question mark orange">
                    <span>Pending</span>
                </div>
            </div>
        </li>
    <%
        }
    }
    %>
    <li>
        <div class="icon">
            <img src="/images/popup-preview-icon.svg" alt="Preview icon">
        </div>
        <div class="info">
            <span>Preview</span>
        </div>
        <div class="figur">
            <img src="/images/popup-not-listed.webp" alt="Not listed img">
        </div>
    </li>
</script>
@endsection
