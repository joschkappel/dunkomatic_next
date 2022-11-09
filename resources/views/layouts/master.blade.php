<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    {{-- Base Meta Tags --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Custom Meta Tags --}}
    @yield('meta_tags')

    {{-- Title --}}
    <title>
        @yield('title_prefix', config('dunkomatic.title_prefix', ''))
        @yield('title', config('dunkomatic.title', 'dunkomatic'))
        @yield('title_postfix', config('dunkomatic.title_postfix', ''))
    </title>

    {{-- Custom stylesheets  --}}
    <style>
        .welcomepage {
            height: 100vh;
            min-height: 500px;
            background-image: url('{{asset('img/'.config('dunkomatic.grafics.welcome_l', 'oops.jpg'))}}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat
        }
        @media only screen and (orientation: landscape){
            .welcomepage{
                background-image:url('{{asset('img/'.config('dunkomatic.grafics.welcome_l', 'oops.jpg'))}}');
            }
        }
        @media only screen (orientation: portrait){
            .welcomepage{
                background-image:url('{{asset('img/'.config('dunkomatic.grafics.welcome_p', 'oops.jpg'))}}');
            }
        }
        </style>
    @yield('app_css_pre')

    {{-- Base Stylesheets --}}
   <link rel="stylesheet" href="{{ asset('css/app.css') }}">
   <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">
    {{-- livewire style --}}
    @livewireStyles
    @yield('app_css')

    {{-- Favicon --}}
    @if(config('dunkomatic.use_ico_only'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
    @elseif(config('dunkomatic.use_full_favicon'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicons/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicons/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicons/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicons/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicons/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicons/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicons/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicons/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicons/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('favicons/android-icon-192x192.png') }}">
        <link rel="manifest" href="{{ asset('favicons/manifest.json') }}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    @endif



</head>

<body class="@yield('classes_body')" @yield('body_data')>

    {{-- Body Content --}}
    @yield('body')

    {{-- Base Scripts --}}
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/vendor.js') }}"></script>

    {{-- Custom Scripts --}}
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip({delay: { show: 1200, hide: 100 }, placement: 'auto'});

            toastr.options.closeButton = true;
            toastr.options.newestOnTop = true;
            toastr.options.progressBar = true;
            toastr.options.positionClass = "toast-top-center";
            toastr.options.preventDuplicates = false;
            toastr.options.showDuration = 200;
            toastr.options.hideDuration = 500;
            toastr.options.timeOut = 5000;
            toastr.options.extendedTimeOut = 500;
            toastr.options.showEasing = "swing";
            toastr.options.hideEasing = "linear";
            toastr.options.showMethod = "fadeIn";
            toastr.options.hideMethod = "fadeOut";
        })
    </script>
    @livewireScripts
    @livewireChartsScripts
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    @yield('app_js')

</body>

</html>
