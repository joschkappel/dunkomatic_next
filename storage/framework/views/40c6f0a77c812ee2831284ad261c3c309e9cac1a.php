<?php $__env->startSection('plugins.Datatables', true); ?>

<?php $__env->startSection('content'); ?>

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->get('auth.title.list', ['region' => Auth::user()->region ]); ?></h3>
                  </div>
                  <!-- /.card-header -->

                  <div class="card-tools p-2">
                  </div>
                  <div class="card-body">

         <table width="100%" class="table table-hover table-bordered table-sm" id="table">
            <thead class="thead-light">
               <tr>
                  <th>Id</th>
                  <th><?php echo app('translator')->get('auth.full_name'); ?></th>
                  <th><?php echo app('translator')->get('auth.email'); ?></th>
                  <th><?php echo app('translator')->get('auth.user.clubs'); ?></th>
                  <th><?php echo app('translator')->get('auth.user.leagues'); ?></th>
                  <th><?php echo e(__('Created at'), false); ?></th>
                  <th><?php echo e(__('Email verfified at'), false); ?></th>
                  <th><?php echo e(__('Approved at'), false); ?></th>
                  <th><?php echo e(__('Rejected at'), false); ?></th>
                  <th><?php echo e(__('Action'), false); ?></th>
               </tr>
            </thead>
         </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- all modals here -->
        <?php echo $__env->make('auth.includes.user_delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('auth.includes.user_block', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
               ajax: '<?php echo e(route('admin.user.dt', app()->getLocale()), false); ?>',
               columns: [
                        { data: 'id', name: 'id', visible: false },
                        { data: 'name', name: 'name' },
                        { data: 'email', name: 'email' },
                        { data: 'clubs', name: 'clubs' },
                        { data: 'leagues', name: 'leagues' },
                        { data: {
                           _: 'created_at.filter',
                           display: 'created_at.display',
                           sort: 'created_at.ts'
                         }, name: 'created_at.ts' },
                       { data: {
                          _: 'email_verified_at.filter',
                          display: 'email_verified_at.display',
                          sort: 'email_verified_at.ts'
                        }, name: 'email_verified_at.ts' },
                        { data: {
                           _: 'approved_at.filter',
                           display: 'approved_at.display',
                           sort: 'approved_at.ts'
                         }, name: 'approved_at.ts' },
                         { data: {
                            _: 'rejected_at.filter',
                            display: 'rejected_at.display',
                            sort: 'rejected_at.ts'
                          }, name: 'rejected_at.ts' },
                        { data: 'action', name: 'action', orderable: false, searchable: false},
                     ]
            });

            $(document).on('click', '#deleteUser', function () {
                $('#user_id').val($(this).data('user-id'));
                $('#user_name').html($(this).data('user-name'));
                var url = "<?php echo e(route('admin.user.destroy', [ 'user'=>':userid:']), false); ?>"
                url = url.replace(':userid:',$(this).data('user-id') );
                $('#confirmDeleteUser').attr('action', url);
                $('#modalDeleteUser').modal('show');
             });
             $(document).on('click', '#blockUser', function () {
                 $('#user_id_block').val($(this).data('user-id'));
                 $('#user_name_block').html($(this).data('user-name'));
                 var url = "<?php echo e(route('admin.user.block', [ 'user'=>':userid:']), false); ?>"
                 url = url.replace(':userid:',$(this).data('user-id') );
                 $('#confirmBlockUser').attr('action', url);
                 $('#modalBlockUser').modal('show');
              });

         });


</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/auth/user_list.blade.php ENDPATH**/ ?>