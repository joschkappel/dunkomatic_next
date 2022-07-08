@extends('layouts.page')

@section('content')
<x-card-list cardTitle="{{ __('club.title.gamehome.edit', ['club'=>$club->shortname]) }}">
  <th>id</th>
  <th>{{ __('game.game_no')}}</th>
  <th>{{ __('game.game_date')}}</th>
  <th>{{ __('game.game_time') }}</th>
  <th>{{ trans_choice('league.league',1) }}</th>
  <th>{{ __('game.team_home') }}</th>
  <th>{{ __('game.team_guest') }}</th>
  <th>{{ __('game.gym_no') }}</th>
  <th>{{ __('game.referee') }} 1</th>
  <th>{{ __('game.referee') }} 2</th>
  <th>gym_id</th>
</x-card-list>
<!-- all modals here -->
@include('game/includes/edit_gamedate_home')
<!-- all modals above -->
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
        language: { "url": "{{URL::asset('lang/vendor/datatables.net/'.app()->getLocale().'.json')}}" },
        ordering: true,
        stateSave: true,
        dom: 'Bflrtip',
        buttons: [
          { extend: 'collection',
                       text: 'Export',
                       buttons: [
                    { extend: 'excelHtml5',
                        text: 'Excel',
                        exportOptions: { orthogonal: 'export', columns: ':visible' },
                        filename: '{{$club->shortname}}_{{ trans_choice('game.homegame',2)}}',
                        sheetName: '{{ trans_choice('game.homegame',2)}}',
                        title: null,
                    },
                    { extend: 'csv',
                            text: 'CSV',
                            exportOptions: { orthogonal: 'export', columns: ':visible' },
                            filename: '{{$club->shortname}}_{{ trans_choice('game.homegame',2)}}',
                            name: 'csv',
                            title: null,
                    },
                ]
          },
          { extend: 'spacer',
                style: 'bar'
          },
          { extend: 'print',
            exportOptions: { orthogonal: 'export', columns: ':visible' },
          }
        ],
        order: [[ 2,'asc'],[ 3,'asc'], [ 4,'asc']],
        ajax: '{{ route('club.game.list',['language' => app()->getLocale(), 'club'=>$club]) }}',
        columns: [
                 { data: 'id', name: 'id', visible: false },
                 { data: {
                     _: 'game_no.display',
                     sort: 'game_no.sort'
                   }, name: 'game_no.sort'},
                 { data: {
                    _: 'game_date.filter',
                    export: 'game_date.filter',
                    display: 'game_date.display',
                    sort: 'game_date.ts'
                  }, name: 'game_date.ts' },
                 { data: 'game_time', name: 'game_time' },
                 { data: 'league.shortname', name: 'league.shortname' },
                 { data: 'team_home', name: 'team_home'},
                 { data: 'team_guest', name: 'team_guest'},
                 { data: {
                    _: 'gym_no.default',
                    export: 'gym_no.default',
                    display: 'gym_no.display'
                  }, name: 'gym_no.default' },
                  { data: 'referee_1', name: 'referee_1'},
                  { data: 'referee_2', name: 'referee_2'},
                 { data: 'gym_id', name: 'gym_id', visible: false },
              ]
    });



});

</script>
@endsection
