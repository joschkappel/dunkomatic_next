<?php $__env->startSection('plugins.Datatables', true); ?>

<?php $__env->startSection('content'); ?>

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->get('member.title.list', ['region'=>session('cur_region')->name ]); ?></h3>
                  </div>
                  <!-- /.card-header -->
          <div class="card-body">
            <?php echo csrf_field(); ?>

         <table width="100%" class="table table-hover table-bordered table-sm" id="table">
            <thead class="thead-light">
               <tr>
                  <th>Id</th>
                  <th>Name</th>
                  <th>Clubs</th>
                  <th>Leagues</th>
                  <th>Region</th>
                  <th>USer account</th>
                  <th><?php echo e(__('Created at'), false); ?></th>
                  <th><?php echo e(__('Updated at'), false); ?></th>
               </tr>
            </thead>
         </table>
          </div>

        </div>
        <!-- /.card-body -->
        <!-- all modals here -->

        <!-- all modals above -->
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
                 ajax: '<?php echo e(route('member.datatable', ['region' => session('cur_region')->id]), false); ?>',
                 columns: [
                          { data: 'id', name: 'id', visible: false },
                          { data: 'name', name: 'name' },
                          { data: 'clubs', name: 'clubs' },
                          { data: 'leagues', name: 'leagues' },
                          { data: 'regions', name: 'regions' },
                          { data: 'user_account', name: 'user_account' },
                          { data: 'created_at', name: 'created_at'},
                          { data: 'updated_at', name: 'updated_at'},
                       ]
              });
            });

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/member/member_list.blade.php ENDPATH**/ ?>