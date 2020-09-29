<aside class="main-sidebar {{ config('menu.classes_sidebar', 'sidebar-dark-primary elevation-4') }}">

    {{-- Sidebar brand logo --}}
    @if(config('dunkomatic.logo_img_xl'))
        @include('partials.common.brand-logo-xl')
    @else
        @include('partials.common.brand-logo-xs')
    @endif

    {{-- Sidebar menu --}}
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('menu.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu"
                @if(config('menu.sidebar_nav_animation_speed') != 300)
                    data-animation-speed="{{ config('menu.sidebar_nav_animation_speed') }}"
                @endif
                @if(!config('menu.sidebar_nav_accordion'))
                    data-accordion="false"
                @endif>
                {{-- Configured sidebar links --}}
                @each('partials.sidebar.menu-item', $menu->menu('sidebar'), 'item')
            </ul>
        </nav>
    </div>

</aside>
