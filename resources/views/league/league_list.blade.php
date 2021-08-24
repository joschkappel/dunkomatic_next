@extends('layouts.page')

@section('plugins.Datatables', true)

@section('content')
<x-card-list cardTitle="{{ __('league.title.list', ['region' => session('cur_region')->name ]) }}">
                  <th>Id</th>
                  <th>@lang('league.shortname')</th>
                  <th>@lang('league.state')</th>
                  <th>@lang('league.agetype')</th>
                  <th>@lang('league.gendertype')</th>
                  <th>@lang('league.size')</th>
                  <th>{{ __('containing')}}@lang('team.assigned')</th>
                  <th>{{ __('containing')}}@lang('team.registered')</th>
                  <th>{{ __('containing')}}@lang('team.selected')</th>
                  <th>{{__('Total Games')}}</th>
                  <th>{{__('Games No Time')}}</th>
                  <th>{{__('Games No Teams')}}</th>
                  <th>{{__('Updated at')}}</th>
</x-card-list>
@endsection

@section('js')
<script>
   $(function() {
         $('#goBack').click(function(e){
            history.back();
         }); 
         
         $('#table').DataTable({
         processing: true,
         serverSide: true,
         responsive: true,
         @if (app()->getLocale() == 'de')
         language: { "url": "{{URL::asset('vendor/datatables-plugins/i18n/German.json')}}" },
         @else
         language: { "url": "{{URL::asset('vendor/datatables-plugins/i18n/English.json')}}" },
         @endif
         order: [[1,'asc']],
         ajax: '{{ route('league.list', ['language'=>app()->getLocale(),'region'=>session('cur_region')->id]) }}',
         columns: [
                  { data: 'id', name: 'id', visible: false },
                  { data:  {
                     _: 'shortname.sort',
                     filter: 'shortname.sort',
                     display: 'shortname.display',
                     sort: 'shortname.sort'
                   }, name: 'shortname.sort' },
                      { data: 'state', name: 'state', width: '1%'},
                  { data: {
                     _: 'age_type.display',
                     filter: 'age_type.display',
                     display: 'age_type.display',
                     sort: 'age_type.sort'
                     }, name: 'age_type.display'
                  },
                   { data: {
                     _: 'gender_type.display',
                     filter: 'gender_type.display',
                     display: 'gender_type.display',
                     sort: 'gender_type.sort'
                     }, name: 'gender_type.display'
                  },
                  { data: {
                     _: 'size.sort',
                     filter: 'size.sort',
                     display: 'size.display',
                     sort: 'size.sort' 
                     }, name: 'size.sort', width: '2%'},
                  { data: {
                     _: 'assigned_rel.sort',
                     filter: 'assigned_rel.sort',
                     display: 'assigned_rel.display',
                     sort: 'assigned_rel.sort' 
                     }, name: 'assigned_rel.sort' },
                  { data: {
                     _: 'registered_rel.sort',
                     filter: 'registered_rel.sort',
                     display: 'registered_rel.display',
                     sort: 'registered_rel.sort' 
                     }, name: 'registered_rel.sort' },
                  { data: {
                     _: 'selected_rel.sort',
                     filter: 'selected_rel.sort',
                     display: 'selected_rel.display',
                     sort: 'selected_rel.sort' 
                     }, name: 'selected_rel.sort' },                   
                  { data: 'games_count', name: 'games_count', width: '2%'},
                  { data: 'games_notime_count', name: 'games_notime_count', width: '2%'},
                  { data: 'games_noshow_count', name: 'games_noshow_count', width: '2%'},
                  { data: 'updated_at', name: 'updated_at'},
               ]
      });
   });

</script>
@endsection
