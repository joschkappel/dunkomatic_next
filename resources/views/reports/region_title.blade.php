<h1>{{ $region->name }}</h1>
<p></p>
<h2>{{$rptname}}</h2>
<p></p>
<h3>{{__('Season')}}  {{ App\Models\Setting::where('name','season')->first()->value }} </h3>
<p></p>
<h2>{{ __('Date')}}: {{ now() }}</h2>