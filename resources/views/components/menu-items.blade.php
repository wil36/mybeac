@props(['route', 'sub', 'icon', 'childrens', 'routes'])

<li class="nk-menu-item has-sub {{ currentRouteActive($routes) }}">
    <a href="{{ route($route) }}" class="nk-menu-link @isset($childrens) nk-menu-toggle @endisset">
        <span class="nk-menu-icon"><em class="{{ $icon }}"></em></span>
        <span class="nk-menu-text">@lang($sub)</span>
    </a>
    @isset($childrens)
        <ul class="nk-menu-sub">
            @foreach ($childrens as $item => $c)
                @if ($c['role'] === auth()->user()->role ||
        auth()->user()->isAdmin())
                    <li class="nk-menu-item {{ currentRouteActive($c['route']) }}">
                        <a href="{{ route($c['route']) }}" class="nk-menu-link"><span
                                class="nk-menu-text">@lang($c['name'])</span></a>
                    </li>
                @endif
            @endforeach
        </ul><!-- .nk-menu-sub -->
    @endisset
</li><!-- .nk-menu-item -->
