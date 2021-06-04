@extends('layouts.master')

@section('app_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@section('app_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body', 'login-page')

@php( $login_url = View::getSection('login_url') ?? config('dunkomatic.login_url', 'login') )
@php( $register_url = View::getSection('register_url') ?? config('dunkomatic.register_url', 'register') )
@php( $password_reset_url = View::getSection('password_reset_url') ?? config('dunkomatic.password_reset_url', 'password/reset') )
@php( $dashboard_url = View::getSection('dashboard_url') ?? config('dunkomatic.dashboard_url', 'start') )

@php( $login_url = $login_url ? route($login_url, app()->getLocale() ) : '' )
@php( $register_url = $register_url ? route($register_url, app()->getLocale()) : '' )
@php( $password_reset_url = $password_reset_url ? route($password_reset_url, [app()->getLocale(),'']) : '' )
@php( $dashboard_url = $dashboard_url ? route('start' ) : '' )

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
            <div class="login-box center">
                <div class="login-logo">
                    <a href="{{ $dashboard_url }}">{!! config('menu.logo') !!}</a>
                </div>
                <div class="card">
                    <div class="card-body login-card-body">
                        <p class="login-box-msg">{{ __('auth.login_message') }}</p>
                        <form action="{{  $login_url }}" method="post">
                            {{ csrf_field() }}
                            <div class="input-group mb-3">
                                <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{ __('auth.email') }}" autofocus>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                                @if ($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                                @endif
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="{{ __('auth.password') }}">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                                @if ($errors->has('password'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('password') }}
                                </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-8">
                                    <div class="icheck-primary">
                                        <input type="checkbox" name="remember" id="remember">
                                        <label for="remember">{{ __('auth.remember_me') }}</label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <button dusk="login" type="submit" class="btn btn-primary btn-block btn-flat">
                                        {{ __('auth.sign_in') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                        @if ($password_reset_url)
                        <p class="mt-2 mb-1">
                        <a href="{{ $password_reset_url }}">
                            {{ __('auth.i_forgot_my_password') }}
                        </a>
                        </p>
                        @endif
                        @if ($register_url)
                            <p class="mb-0">
                                <a href="{{ $register_url }}">
                                    {{ __('auth.register_a_new_membership') }}
                                </a>
                            </p>
                        @endif
                    </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('app_js')
    @stack('js')
    @yield('js')
@stop
