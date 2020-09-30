@extends('layouts.master')

@inject('layoutHelper', \App\Helpers\LayoutHelper)

@if($layoutHelper->isLayoutTopnavEnabled())
    @php( $def_container_class = 'container' )
@else
    @php( $def_container_class = 'container-fluid' )
@endif

@section('app_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body', $layoutHelper->makeBodyClasses())

@section('body_data', $layoutHelper->makeBodyData())

@section('body')
    <div class="wrapper">

        {{-- Top Navbar --}}
        @if($layoutHelper->isLayoutTopnavEnabled())
            @include('layouts.partials.navbar.navbar-layout-topnav')
        @else
            @include('layouts.partials.navbar.navbar')
        @endif

        {{-- Left Main Sidebar --}}
        @if(!$layoutHelper->isLayoutTopnavEnabled())
            @include('layouts.partials.sidebar.left-sidebar')
        @endif

        {{-- Content Wrapper --}}
        <div class="content-wrapper {{ config('menu.classes_content_wrapper') ?? '' }}">

            {{-- Content Header --}}
            <div class="content-header">
                <div class="{{ config('menu.classes_content_header') ?: $def_container_class }}">
                    @yield('content_header')
                </div>
            </div>

            {{-- Main Content --}}
            <div class="content">
                <div class="{{ config('menu.classes_content') ?: $def_container_class }}">
                    @yield('content')
                </div>
            </div>

        </div>

        {{-- Footer --}}
        @hasSection('footer')
            @include('layouts.partials.footer.footer')
        @endif

        {{-- Right Control Sidebar --}}
        @if(config('menu.right_sidebar'))
            @include('layouts.partials.sidebar.right-sidebar')
        @endif

    </div>
@stop

@section('app_js')
    @stack('js')
    @yield('js')
@stop
