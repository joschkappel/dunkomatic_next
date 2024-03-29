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
  <th class="text-center">{{ __('game.overlap') }}</th>
  <x-slot:addButtons>
    @can('update-games')
    <a href="{{ route('club.show.games', ['language' => app()->getLocale(), 'club' => $club]) }}"
        class="btn btn-info float-right mr-2">
        <i class="far fa-edit"></i> @lang('club.action.edit-homegame')</a>
    @endcan
  </x-slot:addBbuttons>
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
    $.fn.dataTable.ext.buttons.import = {
        text: '{{__('game.excel.import')}}',
        action: function ( e, dt, node, config ) {
            window.open('{{ route('club.upload.homegame',['language'=>app()->getLocale(), 'club' => $club ])}}',"_self");
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
          @if ( now()->isBefore( $club->region->close_scheduling_at ?? now()->addMinute(1) ) )
            ,{ extend: 'spacer',
                        style: 'bar'
            },
            'import'
          @endif
        ],
        order: [[ 2,'asc'],[ 3,'asc'], [ 4,'asc']],
        ajax: '{{ route('club.game.list_home',['language' => app()->getLocale(), 'club'=>$club]) }}',
        columns: [
                 { data: 'id', name: 'id', visible: false },
                 { data: {
                     _: 'game_no.display',
                     filter: 'game_no.filter',
                     sort: 'game_no.sort'
                   }, name: 'game_no.sort'},
                 { data: {
                    _: 'game_date.filter',
                    export: 'game_date.filter',
                    display: 'game_date.display',
                    sort: 'game_date.ts'
                  }, name: 'game_date.ts' },
                 { data: 'game_time', name: 'game_time' },
                 { data: 'league', name: 'league' },
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
                 { data: 'duplicate', name: 'duplicate' }
              ],
              createdRow: function( row, data, dataIndex){
                if ( data['duplicate'] != '') {
                    $(row).addClass('table-danger');
                } else {
                    if ( (data['club_id_home'] == '{{$club->id}}') && (data['game_time'] ==  '')) {
                        $(row).addClass('table-warning');
                    } else if (data['club_id_home'] == '{{$club->id}}') {
                        $(row).addClass('table-success');
                    };
                };
                if ( ! moment().hour(data['game_time'].split(":")[0]).isBetween(moment().hour(9).minute(0), moment().hour(20).minute(50))) {
                    $(row).addClass('table-info');
                };
            }
    });

    $('body').on('click', '#gameEditLink', function() {
        moment.locale('{{app()->getLocale()}}');
        var gdate = moment($(this).data('game-date')).format('L');
        var gtime = moment($(this).data('game-time'),'HH:mm:ss').format('LT');
        $("#game_time").val(gtime);
        $("#game_date").val(gdate);
        $("#gym_id").val($(this).data('gym-id'));
        $("#gym_no").val($(this).data('gym-no'));
        $("#game_id").val($(this).data('id'));
        $("#league").val($(this).data('league'));
        $("#modalTitle").html( $(this).data('league') + ' - '+ gdate + '  {{ __('game.action.editdate') }}' );
        var url = "{{route('game.update_home',['game'=>':game:'])}}";
        url = url.replace(':game:', $(this).data('id'));
        $('#formGamedate').attr('action', url);
        $("#modalEditGamedate").modal('show');
      });

});

</script>
@endsection
