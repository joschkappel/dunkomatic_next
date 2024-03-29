@extends('layouts.page')

@section('content')
<x-card-list cardTitle="{{ __('club.title.list', ['region' => $region->name ]) }}"  cardNewAction="{{ ($region->is_base_level) ? route('club.create', ['language'=>app()->getLocale(), 'region'=>$region]) : '' }}" cardNewTitle="{{ ($region->is_base_level) ? __('club.action.create') : '' }}" cardNewAbility="create-clubs">
    <th>Id</th>
    @if ($region->is_top_level)
     <th>{{ trans_choice('region.region',1) }}</th>
    @endif
    <th>@lang('club.shortname')</th>
    <th>@lang('Name')</th>
    <th>@lang('club.club_no')</th>
    <th>@lang('auth.user.account')</th>
    <th>@lang('Inactive')</th>
    <th>{{ trans_choice('team.team', 2) }}</th>
    <th>{{ __('containing') }}@lang('team.assigned')</th>
    <th>{{ __('containing') }}@lang('team.registered')</th>
    <th>{{ __('containing') }}@lang('team.selected')</th>
    <th>{{ __('Total Games') }}</th>
    <th>{{ __('Games No Time') }}</th>
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
                stateSave: true,
                pageLength: {{ config('dunkomatic.table_page_length', 50)}},
                language: { "url": "{{URL::asset('lang/vendor/datatables.net/'.app()->getLocale().'.json')}}" },
                order: [
                    [1, 'asc']
                ],
                ajax: '{{ route('club.list', ['region' => $region ]) }}',
                columns: [{
                        data: 'id',
                        name: 'id',
                        visible: false
                    },
                    @if ($region->is_top_level)
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
                        data: 'club_no',
                        name: 'club_no'
                    },
                    {
                        data: {
                            _: 'has_account.sort',
                            filter: 'has_account.sort',
                            display: 'has_account.display',
                            sort: 'has_account.sort'
                        },
                        name: 'has_account.sort'
                    },
                    {
                        data: {
                            _: 'inactive.sort',
                            filter: 'inactive.sort',
                            display: 'inactive.display',
                            sort: 'inactive.sort'
                        },
                        name: 'inactive.sort'
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
                    { data: {
                        _: 'updated_at.filter',
                        display: 'updated_at.display',
                        sort: 'updated_at.ts'
                        },
                        name: 'updated_at.ts',
                        visible: true
                    },
                ]
            });
        });
    </script>
@endsection
