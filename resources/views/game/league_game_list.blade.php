@extends('layouts.page')

@section('content')
<x-card-list cardTitle="{{ __('league.title.game', ['league'=>$league->shortname]) }}">
                                    <th>id</th>
                                    <th>@lang('game.game_no')</th>
                                    <th>@lang('game.game_date')</th>
                                    <th>@lang('game.game_time')</th>
                                    <th>@lang('game.team_home')</th>
                                    <th>@lang('game.team_guest')</th>
                                    <th>@lang('game.gym_no')</th>
                                    <th>gym_id</th>
                                    <th>{{ __('game.referee') }} 1</th>
                                    <th>{{ __('game.referee') }} 2</th>
</x-card-list>
<!-- all modals here -->
@include('game/includes/edit_game')
<!-- all modals above -->
@endsection

@section('js')

    <script>
        $(function() {
            $('#goBack').click(function(e){
                history.back();
            });
            $.fn.dataTable.ext.buttons.import = {
                text: '{{__('game.excel.import')}}',
                action: function ( e, dt, node, config ) {
                    window.open('{{ route('league.upload.game',['language'=>app()->getLocale(), 'league' => $league ])}}',"_self");
                }
            };

            $('#table').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                language: { "url": "{{URL::asset('lang/vendor/datatables.net/'.app()->getLocale().'.json')}}" },
                ordering: true,
                stateSave: true,
                dom: 'Bflrtip',
                buttons: [
                    { extend: 'collection',
                       text: 'Export',
                       buttons: [
                            {
                                extend: 'excelHtml5',
                                text: 'Excel',
                                exportOptions: { orthogonal: 'export', columns: ':visible' },
                                filename: '{{ $league->shortname }}_{{ trans_choice('game.homegame', 2) }}',
                                title: null,
                                sheetName: '{{ trans_choice('game.homegame', 2) }}',
                            },
                            { extend: 'csv',
                                text: 'CSV',
                                exportOptions: { orthogonal: 'export', columns: ':visible' },
                                filename: '{{ $league->shortname }}_{{ trans_choice('game.homegame', 2) }}',
                                title: null,
                                name: 'csv',
                            },
                        ]
                    },
                    { extend: 'spacer',
                            style: 'bar'
                    },
                    { extend: 'print',
                        exportOptions: { orthogonal: 'export', columns: ':visible' },
                    },
                    @if ( ( $league->state->in([ App\Enums\LeagueState::Scheduling, App\Enums\LeagueState::Freeze])) and
                          ( $league->isCustom  ) )
                        { extend: 'spacer',
                                    style: 'bar'
                        },
                        'import'
                    @endif
                ],
                order: [
                    [1, 'asc']
                ],
                language: { "url": "{{URL::asset('lang/vendor/datatables.net/'.app()->getLocale().'.json')}}" },
                ajax: '{{ route('league.game.dt', ['language' => app()->getLocale(), 'league' => $league]) }}',
                columns: [{
                        data: 'id',
                        name: 'id',
                        visible: false
                    },
                    {
                        data: {
                            _: 'game_no.display',
                            sort: 'game_no.sort'
                        },
                        name: 'game_no.sort'
                    },
                    {
                        data: {
                            _: 'game_date.filter',
                            export: 'game_date.filter',
                            display: 'game_date.display',
                            sort: 'game_date.ts'
                        },
                        name: 'game_date.ts'
                    },
                    {
                        data: 'game_time',
                        name: 'game_time'
                    },
                    {
                        data: 'team_home',
                        name: 'team_home'
                    },
                    {
                        data: 'team_guest',
                        name: 'team_guest'
                    },
                    {
                        data: {
                            _: 'gym_no.default',
                            export: 'gym_no.default',
                            display: 'gym_no.display'
                        },
                        name: 'gym_no.default'
                    },
                    {
                        data: 'gym_id',
                        name: 'gym_id',
                        visible: false
                    },
                    { data: 'referee_1', name: 'referee_1'},
                    { data: 'referee_2', name: 'referee_2'},
                ]
            });

            $('body').on('click', '#gameEditLink', function() {
                moment.locale('{{ app()->getLocale() }}');
                var gdate = moment($(this).data('game-date')).format('L');
                var gtime = moment($(this).data('game-time'), 'HH:mm:ss').format('LT');
                $("#game_time").val(gtime);
                $("#game_date").val(gdate);
                $("#gym_id").val($(this).data('gym-id'));
                $("#gym_no").val($(this).data('gym-no'));
                $("#game_id").val($(this).data('id'));
                $("#game_no_old").val($(this).data('game-no'));
                $("#league").val($(this).data('league'));
                $("#league_id").val($(this).data('league-id'));
                $("#club_id_home").val($(this).data('club-id-home'));
                $("#team_id_home").val($(this).data('team-id-home'));
                $("#team_home").val($(this).data('team-home'));
                $("#team_id_guest").val($(this).data('team-id-guest'));
                $("#team_guest").val($(this).data('team-guest'));
                $("#modalTitle").html( $(this).data('league') + ' - '+ $(this).data('game-no') + '  {{ __('game.action.editdate') }}' );
                var url = "{{ route('game.update', ['game' => ':game:']) }}";
                url = url.replace(':game:', $(this).data('id'));
                @if ($league->schedule->custom_events)
               // $('#formGameCustom').attr('action', url);
               // $("#modalEditGameCustom").modal('show');
                @else
                $('#formGame').attr('action', url);
                $("#modalEditGame").modal('show');
                @endif
            });

        });
    </script>
@endsection
