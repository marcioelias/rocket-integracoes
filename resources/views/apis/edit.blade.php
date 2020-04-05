@extends('layouts.app')

@push('styles')
    <link href="{{ asset('template/plugins/animate/animate.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
<div class="container-fluid">
    <div class="container-fluid">
        <div id="api-crud">
            <api-crud-form :api-id="{{ $model->id }}"/>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/apis.js') }}" defer></script>
@endpush
