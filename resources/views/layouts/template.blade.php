@php $locale = session()->get('locale'); @endphp
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <base href="../">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description"
        content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{!! asset('images/favicon.png') !!}">
    <!-- Page Title  -->
    <title>Ma Beac</title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{!! asset('assets/css/dashlite.css?ver=2.2.0') !!}">
    <link id="skin-default" rel="stylesheet" href="{!! asset('assets/css/theme.css?ver=2.2.0') !!}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    @yield('css')
</head>

<body @php
    $userinfo = Auth::user();
@endphp
    class="no-touch nk-nio-theme                                                       
                                 @if ($userinfo->theme == 1)
    dark-mode
    @endif">
    ">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <div class="nk-sidebar nk-sidebar-fixed is-light " data-content="sidebarMenu">
                <div class="nk-sidebar-element nk-sidebar-head">
                    <div class="nk-sidebar-brand">
                        <a href="{{ route('dashboard') }}" class="logo-link">
                            <img class="logo-light logo-img logo-img-lg" src="{{ asset('images/logo.png') }}"
                                srcset="{{ asset('images/logo.png') }}" alt="logo">
                            <img class="logo-dark logo-img logo-img-lg" src="{{ asset('images/logo.png') }}"
                                srcset="{{ asset('images/logo.png') }}" alt="logo-dark">
                            {{-- <img class="logo-small logo-img logo-img-small" src="{{ asset('images/logo.png') }}"
                                srcset="{{ asset('images/logo.png') }}" alt="logo-small"> --}}
                        </a>
                    </div>
                    <div class="nk-menu-trigger mr-n2">
                        <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu"><em
                                class="icon ni ni-menu-alt-left"></em></a>
                        <a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex"
                            data-target="sidebarMenu"><em class="icon ni ni-menu-alt-left"></em></a>
                    </div>
                </div><!-- .nk-sidebar-element -->
                <div class="nk-sidebar-element">
                    <div class="nk-sidebar-content">
                        <div class="nk-sidebar-menu" data-simplebar>
                            <ul class="nk-menu">

                                <li class="nk-menu-heading">
                                    <h6 class="overline-title text-primary-alt">@lang('Menu Principal')</h6>
                                </li><!-- .nk-menu-heading -->
                                @php
                                    $affiche = false;
                                @endphp
                                @foreach (config('menu') as $name => $elements)
                                    @if ($elements['role'] === auth()->user()->role ||
    auth()->user()->isAdmin())
                                        @if ($elements['role'] == 'admin' && $affiche == false)
                                            <li class="nk-menu-heading">
                                                {{-- <h6 class="overline-title text-primary-alt">@lang('Administration')
                                                </h6> --}}
                                            </li><!-- .nk-menu-heading -->
                                            @php
                                                $affiche = true;
                                            @endphp
                                        @endif
                                        @isset($elements['childrens'])
                                            <x-menu-items :icon="$elements['icon']" :route="$elements['route']"
                                                :sub="$elements['name']" :routes="$elements['routes']"
                                                :childrens="$elements['childrens']">
                                            </x-menu-items>
                                        @else
                                            <x-menu-items :icon="$elements['icon']" :route="$elements['route']"
                                                :sub="$elements['name']" :routes="$elements['routes']">
                                            </x-menu-items>
                                        @endisset
                                    @endif
                                @endforeach
                        </div><!-- .nk-sidebar-menu -->
                    </div><!-- .nk-sidebar-content -->
                </div><!-- .nk-sidebar-element -->
            </div>
            <!-- wrap @s -->
            <div class="nk-wrap ">
                <div class="nk-header nk-header-fixed nk-header-fluid is-light">
                    <div class="container-fluid">
                        <div class="nk-header-wrap">
                            <div class="nk-menu-trigger d-xl-none ml-n1">
                                <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em
                                        class="icon ni ni-menu-alt-left"></em></a>
                            </div>
                            <div class="nk-header-brand d-xl-none">
                                <a href="{{ route('dashboard') }}" class="logo-link">
                                    <img class="logo-light logo-img logo-img-lg" src="{{ asset('images/logo.png') }}"
                                        srcset="{{ asset('images/logo.png') }}" alt="logo">
                                    <img class="logo-dark logo-img logo-img-lg" src="{{ asset('images/logo.png') }}"
                                        srcset="{{ asset('images/logo.png') }}" alt="logo-dark">
                                </a>
                            </div><!-- .nk-header-brand -->
                            <div class="ml-3 nk-header-search ml-xl-0">
                                <h4>@lang($title)</h4>
                            </div><!-- .nk-header-news -->
                            <div class="nk-header-tools">
                                <ul class="nk-quick-nav">
                                    {{-- <li class="nav-item dropdown">
                                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                            @switch($locale)
                                                @case('en')
                                                    <img src="{{ asset('images/usa.png') }}" width="25px">
                                                    @lang('Anglais')
                                                @break
                                                @case('fr')
                                                    <img src="{{ asset('images/france.png') }}" width="25px">
                                                    @lang('Français')
                                                @break
                                                @default
                                                    <img src="{{ asset('images/france.png') }}" width="25px">
                                                    @lang('Français')
                                            @endswitch
                                            <span class="caret"></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item" href="{{ route('lang', 'en') }}"><img
                                                    src="{{ asset('images/usa.png') }}" width="25px">
                                                @lang('Anglais')</a>
                                            <a class="dropdown-item" href="{{ route('lang', 'fr') }}"><img
                                                    src="{{ asset('images/france.png') }}" width="25px">
                                                @lang('Français')</a>
                                        </div>
                                    </li> --}}

                                    <li class="dropdown user-dropdown">
                                        <a href="#" class="dropdown-toggle mr-n1" data-toggle="dropdown">
                                            <div class="user-toggle">
                                                <span style="margin-right: 10px;">
                                                    {{ $userinfo->nom }}
                                                    {{ $userinfo->prenom }}</span>
                                                <div class="user-avatar sm">
                                                    <img class="object-cover w-8 h-8 rounded-full"
                                                        src="{{ isset($userinfo->profile_photo_path)? asset('picture_profile/' . $userinfo->profile_photo_path): 'https://ui-avatars.com/api/?name=' . $userinfo->nom . '&background=1ee0ac&size=150&color=fff' }}"
                                                        alt="" />
                                                </div>
                                            </div>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                                            <div class="dropdown-inner user-card-wrap bg-lighter">
                                                <div class="user-card">
                                                    <div class="user-avatar">
                                                        <img class="object-cover w-8 h-8 rounded-full"
                                                            src="{{ isset($userinfo->profile_photo_path)? asset('picture_profile/' . $userinfo->profile_photo_path): 'https://ui-avatars.com/api/?name=' . $userinfo->nom . '&background=1ee0ac&size=150&color=fff' }}"
                                                            alt="" />
                                                    </div>
                                                    <div class="user-info">
                                                        <span class="lead-text">{{ $userinfo->nom }}
                                                            {{ $userinfo->prenom }}</span>
                                                        <span class="sub-text">{{ $userinfo->email }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="dropdown-inner">
                                                <ul class="link-list">
                                                    <li><a href="{{ route('profile.show') }}"><em
                                                                class="icon ni ni-lock"></em><span>@lang('Sécurité')</span></a>
                                                    </li>
                                                    <li id="dark1"><a class="dark-switch" href="#"><em
                                                                class="icon ni ni-moon"></em><span> @lang('Mode
                                                                Dark')</span></a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="dropdown-inner">
                                                <ul class="link-list">
                                                    <form method="POST" action="{{ route('logout') }}">
                                                        @csrf
                                                        <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                            this.closest('form').submit();"><em
                                                                    class="icon ni ni-signout"></em><span>@lang('Deconnexion')</span></a>
                                                        </li>

                                                    </form>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div><!-- .nk-header-wrap -->
                    </div><!-- .container-fliud -->
                </div>
                @yield('contenu')
            </div>
        </div>
    </div>

    {{-- Script js --}}
    <script src="{!! asset('assets/js/bundle.js?ver=2.2.0') !!}"></script>
    <script src="{!! asset('assets/js/scripts.js?ver=2.2.0') !!}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('.active').removeClass('.active');
        });
        $("#dark1").click(function() {
            $.ajax({
                type: 'GET',
                url: '{{ route('theme') }}',
                success: function(data) {},
                error: function() {
                    console.error(data);
                }
            });
        });
    </script>
    @yield('script')

</body>

</html>
