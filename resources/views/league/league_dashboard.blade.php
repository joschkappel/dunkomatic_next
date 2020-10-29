@extends('layouts.page')
@section('plugins.Select2', true)
@section('plugins.Datatables', true)
@section('plugins.Toastr', true)

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
                          <li @if (count($assigned_clubs) == 0 ) class="list-group-item list-group-item-danger py-0"> @lang('club.entitled.no')
                          @elseif (count($assigned_clubs) == $league->schedule['size'] )  class="list-group-item list-group-item-success py-0"> @lang('club.entitled.all')
                          @else  class="list-group-item list-group-item-warning py-0"> @lang('club.entitled.some', [ 'entitled' => count($assigned_clubs), 'total' => $league->schedule['size']] )
                          @endif
                          </li>
                          <li @if (count($assigned_teams) == 0 ) class="list-group-item list-group-item-danger py-0"> @lang('team.registered.no')
                          @elseif (count($assigned_teams) == $league->schedule['size'] ) class="list-group-item list-group-item-success py-0"> @lang('team.registered.all')
                          @else class="list-group-item list-group-item-warning py-0"> @lang('team.registered.some', ['registered'=>count($assigned_teams), 'total'=>$league->schedule['size']])
                          @endif
                          </li>
                          <li @if (count($games) == 0 ) class="list-group-item list-group-item-danger py-0"> @lang('game.created.no')
                          @else class="list-group-item list-group-item-success py-0"> @lang('game.created.some', ['total'=> count($games)])
                          @endif
                          </li>
                          <li class="list-group-item list-group-item-warning py-0"> @lang('game.notstarted')
                          </li>
                        </ul>
                    </div>
                  </div>
                  </div>
                  <div class="icon">
                      <i class="fas fa-trophy"></i>
                  </div>
                  <a href="{{ route('league.edit',['language'=>app()->getLocale(),'league' => $league ]) }}" class="small-box-footer">
                      @lang('league.action.edit') <i class="fas fa-arrow-circle-right"></i>
                  </a>
              </div>
            </div>
    </div>
</div><!-- /.container-fluid -->
@stop


@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-6 pd-2">

        <!-- card CLS -->
        <div class="card card-outline card-info " id="clubsCard">
          @if ( $league->isGenerated )
          <div class="ribbon-wrapper ribbon-lg">
            <div class="ribbon bg-warning text-lg">
              @lang('league.generated')
            </div>
          </div>
        @endif
          <div class="card-header">
            <h4 class="card-title"><i class="fas fa-basketball-ball"></i> @lang('club.entitlement') / @lang('team.registration')
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
            <table width="100%" class="table table-hover table-bordered table-sm" id="table">
               <thead class="thead-light">
                  <tr>
                     <th>No</th>
                     <th>@lang('club.entitled')</th>
                     @if (!$league->isGenerated)
                     <th>@lang('team.action.de_assign')</th>
                    @endif
                     <th>@lang('team.registered')</th>
                  </tr>
               </thead>
               <tbody>
                 @for ($i = 1; $i <= $league->schedule['size']; $i++)
                 <tr>
                   @isset ( $assigned_clubs[$i] )
                     <td><span class="badge badge-pill badge-dark">{{ $i }}</span></td>
                     <td class="text-dark">{{ $assigned_clubs[$i]['shortname'] }}</td>
                      @if (!$league->isGenerated)<td><button id="deassignClub" data-id="{{ $assigned_clubs[$i]['club_id'] }}" type="button" class="btn btn-outline-danger btn-sm "> <i class="fas fa-unlink"></i> </button></td>@endif
                   @endisset
                   @empty ( $assigned_clubs[$i] )
                     <td><span class="badge badge-pill badge-info">{{ $i }}</span></td>
                     <td class="text-info">@lang('team.unassigned')</td>
                      @if (!$league->isGenerated)<td><button type="button" id="assignClub" class="btn btn-outline-info btn-sm" data-itemid="{{ $i }}" data-toggle="modal" data-target="#modalAssignClub"><i class="fas fa-link"></i></button></td>@endif
                   @endempty
                   @isset ( $assigned_teams[$i] )
                     <td class="text-dark">{{ $assigned_teams[$i]['shortname'] }} {{ $assigned_teams[$i]['team_no'] }}</td>
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
      <div class="card card-outline card-secondary collapsed-card">
        <div class="card-header ">
          <h4 class="card-title"><i class="fas fa-user-tie"></i> {{trans_choice('role.role',2)}}  <span class="badge badge-pill badge-info">{{ count($member_roles) }}</span></h4>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
            </button>
          </div>
          <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          @foreach ($member_roles as $mrole )
          <p><button type="button" id="deleteMemberrole" class="btn btn-outline-danger btn-sm" data-member-id="{{ $mrole->id }}"
            data-role-id="{{ $mrole['pivot']->id }}"
            data-member-name="{{ $mrole->firstname }} {{ $mrole->lastname }}"
            data-role-name="{{ App\Enums\Role::getDescription($mrole['pivot']->role_id) }}"
            data-league-sname="{{ $league->shortname }}" data-toggle="modal" data-target="#modalDeleteMemberRole"><i class="fa fa-trash"></i></button>
          <a href="{{ route('league.membership.edit',['language'=>app()->getLocale(), 'membership' => $mrole['pivot']->id, 'league' => $league ]) }}" class=" px-2">
              {{App\Enums\Role::getDescription($mrole['pivot']->role_id) }} {{ $mrole['pivot']->function }} - {{ $mrole->firstname }} {{ $mrole->lastname }} <i class="fas fa-arrow-circle-right"></i>
          </a></p>
          @endforeach


        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <a href="{{ route('league.membership.create',['language'=>app()->getLocale(), 'league' => $league ])}}" class="btn btn-outline-secondary" >
          <i class="fas fa-plus-circle"></i>  @lang('role.action.create')
          </a>
        </div>
        <!-- /.card-footer -->
      </div>
      <!-- /.card -->
      <!-- card GAMES -->
      <div class="card card-outline card-secondary collapsed-card">
        <div class="card-header">
          <h4 class="card-title"><i class="fas fa-trophy"></i> {{trans_choice('game.game',2)}}    <span class="badge badge-pill badge-info">{{ count($games) }}</span></h4>
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
               <a href="{{ route('file.get', ['filename'=>$f])}}" class="list-group-item list-group-item-action list-group-item-info"> {{ basename($f) }}</a>
          @endforeach
        </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <button type="button" class="btn btn-outline-secondary" id="createGames"
            @if (( count($assigned_teams) < 2) or ($league->isGenerated)) disabled @endif><i class="fas fa-plus-circle"></i>  @lang('game.action.create')
          </button>
          <button type="button" class="btn btn-outline-secondary" id="deleteGames"
              @if (!$league->isGenerated) disabled @endif><i class="fa fa-trash"></i>  @lang('game.action.delete')
          </button>
          <button type="button" class="btn btn-outline-secondary" id="deleteNoshowGames"
              @if (!$league->isGenerated) disabled @endif><i class="fa fa-trash"></i>  @lang('game.action.delete.noshow')
          </button>
          <button type="button" class="btn btn-outline-secondary" id="injectTeam"
              @if ((!$league->isGenerated) or (count($assigned_teams) == $league->schedule['size'])) disabled @endif><i class="fa fa-trash"></i>  @lang('game.action.team.add')
          </button>
          <button type="button" class="btn btn-outline-secondary" id="withdrawTeam"
              @if ((!$league->isGenerated) or (count($assigned_teams) == 0)) disabled @endif><i class="fa fa-trash"></i>  @lang('game.action.team.withdraw')
          </button>
          <a href="{{ route('league.game.index',['language'=>app()->getLocale(), 'league' => $league ]) }}" class="btn btn-primary" >
          <i class="far fa-edit"></i> @lang('league.action.game.list')</a>


        </div>
        <!-- /.card-footer -->
      </div>
      <!-- /.card -->
      <!-- all modals here -->
      @include('league/includes/assign_club')
      @include('member/includes/membership_delete')
      @include('league/includes/withdraw_team')
      @include('league/includes/inject_team')
      <!-- all modals above -->
    </div>
    <!-- ./deck -->
