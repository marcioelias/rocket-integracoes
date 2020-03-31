<nav id="sidebar">
    <div class="shadow-bottom"></div>
    <ul class="list-unstyled menu-categories" id="accordionExample">
        @foreach ($menu as $item)
            <li class="menu">
                <a href="{{ $item['route'] }}" {{ $item['active'] }} {{ (count($item['submenus']) > 0) ? 'data-toggle=collapse' : '' }} aria-expanded=false  class="dropdown-toggle">
                    <div class="">
                        {!! $item['icon'] !!}
                        <span>{{ $item['name'] }}</span>
                    </div>
                @if (count($item['submenus']) > 0)
                    <div>{!! $arrow_svg !!}</div>
                </a>
                <ul class="collapse submenu list-unstyled {{ $item['active'] != '' ? 'show' : '' }}" id="{{ $item['id'] }}" data-parent="#accordionExample">
                    @foreach ($item['submenus'] as $submenu)
                    <li {{ $submenu['active'] ? 'class=active' : '' }}>
                        <a href="{{ $submenu['route'] }}">{{ $submenu['name'] }}</a>
                    </li>
                    @endforeach
                </ul>
                @else
                </a>
                @endif
            </li>
        @endforeach

        {{-- versão original abaixo --}}

        {{-- <li class="menu">
            <a href="{{ route('home') }}" {{ $homeActive ? 'data-active=true' : ''}}  class="dropdown-toggle">
                <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    <span>Dashboard</span>
                </div>
            </a>
        </li>

        <li class="menu">
            <a href="#cadastros" data-toggle="collapse" {{ $cadastrosActive ? 'data-active=true aria-expanded=true' : 'aria-expanded=false' }}   class="dropdown-toggle">
                <div class="">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
                    <span>Cadastros</span>
                </div>
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </div>
            </a>
            <ul class="collapse submenu list-unstyled {{ $cadastrosActive ? 'show' : '' }}" id="cadastros" data-parent="#accordionExample">
                <li>
                    <a href="{{ route('webhooks.index') }}"> Webhooks </a>
                </li>
                <li>
                    <a href="{{ route('apis.index') }}"> API's </a>
                </li>
                <li>
                    <a href="{{ route('api_endpoints.index') }}"> Endpoints </a>
                </li>
                <li>
                    <a href="{{ route('integrations.index') }}"> Integrações </a>
                </li>


                <li>
                    <a href="{{ route('fields.index') }}"> Campos Locais </a>
                </li>
            </ul>
        </li>

        <li class="menu">
            <a href="#relatorios" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span>Relatórios</span>
                </div>
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </div>
            </a>
            <ul class="collapse submenu list-unstyled" id="relatorios" data-parent="#accordionExample">
                <li>
                    <a href="user_profile.html"> Usuários </a>
                </li>
                <li>
                    <a href="user_account_setting.html"> Perfis de Acesso </a>
                </li>
            </ul>
        </li>

        <li class="menu">
            <a href="#users" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span>Usuários</span>
                </div>
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </div>
            </a>
            <ul class="collapse submenu list-unstyled" id="users" data-parent="#accordionExample">
                <li>
                    <a href="{{ route('users.index') }}"> Usuários </a>
                </li>
                <li>
                    <a href="user_account_setting.html"> Perfis de Acesso </a>
                </li>
            </ul>
        </li> --}}
    </ul>
</nav>
