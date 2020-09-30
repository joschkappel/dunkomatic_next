@inject('layoutHelper', \App\Helpers\LayoutHelper)

@php( $dashboard_url = View::getSection('dashboard_url') ?? config('dunkomatic.dashboard_url', 'home') )

@php( $dashboard_url = $dashboard_url ? route($dashboard_url, app()->getLocale()) : '' )

<a href="{{ $dashboard_url }}"
    @if($layoutHelper->isLayoutTopnavEnabled())
        class="navbar-brand {{ config('menu.classes_brand') }}"
    @else
        class="brand-link {{ config('menu.classes_brand') }}"
    @endif>

    {{-- Small brand logo --}}
    <img src="{{ asset(config('menu.logo_img')) }}"
         alt="{{ config('menu.logo_img_alt', '') }}"
         class="{{ config('menu.logo_img_class', 'brand-image img-circle elevation-3') }}"
         style="opacity:.8">

    {{-- Brand text --}}
    <span class="brand-text font-weight-light {{ config('menu.classes_brand_text') }}">
        {!! config('menu.logo') !!}
    </span>

</a>
