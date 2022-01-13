{{-- <x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="block">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="block w-full mt-1" type="email" name="email"
                    :value="old('email', $request->email)" required autofocus />
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="block w-full mt-1" type="password" name="password" required
                    autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-jet-input id="password_confirmation" class="block w-full mt-1" type="password"
                    name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-jet-button>
                    {{ __('Reset Password') }}
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
                        <div class="pb-4 text-center brand-logo">
                            <a href="{{ route('dashboard') }}" class="logo-link">
                                <img class="logo-light logo-img logo-img-lg" src="{{ asset('images/logo.png') }}"
                                    srcset="{{ asset('images/logo.png') }}" alt="logo">
                                <img class="logo-dark logo-img logo-img-lg" src="{{ asset('images/logo.png') }}"
                                    srcset="{{ asset('images/logolykati.png') }}" alt="logo-dark">
                            </a>
                        </div>
                        <div class="card">
                            <div class="card-inner card-inner-lg">
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h5 class="nk-block-title">@lang('Rénitialisation du mot de passe')</h5>

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
                                <form method="POST" class="form-validate" action="{{ route('password.update') }}">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="email">@lang('Email')</label>
                                        </div>
                                        <input class="form-control form-control-lg" id="email" type="email" name="email"
                                            value="{{ old('email', $request->email) }}" required autofocus
                                            placeholder="@lang('Entrer votre mot de passe')">
                                    </div>
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="password">@lang('Mot de Passe')</label>
                                        </div>
                                        <input class="form-control form-control-lg" id="password" type="password"
                                            name="password" required autocomplete="new-password"
                                            placeholder="@lang('Entrer votre mot de passe')">
                                    </div>
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="password_confirmation">@lang('Confirmation
                                                mot de
                                                passe')</label>
                                        </div>
                                        <input class="form-control form-control-lg" id="password_confirmation"
                                            type="password" name="password_confirmation" required
                                            autocomplete="new-password" placeholder="@lang('Confirmer votre mot de passe')">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-lg btn-primary btn-block">@lang('Renitialiser
                                            le mot de
                                            passe')</button>
                                    </div>
                                </form>
                                <div class="pt-4 text-center form-note-s2">
                                    <a href="{{ route('login') }}"><strong>@lang('Retourner à la
                                            connexion')</strong></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content @e -->
        </div>
        <!-- main @e -->
    </div>
@endsection
