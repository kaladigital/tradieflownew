@extends('layouts.master')
@section('content')
    @include('elements.alerts')
    {!! Form::open(['url' => 'quote', 'class' => 'form-horizontal', 'id' => 'quote_form', 'autocomplete' => 'off']) !!}
    @include('quote._form')
    <div class="form-group">
        <p>&nbsp;</p>
        {!! link_to(URL::previous(), 'Cancel', ['class' => 'btn btn-default']) !!}
        {!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
    </div>
    {!! Form::close() !!}
@endsection
