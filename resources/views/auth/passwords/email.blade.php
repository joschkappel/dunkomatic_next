@extends('layouts.master')

@section('app_css')
    @yield('css')
@stop

@section('classes_body', 'login-page')

@php( $password_email_url = View::getSection('password_email_url') ?? config('dunkomatic.password_email_url', 'password/email') )
@php( $dashboard_url = View::getSection('dashboard_url') ?? config('dunkomatic.dashboard_url', 'home') )

@php( $password_email_url = $password_email_url ? route($password_email_url, app()->getLocale() ) : '' )
@php( $dashboard_url = $dashboard_url ? route($dashboard_url, app()->getLocale() ) : '' )

@section('body')
<x-auth-card-form>
    <div class="card-body login-card-body">
        <p class="login-box-msg">{{ __('auth.password_reset_message') }}</p>
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <form action="{{ $password_email_url }}" method="post">
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
            <button type="submit" class="btn btn-primary btn-block btn-flat">
                {{ __('auth.send_password_reset_link') }}
            </button>
        </form>

    </div>
</x-auth-card-form>
@stop

@section('app_js')
    @stack('js')
    @yield('js')
@stop
