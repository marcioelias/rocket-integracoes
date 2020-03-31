@extends('layouts.base')

@push('styles')
    <link href="{{ asset('template/plugins/animate/animate.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('base-content')
<div class="container-fluid">
    <div id="teste">
        <event-crud :webhook-id="1"/>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/teste.js') }}" defer></script>
@endpush
