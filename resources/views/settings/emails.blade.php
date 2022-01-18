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
                    @if($auth_user->gmail_username && strlen($auth_user->gmail_password) && $auth_user->gmail_token)
                        <iframe src="{{ env('APP_URL') }}/cubemail/?token={{ $auth_user->gmail_token }}" border="0" width="100%" height="700px"></iframe>
                    @else
                        <div class="content-body account-info">
                            <div class="alert alert-info text-center">
                                Gmail credentials are missing, use following <a href="/settings/account">link</a> to add them
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
