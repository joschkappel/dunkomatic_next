@inject('menuItemHelper', 'App\Helpers\MenuItemHelper')

@if ($menuItemHelper->isSearchBar($item))

    {{-- Search form --}}
    @include('layouts.partials.navbar.menu-item-search-form')

@elseif ($menuItemHelper->isSubmenu($item))

    {{-- Dropdown menu --}}
    @include('layouts.partials.navbar.menu-item-dropdown-menu')

@elseif ($menuItemHelper->isLink($item))

    {{-- Link --}}
    @include('layouts.partials.navbar.menu-item-link')

@endif
