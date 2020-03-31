@extends('layouts.app')

@push('styles')
    <link href="{{ asset('template/plugins/animate/animate.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('template/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
<div class="container-fluid">
    <form method="POST" action="{{ route('apis.store') }}">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-4">
                <x-form.input-text label="Nome" field="name" />
            </div>
            <div class="form-group col-md-8">
                <x-form.input-url label="Base URL" field="base_url" url="http://" />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                {{-- Método ainda não implementado --}}
                <x-form.input-select label="Autenticação" field="auth_method" :options="['none' => 'Desabilitada', 'token' => 'Token'{{-- , 'jwt' => 'JWT' --}}]" />
            </div>
            <div class="form-group col-md-8">
                <x-form.input-text label="Token" field="token" />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <x-form.input-text label="Usuário" field="username" disabled />
            </div>
            <div class="form-group col-md-6">
                <x-form.input-text label="Senha" field="password" disabled />
            </div>
        </div>
        <div class="form-row">
            <a class="btn mt-3 mr-3" href="{{ route('apis.index') }}">Cancelar</a>
            <button type="submit" class="btn btn-secondary mt-3">Salvar</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('template/plugins/select2/select2.min.js') }}"></script>
{{-- <script>
    var ss = $(".basic").select2({
        tags: false,
    });
</script> --}}
@endpush
