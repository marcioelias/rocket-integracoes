@extends('layouts.app')

@push('styles')
    <link href="{{ asset('template/plugins/animate/animate.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
<div class="container-fluid">
    <div id="teste">
        <json-form-component url="{{ url('/webhook') }}" webhook="{{ $webhook->id }}" />
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/jsonAnaliser.js') }}" defer></script>
@endpush
