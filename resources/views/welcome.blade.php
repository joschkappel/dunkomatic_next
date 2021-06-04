<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>
          @yield('title_prefix', config('dunkomatic.title_prefix', ''))
          @yield('title', config('dunkomatic.title', 'dunkomatic'))
          @yield('title_postfix', config('dunkomatic.title_postfix', ''))
        </title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('vendor/flag-icon-css/css/flag-icon.min.css') }}">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

        <!-- Styles -->
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
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right toplinks">
                      <a href="{{ route('welcome', 'en') }}" ><i class="flag-icon flag-icon-gb"></i></a>
                      <a href="{{ route('welcome', 'de') }}" ><i class="flag-icon flag-icon-de"></i></a>
                    @auth
                        <a href="{{ route('home', ['language'=> app()->getLocale()]) }}">Home</a>
                    @else

                        <a href="{{ route('login', app()->getLocale()) }}">{{ __('auth.sign_in') }}</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register', app()->getLocale()) }}">{{ __('auth.register') }}</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-6 col-md-4 align-self-start">
                            <div class="card border-secondary bg-secondary text-white">
                                <img src="{{asset('img/'.config('dunkomatic.grafics.welcome', 'oops.jpg'))}}" class="card-img" alt="...">
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

                              <div class="links">
                                    <a href="https://www.hbv-basketball.de">HBV</a>
                                    <a href="https://www.hbv-basketball.de">Bundeslige</a>
                                    <a href="https://www.hbv-basketball.de">DBB</a>
                              </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
