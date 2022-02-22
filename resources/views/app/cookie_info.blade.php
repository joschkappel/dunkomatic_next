@extends('layouts.page')

@section('content')
<x-card-list cardTitle="{{ __('about our Cookies')}}" >
<div>
<h5>@lang('We use cookies solely for technical reasons to provide you with best application features').</h5>
<h5>@lang('We dont use any cookies for user analytics').</h5>
</div>
<div>
</br>
</div>
<div>
<h3>@lang('We store these 4 cookies'):</h3>
<ul>
    <li>
        <h4>XSRF-TOKEN</h4><b>@lang('Usage'):</b> @lang('Prevent Cross-Site-Request-Forgery') (CSRF). </br><b> @lang('Expires'):</b> @lang('2 hours after session start').
    </li>
    <li>
        <h4>io</h4><b>@lang('Usage'):</b> @lang('For server broadcasting'). </br><b> @lang('Expires'):</b> @lang('2 hours after session start').
    </li>
    <li>
        <h4>dunkomatic_next_session</h4><b>@lang('Usage'):</b> @lang('Session data like selected region etc'). </br><b> @lang('Expires'):</b> @lang('for the duration of your session').
    </li>
    <li>
        <h4>dunkomatic_next_cookie_consent</h4><b>@lang('Usage'):</b> @lang('your cookie consent'). </br><b> @lang('Expires'):</b> @lang('1 year').
    </li>
</ul>
</div>
</x-card-list>

@include('app.cookie_consent')
@stop

