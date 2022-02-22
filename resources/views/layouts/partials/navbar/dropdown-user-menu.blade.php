@php( $logout_url = View::getSection('logout_url') ?? config('dunkomatic.logout_url', 'logout') )
@php( $profile_url = View::getSection('profile_url') ?? config('dunkomatic.profile_url', 'logout') )

@if (config('menu.use_route_url', false))
    @php( $profile_url = $profile_url ? route($profile_url) : '' )
    @php( $logout_url = $logout_url ? route($logout_url, app()->getLocale()) : '' )
@else
    @php( $profile_url = $profile_url ? url($profile_url) : '' )
    @php( $logout_url = $logout_url ? url($logout_url) : '' )
@endif

<li class="nav-item dropdown user-menu">

    {{-- User menu toggler --}}
    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
        <span>
            {{ Auth::user()->name }}
        </span>
    </a>

    {{-- User menu dropdown --}}
    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

        {{-- User menu header --}}
        @if(!View::hasSection('usermenu_header') && config('menu.usermenu_header'))
            <li class="user-header {{ config('menu.usermenu_header_class', 'bg-primary') }}
                @if(!config('menu.usermenu_image')) h-auto @endif">
                <p class="@if(!config('menu.usermenu_image')) mt-0 @endif">
                    {{ Auth::user()->name }}
                </p>
            </li>
        @else
            @yield('usermenu_header')
        @endif


        {{-- Configured user menu links --}}
        @each('layouts.partials.menuitems.menu-item-top-nav-user', app(\App\Menu::class)->menu(), 'item')

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
                @if(config('menu.logout_method'))
                    {{ method_field(config('menu.logout_method')) }}
                @endif
                {{ csrf_field() }}
            </form>
        </li>

    </ul>

</li>