</div>
</div>
@stop

@section('js')
<script>
  $(function() {

    toastr.options.closeButton = true;
    toastr.options.closeMethod = 'fadeOut';
    toastr.options.closeDuration = 300;
    toastr.options.closeEasing = 'swing';
    toastr.options.progressBar = true;

    $("button#deassignClub").click( function(){
            var club_id = $(this).data("id");
            var url = "{{ route('league.deassign-club', ['league'=>$league, 'club'=>':club:'])}}"
            url = url.replace(':club:', club_id);

            $.ajax({
                type: "POST",
                dataType: 'json',
                data: {
                  id: club_id,
                  _token: "{{ csrf_token() }}",
                  _method: 'DELETE'
                },
                url: url,
                success: function (data) {

                  toastr.options.onHidden = function() {
                    location.reload();
                    console.log('reload');
                  }

                  toastr.info('club deassigned','success');

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

     $("button#withdrawTeam").click( function(){
        $('#modalWithdrawTeam').modal('show');
     });

     $("button#injectTeam").click( function(){
        $('#modalInjectTeam').modal('show');
     });

     $("button#deleteMemberrole").click( function(){
        $('#member_id').val($(this).data('member-id'));
        $('#unit_type').html('{{ trans_choice('league.league',1)}}');
        $('#unit_shortname').html($(this).data('league-sname'));
        $('#role_name').html($(this).data('role-name'));
        $('#member_name').html($(this).data('member-name'));
        var url = "{{ route('membership.destroy', ['language'=>app()->getLocale(), 'membership'=>':role:' ])}}";
        url = url.replace(':role:', $(this).data('role-id'));
        $('#confirmDeleteMemberRole').attr('action', url);
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
      $("button#deleteGames").click( function(){
              $.ajax({
                  type: "POST",
                  dataType: "json",
                  data: {
                    _method: "DELETE",
                    _token: "{{ csrf_token() }}"
                  },
                  url: "{{ route('league.game.destroy', ['league' => $league ]) }}",
                  success: function (data) {
                    location.reload()
                  },
                  error: function (data) {
                      console.log('Error:', data);
                  }
              });
       });
       $("button#deleteNoshowGames").click( function(){
               $.ajax({
                   type: "POST",
                   dataType: "json",
                   data: {
                     _method: "DELETE",
                     _token: "{{ csrf_token() }}"
                   },
                   url: "{{ route('league.game.destroy_noshow', [ 'league' => $league ]) }}",
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
