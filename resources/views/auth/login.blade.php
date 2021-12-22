{{-- <x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 text-sm font-medium text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="block w-full mt-1" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-jet-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="text-sm text-gray-600 underline hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-jet-button class="ml-4">
                    {{ __('Log in') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout> --}}

@extends('layouts.templateerrors')

@section('title')
    Detail Pojet
@endsection

@section('contenu')
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- wrap @s -->
            <div class="nk-wrap nk-wrap-nosidebar">
                <!-- content @s -->
                <div class="nk-content ">
                    <div class="nk-block nk-block-middle nk-auth-body wide-xs">
                        <div class="pb-4 text-center">
                            <a href="{{ route('dashboard') }}" class="logo-link">
                                <img class="logo-light logo-img logo-img-lg" src="{{ asset('images/logolykati.png') }}"
                                    srcset="{{ asset('images/logolykati.png') }}" alt="logo">
                                <img class="logo-dark logo-img logo-img-lg" src="{{ asset('images/logolykati.png') }}"
                                    srcset="{{ asset('images/logolykati.png') }}" alt="logo-dark">
                            </a>
                        </div>
                        <div class="card card-bordered">
                            <div class="card-inner card-inner-lg">
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h4 class="nk-block-title">@lang('Connexion')</h4>
                                        <div class="nk-block-des">
                                            <p>@lang('Accéder à votre espace de travail en vous connectant grace à vos
                                                paramètres de connexion.')</p>
                                        </div>
                                    </div>
                                    @if (session('status'))
                                        <br>
                                        <div class="alert alert-success">
                                            {{ session('status') }}
                                        </div>
                                    @endif
                                    @if (count($errors) > 0)
                                        <br>
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                                <form method="POST" class="form-validate" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="email">@lang('Email')</label>
                                        </div>
                                        <input type="email" class="form-control form-control-lg" required id="email"
                                            placeholder="@lang('Entrer votre email')" name="email" :value="old('email')"
                                            required autofocus>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="password">@lang('Mot de passe')</label>
                                            {{-- <a class="link link-primary link-sm" href="html/pages/auths/auth-reset-v2.html">Forgot Code?</a> --}}
                                        </div>
                                        <div class="form-control-wrap">
                                            <a href="#" class="form-icon form-icon-right passcode-switch"
                                                data-target="password">
                                                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                            </a>
                                            <input type="password" name="password" autocomplete="current-password"
                                                class="form-control form-control-lg" id="password"
                                                placeholder="@lang('Entrer votre mot de passe')" required>
                                        </div>
                                    </div>
                                    <div class="block mt-4">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="remember"
                                                id="remember_me">
                                            <label class="custom-control-label" for="remember_me">@lang('Gader ma session
                                                connecter')</label>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-start mt-4">
                                        @if (Route::has('password.request'))
                                            <a class="text-sm text-gray-600 underline hover:text-gray-900"
                                                href="{{ route('password.request') }}">
                                                @lang('Avez-vous oublié votre mot de passe?')
                                            </a>
                                        @endif
                                    </div><br>
                                    <div class="form-group">
                                        <button type="submit"
                                            class="btn btn-lg btn-primary btn-block">@lang('Valider')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- wrap @e -->
            </div>
            <!-- content @e -->
        </div>
        <!-- main @e -->
    </div>
@endsection
