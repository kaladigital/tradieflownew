@extends('layouts.master')
@section('content')
    @if($auth_user->role == 'sales')
        @include('admin.left_sidebar_admin_menu',['active_page' => 'settings'])
    @else
        @include('dashboard.left_sidebar_menu',['active_page' => 'settings'])
    @endif
    <div class="col-md-auto col-12 content-wrap onboarding">
        <div class="content-inner text-center">
            <div class="title-wrap">
                <a href="/onboarding" class="btn back-btn">
                    <img src="/images/left-arrow-green.svg" alt="Left arrow green">
                    <span>Back</span>
                </a>
                <h1>
                    Demo
                    <span class="green-text">Onboarding</span>
                </h1>
            </div>
            <h3>Book a Free 30-Minute Demo</h3>
            <div class="calendar-container">
                <div class="calendar-wrap">
                    <iframe src="https://calendly.com/carltradieflowco/30min?hide_gdpr_banner=1&embed_domain=tradiedigital.co&embed_type=Inline&text_color=333332&primary_color=ff9523&" width="1000" height="700" scrolling="no"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection
