@extends('adminlte::page')

@section('css')
  <link href="{{ URL::asset('vendor/pace-progress/themes/blue/pace-theme-center-radar.css') }}" rel="stylesheet" />
@endsection

@section('content_header')
<div class="container-fluid">
    <div class="row ">
      <div class="col-sm">
              <!-- small card CLUB -->
              <div class="small-box bg-gray">
                  <div class="inner">
                      <h3>{{ $club->shortname }}</h3>
                      <h5>{{ $club->name }}</h5>
                  </div>
                  <div class="icon">
                      <i class="fas fa-basketball-ball"></i>
                  </div>
                  <a href="{{ route('club.edit',['club' => $club ]) }}" class="small-box-footer">
                      More info <i class="fas fa-arrow-circle-right"></i>
                  </a>
              </div>
            </div>
        {{-- <div class="col-sm-6">
            <h1>Calendar</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Calendar</li>
            </ol>
        </div> --}}
    </div>
</div><!-- /.container-fluid -->
@stop

@section('plugins.Datatables', true)
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-6 pd-2">

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
              data-club-sname="{{ $club->shortname }}" data-toggle="modal" data-target="#modalDeleteMemberRole"><i class="fa fa-trash"></i></button>
            <a href="{{ route('club.memberrole.edit',['memberrole' => $mrole, 'club' => $club ]) }}" class=" px-2">
                {{ $mrole['role']['name'] }} {{ $mrole['function'] }} - {{ $mrole['member']['firstname'] }} {{ $mrole['member']['lastname'] }} <i class="fas fa-arrow-circle-right"></i>
            </a></p>
            @endforeach

          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <a href="{{ route('club.memberrole.create',['club' => $club ]) }}" class="btn btn-primary" >
            <i class="fas fa-plus-circle"></i>  New Role
            </a>
          </div>
          <!-- /.card-footer -->
        </div>
        <!-- /.card -->
        <!-- card TEAMS -->
        <div class="card card-outline card-info collapsed-card">
          <div class="card-header">
            <h4 class="card-title"><i class="fas fa-users"></i> Teams  <span class="badge badge-pill badge-info">{{ count($teams) }}</span></h4>
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
                     <th></th>
                     <th>Team</th>
                     <th>League</th>
                     <th>Assign or Deassign</th>
                  </tr>
               </thead>
               <tbody>
               @foreach ($teams as $team )
                 <tr>
                   <td><button id="deleteTeam" data-league-sname="{{$team->league['shortname']}}" data-team-id="{{ $team->id }}" data-team-no="{{ $team->team_no }}" data-club-sname="{{ $club->shortname }}" type="button" class="btn btn-outline-danger btn-sm "> <i class="fas fa-trash"></i> </button></td>
                   <td><a href="{{ route('team.edit', ['team' =>$team->id] ) }}">{{$club->shortname}}{{ $team->team_no }}<i class="fas fa-arrow-circle-right"></i></a>
                   @isset ( ($team->league['shortname']) )
                     <td><button type="button" class="btn btn-dark btn-sm " disabled>{{$team->league['shortname']}}</button></td>
                     <td><button id="deassignLeague" data-league-id="{{$team->league['id']}}" data-team-id="{{ $team->id }}" data-club-id="{{ $club->id }}" type="button" class="btn btn-outline-secondary btn-sm "> <i class="fas fa-unlink"></i> </button></td>
                   @endisset
                   @empty ( ($team->league['shortname']) )
                     <td class="text-info">unassigned (was: {{ $team->league_prev }})</td>
                     <td><button type="button" id="assignLeague" class="btn btn-outline-info btn-sm" data-team-id="{{ $team->id }}" data-club-id="{{ $club->id }}" data-toggle="modal" data-target="#modalAssignLeague"><i class="fas fa-link"></i></button></td>
                   @endempty
              @endforeach
            </tbody>
         </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <a href="{{ route('club.team.create',['club' => $club ]) }}" class="btn btn-primary" >
            <i class="fas fa-plus-circle"></i>  New Team</a>
            <a href="{{ route('team.plan-leagues',['club' => $club ]) }}" class="btn btn-primary" >
            <i class="fas fa-map"></i>  Plan Season</a>
          </div>
          <!-- /.card-footer -->
        </div>
        <!-- /.card -->

    </div>

    <div class="col-sm-6">
      <!-- card GYMS -->
      <div class="card card-outline card-info collapsed-card">
        <div class="card-header">
          <h4 class="card-title"><i class="fas fa-building"></i> Gyms  <span class="badge badge-pill badge-info">{{ count($gyms) }}</span></h4>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
            </button>
          </div>
          <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          @foreach ($gyms as $gym )
          <p><button type="button" id="deleteGym" class="btn btn-outline-danger btn-sm" data-gym-id="{{ $gym->id }}"
            data-gym-name="{{ $gym->gym_no }} - {{ $gym->name }}"
            data-club-sname="{{ $club->shortname }}" data-toggle="modal" data-target="#modalDeleteGym"><i class="fa fa-trash"></i></button>
            <a href="{{ route('gym.edit',['gym' => $gym ]) }}" class=" px-2">
              {{ $gym->gym_no }} - {{ $gym->name }} <i class="fas fa-arrow-circle-right"></i>
          </a></p>
          @endforeach
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <a href="{{ route('club.gym.create',['club' => $club ]) }}" class="btn btn-primary" >
          <i class="fas fa-plus-circle"></i>  New Gym
          </a>
        </div>
        <!-- /.card-footer -->
      </div>
      <!-- /.card -->

      <!-- card GAMES -->
      <div class="card card-outline card-info collapsed-card">
        <div class="card-header">
          <h4 class="card-title"><i class="fas fa-trophy"></i> Home Games    <span class="badge badge-pill badge-info">{{ count($games_home)}}</span></h4>
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
    </div>
    <!-- ./deck -->
    <!-- all modals here -->
    @include('club/includes/assign_league')
    @include('member/includes/memberroles_delete')
    @include('team/includes/team_delete')
    @include('club/gym/includes/gym_delete')
    <!-- all modals above -->
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
<script data-pace-options='maxProgressPerFrame: 2'  src="{{ URL::asset('vendor/pace-progress/pace.js') }}"></script>

