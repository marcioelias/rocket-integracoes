@extends('layouts.app')

@push('styles')
    <link href="{{ asset('template/plugins/animate/animate.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
<div class="container-fluid">
    <div id="webhook-events">
        <event-crud :event-id="{{ $model->id }}" />
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/webhookEvents.js') }}" defer></script>
@endpush
