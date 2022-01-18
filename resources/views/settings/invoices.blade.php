@extends('layouts.master')
@section('view_css')
    <link rel="stylesheet" href="/js/select2/css/select2.min.css">
@endsection
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
                    @include('settings.settings_menu',['active_page' => 'invoices', 'user_onboarding' => $user_onboarding])
                    <div class="col-md-auto col-12 contents">
                        @include('elements.alerts')
                        {{ Form::model($user_invoice_setting,['action' => ['SettingsController@updateInvoice'],'method' => 'patch', 'autocomplete' => 'off']) }}
                            <div class="content-body account-info">
                                <h3>Invoices</h3>
                                <div class="visual-section">
                                    <div class="inner-container d-flex align-items-center">
                                        <div class="note-wrap order-md-2 order-lg-1 d-flex">
                                            <div class="icon">
                                                <img src="/images/info-icon.svg" alt="Info icon">
                                            </div>
                                            <p class="info">
                                                You can send quotes and invoices directly from Tradieflow! This information below will be displayed on invoices to your customers.
                                            </p>
                                        </div>
                                        <div class="graphics-figure ml-auto order-md-1 order-lg-2">
                                            <img src="/images/invoices-visual-figure.svg" alt="Invoices visual figure">
                                        </div>
                                    </div>
                                </div>
                                <h6>Business Information</h6>
                                <p>Please fill our the form according to your business data. We will raise your invoices based on the given data below.</p>
                                <div class="inner-container">
                                    <div class="form-group-row form-row">
                                        <div class="form-group required col-12 col-lg-6">
                                            {{ Form::text('company_name', null, ['id' => 'company_name', 'class' => 'form-control', 'required' => 'required', 'placeholder' => 'Company Name']) }}
                                            {{ Form::label('company_name', 'Company Name') }}
                                        </div>
                                        <div class="form-group col-12 col-lg-6">
                                            {{ Form::email('email', null, ['id' => 'email', 'class' => 'form-control', 'placeholder' => 'Email']) }}
                                            {{ Form::label('email', 'Email') }}
                                        </div>
                                    </div>
                                    <div class="form-group-row form-row">
                                        <div class="form-group required select-group col-12 col-lg-6">
                                            {{ Form::select('country_id', $countries, null, ['placeholder' => 'Select', 'id' => 'country_id', 'class' => 'form-control', 'required' => 'required']) }}
                                            {{ Form::label('country_id', 'Country') }}
                                        </div>
                                        <div class="form-group required col-12 col-lg-6">
                                            {{ Form::text('zip_code', null, ['id' => 'zip_code', 'class' => 'form-control', 'required' => 'required', 'placeholder' => 'Area/ZIP Code']) }}
                                            {{ Form::label('zip_code', 'Area/ZIP Code') }}
                                        </div>
                                    </div>
                                    <div class="form-group-row form-row">
                                        <div class="form-group required col-12 col-lg-6">
                                            {{ Form::text('city', null, ['id' => 'city', 'class' => 'form-control', 'required' => 'required', 'placeholder' => 'City']) }}
                                            {{ Form::label('city', 'City') }}
                                        </div>
                                        <div class="form-group required col-12 col-lg-6">
                                            {{ Form::text('state', null, ['id' => 'state', 'class' => 'form-control', 'required' => 'required', 'placeholder' => 'State']) }}
                                            {{ Form::label('state', 'State') }}
                                        </div>
                                    </div>
                                    <div class="form-group required mb-0">
                                        {{ Form::text('address', null, ['id' => 'address', 'class' => 'form-control', 'required' => 'required', 'placeholder' => 'Address Line']) }}
                                        {{ Form::label('address', 'Address Line') }}
                                    </div>
                                    <div class="row accounting-info-row">
                                        <div class="col-12">
                                            <h6>Accounting</h6>
                                            <p>We will need to display your accounting data on your invoices. Please fill out the below form!</p>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group-row form-row">
                                                <div class="form-group col-12 col-lg-6">
                                                    {{ Form::text('gst_vat', null, ['id' => 'gst_vat', 'class' => 'form-control', 'placeholder' => 'VAT ID']) }}
                                                    {{ Form::label('gst_vat', 'GST Number/VAT ID') }}
                                                </div>
                                                <div class="form-group col-12 col-lg-6">
                                                    {{ Form::text('company_registration_number', null, ['id' => 'company_registration_number', 'class' => 'form-control', 'placeholder' => 'Company Registration Number']) }}
                                                    {{ Form::label('company_registration_number', 'ABN/Company Registration Number') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row banking-info-row">
                                        <div class="col-12">
                                            <h6>Banking</h6>
                                            <p>We will need to display your banking information on your invoices. Please fill out the below form!</p>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group-row form-row">
                                                <div class="form-group required col-12 col-lg-6">
                                                    {{ Form::text('bank_account_holder_name', null, ['id' => 'bank_account_holder_name', 'class' => 'form-control', 'placeholder' => 'Account Holder Name', 'required' => 'required']) }}
                                                    {{ Form::label('bank_account_holder_name', 'Account Holder Name') }}
                                                </div>
                                                <div class="form-group required col-12 col-lg-6">
                                                    {{ Form::select('bank_account_holder_type', $account_holder_types, null, ['id' => 'bank_account_holder_type', 'class' => 'form-control', 'required' => 'required']) }}
                                                    {{ Form::label('bank_account_holder_type', 'Account Holder Type') }}
                                                </div>
                                            </div>
                                            <div class="form-group-row form-row">
                                                <div class="form-group required col-12 col-lg-6">
                                                    {{ Form::select('bank_account_country_id', $countries, null, ['placeholder' => 'Select', 'id' => 'bank_account_country_id', 'class' => 'form-control', 'required' => 'required']) }}
                                                    {{ Form::label('bank_account_country_id', 'Country') }}
                                                </div>
                                                <div class="form-group required col-12 col-lg-6">
                                                    {{ Form::select('bank_account_currency', $currencies, null, ['id' => 'bank_account_currency', 'class' => 'form-control', 'required' => 'required', 'placeholder' => 'Currency']) }}
                                                    {{ Form::label('bank_account_currency', 'Currency') }}
                                                </div>
                                            </div>
                                            <div class="form-group required">
                                                {{ Form::text('bank_account_number', null, ['id' => 'bank_account_number', 'class' => 'form-control', 'required' => 'required', 'placeholder' => 'Bank Account Number']) }}
                                                {{ Form::label('bank_account_number', 'Bank Account Number') }}
                                            </div>
                                            <div class="form-group required" id="iban_container" style="display:none;">
                                                {{ Form::text('bank_account_iban', null, ['id' => 'bank_account_iban', 'class' => 'form-control', 'placeholder' => 'IBAN']) }}
                                                {{ Form::label('bank_account_iban', 'IBAN') }}
                                            </div>
                                            <div class="form-group" id="routing_container" style="display:none;">
                                                {{ Form::text('bank_account_routing_swift', null, ['id' => 'bank_account_routing_swift', 'class' => 'form-control', 'placeholder' => 'Routing Number/SWIFT']) }}
                                                {{ Form::label('bank_account_routing_swift', 'Routing Number/SWIFT') }}
                                            </div>
                                            <div class="form-group" id="bsb_code_container" style="display:none;" style="display:none;">
                                                {{ Form::text('bank_bsb_code', null, ['id' => 'bank_bsb_code', 'class' => 'form-control', 'placeholder' => 'BCB Code']) }}
                                                {{ Form::label('bank_bsb_code', 'BCB Code') }}

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="action-row">
                                @if($user_onboarding->status == 'pending')
                                    <a href="/settings/skip/invoices" class="btn btn--round btn-secondary">Skip</a>
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
@section('view_script')
<script type="text/javascript" src="/js/select2/js/select2.min.js"></script>
<script type="text/javascript" src="/js/jquery.inputmask.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#bank_bsb_code').inputmask('999999',{ clearIncomplete: true });
        $('#country_id,#bank_account_country_id').select2({
            width: '100%',
        });

        $(document).on('change','#bank_account_country_id',function(){
            var country = $.trim($('#bank_account_country_id option:selected').text());
            console.log(country);
            switch (country) {
                case 'USA':
                    $('#routing_container').addClass('required');
                    $('#bank_account_routing_swift').attr('required','required');
                    $('#bank_bsb_code').removeAttr('required');
                    $('#iban_container').hide(function(){
                        $('#bank_account_iban').removeAttr('required');
                    });
                break;
                case 'Australia':
                    $('#bsb_code_container').addClass('required').show();
                    $('#bank_bsb_code').attr('required','required');
                    $('#routing_container').removeClass('required');
                    $('#bank_account_routing_swift').removeAttr('required');
                    $('#iban_container').hide(function(){
                        $('#bank_account_iban').removeAttr('required');
                    });

                    $('#routing_container').hide(function(){
                        $('#bank_account_routing_swift').removeAttr('required');
                    });
                break;
                default:
                    $('#bank_bsb_code').removeAttr('required');
                    $('#bsb_code_container').hide().removeClass('required');
                    $('#routing_container').removeClass('required');
                    $('#bank_account_routing_swift').removeAttr('required');
                    $('#iban_container').show(function(){
                        $('#bank_account_iban').attr('required','required');
                    });

                    $('#routing_container').hide(function(){
                        $('#bank_account_routing_swift').removeAttr('required');
                    });
                break;
            }
            return false;
        });

        $('#bank_account_holder_type,#bank_account_currency').select2({
            width: '100%',
            minimumResultsForSearch: -1,
        });

        $('#bank_account_country_id').trigger('change');
    });
</script>
@endsection
