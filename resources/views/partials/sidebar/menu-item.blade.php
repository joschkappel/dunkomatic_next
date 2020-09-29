@inject('menuItemHelper', \App\Helpers\MenuItemHelper)

@if ($menuItemHelper->isHeader($item))

    {{-- Header --}}
    <li @if(isset($item['id'])) id="{{ $item['id'] }}" @endif class="nav-header">
        {{ is_string($item) ? $item : $item['header'] }}
    </li>

@elseif ($menuItemHelper->isSearchBar($item))

    {{-- Search form --}}
    @include('partials.sidebar.menu-item-search-form')

@elseif ($menuItemHelper->isSubmenu($item))

    {{-- Treeview menu --}}
    @include('partials.sidebar.menu-item-treeview-menu')

@elseif ($menuItemHelper->isLink($item))

    {{-- Link --}}
    @include('partials.sidebar.menu-item-link')

@endif
