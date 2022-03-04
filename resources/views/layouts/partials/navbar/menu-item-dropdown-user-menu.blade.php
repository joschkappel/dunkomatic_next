@php( $logout_url = View::getSection('logout_url') ?? config('dunkomatic.logout_url', 'logout') )
@php( $profile_url = View::getSection('profile_url') ?? config('dunkomatic.profile_url', 'profile') )

@php( $profile_url = $profile_url ? route($profile_url, ['language'=>app()->getLocale(),'user'=>Auth::user()]) : '' )
@php( $logout_url = $logout_url ? route($logout_url, app()->getLocale()) : '' )


<li class="nav-item dropdown user-menu">

    {{-- User menu toggler --}}
    <a href="#" class="nav-link dropdown-toggle " data-toggle="dropdown">
        @if (Auth::user()->avatar != null )
            <img src="{{ Auth::user()->avatar }}"
            class="user-image img-circle elevation-2"
            alt="{{ Auth::user()->name }}">
        @endif
        <span @if (Auth::user()->avatar != null ) class="d-none d-md-inline" @endif>
            {{ Auth::user()->name }}
        </span>
    </a>

    {{-- User menu dropdown --}}
    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

        {{-- User menu header --}}
        @if(!View::hasSection('usermenu_header') && config('menu.usermenu_header'))
            <li class="user-header {{ config('menu.usermenu_header_class', 'bg-primary') }}
                @if( Auth::user()->avatar == null ) h-auto @endif">
                @if(Auth::user()->avatar != null)
                    <img src="{{ Auth::user()->avatar }}"
                         class="img-circle elevation-2"
                         alt="{{ Auth::user()->name }}">
                @endif
                <p class="@if(!Auth::user()->avatar == null) mt-0 @endif">
                      {{ Auth::user()->name }}
                </p>
            </li>
        @else
            @yield('usermenu_header')
        @endif

        {{-- Configured user menu links --}}

        @each('layouts.partials.navbar.dropdown-item', app(\App\Menu::class)->menu("navbar-user"), 'item')

        {{-- User menu body --}}
        @hasSection('usermenu_body')
            <li class="user-body">
                @yield('usermenu_body')
            </li>
        @endif

        {{-- User menu footer --}}
        <li class="user-footer">
            @if($profile_url)
                <a href="{{ $profile_url }}" class="btn btn-default btn-flat">
                    <i class="fa fa-fw fa-user"></i>
                    {{ __('auth.profile') }}
                </a>
            @endif
            <a class="btn btn-default btn-flat float-right @if(!$profile_url) btn-block @endif"
               href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa fa-fw fa-power-off"></i>
                {{ __('auth.log_out') }}
            </a>
            <form id="logout-form" action="{{ $logout_url }}" method="POST" style="display: none;">
                @if(config('dunkomatic.logout_method'))
                    {{ method_field(config('dunkomatic.logout_method')) }}
                @endif
                {{ csrf_field() }}
            </form>
        </li>

    </ul>

</li>
