@extends('layouts.master')

@section('app_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body', 'login-page')

@php( $login_url = View::getSection('login_url') ?? config('dunkomatic.login_url', 'login') )
@php( $register_url = View::getSection('register_url') ?? config('dunkomatic.register_url', 'register') )
@php( $password_reset_url = View::getSection('password_reset_url') ?? config('dunkomatic.password_reset_url', 'password/reset') )

@php( $login_url = $login_url ? route($login_url, app()->getLocale() ) : '' )
@php( $register_url = $register_url ? route($register_url, app()->getLocale()) : '' )
@php( $password_reset_url = $password_reset_url ? route($password_reset_url, [app()->getLocale(),'']) : '' )

@section('body')
<x-auth-card-form colWidth="6">
    <div class="card-body">
        <div class="row justify-content-center ">
            <div class="col-sm">
                <form action="{{  $login_url }}" method="post">
                    @csrf
                    <p class="card-text login-box-msg">{{ __('auth.title.login') }}</p>
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
                    <div class="row justify-content-between mb-3">
                        <div class="col-sm-6">
                            <div class="icheck-primary">
                                <input type="checkbox" name="remember" id="remember">
                                <label for="remember">{{ __('auth.remember_me') }}</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div>
                                <button dusk="login" type="submit" class="btn btn-primary">
                                    {{ __('auth.sign_in') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <hr class="border-top border-secondary">
                    <p class="mt-2 mb-1">
                    <a href="{{ $password_reset_url }}">
                        {{ __('auth.i_forgot_my_password') }}
                    </a>
                    </p>
                </div>
            </div>
        </div>
    </div><!-- /.card-body -->
</x-auth-card-form>
@stop

@section('app_js')
    @stack('js')
    @yield('js')
@stop
