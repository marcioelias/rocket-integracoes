@extends('layouts.app')

@push('styles')
    <link href="{{ asset('template/plugins/animate/animate.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('template/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
{{-- {{ dd($endpoints->api->name.'/'.$endpoints->name) }} --}}
{{-- {{ dd($api->name.'/'.$name) }} --}}
{{-- @foreach ($endpoints as $endpoint)
    {{ dd($endpoint->api->name) }}
@endforeach --}}
<div class="container-fluid">
    <form method="POST" action="{{ route('integrations.store') }}">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-4">
                <x-form.input-text label="Nome" field="name" />
            </div>
            <div class="form-group col-md-4">
                <x-form.input-select-db label="Webhook" field="webhook_id" :options="$webhooks" keyField="id" displayField="name" />
            </div>
            <div class="form-group col-md-4">
                <x-form.input-select-db label="Endpoint" field="api_endpoint_id" :options="$endpoints" keyField="id" displayExpression="return $option->api->name.'/'.$option->name;" />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col">
                <x-form.input-textarea label="Mapeamento" field="field_mapping" rows="4" />
            </div>
        </div>
        <div class="form-row">
            <a class="btn mt-3 mr-3" href="{{ route('integrations.index') }}">Cancelar</a>
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
