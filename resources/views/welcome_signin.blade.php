@extends('layouts.welcome')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg p-3 mb-5">
                <div class="card-body login-card-body">
                    <div class="row d-inline-flex">
                        <div class="col-sm">
                            <p class="login-box-msg">{{ __('auth.sociallogin_message') }}</p>
                            <div class="d-flex justify-content-center mb-3">
                                <a class="btn btn-outline-dark border-dark rounded-pill" role="button" href="{{ route('oauth.redirect', ['provider'=>'google'])}}"><i class="fab fa-google"></i><span class="px-2">@lang('auth.sign_in_with', ['provider'=>'Google'])</span></a>
                            </div>
{{--                             <div class="d-flex justify-content-center mb-3">
                                <a class="btn btn-outline-primary border-primary rounded-pill"  role="button" href="{{ route('oauth.redirect', ['provider'=>'twitter'])}}"><i class="fab fa-twitter"></i><span class="mx-2">@lang('auth.sign_in_with', ['provider'=>'Twitter'])</span></a>
                            </div>
                            <div class="d-flex justify-content-center mb-3">
                                <a class="btn btn-primary border-primary rounded-pill"  role="button" href="{{ route('oauth.redirect', ['provider'=>'facebook'])}}"><i class="fab fa-facebook"></i><span class="mx-2">@lang('auth.sign_in_with', ['provider'=>'Facebook'])</span></a>
                            </div>
 --}}                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-2"><hr class="border border-gray"></div>
                        <div class="col-md-4 text-gray">@lang('auth.login_message')</div>
                        <div class="col-md-2"><hr class="border border-gray"></div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="d-flex justify-content-center mb-3">
                            <a class="btn btn-dark border-dark rounded-pill"  role="button" href="{{ route('login', ['language'=>app()->getLocale()])}}">@lang('auth.sign_in')</span></a>
                        </div>

                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <hr class="border border-secondary">
                            <div class="d-flex mb-3">
                                @lang('auth.i_dont_have_a_membership')
                                <a class="px-2" href="{{ route('welcome_signup', app()->getLocale())}}">@lang('auth.register')</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
