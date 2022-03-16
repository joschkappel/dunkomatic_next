@extends('layouts.master')


@section('app_css')

<style>
    html, body {
        background-color: rgba(255, 255, 255, 0);
        color: #8e6d8e;
        font-family: 'Nunito', sans-serif;
        font-weight: 200;
        height: 100vh;
        margin: 0;
    }

    .full-height {
        height: 100vh;
    }

    .flex-center {
        align-items: center;
        display: flex;
        justify-content: center;
    }

    .position-ref {
        position: relative;
    }

    .top-right {
        position: absolute;
        right: 10px;
        top: 18px;
    }

    .content {
        text-align: center;
    }

    .title {
        font-size: 64px;
    }

    .links > a {
        color: #8e6d8e;
        padding: 0 25px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: .1rem;
        text-decoration: none;
        text-transform: uppercase;
    }
    .toplinks > a {
        color: #8e6d8e;
        padding: 0 25px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: .1rem;
        text-decoration: none;
        text-transform: uppercase;
    }

    .m-b-md {
        margin-bottom: 30px;
    }
</style>
@stop


@section('body')
        <div class="flex-center position-ref ">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-6 col-md-4 align-self-start">
                            <div class="card border-secondary bg-secondary text-white">
                                <img src="{{asset('img/'.config('dunkomatic.grafics.welcome_p', 'oops.jpg'))}}" class="card-img" alt="...">
                                <div class="card-img-overlay">
                                </div>
                            </div>
                        </div>
                        <div class="col-10 col-md-6 align-self-center">
                              <div class="title m-b-sm">
                                @yield('title_prefix', config('dunkomatic.title_prefix', ''))
                                @yield('title', config('dunkomatic.title', 'dunkomatic'))
                                @yield('title_postfix', config('dunkomatic.title_postfix', ''))
                              </div>
                              @yield('content')
                              <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="card border-0 p-3 mb-5">
                                        <div class="card-body">
                                            <a href="{{ route('welcome_signin', 'en') }}" class="card-link"><i class="flag-icon flag-icon-gb"></i></a>
                                            <a href="{{ route('welcome_signin', 'de') }}" class="card-link"><i class="flag-icon flag-icon-de"></i></a>
                                            @auth
                                            <a href="{{ route('home', ['language'=> app()->getLocale()]) }}" class="card-link">Home</a>
                                            @endauth
                                            <a href="https://www.hbv-basketball.de" class="card-link">Hessischer Basketball Verband</a>
                                        </div>
                                    </div>
                                </div>
                              </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
