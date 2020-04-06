@extends('layouts.base')

@push('styles')
<link href="{{ asset('template/assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('template/assets/css/forms/switches.css') }}" rel="stylesheet">
<link href="{{ asset('template/plugins/animate/animate.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('template/plugins/sweetalerts/sweetalert2.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('template/assets/css/authentication/form-2.css') }}" rel="stylesheet">
@endpush

@push('head-scripts')
<script>
    window.Laravel = {!! json_encode([
        'csrfToken' => csrf_token(),
    ]) !!};
</script>
<script src="{{ asset('template/plugins/sweetalerts/promise-polyfill.js') }}"></script>
@endpush

@section('base-content')
    <div id="reset-password">
        <send-email-reset-password />
    </div>

@endsection

@push('scripts')
<script src="{{ asset('template/plugins/sweetalerts/sweetalert2.min.js') }}"></script>
<script src="{{ asset('js/resetPassword.js') }}"></script>
@endpush
