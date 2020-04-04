@extends('layouts.app')

@push('styles')
    <link href="{{ asset('template/plugins/table/datatable/datatables.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('template/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('template/plugins/animate/animate.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('template/assets/css/components/custom-modal.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
    <div class="container-fluid">
        {{$dataTable->table()}}
    </div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
<script src="{{ asset('template/plugins/highlight/highlight.pack.js') }}"></script>
{{$dataTable->scripts()}}
<x-delete-scripts />
@endpush
