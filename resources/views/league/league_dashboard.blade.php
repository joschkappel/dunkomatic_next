@extends('adminlte::page')
@section('plugins.Select2', true)

@section('content_header')
<div class="container-fluid">
    <div class="row ">
      <div class="col-sm">
              <div class="small-box bg-gray">
                  <div class="inner">
                    <div class="row">
                      <div class="col-sm-8 pd-2">
                        <h3>{{ $league->shortname }}</h3>
                        <h5>{{ $league->name }} </h5>
                    </div>
                      <div class="col-sm-4 pd-2">
                        <ul class="list-group">
                          <li @if (count($assigned_clubs)==0 ) class="list-group-item list-group-item-danger py-0"> no clubs
                          @elseif (count($assigned_clubs)==$league->schedule['size'] )  class="list-group-item list-group-item-success py-0"> all clubs
                          @else  class="list-group-item list-group-item-warning py-0"> {{ count($assigned_clubs) }} of {{$league->schedule['size'] }} clubs
                          @endif assigned
                          </li>
                          <li @if (count($assigned_teams)===0 ) class="list-group-item list-group-item-danger py-0"> no teams
                          @elseif (count($assigned_teams)===$league->schedule['size'] ) class="list-group-item list-group-item-success py-0"> all teams
                          @else class="list-group-item list-group-item-warning py-0"> {{ count($assigned_teams) }} of {{$league->schedule['size'] }} teams
                          @endif registered
                          </li>
                          <li class="list-group-item list-group-item-warning py-0">no games created
                          </li>
                          <li class="list-group-item list-group-item-warning py-0">competition not started
                          </li>
                        </ul>
                    </div>
                  </div>
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
                     <td><button id="deassignClub" data-id="{{ $assigned_clubs[$i]['club_id'] }}" type="button" class="btn btn-outline-danger btn-sm "> <i class="fas fa-unlink"></i> </button></td>
                   @endisset
                   @empty ( $assigned_clubs[$i] )
                     <td><span class="badge badge-pill badge-info">{{ $i }}</span></td>
                     <td class="text-info">unassigned</td>
                     <td><button type="button" id="assignClub" class="btn btn-outline-info btn-sm" data-itemid="{{ $i }}" data-toggle="modal" data-target="#modalAssignClub"><i class="fas fa-link"></i></button></td>
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
      <!-- card MEMBERS -->
      <div class="card card-outline card-info collapsed-card">
        <div class="card-header">
          <h4 class="card-title"><i class="fas fa-user-tie"></i> Roles  <span class="badge badge-pill badge-info">{{ count($member_roles) }}</span></h4>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
            </button>
          </div>
          <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          @foreach ($member_roles as $mrole )
          <p><button type="button" id="deleteMemberrole" class="btn btn-outline-danger btn-sm" data-member-id="{{ $mrole['member']->id }}"
            data-role-id="{{ $mrole->id }}"
            data-member-name="{{ $mrole['member']['firstname'] }} {{ $mrole['member']['lastname'] }}"
            data-role-name="{{ $mrole['role']['name'] }}"
            data-league-sname="{{ $league->shortname }}" data-toggle="modal" data-target="#modalDeleteMemberRole"><i class="fa fa-trash"></i></button>
          <a href="{{ route('league.memberrole.edit',['memberrole' => $mrole, 'league' => $league ]) }}" class=" px-2">
              {{ $mrole['role']['name'] }} {{ $mrole['function'] }} - {{ $mrole['member']['firstname'] }} {{ $mrole['member']['lastname'] }} <i class="fas fa-arrow-circle-right"></i>
          </a></p>
          @endforeach


        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <a href="{{ route('league.memberrole.create',['league' => $league ])}}" class="btn btn-primary" >
          <i class="fas fa-plus-circle"></i>  New Role
          </a>
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
          <button type="button" class="btn btn-primary" id="createGames"
            @if ( count($assigned_teams) < 2) disabled @endif>
          <i class="fas fa-plus-circle"></i>  Create Games
        </button>
        </div>
        <!-- /.card-footer -->
      </div>
      <!-- /.card -->
      <!-- all modals here -->
      @include('league/includes/assign_club')
      @include('member/includes/memberroles_delete')
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

     $("button#deleteMemberrole").click( function(){
        $('#member_id').val($(this).data('member-id'));
        $('#unit_shortname').html($(this).data('league-sname'));
        $('#role_name').html($(this).data('role-name'));
        $('#member_name').html($(this).data('member-name'));
        $('#confirmDeleteMemberRole').attr('action', '/memberrole/'+$(this).data('role-id'));
        $('#modalDeleteMember').modal('show');
     });

     $("button#createGames").click( function(){
             $.ajax({
                 type: "POST",
                 dataType: "json",
                 data: {
                   _token: "{{ csrf_token() }}"
                 },
                 url: "{{ route('league.game.store', ['league' => $league ]) }}",
                 success: function (data) {
                   location.reload()
                 },
                 error: function (data) {
                     console.log('Error:', data);
                 }
             });
      });
  });
</script>
@stop
