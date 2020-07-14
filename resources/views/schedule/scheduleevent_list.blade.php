@extends('adminlte::page')

@section('content_header')
    @if(Session::has('success'))
    <div class="alert alert-success">
        {{Session::get('success')}}
    </div>
@endif
@stop
@section('css')
<!-- Bootstrap Color Picker -->
<link href="{{ URL::asset('vendor/daterangepicker/daterangepicker.css') }}" rel="stylesheet">
.red {
  background-color: red !important;
}
@endsection

@section('plugins.Datatables', true)
@section('content')

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">List of events for  {{ $schedule->name }} /  {{ $eventcount }}</h3>
                  </div>
                  <!-- /.card-header -->

                  <div class="card-tools p-2">
            <button type="button" class="btn btn-info btn-sm mb-3" data-toggle="modal" data-target="#modalCreateEvents"{{ ($eventcount > 0) ? 'disabled' : '' }}>Create Events</button>
            <button type="button" class="btn btn-info btn-sm mb-3" data-toggle="modal" data-target="#modalCloneEvents"{{ ($eventcount > 0) ? 'disabled' : '' }}>Clone Events</button>
            <button type="button" class="btn btn-info btn-sm mb-3" data-toggle="modal" data-target="#modalShiftEvents"{{ ($eventcount == 0) ? 'disabled' : '' }}>Shift Events</button>
            <button type="button" class="btn btn-info btn-sm mb-3" data-toggle="modal" data-target="#modalDeleteEvents"{{ ($eventcount == 0) ? 'disabled' : '' }}>Delete All Events</button>
          </div>
          <div class="card-body">
            @csrf

         <table class="table table-hover table-bordered table-sm" id="table">
            <thead class="thead-light">
               <tr>
                  <th>Id</th>
                  <th>Game Day Sort</th>
                  <th>Game Day</th>
                  <th>Game Date</th>
                  <th>All Weekend</th>
                  <th>created at</th>
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

@section('footer')
jochen
@stop


@section('js')
<script src="{{ URL::asset('vendor/moment/moment.min.js') }}"></script>
<script src="{{ URL::asset('vendor/daterangepicker/daterangepicker.js') }}"></script>

<script>
         $(function() {
               $('#table').DataTable({
               processing: true,
               serverSide: true,
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
              //   createdRow: function( row, data, dataIndex ) {
              //      if ( data.game_date.indexOf('DUPLICATE') >= 0 ) {
              //        $(row).addClass('bg-warning');
              //     }}
               });
         });

        var old_gamedate;
        let thisyear = new Date().getFullYear();
        let oneYearFromNow = new Date().getFullYear() + 1;


        $('body').on('click', '#eventEditLink', function(){
            $('#game_day').val($(this).data('game-day'));
            console.log($(this).data('game-date'));
            old_gamedate = moment($(this).data('game-date')).format('MM/DD/YYYY');
            if ($(this).data('weekend')=='1'){
              $('input[name="full_weekend"]').attr('checked', true);
            } else {
              $('input[name="full_weekend"]').attr('checked', false);
            }
            $('input[name="game_date"]').daterangepicker({
                startDate: old_gamedate,
                singleDatePicker: true,
                showDropdowns: true,
                opens: 'center',
                drops: 'auto',
                minYear: thisyear,
                maxYear: oneYearFromNow
            });
            $('#editEventForm').attr('action', '/schedule_event/'+$(this).data('id'));
            $('#modalEditEvent').modal('show');
         });


</script>

@stop
