<li class="nav-item">
    <a class="nav-link" data-widget="pushmenu" href="#"
        @if(config('menu.sidebar_collapse_remember'))
            data-enable-remember="true"
        @endif
        @if(!config('menu.sidebar_collapse_remember_no_transition'))
            data-no-transition-after-reload="false"
        @endif
        @if(config('menu.sidebar_collapse_auto_size'))
            data-auto-collapse-size="{{ config('menu.sidebar_collapse_auto_size') }}"
        @endif>
        <i class="fas fa-bars"></i>
        <span class="sr-only">{{ __('auth.toggle_navigation') }}</span>
    </a>
</li>
