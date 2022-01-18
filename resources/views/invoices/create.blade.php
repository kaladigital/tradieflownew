@extends('layouts.master')
@section('view_css')
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
    <div class="col-md-auto col-12 content-wrap add-new-invoice">
        <div class="content-inner">
            @include('elements.alerts')
            <div class="row align-items-center heading-row">
                <div class="col-auto">
                    <h2>Raise an Invoice</h2>
                </div>
                <div class="col-auto action-col ml-sm-auto d-flex align-items-center">
                    <ul class="nav nav-tabs action-triger">
                        <li class="nav-item active">
                            <a class="nav-link" href="./add-new-invoice.html">Filling</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./invoices-preview.html">Preview</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="content-body-row">
                {!! Form::model($invoice, ['url' => 'invoices', 'autocomplete' => 'off', 'id' => 'invoice_form']) !!}
                    @include('invoices._form')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
