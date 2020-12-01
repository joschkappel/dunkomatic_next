<?php $__env->startSection('plugins.Select2', true); ?>
<?php $__env->startSection('plugins.Datatables', true); ?>
<?php $__env->startSection('plugins.Toastr', true); ?>

<?php $__env->startSection('content_header'); ?>
<div class="container-fluid">
    <div class="row ">
      <div class="col-sm">
            <div class="small-box bg-gray">
                  <div class="inner">
                    <div class="row">
                      <div class="col-sm-8 pd-2">
                        <h3><?php echo e($league->shortname, false); ?></h3>
                        <h5><?php echo e($league->name, false); ?> </h5>
                    </div>
                      <div class="col-sm-4 pd-2">
                        <ul class="list-group">
                          <li <?php if(count($assigned_clubs) == 0 ): ?> class="list-group-item list-group-item-danger py-0"> <?php echo app('translator')->get('club.entitled.no'); ?>
                          <?php elseif(count($assigned_clubs) == $league->schedule['size'] ): ?>  class="list-group-item list-group-item-success py-0"> <?php echo app('translator')->get('club.entitled.all'); ?>
                          <?php else: ?>  class="list-group-item list-group-item-warning py-0"> <?php echo app('translator')->get('club.entitled.some', [ 'entitled' => count($assigned_clubs), 'total' => $league->schedule['size']] ); ?>
                          <?php endif; ?>
                          </li>
                          <li <?php if(count($assigned_teams) == 0 ): ?> class="list-group-item list-group-item-danger py-0"> <?php echo app('translator')->get('team.registered.no'); ?>
                          <?php elseif(count($assigned_teams) == $league->schedule['size'] ): ?> class="list-group-item list-group-item-success py-0"> <?php echo app('translator')->get('team.registered.all'); ?>
                          <?php else: ?> class="list-group-item list-group-item-warning py-0"> <?php echo app('translator')->get('team.registered.some', ['registered'=>count($assigned_teams), 'total'=>$league->schedule['size']]); ?>
                          <?php endif; ?>
                          </li>
                          <li <?php if(count($games) == 0 ): ?> class="list-group-item list-group-item-danger py-0"> <?php echo app('translator')->get('game.created.no'); ?>
                          <?php else: ?> class="list-group-item list-group-item-success py-0"> <?php echo app('translator')->get('game.created.some', ['total'=> count($games)]); ?>
                          <?php endif; ?>
                          </li>
                          <li class="list-group-item list-group-item-warning py-0"> <?php echo app('translator')->get('game.notstarted'); ?>
                          </li>
                        </ul>
                    </div>
                  </div>
                  </div>
                  <div class="icon">
                      <i class="fas fa-trophy"></i>
                  </div>
                  <a href="<?php echo e(route('league.edit',['language'=>app()->getLocale(),'league' => $league ]), false); ?>" class="small-box-footer">
                      <?php echo app('translator')->get('league.action.edit'); ?> <i class="fas fa-arrow-circle-right"></i>
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

        <!-- card CLS -->
        <div class="card card-outline card-info " id="clubsCard">
          <?php if( $league->isGenerated ): ?>
          <div class="ribbon-wrapper ribbon-lg">
            <div class="ribbon bg-warning text-lg">
              <?php echo app('translator')->get('league.generated'); ?>
            </div>
          </div>
        <?php endif; ?>
          <div class="card-header">
            <h4 class="card-title"><i class="fas fa-basketball-ball"></i> <?php echo app('translator')->get('club.entitlement'); ?> / <?php echo app('translator')->get('team.registration'); ?>
              <span class="badge badge-pill badge-info"><?php echo e(count($assigned_clubs), false); ?></span> /
              <span class="badge badge-pill badge-info"><?php echo e(count($assigned_teams), false); ?></span> /
              <span class="badge badge-pill badge-info"><?php echo e($league->schedule['size'], false); ?></span>
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
                     <th><?php echo app('translator')->get('club.entitled'); ?></th>
                     <?php if(!$league->isGenerated): ?>
                     <th><?php echo app('translator')->get('team.action.de_assign'); ?></th>
                    <?php endif; ?>
                     <th><?php echo app('translator')->get('team.registered'); ?></th>
                  </tr>
               </thead>
               <tbody>
                 <?php for($i = 1; $i <= $league->schedule['size']; $i++): ?>
                 <tr>
                   <?php if(isset( $assigned_clubs[$i] )): ?>
                     <td><span class="badge badge-pill badge-dark"><?php echo e($i, false); ?></span></td>
                     <td class="text-dark"><?php echo e($assigned_clubs[$i]['shortname'], false); ?></td>
                      <?php if(!$league->isGenerated): ?><td><button id="deassignClub" data-id="<?php echo e($assigned_clubs[$i]['club_id'], false); ?>" type="button" class="btn btn-outline-danger btn-sm "> <i class="fas fa-unlink"></i> </button></td><?php endif; ?>
                   <?php endif; ?>
                   <?php if(empty( $assigned_clubs[$i] )): ?>
                     <td><span class="badge badge-pill badge-info"><?php echo e($i, false); ?></span></td>
                     <td class="text-info"><?php echo app('translator')->get('team.unassigned'); ?></td>
                      <?php if(!$league->isGenerated): ?><td><button type="button" id="assignClub" class="btn btn-outline-info btn-sm" data-itemid="<?php echo e($i, false); ?>" data-toggle="modal" data-target="#modalAssignClub"><i class="fas fa-link"></i></button></td><?php endif; ?>
                   <?php endif; ?>
                   <?php if(isset( $assigned_teams[$i] )): ?>
                     <td class="text-dark"><?php echo e($assigned_teams[$i]['shortname'], false); ?> <?php echo e($assigned_teams[$i]['team_no'], false); ?></td>
                   <?php endif; ?>
                   <?php if(empty( $assigned_teams[$i] )): ?>
                     <td></td>
                   <?php endif; ?>
                 </tr>
                 <?php endfor; ?>
                 
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
          <h4 class="card-title"><i class="fas fa-user-tie"></i> <?php echo e(trans_choice('role.role',2), false); ?>  <span class="badge badge-pill badge-info"><?php echo e(count($members), false); ?></span></h4>
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
            data-league-sname="<?php echo e($league->shortname, false); ?>" data-toggle="modal" data-target="#modalDeleteMember"><i class="fa fa-trash"></i></button>
          <a href="<?php echo e(route('membership.league.edit',['language'=>app()->getLocale(), 'member' => $member, 'league' => $league ]), false); ?>" class=" px-2"><?php echo e($member->name, false); ?> <i class="fas fa-arrow-circle-right"></i></a>
            <?php $__currentLoopData = $member['memberships']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $membership): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <span class="badge badge-secondary"><?php echo e(App\Enums\Role::getDescription($membership->role_id), false); ?></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </p>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <a href="<?php echo e(route('membership.league.create',['language'=>app()->getLocale(), 'league' => $league ]), false); ?>" class="btn btn-outline-secondary" >
          <i class="fas fa-plus-circle"></i>  <?php echo app('translator')->get('role.action.create'); ?>
          </a>
        </div>
        <!-- /.card-footer -->
      </div>
      <!-- /.card -->
      <!-- card GAMES -->
      <div class="card card-outline card-secondary collapsed-card">
        <div class="card-header">
          <h4 class="card-title"><i class="fas fa-trophy"></i> <?php echo e(trans_choice('game.game',2), false); ?>    <span class="badge badge-pill badge-info"><?php echo e(count($games), false); ?></span></h4>
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
          <button type="button" class="btn btn-outline-secondary" id="createGames"
            <?php if(( count($assigned_teams) < 2) or ($league->isGenerated)): ?> disabled <?php endif; ?>><i class="fas fa-plus-circle"></i>  <?php echo app('translator')->get('game.action.create'); ?>
          </button>
          <button type="button" class="btn btn-outline-secondary" id="deleteGames"
              <?php if(!$league->isGenerated): ?> disabled <?php endif; ?>><i class="fa fa-trash"></i>  <?php echo app('translator')->get('game.action.delete'); ?>
          </button>
          <button type="button" class="btn btn-outline-secondary" id="deleteNoshowGames"
              <?php if(!$league->isGenerated): ?> disabled <?php endif; ?>><i class="fa fa-trash"></i>  <?php echo app('translator')->get('game.action.delete.noshow'); ?>
          </button>
          <button type="button" class="btn btn-outline-secondary" id="injectTeam"
              <?php if((!$league->isGenerated) or (count($assigned_teams) == $league->schedule['size'])): ?> disabled <?php endif; ?>><i class="fa fa-trash"></i>  <?php echo app('translator')->get('game.action.team.add'); ?>
          </button>
          <button type="button" class="btn btn-outline-secondary" id="withdrawTeam"
              <?php if((!$league->isGenerated) or (count($assigned_teams) == 0)): ?> disabled <?php endif; ?>><i class="fa fa-trash"></i>  <?php echo app('translator')->get('game.action.team.withdraw'); ?>
          </button>
          <a href="<?php echo e(route('league.game.index',['language'=>app()->getLocale(), 'league' => $league ]), false); ?>" class="btn btn-primary" >
          <i class="far fa-edit"></i> <?php echo app('translator')->get('league.action.game.list'); ?></a>
          <a href="<?php echo e(route('cal.league',['language'=>app()->getLocale(), 'league' => $league ]), false); ?>" class="btn btn-secondary" >
          <i class="fas fa-calendar-alt"></i> iCAL</a>
        </div>
        <!-- /.card-footer -->
      </div>
      <!-- /.card -->
      <!-- all modals here -->
      <?php echo $__env->make('league/includes/assign_club', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      <?php echo $__env->make('member/includes/member_delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      <?php echo $__env->make('league/includes/withdraw_team', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      <?php echo $__env->make('league/includes/inject_team', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      <!-- all modals above -->
    </div>
    <!-- ./deck -->
</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
  $(function() {

    toastr.options.closeButton = true;
    toastr.options.closeMethod = 'fadeOut';
    toastr.options.closeDuration = 300;
    toastr.options.closeEasing = 'swing';
    toastr.options.progressBar = true;

    $("button#deassignClub").click( function(){
            var club_id = $(this).data("id");
            var url = "<?php echo e(route('league.deassign-club', ['league'=>$league, 'club'=>':club:']), false); ?>"
            url = url.replace(':club:', club_id);

            $.ajax({
                type: "POST",
                dataType: 'json',
                data: {
                  id: club_id,
                  _token: "<?php echo e(csrf_token(), false); ?>",
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

     $("button#deleteMember").click( function(){
        $('#member_id').val($(this).data('member-id'));
        $('#unit_type').html('<?php echo e(trans_choice('league.league',1), false); ?>');
        $('#unit_shortname').html($(this).data('league-sname'));
        $('#role_name').html($(this).data('role-name'));
        $('#member_name').html($(this).data('member-name'));
        var url = "<?php echo e(route('membership.league.destroy', ['league'=>$league, 'member'=>':member:' ]), false); ?>";
        url = url.replace(':member:', $(this).data('member-id'));
        $('#confirmDeleteMember').attr('action', url);
        $('#modalDeleteMember').modal('show');
     });

     $("button#createGames").click( function(){
             $.ajax({
                 type: "POST",
                 dataType: "json",
                 data: {
                   _token: "<?php echo e(csrf_token(), false); ?>"
                 },
                 url: "<?php echo e(route('league.game.store', ['league' => $league ]), false); ?>",
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
                    _token: "<?php echo e(csrf_token(), false); ?>"
                  },
                  url: "<?php echo e(route('league.game.destroy', ['league' => $league ]), false); ?>",
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
                     _token: "<?php echo e(csrf_token(), false); ?>"
                   },
                   url: "<?php echo e(route('league.game.destroy_noshow', [ 'league' => $league ]), false); ?>",
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/league/league_dashboard.blade.php ENDPATH**/ ?>