@extends('adminlte::page')


@section('css')
  <link type="text/css" rel="stylesheet" href="{{ URL::asset('vendor/datatables/css/dataTables.bootstrap4.min.css') }}" />
  <link type="text/css" rel="stylesheet" href="{{ URL::asset('vendor/datatables-plugins/responsive/css/responsive.bootstrap4.min.css') }}" />
  <link type="text/css" rel="stylesheet" href="{{ URL::asset('vendor/datatables-plugins/buttons/css/buttons.bootstrap4.min.css') }}" />

@endsection


@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-12">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">@lang('club.title.gamehome.edit', ['club'=>$club->shortname])</h3>
                </div>
                <!-- /.card-header -->
                    <div class="card-body">
                      <table class="table table-hover table-bordered table-sm" id="table">
                         <thead class="thead-light">
                            <tr>
                               <th>Id</th>
                               <th>@lang('game.game_no')</th>
                               <th>@lang('game.game_date')</th>
                               <th>@lang('game.gym_no')</th>
                               <th>@lang('game.game_time')</th>
                               <th class="text-center">@lang('game.overlap')</th>
                               <th>{{ trans_choice('league.league',1)}}</th>
                               <th>@lang('game.team_home')</th>
                               <th>@lang('game.team_guest')</th>
                            </tr>
                         </thead>
                      </table>
                    </div>
            </div>
        </div>
    </div>
    <!-- all modals here -->
    @include('game/includes/edit_gamedate')
    <!-- all modals above -->
</div>
@endsection

@section('js')

<script src="{{ URL::asset('vendor/moment/moment.min.js') }}"></script>
<script src="{{ URL::asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('vendor/datatables-plugins/responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('vendor/datatables-plugins/responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('vendor/datatables-plugins/buttons/js/dataTables.buttons.min.js') }}"></script>


<script src="{{ URL::asset('vendor/datatables-plugins/jszip/jszip.min.js') }}"></script>

<script src="{{ URL::asset('vendor/datatables-plugins/buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('vendor/datatables-plugins/buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ URL::asset('vendor/datatables-plugins/buttons/js/buttons.flash.min.js') }}"></script>
<script src="{{ URL::asset('vendor/datatables-plugins/buttons/js/buttons.print.min.js') }}"></script>


@if (app()->getLocale() == 'de')
  <script src="{{ URL::asset('vendor/moment/locale/de.js') }}"></script>
@endif

<script>
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
        ordering: true,
        stateSave: true,
        dom: 'Bflrtip',
        buttons: [
          { extend: 'excelHtml5',
            text: '{{__('game.excel.export')}}',
            exportOptions: { orthogonal: 'export' },
            title: '{{$club->shortname}}_{{ trans_choice('game.homegame',2)}}',
            sheetName: '{{ trans_choice('game.homegame',2)}}',
          },
          'print',
          'import'
        ],
        order: [[ 2,'asc'],[ 3,'asc'], [ 4,'asc']],
        @if (app()->getLocale() == 'de')
        language: { "url": "{{URL::asset('vendor/datatables-plugins/i18n/German.json')}}" },
        @endif
        ajax: '{{ route('club.game.list_home',['language' => app()->getLocale(), 'club'=>$club]) }}',
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
                 { data: {
                    _: 'gym_no.default',
                    export: 'gym_no.default',
                    display: 'gym_no.display'
                  }, name: 'gym_no.default' },
                 { data: 'game_time', name: 'game_time' },
                 { data: 'duplicate', name: 'duplicate' },
                 { data: 'league.shortname', name: 'league.shortname'  },
                 { data: 'team_home', name: 'team_home'},
                 { data: 'team_guest', name: 'team_guest'},
              ]
    });

  $('body').on('click', '#gameEditLink', function() {
        moment.locale('{{app()->getLocale()}}');
        var gdate = moment($(this).data('game-date')).format('L');
        var gtime = moment($(this).data('game-time'),'HH:mm:ss').format('LT');
        $("#game_time").val(gtime);
        $("#game_date").val(gdate);
        $("#club_id").val($(this).data('club-id-home'));
        var url = "{{route('game.update',['game'=>':game:'])}}";
        url = url.replace(':game:', $(this).data('id'));
        $('#formGamedate').attr('action', url);
        $("#modalEditGamedate").modal('show');
      });




</script>
@endsection
