@extends('layouts.app')

@push('styles')
    <link href="{{ asset('template/plugins/animate/animate.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
<div class="container-fluid">
    <form method="POST" action="{{ route('products.update', $model->id) }}">
        @csrf
        @method('PUT')
        <div class="form-row">
            <div class="form-group col-md-6">
                <x-form.input-select-db label="Webhook" field="webhook_id" :options="$webhooks" keyField="id" displayField="name" :value="$model->webhook_id" />
            </div>
            <div class="form-group col-md-6">
                <x-form.input-text label="Código" field="product_code" :value="$model->product_code" />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col">
                <x-form.input-text label="Nome" field="name" :value="$model->name" />
            </div>
        </div>
        <div class="form-row">
            <a class="btn mt-3 mr-3" href="{{ route('products.index') }}">Cancelar</a>
            <button type="submit" class="btn btn-secondary mt-3">Salvar</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')

@endpush
