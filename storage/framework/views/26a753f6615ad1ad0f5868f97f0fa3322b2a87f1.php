<?php $__env->startSection('plugins.Datatables', true); ?>

<?php $__env->startSection('content_header'); ?>
    <?php if(Session::has('success')): ?>
    <div class="alert alert-success">
        <?php echo e(Session::get('success'), false); ?>

    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->get('region.title.list'); ?></h3>
                  </div>
                  <!-- /.card-header -->

                  <div class="card-tools p-2">
                    <a href="<?php echo e(route('region.create', app()->getLocale()), false); ?>" class="text-center btn btn-success mb-3"><i class="fas fa-plus-circle"></i> <?php echo app('translator')->get('region.action.create'); ?></a>
          </div>
          <div class="card-body">

         <table width="100%" class="table table-hover table-bordered" id="table">
            <thead class="thead-light">
               <tr>
                  <th>Id</th>
                  <th><?php echo app('translator')->get('region.code'); ?></th>
                  <th><?php echo app('translator')->get('region.name'); ?></th>
                  <th><?php echo app('translator')->get('region.hq'); ?></th>
                  <th><?php echo e(__('Created at'), false); ?></th>
                  <th><?php echo e(__('Updated at'), false); ?></th>
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
               ajax: '<?php echo e(route('region.list.dt', ['language'=>app()->getLocale()]), false); ?>',
               columns: [
                        { data: 'id', name: 'id', visible: false },
                        { data: 'code', name: 'code' },
                        { data: 'name', name: 'name' },
                        { data: 'hq', name: 'hq' },
                        { data: 'created_at', name: 'created_at'},
                        { data: 'updated_at', name: 'updated_at'},
                     ]
            });
         });


</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/admin/region_list.blade.php ENDPATH**/ ?>