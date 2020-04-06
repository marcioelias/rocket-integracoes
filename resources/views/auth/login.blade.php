@extends('layouts.base')

@push('styles')
<link href="{{ asset('template/assets/css/authentication/form-2.css') }}" rel="stylesheet">
<link href="{{ asset('template/assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('template/assets/css/forms/switches.css') }}" rel="stylesheet">
@endpush

@section('base-content')
<div class="form">
    <div class="form-container outer">
        <div class="form-form">
            <div class="form-form-wrap">
                <div class="form-container">
                    <div class="form-content">

                        <h1 class="">{{ config('app.name', 'Laravel') }}</h1>
                        <p class="">Entre com as suas credenciais de acesso.</p>

                        <form class="text-left" method="POST" action="{{ route('login') }}">
                            @csrf

                            <div id="username-field" class="field-wrapper input">
                                <label for="username">USUÁRIO</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                <input id="username" name="username" type="text" class="form-control" placeholder="Ex joao" value="{{ old('username') }}">
                                @if ($errors->has('username'))
                                    <span class="invalid-feedback" style="display: block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div id="password-field" class="field-wrapper input mb-2">
                                <label for="password">SENHA</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                <input id="password" name="password" type="password" class="form-control" placeholder="Password">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="toggle-password" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" style="display: block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="division"></div>

                            <div class="d-sm-flex justify-content-between">
                                <div class="field-wrapper">
                                    <button type="submit" class="btn btn-primary" value="">Acessar</button>
                                </div>
                            </div>
                            <div class="division"></div>
                            <a href="{{ route('password.request') }}" class="forgot-pass-link float-right">Esqueceu a senha?</a>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('template/assets/js/authentication/form-2.js') }}"></script>
@endpush
