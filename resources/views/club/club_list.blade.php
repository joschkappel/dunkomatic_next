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
                    <h3 class="card-title">List of Clubs for region {{ Auth::user()->region }}</h3>
                  </div>
                  <!-- /.card-header -->

                  <div class="card-tools p-2">
                    <a href="{{ route('club.create') }}" class="text-center btn btn-success btn-sm mb-3">Create Club</a>
          </div>
          <div class="card-body">

         <table class="table table-hover table-bordered table-sm" id="table">
            <thead class="thead-light">
               <tr>
                  <th>Id</th>
                  <th>shortname</th>
                  <th>Name</th>
                  <th>Region</th>
                  <th>created at</th>
                  <th>Action</th>
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
               ajax: '{{ url('club/list') }}',
               columns: [
                        { data: 'id', name: 'id', visible: false },
                        { data: 'shortname', name: 'shortname' },
                        { data: 'name', name: 'name' },
                        { data: 'region', name: 'region' },
                        { data: 'created_at', name: 'created_at'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                     ]
            });
         });

         $('body').on('click', '.deleteClub', function () {

                 var club_id = $(this).data("id");
                 if(confirm("Are You sure want to delete !"))
                 {
                   $.ajax({
                       type: "get",
                       url: "club/delete/"+club_id,
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
