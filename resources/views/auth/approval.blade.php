@extends('layouts.master')

@section('app_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body', 'login-page')

@php( $dashboard_url = View::getSection('dashboard_url') ?? config('dunkomatic.dashboard_url', 'home') )

@php( $dashboard_url = $dashboard_url ? route($dashboard_url,app()->getLocale()) : '' )


@section('body')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ $dashboard_url }}">{!! config('menu.logo') !!}</a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">{{ __('auth.approval_message') }}</p>
                @if (session('resent'))
                    <div class="alert alert-success" role="alert">
                        {{ __('auth.verify_email_sent') }}
                    </div>
                @endif

                {{ __('auth.verify_check_your_email') }}
                {{ __('auth.verify_if_not_recieved') }},

                <form class="d-inline" method="POST" action="{{ route('verification.resend', app()->getLocale()) }}">
                    @csrf
                    <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('auth.verify_request_another') }}</button>.
                </form>
            </div>
        </div>
    </div>
@stop

@section('app_js')
    @stack('js')
    @yield('js')
@stop
