@extends('layouts.app')

@section('content')
    @foreach ($webhook->webhookCalls() as $call)
        {{ dd($call) }}
    @endforeach
@endsection
