@extends('layouts.master')

@section('app_css')
    @yield('css')
@stop

@section('classes_body', 'lockscreen')

@php( $password_reset_url = View::getSection('password_reset_url') ?? config('dunkomatic.password_reset_url', 'password/reset') )
@php( $dashboard_url = View::getSection('dashboard_url') ?? config('dunkomatic.dashboard_url', 'home') )

@php( $password_reset_url = $password_reset_url ? route($password_reset_url, app()->getLocale() ) : '' )
@php( $dashboard_url = $dashboard_url ? route($dashboard_url, app()->getLocale() ) : '' )

@section('body')
    <div class="lockscreen-wrapper">
        <div class="lockscreen-logo">
            <a href="{{ $dashboard_url }}">{!! config('menu.logo') !!}</a>
        </div>

        <div class="lockscreen-name">{{{ isset(Auth::user()->name) ? Auth::user()->name : Auth::user()->email }}}</div>

        <div class="lockscreen-item">
            @if(config('menu.usermenu_image'))
            <div class="lockscreen-image">
                <img src="{{ Auth::user()->adminlte_image() }}" alt="{{ Auth::user()->name }}">
            </div>
            @endif

            <form method="POST" action="{{ route('password.confirm') }}" class="lockscreen-credentials @if(!config('menu.usermenu_image'))ml-0 @endif">
                @csrf
                <div class="input-group">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="{{ __('auth.password') }}" autofocus>

                    <div class="input-group-append">
                        <button type="submit" class="btn"><i class="fas fa-arrow-right text-muted"></i></button>
                    </div>
                </div>
            </form>
        </div>
        @error('password')
            <div class="lockscreen-subitem text-center" role="alert">
                <b class="text-danger">{{ $message }}</b>
            </div>
        @enderror
        <div class="help-block text-center">
            {{ __('auth.confirm_password_message') }}
        </div>
        <div class="text-center">
            <a href="{{ $password_reset_url }}">
                {{ __('auth.i_forgot_my_password') }}
            </a>
        </div>
    </div>
@stop

@section('app_js')
    @stack('js')
    @yield('js')
@stop
