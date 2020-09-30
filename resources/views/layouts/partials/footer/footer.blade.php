<footer class="main-footer">
    @yield('footer')
    <div class="float-right d-none d-sm-block">
        <b>Version</b> {{ config('app.version')}}
    </div>
    <strong>&copy; Copyright 2020 <a href="{{ config('app.creator_url')}}">w.p.o. projects</a>.</strong> All rights
    reserved.
</footer>
