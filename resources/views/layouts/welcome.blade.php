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
        font-size: 84px;
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


@php( $login_url = View::getSection('login_url') ?? config('dunkomatic.login_url', 'login') )
@php( $register_url = View::getSection('register_url') ?? config('dunkomatic.register_url', 'register') )
@php( $password_reset_url = View::getSection('password_reset_url') ?? config('dunkomatic.password_reset_url', 'password/reset') )

@php( $login_url = $login_url ? route($login_url, app()->getLocale() ) : '' )
@php( $register_url = $register_url ? route($register_url, app()->getLocale()) : '' )
@php( $password_reset_url = $password_reset_url ? route($password_reset_url, [app()->getLocale(),'']) : '' )


@section('body')
        <div class="flex-center position-ref full-height">
            <div class="top-right toplinks">
                <a href="{{ route('welcome_signin', 'en') }}" ><i class="flag-icon flag-icon-gb"></i></a>
                <a href="{{ route('welcome_signin', 'de') }}" ><i class="flag-icon flag-icon-de"></i></a>
            @auth
                <a href="{{ route('home', ['language'=> app()->getLocale()]) }}">Home</a>
            @endauth
                <a href="https://www.hbv-basketball.de">Hessischer Basketball Verband</a>
            </div>

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
                        <div class="col-12 col-md-8 align-self-center">
                              <div class="title m-b-md">
                                @yield('title_prefix', config('dunkomatic.title_prefix', ''))
                                @yield('title', config('dunkomatic.title', 'dunkomatic'))
                                @yield('title_postfix', config('dunkomatic.title_postfix', ''))
                              </div>
                              @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
