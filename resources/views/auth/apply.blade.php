@extends('layouts.master')

@section('app_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body', 'register-page')

@section('body')
<x-auth-card-form>
    <div class="card-body">
        <div class="row justify-content-center">
            <div class="col-sm">
                <form action="{{ route('apply', ['language'=>app()->getLocale(),'user'=>$user]) }}" method="post">
                    @method('POST')
                    @csrf
                    <p class="login-box-msg">{{ __('auth.title.apply') }}</p>
                    <div class="input-group mb-3">
                        <input type="text" name="name" class="form-control" readonly value="{{ $user->name }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" readonly value="{{ $user->email }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
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
                        <input id="captcha" type="text" class="form-control {{ $errors->has('captcha') ? 'is-invalid' : '' }}" placeholder="Enter Captcha" name="captcha">
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
