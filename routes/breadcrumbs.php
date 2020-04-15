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

// Integrações
Breadcrumbs::for('integracoes', function ($trail) {
    $trail->push('Integrações');
});

// Configurações
Breadcrumbs::for('configuracoes', function ($trail) {
    $trail->push('Configurações');
});

// Acesso
Breadcrumbs::for('acesso', function ($trail) {
    $trail->push('Acesso');
});

// Integrações > Webhooks
Breadcrumbs::for('webhooks.index', function ($trail) {
    $trail->parent('integracoes');
    $trail->push('Webhooks', route('webhooks.index'));
});

// Integrações > Webhooks > show
Breadcrumbs::for('webhooks.show', function ($trail) {
    $trail->parent('webhooks.index');
    $trail->push('Webhook', route('webhooks.index'));
});

// Integrações > Webhooks > Create
Breadcrumbs::for('webhooks.create', function ($trail) {
    $trail->parent('webhooks.index');
    $trail->push('Novo', route('webhooks.create'));
});

// Integrações > Webhooks > edit > webhook
Breadcrumbs::for('webhooks.edit', function ($trail, $model) {
    $trail->parent('webhooks.index');
    $trail->push($model->name, route('webhooks.edit', $model->id));
});

// Integrações > Apis
Breadcrumbs::for('apis.index', function ($trail) {
    $trail->parent('integracoes');
    $trail->push('Apis', route('apis.index'));
});

// Integrações > Apis > Create
Breadcrumbs::for('apis.create', function ($trail) {
    $trail->parent('apis.index');
    $trail->push('Novo', route('apis.create'));
});

// Integrações > Apis > edit > api
Breadcrumbs::for('apis.edit', function ($trail, $model) {
    $trail->parent('apis.index');
    $trail->push($model->name, route('api_endpoints.edit', $model->id));
});

// Integrações > EndPoints
Breadcrumbs::for('api_endpoints.index', function ($trail) {
    $trail->parent('integracoes');
    $trail->push('Endpoints', route('api_endpoints.index'));
});

// Integrações > EndPoints > Create
Breadcrumbs::for('api_endpoints.create', function ($trail) {
    $trail->parent('api_endpoints.index');
    $trail->push('Novo', route('api_endpoints.create'));
});

// Integrações > EndPoints > edit > endpoins
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


// Configurações > Eventos
Breadcrumbs::for('events.index', function ($trail) {
    $trail->parent('configuracoes');
    $trail->push('Eventos', route('events.index'));
});

// Configurações > Eventos > Create
Breadcrumbs::for('events.create', function ($trail) {
    $trail->parent('events.index');
    $trail->push('Novo', route('events.create'));
});

// Configurações > Eventos > edit > Evento
Breadcrumbs::for('events.edit', function ($trail, $model) {
    $trail->parent('events.index');
    $trail->push($model->name, route('events.edit', $model->id));
});


// Configurações > Ações
Breadcrumbs::for('actions.index', function ($trail) {
    $trail->parent('configuracoes');
    $trail->push('Ações', route('actions.index'));
});

// Configurações > Ações > Create
Breadcrumbs::for('actions.create', function ($trail) {
    $trail->parent('actions.index');
    $trail->push('Novo', route('actions.create'));
});

// Configurações > Ações > edit > Ação
Breadcrumbs::for('actions.edit', function ($trail, $model) {
    $trail->parent('actions.index');
    $trail->push($model->product->name.' - '.$model->event->name, route('actions.edit', $model->id));
});

// Configurações > Encurtador de URL
Breadcrumbs::for('short_url_configs.edit', function ($trail) {
    $trail->parent('configuracoes');
    $trail->push('Short URL', route('short_url_configs.edit'));
});

// Acesso > Users
Breadcrumbs::for('users.index', function ($trail) {
    $trail->parent('acesso');
    $trail->push('Usuários', route('users.index'));
});

// Acesso > Users > Create
Breadcrumbs::for('users.create', function ($trail) {
    $trail->parent('users.index');
    $trail->push('Novo', route('users.create'));
});

// Acesso > Users > edit > User
Breadcrumbs::for('users.edit', function ($trail, $model) {
    $trail->parent('users.index');
    $trail->push($model->name, route('users.edit', $model->id));
});

// Usuário
Breadcrumbs::for('user', function ($trail, $model) {
    $trail->push($model->name);
});

// Perfil
Breadcrumbs::for('users.profile', function ($trail, $model) {
    $trail->parent('user', $model);
    $trail->push('Profile');
});

// Integrações > Postbacks
Breadcrumbs::for('webhook_calls.index', function ($trail) {
    $trail->parent('integracoes');
    $trail->push('Postbacks', route('webhook_calls.index'));
});

// Integrações > Postbacks > postback > show
Breadcrumbs::for('webhook_calls.show', function ($trail, $model) {
    $trail->parent('webhook_calls.index');
    $trail->push($model->transaction_code, route('webhook_calls.show', $model->id));
});
