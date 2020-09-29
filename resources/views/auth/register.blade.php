@extends('layouts.master')

@section('app_css')
  <!-- iCheck for checkboxes and radio inputs -->
<link href="{{ URL::asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}" rel="stylesheet">

    @stack('css')
    @yield('css')
@stop

@section('classes_body', 'register-page')

@php( $login_url = View::getSection('login_url') ?? config('dunkomatic.login_url', 'login') )
@php( $register_url = View::getSection('register_url') ?? config('dunkomatic.register_url', 'register') )
@php( $dashboard_url = View::getSection('dashboard_url') ?? config('dunkomatic.dashboard_url', 'home') )

@php( $login_url = $login_url ? route($login_url, app()->getLocale()) : '' )
@php( $register_url = $register_url ? route($register_url, app()->getLocale()) : '' )
@php( $dashboard_url = $dashboard_url ? route($dashboard_url,app()->getLocale()) : '' )

@section('body')
    <div class="register-box">
        <div class="register-logo">
            <a href="{{ $dashboard_url }}">{!! config('menu.logo') !!}</a>
        </div>
        <div class="card">
            <div class="card-body register-card-body">
                <p class="login-box-msg">{{ __('auth.register_message') }}</p>
                <form action="{{ $register_url }}" method="post">
                    {{ csrf_field() }}

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
                        <select class='sel-region js-states form-control select2' id='selRegion' name='region'>
                      </select>
                      <span class="input-group-btn">
                        <button class="btn btn-default" type="button" data-select2-open="region">
                          <span class="fas fa-globe-europe"></span>
                        </button>
                      </span>
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
                    <button type="submit" class="btn btn-primary btn-block btn-flat">
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
@stop

@section('app_js')

    @stack('js')
    <script>
      $(document).ready(function(){

          $("#selRegion").select2({
              multiple: false,
              allowClear: false,
              minimumResultsForSearch: 10,
              placeholder: "{{__('club.region')}}",
              ajax: {
                      url: "{{ route('region.admin.sb')}}",
                      type: "get",
                      delay: 250,
                      processResults: function (response) {
                        return {
                          results: response
                        };
                      },
                      cache: true
                    }
          });
        });
    </script>
    @yield('js')
@stop
