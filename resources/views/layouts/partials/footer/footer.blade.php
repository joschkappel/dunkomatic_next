<footer class="main-footer">
    @yield('footer')
    <div class="float-right d-none d-sm-block">
        <b>Version </b><a href="{{ route('release_info', ['language'=>app()->getLocale()])}}"> {{ config('app.version')}}</a>
    </div>
    <div class="float-left d-none d-sm-block">
        <div class="d-inline-flex ">
            {{-- <div class="dot-bouncing mx-2"></div> --}}
            <div class="mx-2">&copy; Copyright 2021 by<a href="{{ config('app.creator_url')}}"> jochen kappel </a>. All rights reserved.</div>
            {{-- <div class="dot-rolling mx-4"></div> --}}
            <div class="mx-4"><i class="fas fa-basketball-ball text-orange"></i></div>
            <div class="mx-2"><a href="{{ route('impressum', ['language'=>app()->getLocale()])}}" >@lang('About Us')</a></div>
            {{-- <div class="dot-rolling mx-4"></div> --}}
            <div class="mx-4"><i class="fas fa-basketball-ball text-orange"></i></div>
            <div class="mx-2"><a href="{{ route('dsgvo', ['language'=>app()->getLocale()])}}" >@lang('Data Privacy')</a></div>
            <div class="mx-4"><i class="fas fa-basketball-ball text-orange"></i></div>
            <div class="mx-2"><a href="{{ route('faq', ['language'=>app()->getLocale()])}}" >FAQ</a></div>
            <div class="mx-4"><i class="fas fa-basketball-ball text-orange"></i></div>
            <div class="mx-2"><a href="{{ route('contact', ['language'=>app()->getLocale()])}}" >@lang('Contact')</a></div>
        </div>
    </div>
</footer>
