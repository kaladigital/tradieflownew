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
                @include('settings.settings_menu',['active_page' => 'integrations', 'user_onboaridng' => $user_onboarding])
                <div class="col-md-auto col-12 contents">
                    {!! Form::model($auth_user,['url' => 'settings/integrations', 'autocomplete' => 'off']) !!}
                        <div class="content-body account-integrations">
                            <h3>Integrations</h3>
                            <div class="visual-section">
                                <div class="inner-container d-flex align-items-center">
                                    <div class="note-wrap order-md-2 order-lg-1 d-flex">
                                        <div class="icon">
                                            <img src="/images/info-icon.svg" alt="Info icon">
                                        </div>
                                        <p class="info">
                                            Connect your existing software, to turn Tradieflow into your very own business
                                        </p>
                                    </div>
                                    <div class="graphics-figure ml-auto order-md-1 order-lg-2">
                                        <img src="/images/integrations-visual-figure.svg" alt="Integrations visual figure">
                                    </div>
                                </div>
                            </div>
                            <div class="row account-invoicing">
                                <div class="col-12">
                                    <h6>Accounting/Invoicing</h6>
                                    <p class="smile-icon">We can communicate with your favorite accounting software, so we can automatize invoicing and the changes on the statuses of your payments.</p>
                                    <div class="added-items">
                                        <div class="added-items-inner">
                                            <div class="item-row row items-heading no-gutters">
                                                <div class="col-auto info-col">
                                                    &nbsp;
                                                </div>
                                                <div class="col-auto ml-auto edit-col actions-col"></div>
                                                <div class="col-auto remove-col ml-0 actions-col">
                                                    <h6 class="remove_btn" style="{{ $user_xero_account ? '' : 'display:none;' }}">Remove?</h6>
                                                </div>
                                                <div class="col-auto emails-col switch-col actions-col">
                                                    <h6>Allow</h6>
                                                </div>
                                            </div>
                                            <div class="item-row row no-gutters">
                                                <div class="col-auto info-col">
                                                    <img src="/images/xero.jpg" alt="Xero icon" class="icon">
                                                    <h6>Xero</h6>
                                                </div>
                                                <div class="col-auto edit-col switch-col ml-auto"></div>
                                                <div class="col-auto remove-col ml-0">
                                                    <a class="btn remove_btn" href="/settings/remove/xero" style="{{ $user_xero_account ? '' : 'display:none;' }}">
                                                        <img src="/images/delete-icon.svg" alt="Delete icon">
                                                    </a>
                                                </div>
                                                <div class="col-auto allow-col switch-col">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input" id="check_xero_account" autocomplete="off" {{ $user_xero_account && $user_xero_account->active ? 'checked="checked"' : '' }}>
                                                        <label class="custom-control-label" for="check_xero_account"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="action-row">
                            @if($user_onboarding->status == 'pending')
                                <a href="/settings/skip/integrations" class="btn btn--round btn-secondary">Skip</a>
                                <a href="/settings/skip/integrations" class="btn btn--round btn-primary">Continue</a>
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
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('change','#check_xero_account',function(){
            $.post('/settings/xero/check',{ checked: $(this).prop('checked') ? 1 : 0 },function(data){
                if (data.redirect) {
                    location.href = '/settings/xero/connect';
                }
                else{
                    $('.remove_btn').show();
                }
            });
            return false;
        });
    });
</script>
@endsection
