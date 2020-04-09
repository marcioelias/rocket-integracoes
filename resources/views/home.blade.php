@extends('layouts.app')

@push('styles')
{{-- <link href="{{ asset('template/plugins/apex/apexcharts.css') }}" rel="stylesheet"> --}}
<link href="{{ asset('template/assets/css/dashboard/dash_1.css') }}" rel="stylesheet">
@endpush
@section('content')
<div id="dashboard" style="display: contents">
    <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing" style="overflow: hidden">
        <last-api-calls />
    </div>

    <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing" id="dashboard">
        <div class="widget widget-chart-two">
            <div class="widget-heading">
                <h5 class="">Chamadas API</h5>
            </div>
            <div class="widget-content">
                <api-calls-graph></api-calls-graph>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- <script src="{{ asset('template/plugins/apex/apexcharts.min.js') }}" defer></script> --}}
{{-- <script src="{{ asset('template/assets/js/dashboard/dash_1.js') }}" defer></script> --}}
<script src="{{ asset('/js/dashboard.js') }}"></script>
@endpush
