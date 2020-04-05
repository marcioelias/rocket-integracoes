@extends('layouts.app')

@push('styles')
    <link href="{{ asset('template/plugins/animate/animate.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
<div class="container-fluid">
    <div id="product-crud">
        <product-crud-form />
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/products.js') }}" defer></script>
@endpush
