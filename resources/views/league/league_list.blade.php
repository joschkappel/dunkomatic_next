@extends('layouts.page')

@section('content')
<x-card-list cardTitle="{{ __('league.title.list', ['region' => $region->name ]) }}">
                  <th>Id</th>
                  @if ($region->is_base_level)
                    <th>{{ trans_choice('region.region',1) }}</th>
                  @endif
                  <th>@lang('league.shortname')</th>
                  <th>@lang('league.agetype')</th>
                  <th>@lang('league.gendertype')</th>
                  <th>@lang('league.size')</th>
                  <th>@lang('league.state')</th>
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
         serverSide: false,
         responsive: true,
         stateSave: true,
         pageLength: {{ config('dunkomatic.table_page_length', 50)}},
         language: { "url": "{{URL::asset('lang/vendor/datatables.net/'.app()->getLocale().'.json')}}" },
         order: [[1,'asc']],
         ajax: '{{ route('league.list', ['language'=>app()->getLocale(),'region'=>$region]) }}',
         columns: [
                  { data: 'id', name: 'id', visible: false },
                  @if ($region->is_base_level)
                    { data: 'alien_region', name: 'alien_region'},
                  @endif
                  { data:  {
                     _: 'shortname.sort',
                     filter: 'shortname.sort',
                     display: 'shortname.display',
                     sort: 'shortname.sort'
                   }, name: 'shortname.sort' },
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
                  { data: 'state', name: 'state', width: '20%'},
                  { data: 'games_count', name: 'games_count', width: '2%'},
                  { data: 'games_notime_count', name: 'games_notime_count', width: '2%'},
                  { data: 'games_noshow_count', name: 'games_noshow_count', width: '2%'},
                  { data: 'updated_at', name: 'updated_at'},
               ]
      });
   });

</script>
@endsection
