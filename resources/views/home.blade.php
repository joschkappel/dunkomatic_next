@extends('layouts.page')

@section('title', 'DunkOmatic')

@section('content_header')
    @if (!Auth::user()->member()->first()->is_complete) <h3 class="m-0 text-danger">@lang('auth.complete.profile') </h3>
      <a href="{{ route(@config('dunkomatic.profile_url'), ['language'=>app()->getLocale(),'user'=>Auth::user()]) }}" class="text-center btn btn-danger btn-sm mb-3">@lang('auth.action.complete.profile')</a>
    @else <h1 class="m-0 text-dark">{{ trans_choice('message.message',2)}}</h1>
    @endif
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- <p class="mb-0">{{ __('You are logged in!') }}</p> --}}
                    @include('message.includes.message_timeline')
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('js')
    <script> console.log('Hi!'); </script>

@stop
