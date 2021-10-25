@extends('layouts.page')

@section('plugins.Datatables', true)

@section('content')
<x-card-list cardTitle="{{ __('region.title.list') }}" cardNewAction="{{ route('region.create', app()->getLocale()) }}" cardNewTitle="{{ __('region.action.create') }}" cardNewAbility="create-regions">
                  <th>Id</th>
                  <th>{{ __('region.code') }}</th>
                  <th>{{ __('region.name') }}</th>
                  <th>{{ __('region.hq') }}</th>
                  <th>{{ App\Enums\Role::RegionLead()->description  }}</th>
                  <th>{{ trans_choice('club.club',2)}}</th>
                  <th>{{ trans_choice('team.team',2)}}</th>
                  <th>{{ trans_choice('gym.gym',2)}}</th>
                  <th>{{ trans_choice('league.league',2)}}</th>
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
               ajax: '{{ route('region.list.dt', ['language'=>app()->getLocale()]) }}',
               columns: [
                        { data: 'id', name: 'id', visible: false },
                        { data: 'code', name: 'code' },
                        { data: 'name', name: 'name' },
                        { data: 'hq', name: 'hq' },
                        { data: 'regionadmin', name: 'regionadmin' },
                        { data: 'clubs_count', name: 'clubs' },
                        { data: 'teams_count', name: 'teams' },
                        { data: 'gyms_count', name: 'gyms' },
                        { data: 'leagues_count', name: 'leagues' },
                     ]
            });
         });


</script>
@stop
