@extends('layouts.base')

@push('head-scripts')
<script src="{{ asset('template/assets/js/loader.js') }}"></script>
<script>
    window.Laravel = {!! json_encode([
        'csrfToken' => csrf_token(),
    ]) !!};
</script>
@endpush

@push('styles')
<link href="{{ asset('template/assets/css/loader.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('template/assets/css/elements/tooltip.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('template/assets/css/elements/popover.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('template/assets/css/components/custom-modal.css" rel="stylesheet')}} " type="text/css">
{{-- <link href="{{ asset('template/assets/css/plugins.css') }}" rel="stylesheet" type="text/css" /> --}}
@endpush

@section('base-content')
<x-loader />
<!--  BEGIN NAVBAR  -->
<x-nav-bar />
<x-breadcrumb :breadbrumb="Route::currentRouteName()" :model="isset($model) ? $model : null" />
<!--  END NAVBAR  -->

<!--  BEGIN MAIN CONTAINER  -->
<div class="main-container" id="container">

    <div class="overlay"></div>
    <div class="search-overlay"></div>

    <!--  BEGIN SIDEBAR  -->
    <div class="sidebar-wrapper sidebar-theme">
        <x-side-bar />
    </div>
    <!--  END SIDEBAR  -->

    <!--  BEGIN CONTENT AREA  -->
    <div id="content" class="main-content">
        <div class="layout-px-spacing">
            <div class="row layout-top-spacing">
                @yield('content')
            </div>
        </div>
        <!--  BEGIN FOOTER  -->
        <x-footer />
        <!--  END FOOTER  -->
    </div>
    <!--  END CONTENT AREA  -->
</div>
@endsection

@push('scripts')
    <script src="{{ asset('template/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('template/assets/js/app.js') }}" ></script>
    <script >
        $(document).ready(function() {
            App.init();
        });
    </script>
{{--     <script src="{{ asset('template/plugins/highlight/highlight.pack.js') }}"></script> --}}
    <script src="{{ asset('template/assets/js/custom.js') }}" ></script>
    {{-- <script src="{{ asset('template/assets/js/elements/tooltip.js') }}"></script> --}}
    <script src="{{ asset('template/assets/js/elements/popovers.js') }}"></script>
    {{-- <script>
        $('[data-toggle="tooltip"]').tooltip();
    </script> --}}
@endpush
