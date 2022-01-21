@extends('layouts.page')

@section('content')
<x-card-list cardTitle="{{ __('game.title.list', ['region'=>$region->name ]) }}">
    <th>ID</th>
    <th>@lang('game.game_date')</th>
    <th>@lang('game.gym_no')</th>
    <th>@lang('game.game_time')</th>
    <th>{{ trans_choice('league.league',1)}}</th>
    <th>@lang('game.game_no')</th>
    <th>@lang('game.team_home')</th>
    <th>@lang('game.team_guest')</th>
    <th>{{ __('game.referee') }} 1</th>
    <th>{{ __('game.referee') }} 2</th>
</x-card-list>
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
                        window.open('{{ route('region.upload.game',['language'=>app()->getLocale(), 'region' => $region ])}}',"_self");
                    }
                };
              $('#table').DataTable({
                 processing: true,
                 serverSide: false,
                 responsive: true,
                 language: { "url": "{{URL::asset('lang/vendor/datatables.net/'.app()->getLocale().'.json')}}" },
                 ordering: true,
                stateSave: true,
                @if ( now()->between( $region->close_scheduling_at ?? now()->subMinutes(1), $region->close_referees_at ?? now()->addMinutes(1) ) )
                dom: 'Bflrtip',
                buttons: [
                    { extend: 'collection',
                       text: 'Export',
                       buttons: [
                        { extend: 'excelHtml5',
                            exportOptions: { orthogonal: 'export' },
                            title: '{{$region->code}}_{{ __('game.allregion') }}',
                            sheetName: '{{ __('game.allregion')}}',
                        },
                        { extend: 'csv',
                                exportOptions: { orthogonal: 'export', columns: ':visible' },
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
                    'copy'
                    @can('update-games')
                    ,{ extend: 'spacer',
                        style: 'bar'
                    },
                    'import'
                    @endcan
                ],
                @endif
                 ajax: '{{ route('game.datatable', ['region' => $region, 'language'=> app()->getLocale()]) }}',
                 order: [[ 1, 'asc' ], [ 3, 'asc' ],[ 4, 'asc' ]],
                 columns:  [
                    { data: 'id', name: 'id',visible: false },
                    { data: {
                            _: 'game_date.filter',
                            export: 'game_date.filter',
                            display: 'game_date.display',
                            sort: 'game_date.ts'
                        },
                        name: 'game_date.ts'
                    },
                    { data: {
                            _: 'gym_no.default',
                            export: 'gym_no.default',
                            display: 'gym_no.display'
                        },
                        name: 'gym_no.default'
                    },
                    { data: 'game_time', name: 'game_time'},
                    { data: 'game_league', name: 'game_league'},
                    { data: { _: 'game_no.display', sort: 'game_no.sort' }, name: 'game_no.sort' },
                    { data: 'team_home', name: 'team_home' },
                    { data: 'team_guest', name: 'team_guest' },
                    { data: 'referee_1', name: 'referee_1'},
                    { data: 'referee_2', name: 'referee_2'}
                ]

              });
        });
</script>
@endsection
