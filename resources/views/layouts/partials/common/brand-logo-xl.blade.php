@inject('layoutHelper', 'App\Helpers\LayoutHelper')

@php( $dashboard_url = View::getSection('dashboard_url') ?? config('dunkomatic.dashboard_url', 'home') )

@php( $dashboard_url = $dashboard_url ? route($dashboard_url, app()->getLocale()) : '' )

<a href="{{ $dashboard_url }}"
    @if($layoutHelper->isLayoutTopnavEnabled())
        class="navbar-brand logo-switch"
    @else
        class="brand-link logo-switch"
    @endif>

    {{-- Small brand logo --}}
    <img src="{{ asset(config('menu.logo_img')) }}"
         alt="{{ config('menu.logo_img_alt', '') }}"
         class="{{ config('menu.logo_img_class', 'brand-image-xl') }} logo-xs">

    {{-- Large brand logo --}}
    <img src="{{ asset(config('menu.logo_img_xl')) }}"
         alt="{{ config('menu.logo_img_alt', '') }}"
         class="{{ config('menu.logo_img_xl_class', 'brand-image-xs') }} logo-xl">

</a>
