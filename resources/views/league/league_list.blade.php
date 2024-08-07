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

                  <x-slot:addButtons>
                    <button type="button" class="btn btn-outline-secondary mr-2" id="getHelp">{{ __('Help')}}</button>
                  </x-slot:addButtons>
</x-card-list>
@include('league.includes.league_list_help')
@endsection

@section('js')
<script>
   $(function() {
         $('#goBack').click(function(e){
            history.back();
         });

         $(document).on('click', 'button#getHelp', function() {
                $('#modalLeagueListHelp').modal('show');
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
                     }, name: 'age_type.display', width: '1%'
                  },
                   { data: {
                     _: 'gender_type.display',
                     filter: 'gender_type.display',
                     display: 'gender_type.display',
                     sort: 'gender_type.sort'
                     }, name: 'gender_type.display', width: '1%'
                  },
                  { data: {
                     _: 'size.sort',
                     filter: 'size.sort',
                     display: 'size.display',
                     sort: 'size.sort'
                     }, name: 'size.sort', width: '1%'},
                  { data: 'state', name: 'state', width: '60%'},
                  { data: 'games_count', name: 'games_count', width: '1%'},
                  { data: 'games_notime_count', name: 'games_notime_count', width: '1%'}
               ]
      });
   });

</script>
@endsection
