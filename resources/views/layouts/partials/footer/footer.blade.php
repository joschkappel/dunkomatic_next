<footer class="main-footer">
    @yield('footer')
    <div class="float-right d-none d-sm-block">
        <b>Version</b> {{ config('app.version')}}
    </div>
    <div class="float-left d-none d-sm-block">
        <div class="d-inline-flex ">
        <strong>&copy; Copyright 2020 <a href="{{ config('app.creator_url')}}">w.p.o. projects</a>.</strong> All rights
    reserved.
        <div class="dot-bouncing"></div>
    </div>

    </div>
</footer>
