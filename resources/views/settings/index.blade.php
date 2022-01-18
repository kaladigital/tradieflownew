@extends('layouts.master')
@section('content')
    @if($auth_user->role == 'sales')
        @include('admin.left_sidebar_admin_menu',['active_page' => 'home'])
    @else
        @include('dashboard.left_sidebar_menu',['active_page' => 'home'])
    @endif
    <div class="col-md-auto col-12 content-wrap home">
        <div class="content-inner">
            <img src="/images/main-bg.png" alt="Main backgorund" class="main-bg-figure">
            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <div class="info-text">
                        <span>Customize your settings and start using the mobil app!</span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 text-center welcome-text">
                    <div class="title-text">
                        <h1>
                            <span>Stay</span>
                            <span class="green-text">Tuned</span>!
                        </h1>
                    </div>
                    <div class="info d-flex flex-row align-items-center">
                        <div class="icon">
                            <img src="/images/roket.png" alt="Roket icon">
                        </div>
                        <div class="details">
                            <h6> <span class="green-text">Yaay!</span> We are launching our desktop  application in the next 2 months.</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