<script>
  $(function() {
    $("button#assignLeague").click( function(){
       $('#team_id').val($(this).data('team-id'));
       $('#club_id').val($(this).data('club-id'));
       $('#modalAssignLeague').modal('show');
    });
    $("button#deassignLeague").click( function(){
       var team_id = $(this).data('team-id');
       var club_id = $(this).data('club-id');
       var league_id = $(this).data('league-id');
       Pace.restart();
       Pace.track(function () {
         $.ajax({
             type: 'POST',
             url: "{{ route('team.deassign-league')}}",
             type: "POST",
             dataType: 'json',
             data: {
               club_id: club_id,
               team_id: team_id,
               league_id: league_id,
               _token: "{{ csrf_token() }}",
               _method: 'DELETE'},
             success: function(response) {
               location.reload()
             },
         });
       });
    });
    $("button#deleteMemberrole").click( function(){
       $('#member_id').val($(this).data('member-id'));
       $('#unit_shortname').html($(this).data('club-sname'));
       $('#role_name').html($(this).data('role-name'));
       $('#member_name').html($(this).data('member-name'));
       $('#confirmDeleteMemberRole').attr('action', '/memberrole/'+$(this).data('role-id'));
       $('#modalDeleteMember').modal('show');
    });
    $("button#deleteTeam").click( function(){
       $('#team_id').val($(this).data('team-id'));
       $('#club_shortname').html($(this).data('club-sname'));
       $('#league_shortname').html($(this).data('league-sname'));
       $('#team_name').html($(this).data('club-sname')+$(this).data('team-no'));
       $('#confirmDeleteTeam').attr('action', '/team/'+$(this).data('team-id'));
       $('#modalDeleteTeam').modal('show');
    });
    $("button#deleteGym").click( function(){
       $('#gym_id').val($(this).data('gym-id'));
       $('#club_shortname').html($(this).data('club-sname'));
       $('#gym_name').html($(this).data('gym-name'));
       $('#confirmDeleteGym').attr('action', '/gym/'+$(this).data('gym-id'));
       $('#modalDeleteGym').modal('show');
    });
  });

</script>
@stop
