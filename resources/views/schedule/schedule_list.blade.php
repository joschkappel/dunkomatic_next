@extends('adminlte::page')

@section('content_header')
    @if(Session::has('success'))
    <div class="alert alert-success">
        {{Session::get('success')}}
    </div>
@endif
@stop

@section('plugins.Datatables', true)
@section('content')

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">List of Schedules for region {{ Auth::user()->region }}</h3>
                  </div>
                  <!-- /.card-header -->

                  <div class="card-tools p-2">
            <a href="{{ route('schedule.create') }}" class="text-center btn btn-success btn-sm mb-3">Create New Schedule</a>
          </div>
          <div class="card-body">
            @csrf

         <table class="table table-hover table-bordered table-sm" id="table">
            <thead class="thead-light">
               <tr>
                  <th>Id</th>
                  <th>Name</th>
                  <th>Region</th>
                  <th>Eventcolor</th>
                  <th>Color</th>
                  <th>Team Size</th>
                  <th>Events</th>
                  <th>Active</th>
                  <th>created at</th>
               </tr>
            </thead>
         </table>
          </div>
          <!-- /.card-body -->

        </div>
      </div>
    </div>
@stop

@section('footer')
jochenk
@stop


@section('js')

<script>
         $(function() {
               $('#table').DataTable({
               processing: true,
               serverSide: true,
               order: [[1,'asc']],
               ajax: '{{ route('schedule.list') }}',
               columns: [
                        { data: 'id', name: 'id', visible: false },
                        { data: 'name', name: 'name' },
                        { data: 'region_id', name: 'region_id' },
                        { data: 'eventcolor', name: 'eventcolor', visible: false  },
                        { data: 'color', name: 'color', orderable: false, searchable: false },
                        { data: 'size.description', name: 'description'},
                        { data: 'events', name: 'events'},
                        { data: 'active', name: 'active', searchable: false },
                        { data: 'created_at', name: 'created_at'},
                     ]
            });
         });

         $('body').on('click', '.deleteScheduleType', function () {

                 var st_id = $(this).data("id");
                 if(confirm("Are You sure want to delete !"))
                 {
                   $.ajax({
                       type: "post",
                       url: "schedule/delete/"+st_id,
                       dataType:"json",
                       data: {
                            "_token": "{{ csrf_token() }}",
                            "_method": 'DELETE'
                        },
                       success: function (data) {
                         var oTable = $('#table').dataTable();
                         oTable.fnDraw(false);
                       },
                       error: function (data) {
                           console.log('Error:', data);
                       }
                   });
                }
             });
</script>
@stop
