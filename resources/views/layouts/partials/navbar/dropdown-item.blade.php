@inject('menuItemHelper', 'App\Helpers\MenuItemHelper')

@if ($menuItemHelper->isSubmenu($item))

    {{-- Dropdown submenu --}}
    @include('layouts.partials.navbar.dropdown-item-submenu')

@elseif ($menuItemHelper->isLink($item))

    {{-- Dropdown link --}}
    @include('layouts.partials.navbar.dropdown-item-link')

@endif
