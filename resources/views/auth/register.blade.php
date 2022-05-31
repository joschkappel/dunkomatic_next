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
<x-auth-card-form colWidth="6">
    <div class="card-body">
        <div class="row justify-content-center">
            <div class="col-sm">
                <form action="{{ $register_url }}" method="post">
                    @csrf
                    <p class="login-box-msg">{{ __('auth.title.register') }}</p>
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
                    <div class="input-group mb-3">
                        <select class='sel-region form-control select2 {{ $errors->has('region_id') ? 'is-invalid' : '' }}' id='selRegion' name='region_id'>
                        </select>
                        @if ($errors->has('region_id'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('region_id') }}</strong>
                        </div>
                        @endif
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
                    <div class="form-group  mb-3">
                        <div class="captcha">
                            <span>{!! captcha_img('math') !!}</span>
                            <button type="button" class="btn btn-danger" class="reload" id="reload">
                                <i class="fas fa-redo"></i>
                            </button>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input id="captcha" type="text" class="form-control {{ $errors->has('captcha') ? 'is-invalid' : '' }}" placeholder="@lang('Enter the Result of the Equation')" name="captcha">
                        @if ($errors->has('captcha'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('captcha') }}</strong>
                        </div>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ __('auth.register') }}
                    </button>
                </form>
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

            $('#reload').click(function () {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('reload_captcha', ['language'=>app()->getLocale()])}}',
                    success: function (data) {
                        $(".captcha span").html(data.captcha);
                    }
                });
            });
        });
    </script>
@yield('js')
@stop
