@extends('layouts.app')

@section('content')
    <div class="form-row">
        <div class="col col-md-6">
            <label for="webhook_id">Webhook</label>
            <div class="form-control">{{ $model->webhook->name }}</div>
        </div>
        <div class="row col-md-6">
            <label for="transaction_code">Webhook</label>
            <div class="form-control">{{ $model->transaction_code }}</div>
        </div>
    </div>
    <div class="row">
        <div class="col col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Dados Recebidos</h5>
                </div>
                <div class="card-body">
                    {{-- json pretty --}}
                </div>
            </div>
        </div>
        <div class="col col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Dados Mapeados</h5>
                </div>
                <div class="card-body">
                    {{-- json pretty --}}
                </div>
            </div>
        </div>
    </div>
@endsection
