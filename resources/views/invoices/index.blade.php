@extends('layouts.master')
@section('view_css')
<link rel="stylesheet" href="/js/jquery-confirm/jquery-confirm.min.css">
<link rel="stylesheet" href="/js/jquery-datetimepicker/jquery.datetimepicker.min.css">
<link rel="stylesheet" href="/js/select2/css/select2.min.css">
<link rel="stylesheet" href="/js/jquery-ui/jquery-ui.css">
@endsection
@section('content')
    @if($auth_user->role == 'sales')
        @include('admin.left_sidebar_admin_menu',['active_page' => 'invoices'])
    @else
        @include('dashboard.left_sidebar_full_menu',['active_page' => 'invoices'])
    @endif
    <div class="col-md-auto col-12 content-wrap invoice-main-content">
        <div class="content-inner">
            <div class="row align-items-center heading-row">
                <div class="col-auto">
                    <h2>Invoices</h2>
                </div>
                <div class="col-auto action-col ml-xl-auto d-flex align-items-center">
                    <a href="/invoices/create" class="btn btn-primary btn--round d-flex add-btn action-triger">
                        <img src="/images/plus-Icons.svg" alt="Pluse icon white" class="icon">
                        Add New
                    </a>
                    <ul class="nav nav-tabs action-triger">
                        <li class="nav-item">
                            <a href="#" class="nav-link type_of_invoice_filter {{ !$request['invoice_type'] ? 'active' : '' }}" data-type="" aria-current="all">All</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link type_of_invoice_filter {{ $request['invoice_type'] == 'overdue' ? 'active' : '' }}" data-type="overdue">Overdue</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link type_of_invoice_filter {{ $request['invoice_type'] == 'payed' ? 'active' : '' }}" data-type="payed">Payed</a>
                        </li>
                    </ul>
                    <button class="btn btn--round btn-outline d-flex action-triger filter-btn" id="filter-view">
                        <img src="/images/filter-icon.svg" alt="Filter icon" class="icon">
                        Filter
                    </button>
                </div>
            </div>
            <div class="statistical-boxes">
                <div class="row">
                    <div class="col-12 col-sm-6 col-lg-3 box-item">
                        <div class="statistical-boxe-item">
                            <div class="figure">
                                <img class="icon-green" src="/images/money-icon-green.svg" alt="Money icon green">
                                <img class="icon-red" src="/images/money-icon-red.svg" alt="Money icon Red">
                            </div>
                            <div class="info">
                                <span>Total Earned</span>
                                <h2>$ {{ number_format($invoice_totals['0']->total,2) }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3 box-item">
                        <div class="statistical-boxe-item">
                            <div class="figure">
                                <img class="icon-green" src="/images/money-icon-green.svg" alt="Money icon green">
                                <img class="icon-red" src="/images/money-icon-red.svg" alt="Money icon Red">
                            </div>
                            <div class="info">
                                <span>Total Paid</span>
                                <h2>$ {{ number_format($invoice_totals['1']->total,2) }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3 box-item">
                        <div class="statistical-boxe-item overdue">
                            <div class="figure">
                                <img class="icon-green" src="/images/money-icon-green.svg" alt="Money icon green">
                                <img class="icon-red" src="/images/money-icon-red.svg" alt="Money icon Red">
                            </div>
                            <div class="info">
                                <span>Outstanding Invoices</span>
                                <h2>$ {{ number_format($invoice_totals['2']->total,2) }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3 box-item">
                        <div class="statistical-boxe-item payment-type">
                            <div class="figure">
                                <!-- <div class="circle-progress" data-progress="75">
                                   <svg>
                                      <circle cx="22.5" cy="22.5" r="22.5" fill="none" stroke="#eff2f9" stroke-width="2">
                                      </circle>
                                      <circle cx="22.5" cy="22.5" r="22.5" fill="none" stroke="#43d14f" stroke-width="2">
                                      </circle>
                                   </svg>
                                   <h6>75<span>%</span></h6>
                                </div> -->
                                <img src="/images/money-icon-yellow.svg" alt="Money icon yellow">
                            </div>
                            <div class="info">
                                <span>Overdue Invoices</span>
                                <h2>$ {{ number_format($invoice_totals['3']->total,2) }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="invoice-chart">
                <canvas id="invoice_chart" height="240"></canvas>
            </div>
            <div class="invoice-chart">
                <img src="/images/invoice-chart.png" alt="Invoce chart" class="place-holder-image">
            </div>
            <div class="list-items-wrapper">
                <button class="btn btn-outline btn--round delete-btn action-triger ml-4" id="delete_items" style="display:none;">
                    <img src="/images/delete-red.svg" alt="Delete icon red" class="icon">
                    <span id="delete_btn_text"></span>
                </button>
                <div class="list-items">
                    <div class="row items-heading no-gutters">
                        <div class="col-auto number">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="check_all_items" autocomplete="off">
                                <label class="custom-control-label" for="check_all_items"></label>
                            </div>
                            <button class="btn sort-btn {{ in_array($request['sort_by'],['number_asc','number_desc']) ? ($request['sort_by'] == 'number_asc' ? 'sorted-asc' : 'sorted-desc') : '' }}">
                                <span>Number</span>
                                <a href="#" class="sort_by" data-type="{{ $request['sort_by'] == 'number_asc' ? 'number_desc' : 'number_asc' }}">
                                    <svg width="10" height="9" viewBox="0 0 10 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.3" d="M5.43301 8.25C5.24056 8.58333 4.75944 8.58333 4.56699 8.25L0.23686 0.75C0.0444095 0.416667 0.284973 1.69368e-08 0.669873 5.05859e-08L9.33013 8.07689e-07C9.71503 8.41338e-07 9.95559 0.416668 9.76314 0.750001L5.43301 8.25Z" fill="#86969E"/>
                                    </svg>
                                </a>
                            </button>
                        </div>
                        <div class="col-auto client">
                            <button class="btn sort-btn {{ in_array($request['sort_by'],['client_asc','client_desc']) ? ($request['sort_by'] == 'client_asc' ? 'sorted-asc' : 'sorted-desc') : '' }}">
                                <span>Client</span>
                                <a href="#" class="sort_by" data-type="{{ $request['sort_by'] == 'client_asc' ? 'client_desc' : 'client_asc' }}">
                                    <svg width="10" height="9" viewBox="0 0 10 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.3" d="M5.43301 8.25C5.24056 8.58333 4.75944 8.58333 4.56699 8.25L0.23686 0.75C0.0444095 0.416667 0.284973 1.69368e-08 0.669873 5.05859e-08L9.33013 8.07689e-07C9.71503 8.41338e-07 9.95559 0.416668 9.76314 0.750001L5.43301 8.25Z" fill="#86969E"/>
                                    </svg>
                                </a>
                            </button>
                        </div>
                        <div class="col-auto raised-on">
                            <button class="btn sort-btn {{ in_array($request['sort_by'],['raised_on_asc','raised_on_desc']) ? ($request['sort_by'] == 'raised_on_asc' ? 'sorted-asc' : 'sorted-desc') : '' }}">
                                <span>Raised on</span>
                                <a href="#" class="sort_by" data-type="{{ $request['sort_by'] == 'raised_on_asc' ? 'raised_on_desc' : 'raised_on_asc' }}">
                                    <svg width="10" height="9" viewBox="0 0 10 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.3" d="M5.43301 8.25C5.24056 8.58333 4.75944 8.58333 4.56699 8.25L0.23686 0.75C0.0444095 0.416667 0.284973 1.69368e-08 0.669873 5.05859e-08L9.33013 8.07689e-07C9.71503 8.41338e-07 9.95559 0.416668 9.76314 0.750001L5.43301 8.25Z" fill="#86969E"/>
                                    </svg>
                                </a>
                            </button>
                        </div>
                        <div class="col-auto payed-on">
                            <button class="btn sort-btn {{ in_array($request['sort_by'],['payed_on_asc','payed_on_desc']) ? ($request['sort_by'] == 'payed_on_asc' ? 'sorted-asc' : 'sorted-desc') : '' }}">
                                <span>Payed on</span>
                                <a href="#" class="sort_by" data-type="{{ $request['sort_by'] == 'payed_on_asc' ? 'payed_on_desc' : 'payed_on_asc' }}">
                                    <svg width="10" height="9" viewBox="0 0 10 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.3" d="M5.43301 8.25C5.24056 8.58333 4.75944 8.58333 4.56699 8.25L0.23686 0.75C0.0444095 0.416667 0.284973 1.69368e-08 0.669873 5.05859e-08L9.33013 8.07689e-07C9.71503 8.41338e-07 9.95559 0.416668 9.76314 0.750001L5.43301 8.25Z" fill="#86969E"/>
                                    </svg>
                                </a>
                            </button>
                        </div>
                        <div class="col-auto amount">
                            <button class="btn sort-btn {{ in_array($request['sort_by'],['amount_asc','amount_desc']) ? ($request['sort_by'] == 'amount_asc' ? 'sorted-asc' : 'sorted-desc') : '' }}">
                                <span>Amount</span>
                                <a href="#" class="sort_by" data-type="{{ $request['sort_by'] == 'amount_asc' ? 'amount_desc' : 'amount_asc' }}">
                                    <svg width="10" height="9" viewBox="0 0 10 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.3" d="M5.43301 8.25C5.24056 8.58333 4.75944 8.58333 4.56699 8.25L0.23686 0.75C0.0444095 0.416667 0.284973 1.69368e-08 0.669873 5.05859e-08L9.33013 8.07689e-07C9.71503 8.41338e-07 9.95559 0.416668 9.76314 0.750001L5.43301 8.25Z" fill="#86969E"/>
                                    </svg>
                                </a>
                            </button>
                        </div>
                        <div class="col-auto quick-actions">
                            <button class="btn sort-btn">
                                <span>Quick Actions</span>
                                <svg width="10" height="9" viewBox="0 0 10 9" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.3" d="M5.43301 8.25C5.24056 8.58333 4.75944 8.58333 4.56699 8.25L0.23686 0.75C0.0444095 0.416667 0.284973 1.69368e-08 0.669873 5.05859e-08L9.33013 8.07689e-07C9.71503 8.41338e-07 9.95559 0.416668 9.76314 0.750001L5.43301 8.25Z" fill="#86969E" /></svg>
                            </button>
                        </div>
                        <div class="col-auto more">
                            <span>More</span>
                        </div>
                    </div>
                    @foreach($invoices as $item)
                        <div class="row items-body no-gutters invoice_row_item" data-id="{{ $item->invoice_id }}">
                            <div class="col-auto lead-name">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input check_item" name="invoice_{{ $item->invoice_id }}" id="invoice_{{ $item->invoice_id }}" autocomplete="off">
                                    <label class="custom-control-label" for="invoice_{{ $item->invoice_id }}"></label>
                                </div>
                                <a href="/invoices/{{ $item->invoice_id }}/edit">{{ $item->invoice_number_label }}</a>
                            </div>
                            <div class="col-auto client">
                                <span>
                                    @if($item->Client)
                                        <a href="/client/{{ $item->client_id }}" class="link-default-color">
                                            {{ $item->Client->name }}
                                        </a>
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </div>
                            <div class="col-auto raised-on">
                                <span>{{ $item->created_at ? $item->created_at->format('M j, Y') : '' }}</span>
                            </div>
                            <div class="col-auto payed-on">
                                <span class="invoice_paid_container">
                                    @if($item->has_paid)
                                        {{ $item->paid_date ? \Carbon\Carbon::createFromFormat('Y-m-d',$item->paid_date)->format('M j, Y') : 'Paid' }}
                                    @else
                                        Pending
                                    @endif
                                </span>
                            </div>
                            <div class="col-auto amount">
                                <span>{{ number_format($item->total_gross_amount,2) }} USD</span>
                            </div>
                            <div class="col-auto quick-actions">
                                <a href="/invoices/{{ $item->invoice_id }}/duplicate" class="btn btn-duplicate">
                                    <img src="/images/duplicate-icon.svg" alt="Duplicate">
                                </a>
                                <a href="/invoice/{{ $item->invoice_unique_number }}" class="btn btn-print print_invoice">
                                    <img src="/images/print-icon.png" alt="Print">
                                </a>
                            </div>
                            <div class="col-auto more">
                                @if($item->Client)
                                    <div class="dropdown-wrap">
                                        <button type="button" class="btn more-btn dropdown-toggle" data-toggle="dropdown">
                                            <span></span>
                                        </button>
                                        <ul class="dropdown-items dropdown-menu">
                                            <li data-label="call">
                                                <a class="dropdown-item" href="/client/{{ $item->client_id }}/pre/call">Call</a>
                                            </li>
                                            <li data-label="send-invoice-via-text">
                                                <a class="dropdown-item" href="#">Send Invoice via Text</a>
                                            </li>
                                            <li data-label="send-invoice-via-email">
                                                <a class="dropdown-item" href="#">Send Invoice via Email</a>
                                            </li>
                                            @if(!$item->has_paid)
                                                <li data-label="payment-received">
                                                    <a href="/invoices/{{ $item->invoice_id }}/payment-received" class="dropdown-item">Payment Received</a>
                                                </li>
                                            @endif
                                            <li data-label="client-profile">
                                                <a class="dropdown-item" href="/client/{{ $item->client_id }}">View Profile</a>
                                            </li>
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    <div class="row pagination-row no-gutters">
                        <div class="page-count col-auto">
                            Show {{ $invoices->currentPage() }} of {{ $invoices->lastPage() }} pages
                        </div>
                        @include('elements.pagination',['paginator' => $invoices])
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="filter-modal modal-wrapper" id="filter_modal">
        <div class="modal-inner card">
            <div class="card-header d-flex align-items-center">
                <div>
                    <h5>Filter Invoices</h5>
                    <button id="clear_all_filters" class="btn clear-filter-btn">Clear All Filters</button>
                </div>
                <button class="btn close-modal ml-auto" data-dismiss="modal" aria-label="Close">
                    <img src="/images/Close.svg" alt="close icon">
                </button>
            </div>
            <div class="modal-body ui-front">
                {!! Form::open(['url' => 'invoices', 'method' => 'GET', 'id' => 'filter_form', 'autocomplete' => 'off']) !!}
                    <div class="form-wrap">
                        <h6>Client’s Stage</h6>
                        <div class="form-group custom-select-group dropdown-wrap">
                            {!! Form::select('client_stage',$client_statuses,$request['status'],['id' => 'client_stage', 'placeholder' => 'All']) !!}
                            {!! Form::label('client_stage','Client’s Stage') !!}
                        </div>
                        <h6>Client’s name</h6>
                        <div class="form-group search-wrapper d-flex align-items-center">
                            {!! Form::text('client_search',null,['id' => 'client_search', 'class' => 'form-control', 'placeholder' => 'Start for Names']) !!}
                            {!! Form::label('client_search','Start for Names') !!}
                        </div>
                        <h6>Type of Client</h6>
                        <div class="form-group">
                            <ul class="nav nav-tabs action-triger">
                                <li class="nav-item">
                                    <a class="nav-link type_of_client_filter {{ !$request['filter_type'] ? 'active' : '' }}" data-type="" aria-current="all" href="#">All</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link type_of_client_filter {{ $request['filter_type'] == 'individual' ? 'active' : '' }}" data-type="individual" href="#">Individual</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link type_of_client_filter {{ $request['filter_type'] == 'company' ? 'active' : '' }}" data-type="company" href="#">Company</a>
                                </li>
                            </ul>
                        </div>
                        <h6>Amount</h6>
                        <div class="form-group-row form-row">
                            <div class="form-group col-12 col-sm-6">
                                {!! Form::text('amount_from',null,['class' => 'form-control', 'id' => 'amount_from', 'placeholder' => 'Form']) !!}
                                {!! Form::label('amount_from','From') !!}
                                <div class="currency">
                                    <span>USD</span>
                                </div>
                            </div>
                            <div class="form-group col-12 col-sm-6">
                                {!! Form::text('amount_to',null,['class' => 'form-control', 'id' => 'amount_to', 'placeholder' => 'To']) !!}
                                {!! Form::label('amount_to','From') !!}
                                <div class="currency">
                                    <span>USD</span>
                                </div>
                            </div>
                        </div>
                        <h6>Raised on</h6>
                        <div class="form-group-row form-row">
                            <div class="form-group date-calendar col-12 col-sm-6">
                                {!! Form::text('raised_start_date',null,['class' => 'form-control', 'id' => 'raised_start_date']) !!}
                                {!! Form::label('raised_start_date','Date from') !!}
                            </div>
                            <div class="form-group date-calendar col-12 col-sm-6">
                                {!! Form::text('raised_end_date',null,['class' => 'form-control', 'id' => 'raised_end_date']) !!}
                                {!! Form::label('raised_end_date','Date to') !!}
                            </div>
                        </div>
                    </div>
                    {!! Form::hidden('client_type',null,['id' => 'client_type']) !!}
                    {!! Form::hidden('filter_type',null,['id' => 'filter_type']) !!}
                    {!! Form::hidden('invoice_type',null,['id' => 'invoice_type']) !!}
                    {!! Form::hidden('client_id',null,['id' => 'client_id']) !!}
                    {!! Form::hidden('sort_by',null,['id' => 'sort_by']) !!}
                {!! Form::close() !!}
            </div>
            <div class="card-footer">
                <div class="btn-row">
                    <button class="btn btn--sqr btn-secondary" data-dismiss="modal" aria-label="Close">Cancel</button>
                    <button type="submit" class="btn btn--sqr btn-primary" id="filter_invoice_process">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('view_script')
<script type="text/javascript" src="/js/jquery-confirm/jquery-confirm.min.js"></script>
<script type="text/javascript" src="/js/chart-js/chart.min.js"></script>
<script type="text/javascript" src="/js/jquery-datetimepicker/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript" src="/js/select2/js/select2.min.js"></script>
<script type="text/javascript" src="/js/jquery.inputmask.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#amount_from').inputmask('decimal',{
            rightAlign: false,
            placeholder: ''
        });

        $('#amount_to').inputmask('decimal',{
            rightAlign: false,
            placeholder: ''
        });

        $('#raised_start_date').datetimepicker({
            timepicker:false,
            format: 'D, j F, Y',
            lang:'en',
            validateOnBlur: false,
            closeOnTimeSelect: true,
            scrollMonth : false,
            scrollInput : false
        });

        $('#raised_end_date').datetimepicker({
            timepicker:false,
            format: 'D, j F, Y',
            lang:'en',
            validateOnBlur: false,
            closeOnTimeSelect: true,
            scrollMonth : false,
            scrollInput : false
        });

        $('#client_search').autocomplete({
            autoFill: true,
            source: function( request, response ) {
                $.ajax( {
                    url: '/invoices/client/search',
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
            // select: function(event, ui) {
            //     $('#dial_number_phone').val(ui.item.phone);
            //     handle_dial_clear_btn();
            //     return false;
            // }
        }).bind('focus',function(){ $(this).autocomplete("search"); } );

        $('#client_stage').select2({
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

        $(document).on('click','.type_of_invoice_filter',function(){
            $('.type_of_invoice_filter').not($(this)).removeClass('active');
            $(this).addClass('active');
            $('#invoice_type').val($(this).attr('data-type'));
            $('#filter_form').submit();
            return false;
        });

        $(document).on('click','.type_of_client_filter',function(){
            $('.type_of_client_filter').not($(this)).removeClass('active');
            $(this).addClass('active');
            $('#filter_type').val($(this).attr('data-type'));
            return false;
        });

        $(document).on('click','#filter-view',function(){
            $('#filter_modal').modal('show');
            return false;
        });

        $(document).on('click','#filter_invoice_process',function(){
            $('#filter_form').submit();
            return false;
        });

        $(document).on('click','#clear_all_filters',function(){
            $('#filter_form')['0'].reset();
            $('#client_stage').trigger('change');
            $('.type_of_client_filter:first').trigger('click');
            return false;
        });

        $(document).on('change','#check_all_items',function(){
            if ($(this).prop('checked')) {
                $('.check_item').prop('checked',true);
                var total_delete_items = $('.check_item').filter(':checked').length;
                if (total_delete_items) {
                    $('#delete_btn_text').text('Delete (' + total_delete_items + ')');
                    $('#delete_items').removeClass('delete-btn').addClass('d-flex').show();
                }
            }
            else{
                $('.check_item').prop('checked',false);
                $('#delete_items').addClass('delete-btn').removeClass('d-flex').hide();
            }
            return false;
        });

        $(document).on('change','.check_item',function(){
            if (!$(this).prop('checked')) {
                $('#check_all_items').prop('checked',false);
            }
            var total_delete_items = $('.check_item').filter(':checked').length;
            if (total_delete_items) {
                $('#delete_btn_text').text('Delete (' + total_delete_items + ')');
                $('#delete_items').removeClass('delete-btn').addClass('d-flex').show();;
            }
            else{
                $('#delete_items').addClass('delete-btn').removeClass('d-flex').hide();
            }
            return false;
        });

        $(document).on('click','#delete_items',function(){
            var total_delete_items = $('.check_item').filter(':checked').length;
            $.confirm({
                title: 'Confirmation required',
                content: 'Are you sure you want to delete selected invoice' + (total_delete_items > 1 ? 's' : ''),
                buttons: {
                    confirm: function () {
                        var invoice_ids = [];
                        $('.check_item').filter(':checked').each(function(){
                            invoice_ids.push($(this).closest('.invoice_row_item').attr('data-id'))
                        });

                        $.post('/invoices/delete',{ invoice_ids: invoice_ids },function(data){
                            location.reload();
                        },'json');
                    },
                    cancel: function () {

                    }
                }
            });
            return false;
        });

        var chart_data = [];
        var chart_colors = [];
        @foreach($chart_data as $item)
            chart_data.push('{{ $item->total }}');
            chart_colors.push('rgba(67, 209, 79, 1)');
        @endforeach

        var ctx = document.getElementById('invoice_chart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['{{ $previous_year }}', '01', '02', '03', '04', '05','06', '07', '08', '09', '10', '11','12', '{{ $next_year }}'],
                datasets: [{
                    borderRadius: 8,
                    barThickness: 28,
                    label: 'Earned',
                    data: chart_data,
                    backgroundColor: chart_colors,
                    borderColor: chart_colors,
                    borderWidth: 1
                }]
            },
            options: {
                plugins:{
                    legend: {
                        display: false
                    },
                },
                scales: {
                    x: {
                        grid:{
                            display:false
                        }
                    },
                    y: {
                        display:false,
                    },
                },
                scaleShowLabels: false,
                maintainAspectRatio: false,
            }
        });

        $(document).on('click','.sort_by',function(){
            $('#sort_by').val($(this).attr('data-type'));
            $('#filter_form').submit();
            return false;
        });

        $(document).on('click','.pagination a',function(){
            var page_ref = $(this).attr('href');
            $('#page').val(page_ref.split('page=')['1']);
            $('#filter_form').trigger('submit');
            return false;
        });


        // const data = {
        //     labels: ['1','2','3','4','5','6','7'],
        //     datasets: [{
        //         label: 'My First Dataset',
        //         data: [65, 59, 80, 81, 56, 55, 40],
        //         backgroundColor: [
        //             'rgba(255, 99, 132, 0.2)',
        //             'rgba(255, 159, 64, 0.2)',
        //             'rgba(255, 205, 86, 0.2)',
        //             'rgba(75, 192, 192, 0.2)',
        //             'rgba(54, 162, 235, 0.2)',
        //             'rgba(153, 102, 255, 0.2)',
        //             'rgba(201, 203, 207, 0.2)'
        //         ],
        //         borderColor: [
        //             'rgb(255, 99, 132)',
        //             'rgb(255, 159, 64)',
        //             'rgb(255, 205, 86)',
        //             'rgb(75, 192, 192)',
        //             'rgb(54, 162, 235)',
        //             'rgb(153, 102, 255)',
        //             'rgb(201, 203, 207)'
        //         ],
        //         borderWidth: 1
        //     }]
        // };
        //
        // var ctx = document.getElementById('myChart').getContext('2d');
        // var myChart = new Chart(ctx, data);
    });
</script>
@endsection
