@extends('layouts.page')

@section('plugins.Datatables', true)
@section('plugins.DatatableButtons', true)

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-12">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        @lang('league.title.game', ['league'=>$league->shortname])</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-hover table-bordered table-sm" id="table">
                        <thead class="thead-light">
                            <tr>
                                <th>id</th>
                                <th>
                                    @lang('game.game_no')</th>
                                <th>
                                    @lang('game.game_date')</th>
                                <th>
                                    @lang('game.gym_no')</th>
                                <th>gym_id</th>
                                <th>
                                    @lang('game.game_time')</th>
                                <th>
                                    @lang('game.team_home')</th>
                                <th>
                                    @lang('game.team_guest')</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- all modals here -->
    <!-- all modals above -->
</div>
@endsection

@section('js')

<script src="{{ URL::asset('vendor/moment/moment-with-locales.min.js') }}"></script>

<script>
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
            title: '{{$league->shortname}}_{{ trans_choice('game.homegame',2)}}',
            sheetName: '{{ trans_choice('game.homegame',2)}}',
          },
          'print'
        ],
        order: [[ 1,'asc']],
        @if (app()->getLocale() == 'de')
        language: { "url": "{{URL::asset('vendor/datatables-plugins/i18n/German.json')}}" },
        @endif
        @if (app()->getLocale() == 'en')
        language: { "url": "{{URL::asset('vendor/datatables-plugins/i18n/English.json')}}" },
        @endif
        ajax: '{{ route('league.game.dt',['language' => app()->getLocale(), 'league'=>$league]) }}',
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
                 { data: 'gym_id', name: 'gym_id', visible: false },
                 { data: 'game_time', name: 'game_time' },
                 { data: 'team_home', name: 'team_home'},
                 { data: 'team_guest', name: 'team_guest'},
              ]
    });

</script>
@endsection
