<?php $__env->startSection('plugins.Datatables', true); ?>

<?php $__env->startSection('content'); ?>

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->get('club.title.stats', ['region' => Auth::user()->region ]); ?></h3>
                  </div>
                  <!-- /.card-header -->

                  <div class="card-tools p-2">
          </div>
          <div class="card-body">

         <table width="100%" class="table table-hover table-bordered table-sm" id="table">
            <thead class="thead-light">
               <tr>
                  <th>Id</th>
                  <th><?php echo app('translator')->get('club.shortname'); ?></th>
                  <th>Name</th>
                  <th><?php echo app('translator')->get('league.entitled'); ?></th>
                  <th><?php echo app('translator')->get('team.registered'); ?></th>
                  <th><?php echo e(__('Total Games'), false); ?></th>
                  <th><?php echo e(__('Games No Time'), false); ?></th>
                  <th><?php echo e(__('Games No Teams'), false); ?></th>
               </tr>
            </thead>
         </table>
          </div>
          <!-- /.card-body -->
        </div>
      </div>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('js'); ?>

<script>
   $(function() {
         $('#table').DataTable({
         processing: true,
         serverSide: true,
         responsive: true,
         <?php if(app()->getLocale() == 'de'): ?>
         language: { "url": "<?php echo e(URL::asset('vendor/datatables-plugins/i18n/German.json'), false); ?>" },
         <?php else: ?>
         language: { "url": "<?php echo e(URL::asset('vendor/datatables-plugins/i18n/English.json'), false); ?>" },
         <?php endif; ?>
         order: [[1,'asc']],
         ajax: '<?php echo e(route('club.list_stats'), false); ?>',
         columns: [
                  { data: 'id', name: 'id', visible: false },
                  { data: 'shortname', name: 'shortname' },
                  { data: 'name', name: 'name' },
                  { data: 'leagues_count', name: 'leagues_count'},
                  { data: 'teams_count', name: 'teams_count'},
                  { data: 'games_home_count', name: 'games_home_count'},
                  { data: 'games_home_notime_count', name: 'games_home_notime_count'},
                  { data: 'games_home_noshow_count', name: 'games_home_noshow_count'},
               ]
      });
   });

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/club/club_stats.blade.php ENDPATH**/ ?>