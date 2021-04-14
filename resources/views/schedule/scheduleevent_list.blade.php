@extends('layouts.page')

@section('plugins.Datatables',true)
@section('plugins.Moment',true)
@section('plugins.TempusDominus',true)
@section('plugins.RangeSlider',true)
{{-- @section('plugins.DateRangePicker',false) --}}


@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">@lang('schedule.title.event.list', [ 'schedule'=>$schedule->name, 'eventcount'=>
                        $eventcount])</h3>
                </div>
                <!-- /.card-header -->

                <div class="card-tools p-2">
                    <button type="button" class="btn btn-info btn-sm mb-3" data-toggle="modal"
                        data-target="#modalCreateEvents"
                        {{ ($eventcount > 0) ? 'disabled' : '' }}>@lang('schedule.action.events.create')</button>
                    <button type="button" class="btn btn-info btn-sm mb-3" data-toggle="modal"
                        data-target="#modalCloneEvents"
                        {{ ($eventcount > 0) ? 'disabled' : '' }}>@lang('schedule.action.events.clone')</button>
                    <button type="button" class="btn btn-info btn-sm mb-3" data-toggle="modal"
                        data-target="#modalDeleteEvents"
                        {{ ($eventcount == 0) ? 'disabled' : '' }}>@lang('schedule.action.events.delete')</button>
                </div>
                <div class="card-body">
                    @csrf
                    <table width="100%" class="table table-hover table-bordered table-sm" id="table">
                        <thead class="thead-light">
                            <tr>
                                <th>Id</th>
                                <th>Game Day Sort</th>
                                <th>@lang('game.game_day')</th>
                                <th>@lang('game.game_date')</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.card-body -->

            </div>
        </div>
        <div class="col-md-6">
            @if ($eventcount > 0)
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">@lang('schedule.title.event.shift', ['schedule'=>$schedule->name])</h3>
                </div>
                @include('schedule/includes/shift_events')
            </div>
            @endif
        </div>
                <!-- all modals here -->
                @include('schedule/includes/create_events')
                @include('schedule/includes/clone_events')
                @include('schedule/includes/delete_events')
                @include('schedule/includes/edit_event')
                <!-- all modals above -->
    </div>
</div>
@stop

@section('js')

<script>
    $('#table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 50,
        searching: false,
        @if(app()->getLocale() == 'de')
        language: {"url": "{{ URL::asset('vendor/datatables-plugins/i18n/German.json') }}"},
        @else
        language: {"url": "{{ URL::asset('vendor/datatables-plugins/i18n/English.json') }}"},
        @endif
        order: [
            [1, 'asc']
        ],
        ajax: '{{ route('schedule_event.dt',$schedule) }}',
        columns: [{
                data: 'id',
                name: 'id',
                visible: false
            },
            {
                data: 'game_day_sort',
                name: 'game_day_sort',
                visible: false
            },
            {
                data: 'game_day',
                name: 'game_day',
                sortable: false
            },
            {
                data: 'game_date',
                name: 'game_date',
                sortable: false
            },
        ]
    });


    var old_gamedate;
    let date = new Date();
    let startDate = date.setDate(date.getDate() + 0);
    let endDate = date.setDate(date.getDate() + 365);


    $('body').on('click', '#eventEditLink', function () {
        $('#game_day').val($(this).data('game-day'));
        console.log($(this).data('game-date'));
        old_gamedate = moment($(this).data('game-date')).format('l');
        if ($(this).data('weekend') == '1') {
            $('input[name="full_weekend"]').attr('checked', true);
        } else {
            $('input[name="full_weekend"]').attr('checked', false);
        }
        moment.locale("{{ app()->getLocale() }}");
        $('#game_date').datetimepicker({
            format: 'L',
            locale: '{{ app()->getLocale()}}',
            defaultDate: moment($(this).data('game-date')).format('l'),
            minDate: startDate,
            maxDate: endDate,
        });
        $('#editEventForm').attr('action', '/schedule_event/' + $(this).data('id'));
        $('#modalEditEvent').modal('show');
    });
</script>

@stop
