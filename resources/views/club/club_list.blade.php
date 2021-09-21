@extends('layouts.page')

@section('plugins.Datatables', true)

@section('content')
<x-card-list cardTitle="{{ __('club.title.list', ['region' => session('cur_region')->name ]) }}" cardNewAction="{{ route('club.create', app()->getLocale()) }}" cardNewTitle="{{ __('club.action.create') }}">
    <th>Id</th>
    @if (session('cur_region')->is_top_level)
     <th>{{ trans_choice('region.region',1) }}</th>
    @endif
    <th>@lang('club.shortname')</th>
    <th>@lang('club.name')</th>
    <th>{{ trans_choice('team.team', 2) }}</th>
    <th>{{ __('containing') }}@lang('team.assigned')</th>
    <th>{{ __('containing') }}@lang('team.registered')</th>
    <th>{{ __('containing') }}@lang('team.selected')</th>
    <th>{{ __('Total Games') }}</th>
    <th>{{ __('Games No Time') }}</th>
    <th>{{ __('Games No Teams') }}</th>
    <th>{{ __('Updated at') }}</th>
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
                    language: { "url": "{{ URL::asset('vendor/datatables-plugins/i18n/German.json') }}" },
                @else
                    language: { "url": "{{ URL::asset('vendor/datatables-plugins/i18n/English.json') }}" },
                @endif
                order: [
                    [1, 'asc']
                ],
                ajax: '{{ route('club.list', ['region' => session('cur_region')->id ]) }}',
                columns: [{
                        data: 'id',
                        name: 'id',
                        visible: false
                    },
                    @if (session('cur_region')->is_top_level)
                    {
                        data: 'region',
                        name: 'region'
                    },
                    @endif
                    {
                        data: {
                            _: 'shortname.sort',
                            filter: 'shortname.sort',
                            display: 'shortname.display',
                            sort: 'shortname.sort'
                        },
                        name: 'shortname.sort'
                    },
                    {
                        data: {
                            _: 'name.sort',
                            filter: 'name.sort',
                            display: 'name.display',
                            sort: 'name.sort'
                        },
                        name: 'name.sort'
                    },
                    {
                        data: 'teams_count',
                        name: 'teams_count'
                    },
                    {
                        data: {
                            _: 'assigned_rel.sort',
                            filter: 'assigned_rel.sort',
                            display: 'assigned_rel.display',
                            sort: 'assigned_rel.sort'
                        },
                        name: 'assigned_rel.sort'
                    },
                    {
                        data: {
                            _: 'registered_rel.sort',
                            filter: 'registered_rel.sort',
                            display: 'registered_rel.display',
                            sort: 'registered_rel.sort'
                        },
                        name: 'registered_rel.sort'
                    },
                    {
                        data: {
                            _: 'selected_rel.sort',
                            filter: 'selected_rel.sort',
                            display: 'selected_rel.display',
                            sort: 'selected_rel.sort'
                        },
                        name: 'selected_rel.sort'
                    },
                    {
                        data: 'games_home_count',
                        name: 'games_home_count'
                    },
                    {
                        data: 'games_home_notime_count',
                        name: 'games_home_notime_count'
                    },
                    {
                        data: 'games_home_noshow_count',
                        name: 'games_home_noshow_count'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                ]
            });
        });
    </script>
@endsection
