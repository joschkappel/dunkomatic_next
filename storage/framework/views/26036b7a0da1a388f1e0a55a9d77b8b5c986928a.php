<?php $__env->startSection('content_header'); ?>
    <?php if(Session::has('success')): ?>
    <div class="alert alert-success">
        <?php echo e(Session::get('success'), false); ?>

    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('plugins.Datatables', true); ?>


<?php $__env->startSection('content'); ?>

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header bg-secondary">
                      <h3 class="card-title"><?php echo app('translator')->get('league.title.list', ['region' =>session('cur_region')->name ]); ?></h3>
                  </div>
                  <!-- /.card-header -->

          <div class="card-body">
         <table width="100%" class="table table-hover table-bordered table-sm" id="table">
            <thead class="thead-light">
               <tr>
                  <th>Id</th>
                  <th><?php echo app('translator')->get('league.shortname'); ?></th>
                  <th><?php echo app('translator')->get('league.name'); ?></th>
                  <th><?php echo e(trans_choice('schedule.schedule',1), false); ?></th>
                  <th><?php echo e(__('Created at'), false); ?></th>
               </tr>
            </thead>
         </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <a href="<?php echo e(route('league.create', app()->getLocale()), false); ?>" class="text-center btn btn-success mb-3"><i class="fas fa-plus-circle"></i> <?php echo app('translator')->get('league.action.create'); ?></a>
          </div>
          <!-- /.card-footer -->
        </div>
      <!-- /.card -->
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
               ajax: '<?php echo e(route('league.list', ['region' => session('cur_region')->id ]), false); ?>',
               columns: [
                        { data: 'id', name: 'id', visible: false },
                        { data: 'shortname', name: 'shortname' },
                        { data: 'name', name: 'name' },
                        { data: 'schedule.name', name: 'name', defaultContent: ''},
                        { data: 'created_at', name: 'created_at'},
                     ]
            });
         });


</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/league/league_list.blade.php ENDPATH**/ ?>