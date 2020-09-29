@php( $logout_url = View::getSection('logout_url') ?? config('dunkomatic.logout_url', 'logout') )

@php( $logout_url = $logout_url ? route($logout_url,app()->getLocale()) : '' )

<li class="nav-item">
    <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fa fa-fw fa-power-off"></i>
        {{ __('auth.log_out') }}
    </a>
    <form id="logout-form" action="{{ $logout_url }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
</li>
