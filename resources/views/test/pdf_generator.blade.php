@extends('layouts.invoice')
@section('content')
    {!! Form::open(['url' => 'test/pdf']) !!}
        {!! Form::textarea('content',null,['class' => 'form-control', 'placeholder' => 'HTML Content']) !!}
        <button type="submit" class="btn btn--round btn-primary">Generate</button>
    {!! Form::close() !!}
@endsection
