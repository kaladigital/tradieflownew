@extends('layouts.master')
@section('content')
    @include('admin.left_sidebar_admin_menu',['active_page' => 'generate'])
    <div class="col-md-auto col-12 content-wrap">
        <div class="content-inner">
            <div>
                <h2 class="page-title">Twilio Customer</h2>
                <div class="content-widget row no-gutters">
                    <div class="col-md-auto col-12 contents">
                        {!! Form::open(['action' => ['AdminController@generate'], 'method' => 'patch', 'autocomplete' => 'off', 'id' => 'lead_form']) !!}
                        <div class="content-body account-info">
                            <h3>Generate Account</h3>
                            <h6>Personal Information</h6>
                            <p>This information will be displayed for your clients.</p>
                            <div class="inner-container">
                                <div class="form-wrap">
                                    @include('elements.alerts')
                                    <div class="form-group-row form-row">
                                        <div class="form-group col-12 col-lg-6">
                                            {!! Form::text('name',null,['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Full Name', 'id' => 'name']) !!}
                                            {!! Form::label('name','Full Name') !!}
                                        </div>
                                    </div>
                                    <div class="form-group-row form-row">
                                        <div class="form-group col-12 col-lg-6">
                                            {!! Form::text('email',null,['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Email', 'id' => 'email']) !!}
                                            {!! Form::label('email','Email') !!}
                                        </div>
                                    </div>
                                    <div class="form-group-row form-row">
                                        <div class="form-group col-12 col-lg-6">
                                            {!! Form::text('password',null,['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Password', 'id' => 'password']) !!}
                                            {!! Form::label('password','Password') !!}
                                        </div>
                                    </div>
                                    <div class="form-group-row form-row">
                                        <div class="form-group col-12 col-lg-6">
                                            {!! Form::select('country_id',$country_id,null,['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Select', 'id' => 'country_id']) !!}
                                            {!! Form::label('country_id','Phone Country') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="result_container"></div>
                        <div class="action-row">
                            <div id="loading_container" style="display: none;">
                                <img src="/images/loader.png" width="24px" class="float-left">
                                <span class="float-left ml-1 loader-text">Processing</span>
                            </div>
                            <button type="submit" class="btn btn--round btn-primary" id="submit_button">Save</button>
                        </div>
                        {!! Form::close() !!}

                        <div class="content-body account-info">
                            <h6>Client Management</h6>
                            <br>
                            <p>You can manage your clients and disable their access to TradieFlow here.</p>
                            @if($users->count())
                                <table>
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Status</th>
                                            <th>Enable Access</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $item)
                                            <tr class="user_item" data-id="{{ $item->user_id }}">
                                                <td>
                                                    <ul class="list-unstyled">
                                                        <li>
                                                            <h5>{{ $item->name }}</h5>
                                                        </li>
                                                        <li>
                                                            <h6 style="color:#0091d5;">{{ $item->phone }}</h6>
                                                        </li>
                                                        <li style="color:#86969E;">
                                                            {{ $item->email }}
                                                        </li>
                                                        <li style="color:#86969E;">
                                                            {{ $item->twilio_password }}
                                                        </li>
                                                    </ul>
                                                </td>
                                                <td>
                                                    <?php
                                                        $desktop_login_date_obj = $item->desktop_first_login_date_time ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$item->desktop_first_login_date_time) : null;
                                                        $mobile_login_date_obj = $item->mobile_first_login_date_time ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$item->mobile_first_login_date_time) : null;
                                                    ?>
                                                    @if(!$item->active)
                                                        Inactive
                                                    @elseif($item->onboarding_status == 'completed')
                                                        Settings Completed
                                                    @elseif($item->onboarding_status == 'pending' && $item->onboarding_created != $item->onboarding_updated)
                                                        Settings Started
                                                    @elseif($desktop_login_date_obj || $mobile_login_date_obj)
                                                        @if($desktop_login_date_obj && $mobile_login_date_obj)
                                                            @if($desktop_login_date_obj->copy()->timestamp > $mobile_login_date_obj->copy()->timestamp)
                                                                First Login to Desktop (after mobile login)
                                                            @else
                                                                Mobile Login (after desktop login)
                                                            @endif
                                                        @elseif($desktop_login_date_obj)
                                                            First Login to Desktop
                                                        @else
                                                            Mobile Login
                                                        @endif
                                                    @else
                                                        Registered
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="col-auto switch-col ml-auto">
                                                        <div class="custom-control custom-switch">
                                                            {{ Form::checkbox($item->user_id,'1',$item->active ? true : null,['id' => 'user_'.$item->user_id, 'class' => 'custom-control-input lead_status']) }}
                                                            <label class="custom-control-label" for="user_{{ $item->user_id }}"></label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info">No records found</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('view_script')
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('submit','#lead_form',function(){
            var name = $('#name').val();
            if (!name) {
                $('#name').focus();
                return false;
            }

            var email = $('#email').val();
            if (!email.length) {
                $('#email').focus();
                return false;
            }

            var password = $('#password').val();
            if (!password.length) {
                $('#password').focus();
                return false;
            }

            var country_id = $('#country_id').val();
            if (!country_id) {
                $('#country_id').focus();
                return false;
            }

            $('#submit_button').hide();
            $('#loading_container').show();
            $('#result_container').empty();
            $.post('/admin/generate',{ name: name, email: email, password: password, country_id: country_id },function(data){
                $('#loading_container').hide();
                $('#submit_button').show();
                if (data.status) {
                    $('#result_container').html(_.template($('#twilio_number_template').html())({
                        phone: data.phone,
                        name: name,
                        email: email,
                        password: password
                    }));
                }
                else{
                    alert(data.error);
                }
            },'json');
            return false;
        });

        $(document).on('change','.lead_status',function(){
            var user_id = $(this).closest('.user_item').attr('data-id');
            $.post('/settings/update/lead/status',{ user_id: user_id, status: $(this).prop('checked') ? '1' : '0' },function(data){

            });
            return false;
        });
    });
</script>
<script type="text/template" id="twilio_number_template">
    <div class="alert alert-success">
        <h4>
            <ul class="list-unstyled">
                <li>Name: <b><%= name %></b></li>
                <li>Email: <b><%= email %></b></li>
                <li>Password: <b><%= password %></b></li>
                <li>Phone: <b><%= phone %></b></li>
            </ul>
        </h4>
    </div>
</script>
@endsection
