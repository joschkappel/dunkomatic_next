@if((config('menu.layout_topnav') or (isset($item['topnav']) && $item['topnav'])) && (!isset($item['topnav_right']) || (isset($item['topnav_right']) && !$item['topnav_right'])))
  @include('layouts.partials.menuitems.menu-item-top-nav', $item)
@endif
