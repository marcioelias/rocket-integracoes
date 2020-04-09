@extends('layouts.app')

@push('styles')
    <link href="{{ asset('template/plugins/animate/animate.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
<div class="container-fluid">
    <div id="short-url">
        <short-url-config-form :model="{{ $model }}"/>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/shortUrlConfig.js') }}" defer></script>
@endpush
