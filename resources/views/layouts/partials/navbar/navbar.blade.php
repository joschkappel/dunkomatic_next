<nav class="main-header navbar
    {{ config('menu.classes_topnav_nav', 'navbar-expand') }}
    {{ config('menu.classes_topnav', 'navbar-white navbar-light') }}">

    {{-- Navbar left links --}}
    <ul class="navbar-nav">
        {{-- Left sidebar toggler link --}}
        @include('layouts.partials.navbar.menu-item-left-sidebar-toggler')
        {{-- Configured left links --}}
        @each('layouts.partials.navbar.menu-item', app(\App\Menu::class)->menu('navbar-left'), 'item')

        {{-- Custom left links --}}
        @yield('content_top_nav_left')
    </ul>

    {{-- Navbar right links --}}
    <ul class="navbar-nav ml-auto">
        {{-- Custom right links --}}
        @yield('content_top_nav_right')

        {{-- Configured right links --}}
        {{-- dd( app(\App\Menu::class)->menu() )--}}
        @each('layouts.partials.navbar.menu-item', app(\App\Menu::class)->menu('navbar-right'), 'item')

        {{-- User menu link --}}
        @if(Auth::user())
            @if(config('menu.usermenu_enabled'))
                @include('layouts.partials.navbar.menu-item-dropdown-user-menu')
            @else
                @include('layouts.partials.navbar.menu-item-logout-link')
            @endif
        @endif

        {{-- Right sidebar toggler link --}}
        @if(config('menu.right_sidebar'))
            @include('layouts.partials.navbar.menu-item-right-sidebar-toggler')
        @endif
    </ul>

</nav>
