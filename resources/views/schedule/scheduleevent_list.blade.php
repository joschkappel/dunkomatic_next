@extends('layouts.page')

@section('plugins.Datatables',true)
@section('plugins.Moment',true)
@section('plugins.TempusDominus',true)
{{-- @section('plugins.DateRangePicker',false) --}}


@section('content')

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">@lang('schedule.title.event.list', [ 'schedule'=>$schedule->name, 'eventcount'=> $eventcount])</h3>
                  </div>
                  <!-- /.card-header -->

                  <div class="card-tools p-2">
            <button type="button" class="btn btn-info btn-sm mb-3" data-toggle="modal" data-target="#modalCreateEvents"{{ ($eventcount > 0) ? 'disabled' : '' }}>@lang('schedule.action.events.create')</button>
            <button type="button" class="btn btn-info btn-sm mb-3" data-toggle="modal" data-target="#modalCloneEvents"{{ ($eventcount > 0) ? 'disabled' : '' }}>@lang('schedule.action.events.clone')</button>
            <button type="button" class="btn btn-info btn-sm mb-3" data-toggle="modal" data-target="#modalShiftEvents"{{ ($eventcount == 0) ? 'disabled' : '' }}>@lang('schedule.action.events.shift')</button>
            <button type="button" class="btn btn-info btn-sm mb-3" data-toggle="modal" data-target="#modalDeleteEvents"{{ ($eventcount == 0) ? 'disabled' : '' }}>@lang('schedule.action.events.delete')</button>
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
                  <th>@lang('game.weekend')</th>
                  <th>{{__('Created at')}}</th>
               </tr>
            </thead>
         </table>
          </div>
          <!-- /.card-body -->
          <!-- all modals here -->
          @include('schedule/includes/create_events')
          @include('schedule/includes/clone_events')
          @include('schedule/includes/shift_events')
          @include('schedule/includes/delete_events')
          @include('schedule/includes/edit_event')
          <!-- all modals above -->
        </div>
      </div>
    </div>
@stop

@section('js')

<script>
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
       ajax: '{{ route('schedule_event.list-dt',$schedule->id) }}',
       columns: [
                { data: 'id', name: 'id', visible: false },
                { data: 'game_day_sort', name: 'game_day_sort', visible: false },
                { data: 'game_day', name: 'game_day', sortable: false },
                { data: 'game_date', name: 'game_date', sortable: false },
                { data: 'full_weekend', name: 'all_weekend'  },
                { data: 'created_at', name: 'created_at'},
             ]
       });


        var old_gamedate;
        let date = new Date();
        let startDate = date.setDate(date.getDate() + 30);
        let endDate = date.setDate(date.getDate() + 365);


        $('body').on('click', '#eventEditLink', function(){
            $('#game_day').val($(this).data('game-day'));
            console.log($(this).data('game-date'));
            old_gamedate = moment($(this).data('game-date')).format('MM/DD/YYYY');
            if ($(this).data('weekend')=='1'){
              $('input[name="full_weekend"]').attr('checked', true);
            } else {
              $('input[name="full_weekend"]').attr('checked', false);
            }
            $('#game_date').datetimepicker({
                format: 'L',
                locale: '{{ app()->getLocale()}}',
                defaultDate: $(this).data('game-date'),
                minDate: startDate,
                maxDate: endDate,
            });
            $('#editEventForm').attr('action', '/schedule_event/'+$(this).data('id'));
            $('#modalEditEvent').modal('show');
         });


</script>

@stop
