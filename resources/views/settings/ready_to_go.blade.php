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
                    @include('settings.settings_menu',['active_page' => 'help', 'user_onboaridng' => $user_onboarding])
                    <div class="col-md-auto col-12 contents">
                        <form>
                            <div class="content-body help-content">
                                <h3>Ready to Go</h3>
                                <div class="visual-section">
                                    <div class="inner-container d-flex align-items-center">
                                        <div class="note-wrap order-md-2 order-lg-1 d-flex">
                                            <div class="icon">
                                                <img src="/images/info-icon.svg" alt="Info icon">
                                            </div>
                                            <p class="info">
                                                We're looking forward to helping your business grow with Tradieflow!
                                            </p>
                                        </div>
                                        <div class="graphics-figure ml-auto order-md-1 order-lg-2">
                                            <img src="/images/help-visual-figure.svg" alt="Help visual figure">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <h6>Download the Application</h6>
                                        <p>Please copy-paste and add the following code to the source code of your website. This will allow us to track your forms.</p>
                                    </div>
                                </div>
                                <div class="inner-container">
                                    <div class="row app-info">
                                        <div class="col-12 col-sm-auto col-md-12 col-lg-auto qr-code-col">
                                            <h5>Scan the QR Code via Phone</h5>
                                            <div class="qr-code">
                                                <img src="/images/QR-code.png" alt="QR Code image">
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-auto col-md-12 col-lg-auto separator-border">
                                            <span>Or</span>
                                        </div>
                                        <div class="col-12 col-sm-auto col-md-12 col-lg-auto app-store-col">
                                            <h5>Search in AppStore</h5>
                                            <div class="app-store">
                                                <img src="/images/app-store.png" alt="App store screen">
                                            </div>
                                        </div>
                                    </div>
                                    <h6>Do you need any help?</h6>
                                    <p>Click on the “Read More” button and send us an email.</p>
                                    <div class="card d-flex help-info-row">
                                        <div class="icon">
                                            <img src="/images/help-icon.svg" alt="Help icon">
                                        </div>
                                        <div class="details">
                                            <h5>Contact</h5>
                                            <p>Contact us via email and get everything resolved easily.</p>
                                            <a href="{{ env('APP_URL') }}/contact-us" target="_blank" class="btn btn--round btn-primary">Read More</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
