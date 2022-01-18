@extends('layouts.master')
@section('view_css')
    <link rel="stylesheet" href="/js/jquery-confirm/jquery-confirm.min.css">
@endsection
@section('content')
    @include('dashboard.left_sidebar_full_menu',['active_page' => 'quote'])
@endsection
