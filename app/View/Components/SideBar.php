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
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>',
                'submenus' => [
                    [
                        'name' => 'Webhook\'s',
                        'route' => route('webhooks.index'),
                        'active' => request()->is('webhooks*'),
                    ],
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
                        'name' => 'Campos',
                        'route' => route('fields.index'),
                        'active' => request()->is('fields*'),
                    ],
                    [
                        'name' => 'Produtos',
                        'route' => route('products.index'),
                        'active' => request()->is('products*'),
                    ],
                    [
                        'name' => 'Eventos',
                        'route' => route('events.index'),
                        'active' => request()->is('events*'),
                    ],
                ]
            ]
        ];
    }


    public function homeActive() {
        return request()->is('/') ? $this->dataActive : '';
    }

    public function cadastrosActive() {
        return (request()->is('webhooks*') ||
            (request()->is('apis*')) ||
            (request()->is('api_endpoints*')) ||
            (request()->is('integrations*')) ||
            (request()->is('fields*')) ||
            (request()->is('products*')) ||
            (request()->is('events*'))
        ) ? $this->dataActive : '';
    }

    public function usersActive() {
        return (request()->is('users')) ? $this->dataActive : '';
    }
}
