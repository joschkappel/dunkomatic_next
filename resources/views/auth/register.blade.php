@extends('layouts.master')

@section('app_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body', 'register-page')

@php( $login_url = View::getSection('login_url') ?? config('dunkomatic.login_url', 'login') )
@php( $register_url = View::getSection('register_url') ?? config('dunkomatic.register_url', 'register') )

@php( $login_url = $login_url ? route($login_url, app()->getLocale()) : '' )
@php( $register_url = $register_url ? route($register_url, app()->getLocale()) : '' )

@section('body')
<x-auth-card-form colWidth="8">
    <div class="card-body register-card-body">
        <div class="row d-inline-flex">
            <div class="col-sm border-right border-primary">
                <p class="login-box-msg">{{ __('auth.socialregister_message') }}</p>
                <div class="d-flex justify-content-center mb-3">
                    <a class="btn btn-outline-dark" role="button" href="{{ route('oauth.redirect', ['provider'=>'google'])}}"><i class="fab fa-google"></i><span class="px-2">@lang('auth.register_with', ['provider'=>'Google'])</span></a>
                </div>
                <div class="d-flex justify-content-center mb-3">
                    <a class="btn btn-outline-primary"  role="button" href="{{ route('oauth.redirect', ['provider'=>'twitter'])}}"><i class="fab fa-twitter"></i><span class="mx-2">@lang('auth.register_with', ['provider'=>'Twitter'])</span></a>
                </div>
                <div class="d-flex justify-content-center mb-3">
                    <a class="btn btn-dark disabled" role="button" href="{{ route('oauth.redirect', ['provider'=>'facebook'])}}"><i class="fab fa-apple"></i><span class="px-2">@lang('auth.register_with', ['provider'=>'Apple'])</span></a>
                </div>
            </div>
            <div class="col-sm">
                <form action="{{ $register_url }}" method="post">
                    @csrf
                    <p class="login-box-msg">{{ __('auth.register_message') }}</p>
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
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ __('auth.register') }}
                    </button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <hr class="border-top border-primary">
                <p class="mt-2 mb-1">
                    <a href="{{ $login_url }}">
                        {{ __('auth.i_already_have_a_membership') }}
                    </a>
                </p>
            </div>
        </div>
    </div><!-- /.card-body -->
</x-auth-card-form>
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

        });
    </script>
@yield('js')
@stop
