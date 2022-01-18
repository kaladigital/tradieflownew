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
                    @include('settings.settings_menu',['active_page' => 'calendar', 'user_onboaridng' => $user_onboarding])
                    <div class="col-md-auto col-12 contents">
                        {!! Form::model($auth_user,['action' => ['SettingsController@updateCalendar'], 'method' => 'patch', 'autocomplete' => 'off']) !!}
                            <div class="content-body">
                                <h3>Calendar</h3>
                                <div class="visual-section">
                                    <div class="inner-container d-flex align-items-center">
                                        <div class="note-wrap order-md-2 order-lg-1 d-flex">
                                            <div class="icon">
                                                <img src="/images/info-icon.svg" alt="Info icon">
                                            </div>
                                            <p class="info">
                                                Fill in your available working hours, so your calendar can give you smart requests about available time slots for your meetings.
                                            </p>
                                        </div>
                                        <div class="graphics-figure ml-auto order-md-1 order-lg-2">
                                            <img src="/images/calendar-visual-figure.svg" alt="Calendar visual figure">
                                        </div>
                                    </div>
                                </div>
                                <h6>Working hours</h6>
                                <p>Please specify your working hours to let our system give you the best free time slot estimates.</p>
                                <div class="inner-container">
                                    <div class="calendar-items">
                                        @foreach($weeks_days as $key => $value)
                                            <div class="calendar-item d-flex align-items-center no-gutters">
                                                <div class="col-auto day-name">
                                                    <span>{{ $value }}</span>
                                                </div>
                                                <div class="col-auto select-time start-time" style="{{ $auth_user->{$key} ? '' : 'display:none;' }}">
                                                    <div class="form-group select-group">
                                                        {!! Form::select($key.'_start',$working_days_hours,null,['class' => 'form-control']) !!}
                                                        {!! Form::label($key.'_start','Start Time') !!}
                                                    </div>
                                                </div>
                                                <div class="col-auto select-time end-time" style="{{ $auth_user->{$key} ? '' : 'display:none;' }}">
                                                    <div class="form-group select-group">
                                                        {!! Form::select($key.'_end',$working_days_hours,null,['class' => 'form-control']) !!}
                                                        {!! Form::label($key.'_end','End Time') !!}
                                                    </div>
                                                </div>
                                                <div class="col-auto switch-col ml-auto">
                                                    <div class="custom-control custom-switch">
                                                        {{ Form::checkbox($key,'1',null,['id' => 'week_num_'.$key, 'class' => 'custom-control-input']) }}
                                                        <label class="custom-control-label" for="week_num_{{ $key }}"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="action-row">
                                @if($user_onboarding->status == 'pending')
                                    <a href="/settings/skip/calendar" class="btn btn--round btn-secondary">Skip</a>
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
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('change','.custom-control-input',function(){
            if ($(this).prop('checked')) {
                $(this).closest('.calendar-item').find('.select-time').fadeIn();
            }
            else {
                $(this).closest('.calendar-item').find('.select-time').fadeOut();
            }
        });
    })
</script>
@endsection
