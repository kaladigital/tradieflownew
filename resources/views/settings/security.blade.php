@extends('layouts.master')
@section('content')
    @if($auth_user->role == 'sales')
        @include('admin.left_sidebar_admin_menu',['active_page' => 'settings'])
    @else
        @include('dashboard.left_sidebar_menu',['active_page' => 'settings'])
    @endif
    <div class="col-11">
        <div class="content">
            <div class="row">
                <div class="col-12">
                    <h2>Settings</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <section class="widget widget-settings">
                        <div class="row">
                            <div class="col-md-2">
                                @include('settings.settings_menu',['active_page' => 'security'])
                            </div>
                            <div class="col-md-10">
                                <div class="tab-content settings-content" id="v-pills-tabContent">
                                    <div class="tab-pane fade active show" id="v-pills-security" role="tabpanel" aria-labelledby="v-pills-security-tab">
                                        <div class="wrap-content">
                                            {!! Form::open(['url' => 'settings/security', 'method' => 'patch', 'class' => 'data-form password-form needs-validation', 'autocomplete' => 'off', 'id' => 'password_change_form']) !!}
                                                <div class="main-content">
                                                    @include('elements.alerts')
                                                    <h3>Security</h3>
                                                    <h4>Change Password</h4>
                                                    <p>You can set up the different users and their permissions to the system.</p>
                                                    <div class="form-row">
                                                        <div class="col-md-12">
                                                            <div class="form-group no-label">
                                                                {!! Form::password('current_password',['class' => 'form-control', 'placeholder' => 'Current Password']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="col-md-12">
                                                            <div class="form-group no-label">
                                                                {!! Form::password('new_password',['class' => 'form-control', 'placeholder' => 'New Password', 'id' => 'new_password']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="col-md-12">
                                                            <div class="form-group no-label">
                                                                {!! Form::password('new_password_confirm',['class' => 'form-control', 'placeholder' => 'New Password', 'id' => 'new_password_confirm']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bottom-control">
                                                    <a href="/" class="btn btn-medium btn-default btn-transparent">Skip</a>
                                                    <button type="submit" class="btn btn-medium btn-default">Continue</button>
                                                </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('view_script')
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#password_change_form').validate({
            rules : {
                current_password: {
                    required: true,
                },
                new_password: {
                    required: true
                },
                new_password_confirm: {
                    required: true,
                    equalTo: '#new_password'
                },
            },
            messages: {
                current_password: {
                    required : 'Please specify current password'
                },
                new_password: {
                    required : 'Please specify new password'
                },
                new_password_confirm: {
                    required : 'Please confirm new password',
                    equalTo: 'Password\'s don\'t match'
                }
            },
        });
    });
</script>
@endsection
