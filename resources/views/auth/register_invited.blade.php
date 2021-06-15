@extends('layouts.master')

@section('plugins.Select2', true)

@section('app_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body', 'register-page')

@php( $register_url = View::getSection('register_url') ?? config('dunkomatic.register_url', 'register') )
@php( $register_url = $register_url ? route($register_url, app()->getLocale()) : '' )

@section('body')
    <div class="register-box">
        <div class="card">
            <div class="card-body register-card-body">
                <p class="login-box-msg">{{ __('auth.register_message') }}</p>
                <form action="{{ $register_url }}" method="post">
                    {{ csrf_field() }}

                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" value="{{ $member->email1 }}" readonly>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
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
                      <input class="form-control" value="{{ $region->name }}" readonly>
                      <input hidden name="region_id" class="form-control" value="{{ $region->id }}" >
                      <span class="input-group-btn">
                        <button class="btn btn-default" type="button" data-select2-open="region_id">
                          <span class="fas fa-globe-europe"></span>
                        </button>
                      </span>
                    </div>
                    <div class="input-group mb-3">
                        <input type="input" name="reason_join" class="form-control" value="invited by {{$user->name}}" readonly>
                        <input hidden name="invited_by" class="form-control" value="{{ $invited_by }}" >
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="far fa-question-circle"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                      <select class='sel-locale js-states form-control select2' id='selLocale' name='locale'>
                        <option @if ( app()->getLocale() == 'en') selected @endif value="en">{{__('english')}}</option>
                        <option @if ( app()->getLocale() == 'de') selected @endif value="de">{{__('deutsch')}}</option>
                      </select>
                      <span class="input-group-btn">
                        <button class="btn btn-default" type="button" data-select2-open="locale">
                          <span class="fas fa-language"></span>
                        </button>
                      </span>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block btn-flat">
                        {{ __('auth.register') }}
                    </button>
                </form>

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
                theme: 'bootstrap4',
                allowClear: false,
                minimumResultsForSearch: 10,
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
                theme: 'bootstrap4',
                allowClear: false,
                templateSelection: formatLocale
            });
        });
    </script>
@yield('js')
@stop
