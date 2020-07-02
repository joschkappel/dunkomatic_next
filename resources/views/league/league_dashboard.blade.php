@extends('adminlte::page')
@section('plugins.Select2', true)

@section('content_header')
<div class="container-fluid">
    <div class="row ">
      <div class="col-sm">
              <!-- small card CLUB -->
              <div class="small-box bg-gray">
                  <div class="inner">
                      <h3>{{ $league->shortname }}</h3>
                      <h5>{{ $league->name }} - {{ $league->schedule['size'] }}</h5>
                  </div>
                  <div class="icon">
                      <i class="fas fa-trophy"></i>
                  </div>
                  <a href="{{ route('league.edit',['league' => $league ]) }}" class="small-box-footer">
                      More info <i class="fas fa-arrow-circle-right"></i>
                  </a>
              </div>
            </div>
    </div>
</div><!-- /.container-fluid -->
@stop

@section('plugins.Datatables', true)
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-6 pd-2">

        <!-- card CLS -->
        <div class="card card-outline card-info " id="clubsCard">
          <div class="card-header">
            <h4 class="card-title"><i class="fas fa-basketball-ball"></i> Entitled Clubs / Registered Teams
              <span class="badge badge-pill badge-info">{{ count($assigned_clubs) }}</span> /
              <span class="badge badge-pill badge-info">{{ count($assigned_teams) }}</span> /
              <span class="badge badge-pill badge-info">{{ $league->schedule['size'] }}</span>
            </h4>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
              </button>
            </div>
            <!-- /.card-tools -->
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table class="table table-hover table-bordered table-sm" id="table">
               <thead class="thead-light">
                  <tr>
                     <th>No</th>
                     <th>Entitled Club</th>
                     <th>Assign or Deassign</th>
                     <th>Registered Team</th>
                  </tr>
               </thead>
               <tbody>
                 @for ($i = 1; $i <= $league->schedule['size']; $i++)
                 <tr>
                   @isset ( $assigned_clubs[$i] )
                     <td><span class="badge badge-pill badge-dark">{{ $i }}</span></td>
                     <td><button type="button" class="btn btn-dark btn-sm " disabled>{{ $assigned_clubs[$i]['shortname'] }} </button></td>
                     <td><button id="deassignClub" data-id="{{ $assigned_clubs[$i]['club_id'] }}" type="button" class="btn btn-outline-danger btn-sm "> <i class="fa fa-trash"></i> </button></td>
                   @endisset
                   @empty ( $assigned_clubs[$i] )
                     <td><span class="badge badge-pill badge-info">{{ $i }}</span></td>
                     <td class="text-info">unassigned</td>
                     <td><button type="button" id="assignClub" class="btn btn-outline-info btn-sm" data-itemid="{{ $i }}" data-toggle="modal" data-target="#modalAssignClub"><i class="fa fa-plus"></i></button></td>
                   @endempty
                   @isset ( $assigned_teams[$i] )
                     <td><button type="button" class="btn btn-dark btn-sm pd-0" disabled>{{ $assigned_teams[$i]['shortname'] }} {{ $assigned_teams[$i]['team_no'] }}</button></td>
                   @endisset
                   @empty ( $assigned_teams[$i] )
                     <td></td>
                   @endempty
                 </tr>
                 @endfor
                 {{-- @endfor --}}
               </tbody>
            </table>


          </div>
          <!-- /.card-body -->
          <div class="card-footer">
          </div>
          <!-- /.card-footer -->
        </div>
        <!-- /.card -->


    </div>

    <div class="col-sm-6">
      <!-- card MEMEBRSS -->
      <div class="card card-outline card-info collapsed-card">
        <div class="card-header">
          <h4 class="card-title"><i class="fas fa-user-tie"></i> Members  <span class="badge badge-pill badge-info">0</span></h4>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
            </button>
          </div>
          <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">

        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <i class="fas fa-plus-circle"></i>  New Member

        </div>
        <!-- /.card-footer -->
      </div>
      <!-- /.card -->
      <!-- card GAMES -->
      <div class="card card-outline card-info collapsed-card">
        <div class="card-header">
          <h4 class="card-title"><i class="fas fa-trophy"></i> Games    <span class="badge badge-pill badge-info">0</span></h4>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
            </button>
          </div>
          <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">

        </div>
        <!-- /.card-body -->
        <div class="card-footer">

        </div>
        <!-- /.card-footer -->
      </div>
      <!-- /.card -->
      <!-- all modals here -->
      @include('league/includes/assign_club')
      <!-- all modals above -->
    </div>
    <!-- ./deck -->
</div>
</div>
@stop

@section('footer')
<div class="float-right d-none d-sm-block">
    <b>Version</b> 0.0.1
</div>
<strong>Copyright &copy; 2020 <a href="http://">w.p.o. projects</a>.</strong> All rights
reserved.
@stop


@section('js')
<script>
  $(function() {
    $("button#deassignClub").click( function(){
            var club_id = $(this).data("id");

            $.ajax({
                type: "POST",
                dataType: 'json',
                data: {
                  id: club_id,
                  _token: "{{ csrf_token() }}",
                  _method: 'DELETE'
                },
                url: "club/"+club_id,
                success: function (data) {
                  location.reload()
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
     });

     $("button#assignClub").click( function(){
        var itemid = $(this).data("itemid");
        $('#itemid').val($(this).data('itemid'));
        $('#modalAssignClub').modal('show');
     });
  });
</script>
@stop
