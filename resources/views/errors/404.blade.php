@extends('layouts.templateerrors')

@section('contenu')
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- wrap @s -->
            <div class="nk-wrap nk-wrap-nosidebar">
                <!-- content @s -->
                <div class="nk-content ">
                    <div class="nk-block nk-block-middle wide-md mx-auto">
                        <div class="nk-block-content nk-error-ld text-center">
                            <img class="nk-error-gfx" src="./images/gfx/error-404.svg" alt="">
                            <div class="wide-xs mx-auto">
                                <h3 class="nk-error-title">Oups! Où êtes-vous ?</h3>
                                <p class="nk-error-text">Désolé mais aucune page n'est disponible pour cette adresse URL.
                                </p>
                                <a href="{{ route('dashboard') }}" class="btn btn-lg btn-primary mt-2">Retour à
                                    l'acceuil</a>
                            </div>
                        </div>
                    </div><!-- .nk-block -->
                </div>
                <!-- wrap @e -->
            </div>
            <!-- content @e -->
        </div>
        <!-- main @e -->
    </div>
@endsection
