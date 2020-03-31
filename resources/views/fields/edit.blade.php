@extends('layouts.app')

@push('styles')
    <link href="{{ asset('template/plugins/animate/animate.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
<div class="container-fluid">
    <form method="POST" action="{{ route('fields.update', $field->id) }}">
        @csrf
        @method('PUT')
        <div class="form-row">
            <div class="form-group col-md-6">
                <x-form.input-text label="Rótulo" field="label" :value="$field->label" />
            </div>
            <div class="form-group col-md-6">
                <x-form.input-text label="Nome do Campo" field="field_name" :value="$field->field_name" />
            </div>
        </div>
        <div class="form-row">
            <a class="btn mt-3 mr-3" href="{{ route('fields.index') }}">Cancelar</a>
            <button type="submit" class="btn btn-secondary mt-3">Salvar</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')

@endpush
