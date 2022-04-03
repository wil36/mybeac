{{-- <x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        @if (session('status'))
            <div class="mb-4 text-sm font-medium text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <x-jet-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="block">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')"
                    required autofocus />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <x-jet-button>
                    {{ __('Email Password Reset Link') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout> --}}


@extends('layouts.templateerrors')

@section('title')
	@lang('Ma Beac')
@endsection

@section('contenu')
	<div class="nk-app-root">
		<!-- main @s -->
		<div class="nk-main">
			<!-- wrap @s -->
			<div class="nk-wrap nk-wrap-nosidebar">
				<!-- content @s -->

				<div class="nk-content">
					<div class="nk-block nk-block-middle nk-auth-body wide-xs">
						<div class="brand-logo pb-4 text-center">
							<a href="{{ route('dashboard') }}" class="logo-link">
								<img class="logo-light logo-img logo-img-lg" src="{{ asset('images/logo.png') }}"
									srcset="{{ asset('images/logo.png') }}" alt="logo">
								<img class="logo-dark logo-img logo-img-lg" src="{{ asset('images/logo.png') }}"
									srcset="{{ asset('images/logo.png') }}" alt="logo-dark">
							</a>
						</div>
						<div class="card">
							<div class="card-inner card-inner-lg">
								<div class="nk-block-head">
									<div class="nk-block-head-content">
										<h5 class="nk-block-title">@lang('Rénitialiser votre mot de passe')</h5>
										<div class="nk-block-des">
											<p>@lang('Si vous avez oublié votre mot de passe, vous pouvez entré votre email
												dans le champ ci-dessous et nous enverons un lien de renitialisation de ce
												dernier.')</p>
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
								<form method="POST" class="form-validate" action="{{ route('password.email') }}">
									@csrf
									<div class="form-group">
										<div class="form-label-group">
											<label class="form-label" for="email">@lang('Email')</label>
										</div>
										<input class="form-control form-control-lg" id="email" type="email" name="email" :value="old('email')" required
											autofocus placeholder="@lang('Entrer votre email')">
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-lg btn-primary btn-block">@lang('Envoyer le
											lien de
											renitialisation')</button>
									</div>
								</form>
								<div class="form-note-s2 pt-4 text-center">
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
