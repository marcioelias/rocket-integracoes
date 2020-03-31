@extends('layouts.app')

@push('styles')
    <link href="{{ asset('template/plugins/animate/animate.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
<div class="container-fluid">
    <form method="POST" action="{{ route('users.store') }}">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-6">
                <x-form.input-text label="Nome" field="name" />
            </div>
            <div class="form-group col-md-6">
                <x-form.input-email label="E-mail" field="email" />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <x-form.input-text label="Usuário" field="username" />
            </div>
            <div class="form-group col-md-4">
                <x-form.input-password label="Senha" field="password" />
            </div>
            <div class="form-group col-md-4">
                <x-form.input-password label="Confirmação" field="password_confirmation" />
            </div>
        </div>
        <div class="form-row">
            <a class="btn mt-3 mr-3" href="{{ route('users.index') }}">Cancelar</a>
            <button type="submit" class="btn btn-secondary mt-3">Salvar</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')

@endpush
