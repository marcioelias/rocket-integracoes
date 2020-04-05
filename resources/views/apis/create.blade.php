@extends('layouts.app')

@push('styles')
    <link href="{{ asset('template/plugins/animate/animate.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('template/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
<div class="container-fluid">
    <div id="api-crud">
        <api-crud-form />
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/apis.js') }}" defer></script>
@endpush
