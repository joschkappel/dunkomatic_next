@extends('layouts.page')

@section('plugins.Pace', true)
@section('plugins.Select2', true)

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
                  <a href="{{ route('club.edit',['language'=> app()->getLocale(),'club' => $club ]) }}" class="small-box-footer" dusk="btn-edit">
                      @lang('club.action.edit') <i class="fas fa-arrow-circle-right"></i>
                  </a>
                  <a id="deleteClub" href="#" data-toggle="modal" data-target="#modalDeleteClub" class="small-box-footer" dusk="btn-delete">
                      @lang('club.action.delete') <i class="fa fa-trash"></i>
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

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-6 pd-2">

        <!-- card MEMBERS -->
        <div class="card card-outline card-info collapsed-card">
          <div class="card-header">
            <h4 class="card-title"><i class="fas fa-user-tie"></i> @lang('role.member')  <span class="badge badge-pill badge-info">{{ count($members) }}</span></h4>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
              </button>
            </div>
            <!-- /.card-tools -->
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            @foreach ($members as $member )
            <p><button type="button" id="deleteMember" class="btn btn-outline-danger btn-sm" data-member-id="{{ $member->id }}"
              data-member-name="{{ $member->name }}"
              data-club-sname="{{ $club->shortname }}" data-toggle="modal" data-target="#modalDeleteMember"><i class="fa fa-trash"></i></button>
            <a href="{{ route('membership.club.edit',[ 'language'=>app()->getLocale(),'member' => $member, 'club' => $club ]) }}" class=" px-2">{{ $member->name }} <i class="fas fa-arrow-circle-right"></i></a>
            @if (! $member->is_user)
            <a href="{{ route('member.invite',[ 'member' => $member]) }}"><i class="fas fa-user-plus"></i></a>
            @endif
              @foreach ($member['memberships'] as $membership)
                @if (($membership->membership_type == 'App\Models\Club' ) and ($membership->membership_id == $club->id))
                <span class="badge badge-primary">
                @else
                <span class="badge badge-secondary">
                @endif
                {{ App\Enums\Role::getDescription($membership->role_id) }}</span>
              @endforeach
          </p>
            @endforeach

          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <a href="{{ route('membership.club.create',[ 'language'=>app()->getLocale(), 'club' => $club ]) }}" class="btn btn-primary" >
            <i class="fas fa-plus-circle"></i>  @lang('role.action.create')
            </a>
          </div>
          <!-- /.card-footer -->
        </div>
        <!-- /.card -->
        <!-- card TEAMS -->
        <div class="card card-outline card-info collapsed-card">
          <div class="card-header">
            <h4 class="card-title"><i class="fas fa-users"></i> {{trans_choice('team.team',2 )}}  <span class="badge badge-pill badge-info">{{ count($teams) }}</span></h4>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
              </button>
            </div>
            <!-- /.card-tools -->
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table width="100%" class="table table-hover table-bordered table-sm" id="table">
               <thead class="thead-light">
                  <tr>
                     <th></th>
                     <th>{{trans_choice('team.team',1 )}}</th>
                     <th>{{trans_choice('league.league',1 )}}</th>
                     <th>@lang('team.action.de_assign')</th>
                  </tr>
               </thead>
               <tbody>

               @foreach ($teams as $team )
                 <tr>
                   @isset ( ($team->league['shortname']) )
                     <td><button id="deleteTeam" data-league-sname="{{$team->league['shortname']}}" data-team-id="{{ $team->id }}" data-team-no="{{ $team->team_no }}" data-club-sname="{{ $club->shortname }}" type="button" class="btn btn-outline-danger btn-sm "> <i class="fas fa-trash"></i> </button></td>
                     <td><a href="{{ route('team.edit', [ 'language'=>app()->getLocale(), 'team' =>$team->id] ) }}">{{$club->shortname}}{{ $team->team_no }}<i class="fas fa-arrow-circle-right"></i></a>
                     <td><button type="button" class="btn btn-dark btn-sm " disabled>{{$team->league['shortname']}}-{{$team->league_char}}</button></td>
                     <td><button id="deassignLeague" data-league-id="{{$team->league['id']}}" data-team-id="{{ $team->id }}" data-club-id="{{ $club->id }}" type="button" class="btn btn-outline-secondary btn-sm "> <i class="fas fa-unlink"></i> </button></td>
                   @endisset
                   @empty ( ($team->league['shortname']) )
                     <td><button id="deleteTeam" data-team-id="{{ $team->id }}" data-team-no="{{ $team->team_no }}" data-club-sname="{{ $club->shortname }}" type="button" class="btn btn-outline-danger btn-sm "> <i class="fas fa-trash"></i> </button></td>
                     <td><a href="{{ route('team.edit', [ 'language'=>app()->getLocale(), 'team' =>$team->id] ) }}">{{$club->shortname}}{{ $team->team_no }}<i class="fas fa-arrow-circle-right"></i></a>
                     <td class="text-info">@lang('team.league.unassigned',['league' => $team->league_prev ])</td>
                     <td><button type="button" id="assignLeague" class="btn btn-outline-info btn-sm" data-team-id="{{ $team->id }}" data-club-id="{{ $club->id }}" data-toggle="modal" data-target="#modalAssignLeague"><i class="fas fa-link"></i></button></td>
                   @endempty
              @endforeach
            </tbody>
         </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <a href="{{ route('club.team.create',['language'=>app()->getLocale(), 'club' => $club ]) }}" class="btn btn-primary" >
            <i class="fas fa-plus-circle"></i> @lang('team.action.create')</a>
            <a href="{{ route('team.plan-leagues',[ 'language'=>app()->getLocale(),'club' => $club ]) }}" class="btn btn-primary" >
            <i class="fas fa-map"></i>  @lang('team.action.plan.season')</a>
            @if ($club->region->pickchar_enabled)
            <a href="{{ route('club.team.pickchar',[ 'language'=>app()->getLocale(),'club' => $club ]) }}" class="btn btn-primary" >
            <i class="fas fa-edit"></i>  @lang('pick chars')</a>
            @endif
          </div>
          <!-- /.card-footer -->
        </div>
        <!-- /.card -->

    </div>

    <div class="col-sm-6">
      <!-- card GYMS -->
      <div class="card card-outline card-info collapsed-card">
        <div class="card-header">
          <h4 class="card-title"><i class="fas fa-building"></i> {{trans_choice('gym.gym',2 )}}  <span class="badge badge-pill badge-info">{{ count($gyms) }}</span></h4>
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
            <a href="{{ route('gym.edit',['language'=>app()->getLocale(), 'gym' => $gym ]) }}" class=" px-2">
              {{ $gym->gym_no }} - {{ $gym->name }} <i class="fas fa-arrow-circle-right"></i>
          </a></p>
          @endforeach
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <a href="{{ route('club.gym.create',['language'=>app()->getLocale(),'club' => $club ]) }}" class="btn btn-primary" >
          <i class="fas fa-plus-circle"></i>  @lang('gym.action.create')
          </a>
        </div>
        <!-- /.card-footer -->
      </div>
      <!-- /.card -->

      <!-- card GAMES -->
      <div class="card card-outline card-info collapsed-card">
        <div class="card-header">
          <h4 class="card-title"><i class="fas fa-trophy"></i> @lang('game.home')    <span class="badge badge-pill badge-info">{{ count($games_home)}}</span></h4>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
            </button>
          </div>
          <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="list-group overflow-auto">
            @foreach ($files as $f)
              @php $fname=explode('/',$f); @endphp
                 <a href="{{ route('file.get', ['season'=>$fname[1], 'region'=>$fname[2], 'type'=> $fname[3],'file'=>$fname[4] ] )}}" class="list-group-item list-group-item-action list-group-item-info"> {{ basename($f) }}</a>
            @endforeach
          </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <a href="{{ route('club.list.homegame',['language'=>app()->getLocale(), 'club' => $club ]) }}" class="btn btn-primary" >
          <i class="far fa-edit"></i> @lang('club.action.edit-homegame')</a>
          <a href="{{ route('club.game.chart',['language'=>app()->getLocale(), 'club' => $club ]) }}" class="btn btn-secondary" >
          <i class="far fa-chart-bar"></i> @lang('club.action.chart-homegame')</a>

        </div>
        <!-- /.card-footer -->
      </div>
      <!-- /.card -->

    <!-- all modals here -->
    @include('club/includes/assign_league')
    @include('club/includes/club_delete')
    @include('member/includes/member_delete')
    @include('team/includes/team_delete')
    @include('club/gym/includes/gym_delete')
    <!-- all modals above -->
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-sm-4">
            <div class="card border-secondary bg-secondary text-white">
                <img src="{{asset('img/'.config('dunkomatic.grafics.club', 'oops.jpg'))}}" class="card-img" alt="...">
                <div class="card-img-overlay">
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
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
             url: "{{ route('team.deassign-league' )}}",
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
    $("button#deleteMember").click( function(){
       $('#member_id').val($(this).data('member-id'));
       $('#unit_shortname').html($(this).data('club-sname'));
       $('#unit_type').html('{{ trans_choice('club.club',1)}}');
       $('#role_name').html($(this).data('role-name'));
       $('#member_name').html($(this).data('member-name'));
       var url = "{{ route('membership.club.destroy', ['club'=>$club, 'member'=>':member:']) }}";
       url = url.replace(':member:', $(this).data('member-id'));
       $('#confirmDeleteMember').attr('action', url);
       $('#modalDeleteMember').modal('show');
    });
    $("button#deleteTeam").click( function(){
       $('#team_id').val($(this).data('team-id'));
       $('#club_shortname').html($(this).data('club-sname'));
       $('#league_shortname').html($(this).data('league-sname'));
       $('#team_name').html($(this).data('club-sname')+$(this).data('team-no'));
       var url = "{{ route('team.destroy', ['team'=>':team:']) }}";
       url = url.replace(':team:', $(this).data('team-id'))
       $('#confirmDeleteTeam').attr('action', url);
       $('#modalDeleteTeam').modal('show');
    });
    $("button#deleteGym").click( function(){
       $('#gym_id').val($(this).data('gym-id'));
       $('#club_shortname').html($(this).data('club-sname'));
       $('#gym_name').html($(this).data('gym-name'));
       var url = "{{ route('gym.destroy', ['gym'=>':gymid:'])}}";
       url = url.replace(':gymid:',$(this).data('gym-id') );
       $('#confirmDeleteGym').attr('action', url);
       $('#modalDeleteGym').modal('show');
    });
    $("#deleteClub").click( function(){
       var url = "{{ route('club.destroy', ['club'=>$club])}}";
       $('#confirmDeleteClub').attr('action', url);
       $('#modalDeleteClub').modal('show');
    });
  });

</script>
@stop
