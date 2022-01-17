<div class="container-fluid welcomepage">
    <div class="container h-100">
        <div class="row h-30 align-items-center">
            <div class="col-12 text-center p-2 bg-transparent text-dark">
                <h1 class="font-weight-light"><a  class="text-dark font-weight-bold" href="{{ route('home',app()->getLocale()) }}">
                <div class="title m-b-md">
                    @yield('title_prefix', config('dunkomatic.title_prefix', ''))
                    @yield('title', config('dunkomatic.title', 'dunkomatic'))
                    @yield('title_postfix', config('dunkomatic.title_postfix', ''))
                  </div>
                </a></h1>
                <p class="lead">{{config('dunkomatic.welcome')}}</p>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="card shadow-lg p-3 mb-5">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>
