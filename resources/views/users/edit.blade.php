@extends('layouts.app')

@push('styles')
    <link href="{{ asset('template/plugins/animate/animate.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
<div class="container-fluid">
    <div id="user-crud">
        <user-crud-form :user-id="{{ $model->id }}" />
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/users.js') }}" defer></script>
@endpush
