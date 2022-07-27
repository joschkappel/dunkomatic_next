<h1>{{ $hqregion->name }}</h1>
<p></p>
<h2>{{$rptname}} {{ ' '.$club->shortname }}</h2>
<p>{{ $club->name}}</p>
<h3>{{__('Season')}}  {{ App\Models\Setting::where('name','season')->first()->value }} </h3>
<p></p>
<h2>{{ __('Date')}}: {{ now() }}</h2>
