<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SideBar extends Component
{

    private $dataActive = 'data-active=true';

    public $arrow_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>';

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.side-bar');
    }


    public function menu() {
        return [
            [
                'name' => 'Home',
                'route' => route('home'),
                'active' => $this->homeActive(),
                'expanded' => false,
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>',
                'submenus' => []
            ],
            [
                'name' => 'Cadastros',
                'route' => '#cadastros',
                'id' => 'cadastros',
                'active' => $this->cadastrosActive(),
                'expanded' => $this->cadastrosActive(),
                'icon' => '<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>',
                'submenus' => [

                    [
                        'name' => 'Campos',
                        'route' => route('fields.index'),
                        'active' => request()->is('fields*'),
                    ],
                    [
                        'name' => 'Produtos',
                        'route' => route('products.index'),
                        'active' => request()->is('products*'),
                    ],
                ]
            ],
            [
                'name' => 'Integrações',
                'route' => '#integracoes',
                'id' => 'integracoes',
                'active' => $this->integracoesActive(),
                'expanded' => $this->integracoesActive(),
                'icon' => '<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect x="9" y="9" width="6" height="6"></rect><line x1="9" y1="1" x2="9" y2="4"></line><line x1="15" y1="1" x2="15" y2="4"></line><line x1="9" y1="20" x2="9" y2="23"></line><line x1="15" y1="20" x2="15" y2="23"></line><line x1="20" y1="9" x2="23" y2="9"></line><line x1="20" y1="14" x2="23" y2="14"></line><line x1="1" y1="9" x2="4" y2="9"></line><line x1="1" y1="14" x2="4" y2="14"></line></svg>',
                'submenus' => [
                    [
                        'name' => 'Api\'s',
                        'route' => route('apis.index'),
                        'active' => request()->is('apis*'),
                    ],
                    [
                        'name' => 'Endpoint\'s',
                        'route' => route('api_endpoints.index'),
                        'active' => request()->is('api_endpoints*'),
                    ],
                    [
                        'name' => 'Webhook\'s',
                        'route' => route('webhooks.index'),
                        'active' => request()->is('webhooks*'),
                    ],
                    [
                        'name' => 'Postback\'s',
                        'route' => route('webhook_calls.index'),
                        'active' => request()->is('webhook_calls*'),
                    ],
                ]
            ],
            [
                'name' => 'Configurações',
                'route' => '#configuracoes',
                'id' => 'configuracoes',
                'active' => $this->configuracoesActive(),
                'expanded' => $this->configuracoesActive(),
                'icon' => '<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>',
                'submenus' => [
                    [
                        'name' => 'Ações',
                        'route' => route('actions.index'),
                        'active' => request()->is('actions*'),
                    ],
                    [
                        'name' => 'Eventos',
                        'route' => route('events.index'),
                        'active' => request()->is('events*'),
                    ],
                    [
                        'name' => 'Short URL',
                        'route' => route('short_url_configs.edit'),
                        'active' => request()->is('short_url_configs*'),
                    ],
                ]
            ],
            [
                'name' => 'Acesso',
                'route' => '#acesso',
                'id' => 'acesso',
                'active' => $this->acessoActive(),
                'expanded' => $this->acessoActive(),
                'icon' => '<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>',
                'submenus' => [
                    [
                        'name' => 'Usuários',
                        'route' => route('users.index'),
                        'active' => request()->is('users*'),
                    ],
                ]
            ],
        ];
    }


    public function homeActive() {
        return request()->is('/') ? $this->dataActive : '';
    }

    public function cadastrosActive() {
        return ((request()->is('fields*')) ||
            (request()->is('products*'))
        ) ? $this->dataActive : '';
    }

    public function integracoesActive() {
        return (request()->is('webhooks*') ||
        (request()->is('webhook_calls*')) ||
        (request()->is('apis*')) ||
        (request()->is('api_endpoints*'))) ? $this->dataActive : '';
    }

    public function configuracoesActive() {
        return ((request()->is('events*')) ||
            (request()->is('actions*')) ||
            (request()->is('short_url_configs*'))
        ) ? $this->dataActive : '';
    }

    public function acessoActive() {
        return (request()->is('users')) ? $this->dataActive : '';
    }
}
