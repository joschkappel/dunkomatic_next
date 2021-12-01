@extends('layouts.master')

@section('app_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body', 'register-page')

@php( $login_url = View::getSection('login_url') ?? config('dunkomatic.login_url', 'login') )
@php( $register_url = View::getSection('register_url') ?? config('dunkomatic.register_url', 'register') )
@php( $dashboard_url = View::getSection('dashboard_url') ?? config('dunkomatic.dashboard_url', 'start') )

@php( $login_url = $login_url ? route($login_url, app()->getLocale()) : '' )
@php( $register_url = $register_url ? route($register_url, app()->getLocale()) : '' )
@php( $dashboard_url = $dashboard_url ? route('start') : '' )

@section('body')
<div class="container-fluid">
    <div class="row justify-content-between">
        <div class="col-6 col-md-4 align-self-start">
            <div class="card border-secondary bg-secondary text-white">
                <img src="{{asset('img/'.config('dunkomatic.grafics.welcome', 'oops.jpg'))}}" class="card-img" alt="...">
                <div class="card-img-overlay">
                </div>
            </div>
        </div>
        <div class="col-6 align-self-center">

            <div class="register-box center">
                <div class="register-logo">
                    <a href="{{ $dashboard_url }}">{!! config('menu.logo') !!}</a>
                </div>
                <div class="card">
                    <div class="card-body register-card-body">
                        <p class="login-box-msg">{{ __('auth.register_message') }}</p>
                        <form action="{{ $register_url }}" method="post">
                            {{ csrf_field() }}

                            <div class="form-group row ">
                                <div class="col-sm">
                            <div class="input-group mb-3">
                                <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}"
                                        placeholder="{{ __('auth.full_name') }}" autofocus>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>

                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                        </div>
                            <div class="input-group mb-3">
                                <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}"
                                        placeholder="{{ __('auth.email') }}">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                        placeholder="{{ __('auth.password') }}">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                                @if ($errors->has('password'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" name="password_confirmation" class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                                        placeholder="{{ __('auth.retype_password') }}">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                                @if ($errors->has('password_confirmation'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group ">
                                <div class="input-group input-group-sm">
                                    <select class='sel-region form-control select2' id='selRegion' name='region_id'>
                                    </select>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <input type="input" name="reason_join" class="form-control {{ $errors->has('reason_join') ? 'is-invalid' : '' }}" value="{{ old('reason_join') }}"
                                        placeholder="{{ __('auth.reason_join') }}">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="far fa-question-circle"></span>
                                    </div>
                                </div>
                                @if ($errors->has('reason_join'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('reason_join') }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group ">
                                <div class="input-group input-group-sm">
                                <select class='sel-locale js-states form-control select2' id='selLocale' name='locale'>
                                <option @if ( app()->getLocale() == 'en') selected @endif value="en">{{__('english')}}</option>
                                <option @if ( app()->getLocale() == 'de') selected @endif value="de">{{__('deutsch')}}</option>
                                </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">
                                {{ __('auth.register') }}
                            </button>
                        </form>
                        <p class="mt-2 mb-1">
                            <a href="{{ $login_url }}">
                                {{ __('auth.i_already_have_a_membership') }}
                            </a>
                        </p>
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div><!-- /.register-box -->
        </div>
    </div>
</div>

@stop

@section('app_js')

@stack('js')
    <script>
        $(function() {

            $("#selRegion").select2({
                multiple: false,
                width: '100%',
                allowClear: false,
                minimumResultsForSearch: Infinity,
                placeholder: "{{__('club.region')}}",
                ajax: {
                    url: "{{ route('region.admin.sb')}}",
                    type: "get",
                    delay: 250,
                    processResults: function (response) {
                        return { results: response };
                        },
                    cache: true
                    }
            });

            function formatLocale (locale) {
                var country = locale.id;
                if (country == "en"){
                    country = 'gb';
                }
                var $locale = $(
                    '<span class="flag-icon flag-icon-'+country+'"></span><span> '+locale.text+'</span></span>'
                );
                return $locale;
            };

            $("#selLocale").select2({
                multiple: false,
                width: '100%',
                allowClear: false,
                minimumResultsForSearch: Infinity,
                templateSelection: formatLocale
            });
        });
    </script>
@yield('js')
@stop
