<?php

return [
  'logo' => 'Dunk-<b>O</b>-Matic',
  'logo_img' => 'img/basket.png',
  'logo_img_class' => 'brand-image img-circle elevation-3',
  'logo_img_xl' => null,
  'logo_img_xl_class' => 'brand-image-xs',
  'logo_img_alt' => 'DunkOMatic',

  'layout_topnav' => false,
  'layout_boxed' => false,
  'layout_fixed_sidebar' => null,
  'layout_fixed_navbar' => null,
  'layout_fixed_footer' => true,

  'classes_body' => '',
  'classes_brand' => '',
  'classes_brand_text' => '',
  'classes_content_wrapper' => '',
  'classes_content_header' => '',
  'classes_content' => 'content-light',
  'classes_sidebar' => 'sidebar-dark-primary elevation-4',
  'classes_sidebar_nav' => '',
  'classes_topnav' => 'navbar-dark navbar-light',
  'classes_topnav_nav' => 'navbar-expand-md',
  'classes_topnav_container' => 'container',

  'sidebar_mini' => 'md',
  'sidebar_collapse' => true,
  'sidebar_collapse_auto_size' => false,
  'sidebar_collapse_remember' => true,
  'sidebar_collapse_remember_no_transition' => true,
  'sidebar_scrollbar_theme' => 'os-theme-light',
  'sidebar_scrollbar_auto_hide' => 'm',
  'sidebar_nav_accordion' => true,
  'sidebar_nav_animation_speed' => 300,

  // https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration

  'right_sidebar' => false,
  'right_sidebar_icon' => 'fas fa-cogs',
  'right_sidebar_theme' => 'dark',
  'right_sidebar_slide' => true,
  'right_sidebar_push' => true,
  'right_sidebar_scrollbar_theme' => 'os-theme-light',
  'right_sidebar_scrollbar_auto_hide' => 'l',
  'menu' => [ ],
  'filters' => [
    App\Menu\Filters\HrefFilter::class,
    App\Menu\Filters\SearchFilter::class,
    App\Menu\Filters\ActiveFilter::class,
    App\Menu\Filters\ClassesFilter::class,
    App\Menu\Filters\GateFilter::class,
    App\Menu\Filters\LangFilter::class,
    App\Menu\Filters\DataFilter::class,
  ],
  'usermenu_enabled' => true,
  'usermenu_header' => true,
  'usermenu_header_class' => 'bg-primary',
  'usermenu_image' => false,
  'usermenu_desc' => false,
  'usermenu_profile_url' => true,


];
