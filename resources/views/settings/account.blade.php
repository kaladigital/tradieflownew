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
                    @include('settings.settings_menu',['active_page' => 'account', 'user_onboaridng' => $user_onboarding])
                    <div class="col-md-auto col-12 contents">
                        {!! Form::model($auth_user,['action' => ['SettingsController@updateAccount'], 'method' => 'patch', 'autocomplete' => 'off', 'id' => 'account_form']) !!}
                        <div class="content-body account-info">
                            <h3>Account</h3>
                            <div class="visual-section">
                                <div class="inner-container d-flex align-items-center">
                                    <div class="note-wrap order-md-2 order-lg-1 d-flex">
                                        <div class="icon">
                                            <img src="/images/info-icon.svg" alt="Info icon">
                                        </div>
                                        <p class="info">
                                            Welcome to Tradieflow, the easiest, way to manage your trade, contractor or home improvement business!
                                        </p>
                                    </div>
                                    <div class="graphics-figure ml-auto order-md-1 order-lg-2">
                                        <img src="/images/account-visual-figure.svg" alt="Account visual figure">
                                    </div>
                                </div>
                            </div>
                            <h6>Personal Information</h6>
                            <p>This information will be displayed for your clients.</p>
                            <div class="inner-container">
                                <div class="form-wrap">
                                    @include('elements.alerts')
                                    <div class="form-group-row form-row">
                                        <div class="form-group col-12 col-lg-6">
                                            {!! Form::text('name',null,['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Full Name']) !!}
                                            {!! Form::label('name','Full Name') !!}
                                        </div>
                                        <div class="form-group col-12 col-lg-6">
                                            {!! Form::text('email',null,['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Email']) !!}
                                            {!! Form::label('email','Email') !!}
                                        </div>
                                    </div>
                                    <div class="form-group-row form-row">
                                        <div class="form-group select-group col-12 col-lg-6">
                                            {!! Form::select('country_id',$countries,null,['class' => 'form-control', 'required' => 'required', 'id' => 'country_id']) !!}
                                            {!! Form::label('country_id','Country') !!}
                                        </div>
                                        <div class="form-group col-12 col-lg-6">
                                            {!! Form::text('zip_code',null,['class' => 'form-control', 'placeholder' => 'Area/ZIP Code']) !!}
                                            {!! Form::label('zip_code','Zip') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="action-row">
                            @if($user_onboarding->status == 'pending')
                                <a href="/settings/skip/account" class="btn btn--round btn-secondary">Skip</a>
                                <button type="submit" class="btn btn--round btn-primary">Continue</button>
                            @else
                                <button type="submit" class="btn btn--round btn-primary">Save</button>
                            @endif
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
