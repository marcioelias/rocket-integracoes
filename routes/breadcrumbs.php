<?php
// Home

use App\Webhook;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('home'));
});

// Cadastros
Breadcrumbs::for('cadastros', function ($trail) {
    $trail->push('Cadastros');
});

// Cadastros > Webhooks
Breadcrumbs::for('webhooks.index', function ($trail) {
    $trail->parent('cadastros');
    $trail->push('Webhooks', route('webhooks.index'));
});

// Cadastros > Webhooks > show
Breadcrumbs::for('webhooks.show', function ($trail) {
    $trail->parent('webhooks.index');
    $trail->push('Webhook', route('webhooks.index'));
});

// Cadastros > Webhooks > Create
Breadcrumbs::for('webhooks.create', function ($trail) {
    $trail->parent('webhooks.index');
    $trail->push('Novo', route('webhooks.create'));
});

// Cadastros > Webhooks > edit > webhook
Breadcrumbs::for('webhooks.edit', function ($trail, $model) {
    $trail->parent('webhooks.index');
    $trail->push($model->name, route('webhooks.edit', $model->id));
});

// Cadastros > Apis
Breadcrumbs::for('apis.index', function ($trail) {
    $trail->parent('cadastros');
    $trail->push('Apis', route('apis.index'));
});

// Cadastros > Apis > Create
Breadcrumbs::for('apis.create', function ($trail) {
    $trail->parent('apis.index');
    $trail->push('Novo', route('apis.create'));
});

// Cadastros > Apis > edit > api
Breadcrumbs::for('apis.edit', function ($trail, $model) {
    $trail->parent('apis.index');
    $trail->push($model->name, route('api_endpoints.edit', $model->id));
});

// Cadastros > EndPoints
Breadcrumbs::for('api_endpoints.index', function ($trail) {
    $trail->parent('cadastros');
    $trail->push('Endpoints', route('api_endpoints.index'));
});

// Cadastros > EndPoints > Create
Breadcrumbs::for('api_endpoints.create', function ($trail) {
    $trail->parent('api_endpoints.index');
    $trail->push('Novo', route('api_endpoints.create'));
});

// Cadastros > EndPoints > edit > endpoins
Breadcrumbs::for('api_endpoints.edit', function ($trail, $model) {
    $trail->parent('api_endpoints.index');
    $trail->push($model->name, route('api_endpoints.edit', $model->id));
});

// Cadastros > Fields
Breadcrumbs::for('fields.index', function ($trail) {
    $trail->parent('cadastros');
    $trail->push('Campos', route('fields.index'));
});

// Cadastros > Fields > Create
Breadcrumbs::for('fields.create', function ($trail) {
    $trail->parent('fields.index');
    $trail->push('Novo', route('fields.create'));
});

// Cadastros > Fields > edit > Field
Breadcrumbs::for('fields.edit', function ($trail, $model) {
    $trail->parent('fields.index');
    $trail->push($model->label, route('fields.edit', $model->id));
});

// Cadastros > Produtos
Breadcrumbs::for('products.index', function ($trail) {
    $trail->parent('cadastros');
    $trail->push('Produtos', route('products.index'));
});

// Cadastros > Produtos > Create
Breadcrumbs::for('products.create', function ($trail) {
    $trail->parent('products.index');
    $trail->push('Novo', route('products.create'));
});

// Cadastros > Produtos > edit > Produto
Breadcrumbs::for('products.edit', function ($trail, $model) {
    $trail->parent('products.index');
    $trail->push($model->name, route('products.edit', $model->id));
});


// Cadastros > Eventos
Breadcrumbs::for('events.index', function ($trail) {
    $trail->parent('cadastros');
    $trail->push('Eventos', route('events.index'));
});

// Cadastros > Eventos > Create
Breadcrumbs::for('events.create', function ($trail) {
    $trail->parent('events.index');
    $trail->push('Novo', route('events.create'));
});

// Cadastros > Eventos > edit > Evento
Breadcrumbs::for('events.edit', function ($trail, $model) {
    $trail->parent('events.index');
    $trail->push($model->label, route('events.edit', $model->id));
});


// Cadastros > Ações
Breadcrumbs::for('actions.index', function ($trail) {
    $trail->parent('cadastros');
    $trail->push('Ações', route('actions.index'));
});

// Cadastros > Ações > Create
Breadcrumbs::for('actions.create', function ($trail) {
    $trail->parent('actions.index');
    $trail->push('Novo', route('actions.create'));
});

// Cadastros > Ações > edit > Ação
Breadcrumbs::for('actions.edit', function ($trail, $model) {
    $trail->parent('actions.index');
    $trail->push($model->product->name.' - '.$model->event->name, route('actions.edit', $model->id));
});
