@extends('layouts.master')
@section('content')
    <div class="container">
        <div>
            <h1>Faq</h1>
        </div>
        <div>
            <div class="row">
                @foreach($faq as $value)
                    <div>
                        <h4>
                            {{$value->title}}
                        </h4>
                        <p class="text-muted">
                            {{$value->description}}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="w-25 d-flex justify-content-center">
            {{$faq->links("pagination::bootstrap-4")}}
        </div>
    </div>
@stop