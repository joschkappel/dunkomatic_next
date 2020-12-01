<?php $__env->startSection('plugins.Pace', true); ?>
<?php $__env->startSection('plugins.Select2', true); ?>

<?php $__env->startSection('content_header'); ?>
<div class="container-fluid">
    <div class="row ">
      <div class="col-sm">
              <!-- small card CLUB -->
              <div class="small-box bg-gray">
                  <div class="inner">
                      <h3><?php echo e($club->shortname, false); ?></h3>
                      <h5><?php echo e($club->name, false); ?></h5>
                  </div>
                  <div class="icon">
                      <i class="fas fa-basketball-ball"></i>
                  </div>
                  <a href="<?php echo e(route('club.edit',['language'=> app()->getLocale(),'club' => $club ]), false); ?>" class="small-box-footer" dusk="btn-edit">
                      <?php echo app('translator')->get('club.action.edit'); ?> <i class="fas fa-arrow-circle-right"></i>
                  </a>
                  <a id="deleteClub" href="#" data-toggle="modal" data-target="#modalDeleteClub" class="small-box-footer" dusk="btn-delete">
                      <?php echo app('translator')->get('club.action.delete'); ?> <i class="fa fa-trash"></i>
                  </a>
              </div>
            </div>
        
    </div>
</div><!-- /.container-fluid -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-6 pd-2">

        <!-- card MEMBERS -->
        <div class="card card-outline card-info collapsed-card">
          <div class="card-header">
            <h4 class="card-title"><i class="fas fa-user-tie"></i> <?php echo app('translator')->get('role.member'); ?>  <span class="badge badge-pill badge-info"><?php echo e(count($members), false); ?></span></h4>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
              </button>
            </div>
            <!-- /.card-tools -->
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <p><button type="button" id="deleteMember" class="btn btn-outline-danger btn-sm" data-member-id="<?php echo e($member->id, false); ?>"
              data-member-name="<?php echo e($member->name, false); ?>"
              data-club-sname="<?php echo e($club->shortname, false); ?>" data-toggle="modal" data-target="#modalDeleteMember"><i class="fa fa-trash"></i></button>
            <a href="<?php echo e(route('membership.club.edit',[ 'language'=>app()->getLocale(),'member' => $member, 'club' => $club ]), false); ?>" class=" px-2"><?php echo e($member->name, false); ?> <i class="fas fa-arrow-circle-right"></i></a>
              <?php $__currentLoopData = $member['memberships']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $membership): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="badge badge-secondary"><?php echo e(App\Enums\Role::getDescription($membership->role_id), false); ?></span>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </p>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <a href="<?php echo e(route('membership.club.create',[ 'language'=>app()->getLocale(), 'club' => $club ]), false); ?>" class="btn btn-primary" >
            <i class="fas fa-plus-circle"></i>  <?php echo app('translator')->get('role.action.create'); ?>
            </a>
          </div>
          <!-- /.card-footer -->
        </div>
        <!-- /.card -->
        <!-- card TEAMS -->
        <div class="card card-outline card-info collapsed-card">
          <div class="card-header">
            <h4 class="card-title"><i class="fas fa-users"></i> <?php echo e(trans_choice('team.team',2 ), false); ?>  <span class="badge badge-pill badge-info"><?php echo e(count($teams), false); ?></span></h4>
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
                     <th><?php echo e(trans_choice('team.team',1 ), false); ?></th>
                     <th><?php echo e(trans_choice('league.league',1 ), false); ?></th>
                     <th><?php echo app('translator')->get('team.action.de_assign'); ?></th>
                  </tr>
               </thead>
               <tbody>

               <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                 <tr>
                   <?php if(isset( ($team->league['shortname']) )): ?>
                     <td><button id="deleteTeam" data-league-sname="<?php echo e($team->league['shortname'], false); ?>" data-team-id="<?php echo e($team->id, false); ?>" data-team-no="<?php echo e($team->team_no, false); ?>" data-club-sname="<?php echo e($club->shortname, false); ?>" type="button" class="btn btn-outline-danger btn-sm "> <i class="fas fa-trash"></i> </button></td>
                     <td><a href="<?php echo e(route('team.edit', [ 'language'=>app()->getLocale(), 'team' =>$team->id] ), false); ?>"><?php echo e($club->shortname, false); ?><?php echo e($team->team_no, false); ?><i class="fas fa-arrow-circle-right"></i></a>
                     <td><button type="button" class="btn btn-dark btn-sm " disabled><?php echo e($team->league['shortname'], false); ?>-<?php echo e($team->league_char, false); ?></button></td>
                     <td><button id="deassignLeague" data-league-id="<?php echo e($team->league['id'], false); ?>" data-team-id="<?php echo e($team->id, false); ?>" data-club-id="<?php echo e($club->id, false); ?>" type="button" class="btn btn-outline-secondary btn-sm "> <i class="fas fa-unlink"></i> </button></td>
                   <?php endif; ?>
                   <?php if(empty( ($team->league['shortname']) )): ?>
                     <td><button id="deleteTeam" data-team-id="<?php echo e($team->id, false); ?>" data-team-no="<?php echo e($team->team_no, false); ?>" data-club-sname="<?php echo e($club->shortname, false); ?>" type="button" class="btn btn-outline-danger btn-sm "> <i class="fas fa-trash"></i> </button></td>
                     <td><a href="<?php echo e(route('team.edit', [ 'language'=>app()->getLocale(), 'team' =>$team->id] ), false); ?>"><?php echo e($club->shortname, false); ?><?php echo e($team->team_no, false); ?><i class="fas fa-arrow-circle-right"></i></a>
                     <td class="text-info"><?php echo app('translator')->get('team.league.unassigned',['league' => $team->league_prev ]); ?></td>
                     <td><button type="button" id="assignLeague" class="btn btn-outline-info btn-sm" data-team-id="<?php echo e($team->id, false); ?>" data-club-id="<?php echo e($club->id, false); ?>" data-toggle="modal" data-target="#modalAssignLeague"><i class="fas fa-link"></i></button></td>
                   <?php endif; ?>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
         </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <a href="<?php echo e(route('club.team.create',['language'=>app()->getLocale(), 'club' => $club ]), false); ?>" class="btn btn-primary" >
            <i class="fas fa-plus-circle"></i> <?php echo app('translator')->get('team.action.create'); ?></a>
            <a href="<?php echo e(route('team.plan-leagues',[ 'language'=>app()->getLocale(),'club' => $club ]), false); ?>" class="btn btn-primary" >
            <i class="fas fa-map"></i>  <?php echo app('translator')->get('team.action.plan.season'); ?></a>
            <a href="<?php echo e(route('club.team.pickchar',[ 'language'=>app()->getLocale(),'club' => $club ]), false); ?>" class="btn btn-primary" >
            <i class="fas fa-edit"></i>  <?php echo app('translator')->get('pick chars'); ?></a>
          </div>
          <!-- /.card-footer -->
        </div>
        <!-- /.card -->

    </div>

    <div class="col-sm-6">
      <!-- card GYMS -->
      <div class="card card-outline card-info collapsed-card">
        <div class="card-header">
          <h4 class="card-title"><i class="fas fa-building"></i> <?php echo e(trans_choice('gym.gym',2 ), false); ?>  <span class="badge badge-pill badge-info"><?php echo e(count($gyms), false); ?></span></h4>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
            </button>
          </div>
          <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <?php $__currentLoopData = $gyms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gym): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <p><button type="button" id="deleteGym" class="btn btn-outline-danger btn-sm" data-gym-id="<?php echo e($gym->id, false); ?>"
            data-gym-name="<?php echo e($gym->gym_no, false); ?> - <?php echo e($gym->name, false); ?>"
            data-club-sname="<?php echo e($club->shortname, false); ?>" data-toggle="modal" data-target="#modalDeleteGym"><i class="fa fa-trash"></i></button>
            <a href="<?php echo e(route('gym.edit',['language'=>app()->getLocale(), 'gym' => $gym ]), false); ?>" class=" px-2">
              <?php echo e($gym->gym_no, false); ?> - <?php echo e($gym->name, false); ?> <i class="fas fa-arrow-circle-right"></i>
          </a></p>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <a href="<?php echo e(route('club.gym.create',['language'=>app()->getLocale(),'club' => $club ]), false); ?>" class="btn btn-primary" >
          <i class="fas fa-plus-circle"></i>  <?php echo app('translator')->get('gym.action.create'); ?>
          </a>
        </div>
        <!-- /.card-footer -->
      </div>
      <!-- /.card -->

      <!-- card GAMES -->
      <div class="card card-outline card-info collapsed-card">
        <div class="card-header">
          <h4 class="card-title"><i class="fas fa-trophy"></i> <?php echo app('translator')->get('game.home'); ?>    <span class="badge badge-pill badge-info"><?php echo e(count($games_home), false); ?></span></h4>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
            </button>
          </div>
          <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="list-group overflow-auto">
            <?php $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php $fname=explode('/',$f); ?>
                 <a href="<?php echo e(route('file.get', ['season'=>$fname[1], 'region'=>$fname[2], 'type'=> $fname[3],'file'=>$fname[4] ] ), false); ?>" class="list-group-item list-group-item-action list-group-item-info"> <?php echo e(basename($f), false); ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <a href="<?php echo e(route('club.list.homegame',['language'=>app()->getLocale(), 'club' => $club ]), false); ?>" class="btn btn-primary" >
          <i class="far fa-edit"></i> <?php echo app('translator')->get('club.action.edit-homegame'); ?></a>
          <a href="<?php echo e(route('club.game.chart',['language'=>app()->getLocale(), 'club' => $club ]), false); ?>" class="btn btn-secondary" >
          <i class="far fa-chart-bar"></i> <?php echo app('translator')->get('club.action.chart-homegame'); ?></a>

        </div>
        <!-- /.card-footer -->
      </div>
      <!-- /.card -->
    </div>
    <!-- ./deck -->
    <!-- all modals here -->
    <?php echo $__env->make('club/includes/assign_league', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('club/includes/club_delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('member/includes/member_delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('team/includes/team_delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('club/gym/includes/gym_delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- all modals above -->
</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
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
             url: "<?php echo e(route('team.deassign-league' ), false); ?>",
             type: "POST",
             dataType: 'json',
             data: {
               club_id: club_id,
               team_id: team_id,
               league_id: league_id,
               _token: "<?php echo e(csrf_token(), false); ?>",
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
       $('#unit_type').html('<?php echo e(trans_choice('club.club',1), false); ?>');
       $('#role_name').html($(this).data('role-name'));
       $('#member_name').html($(this).data('member-name'));
       var url = "<?php echo e(route('membership.club.destroy', ['club'=>$club, 'member'=>':member:']), false); ?>";
       url = url.replace(':member:', $(this).data('member-id'));
       $('#confirmDeleteMember').attr('action', url);
       $('#modalDeleteMember').modal('show');
    });
    $("button#deleteTeam").click( function(){
       $('#team_id').val($(this).data('team-id'));
       $('#club_shortname').html($(this).data('club-sname'));
       $('#league_shortname').html($(this).data('league-sname'));
       $('#team_name').html($(this).data('club-sname')+$(this).data('team-no'));
       var url = "<?php echo e(route('team.destroy', ['team'=>':team:']), false); ?>";
       url = url.replace(':team:', $(this).data('team-id'))
       $('#confirmDeleteTeam').attr('action', url);
       $('#modalDeleteTeam').modal('show');
    });
    $("button#deleteGym").click( function(){
       $('#gym_id').val($(this).data('gym-id'));
       $('#club_shortname').html($(this).data('club-sname'));
       $('#gym_name').html($(this).data('gym-name'));
       var url = "<?php echo e(route('gym.destroy', ['gym'=>':gymid:']), false); ?>";
       url = url.replace(':gymid:',$(this).data('gym-id') );
       $('#confirmDeleteGym').attr('action', url);
       $('#modalDeleteGym').modal('show');
    });
    $("#deleteClub").click( function(){
       var url = "<?php echo e(route('club.destroy', ['club'=>$club]), false); ?>";
       $('#confirmDeleteClub').attr('action', url);
       $('#modalDeleteClub').modal('show');
    });
  });

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/club/club_dashboard.blade.php ENDPATH**/ ?>