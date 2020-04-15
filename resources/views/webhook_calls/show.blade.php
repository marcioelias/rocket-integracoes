@extends('layouts.app')
@push('styles')
<link href="{{ asset('template/assets/css/components/tabs-accordian/custom-accordions.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('content')
{{-- {{ dd($model) }} --}}
    <div class="container-fluid" id="webhook-calls">
        <div class="row mb-3">
            <div class="col col-md-6">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Webhook</span>
                    </div>
                    <div class="form-control">{{ $model->webhook->name }}</div>
                </div>
            </div>
            <div class="col col-md-6">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Transação</span>
                    </div>
                    <div class="form-control">{{ $model->transaction_code }}</div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Produto</span>
                    </div>
                    <div class="form-control">{{ json_decode($model->mapped_data, true)['product_name'] ?? 'Não identificado' }}</div>
                </div>
            </div>
        </div>
        <div id="iconsAccordion" class="accordion-icons">
            <div class="card">
                <div class="card-header" id="accord1">
                    <section class="mb-0 mt-0">
                        <div role="menu" class="collapsed" data-toggle="collapse" data-target="#iconAccordionOne" aria-expanded="true" aria-controls="iconAccordionOne">
                            <div class="accordion-icon"><i class="fas fa-code"></i></div>
                            Dados Recebidos / Mapeados  <div class="icons"><i class="fas fa-chevron-down"></i></div>
                        </div>
                    </section>
                </div>

                <div id="iconAccordionOne" class="collapse" aria-labelledby="..." data-parent="#iconsAccordion">
                    <div class="card-body">
                        <div class="row">
                            <div class="col col-md-6">
                                <h5>Dados Recebidos</h5>
                                <vue-json-pretty :path="'res'" :data="{{ $model->data }}" />
                            </div>
                            <div class="col col-md-6">
                                <h5>Dados Mapeados</h5>
                                <vue-json-pretty :path="'res'" :data="{{ $model->mapped_data }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="accord2">
                    <section class="mb-0 mt-0">
                        <div role="menu" class="collapsed" data-toggle="collapse" data-target="#iconAccordionTwo" aria-expanded="false" aria-controls="iconAccordionTwo">
                            <div class="accordion-icon"><i class="fas fa-business-time"></i></div>
                            Eventos Disparados  <div class="icons"><i class="fas fa-chevron-down"></i></div>
                        </div>
                    </section>
                </div>
                <div id="iconAccordionTwo" class="collapse" aria-labelledby="..." data-parent="#iconsAccordion">
                    <div class="card-body pr-0 pt-0 pb-0 pl-3">
                        @foreach ($model->integrations as $integration)
                        <div class="card border-0">
                            <div class="card-header pl-2 pt-2"><p class="text-white"><i class="fas fa-chevron-right"></i> {{ $integration->event->name }}</p></div>
                            <div class="card-body">
                                @foreach (json_decode($integration->event->conditions, true) as $condition)
                                    <p class="mb-0">{{ ($condition['logic'] == '') ? '' : $condition['logic']['label']  }} {{ $condition['field'] }} {{ $condition['operation']['label'] }} {{ $condition['value'] == '' ? '""' : $condition['value'] }}</p>
                                @endforeach
                                {{-- <vue-json-pretty :path="'res'" :data="{{ $integration->event->conditions }}" /> --}}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="accord3">
                    <section class="mb-0 mt-0">
                        <div role="menu" class="collapsed" data-toggle="collapse" data-target="#iconAccordionThree" aria-expanded="false" aria-controls="iconAccordionThree">
                            <div class="accordion-icon"><i class="fas fa-exchange-alt"></i></div>
                            Ações Executadas <div class="icons"><i class="fas fa-chevron-down"></i></div>
                        </div>
                    </section>
                </div>
                <div id="iconAccordionThree" class="collapse" aria-labelledby="..." data-parent="#iconsAccordion">
                <div class="card-body pr-0 pt-0 pb-0 pl-3">
                    @foreach ($model->integrations as $integration)
                    <div class="card mr-0 border-0">
                        <div class="card-header pl-2 pt-2"><p class="text-white"><i class="fas fa-chevron-right"></i> {{ $integration->action->name }}</p></div>
                        <div class="card-body pr-0 pt-0 pb-0 pl-3">
                            <div class="card border-0">
                                <div class="card-header pl-2 pt-2"><p class="text-white"><i class="fas fa-external-link-alt"></i> {{ $integration->api_call->api_endpoint->name }} - <span>{{ ($integration->api_call->success) ? 'Sucesso' : 'Falha' }}</span></p></div>
                                <div class="card-body mr-0">
                                    <div class="row">
                                        <div class="col col-md-6">
                                            <p>Requisição</p>
                                            <vue-json-pretty :path="'res'" :data="{{ $integration->api_call->request }}" />
                                        </div>
                                        <div class="col col-md-6">
                                            <p>Resposta</p>
                                            <vue-json-pretty :path="'res'" :data="{{ $integration->api_call->response }}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('js/webhookCall.js') }}" defer></script>
@endpush
