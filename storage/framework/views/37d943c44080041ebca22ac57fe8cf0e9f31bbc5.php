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
                  <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->get('schedule.title.list', ['region'=>Auth::user()->region ]); ?></h3>
                  </div>
                  <!-- /.card-header -->

                  <div class="card-tools p-2">
            <a href="<?php echo e(route('schedule.create', app()->getLocale() ), false); ?>" class="text-center btn btn-success btn-sm mb-3"><?php echo app('translator')->get('schedule.action.create'); ?></a>
          </div>
          <div class="card-body">
            <?php echo csrf_field(); ?>

         <table width="100%" class="table table-hover table-bordered table-sm" id="table">
            <thead class="thead-light">
               <tr>
                  <th>Id</th>
                  <th>Name</th>
                  <th><?php echo app('translator')->get('club.region'); ?></th>
                  <th>Eventcolor</th>
                  <th><?php echo app('translator')->get('schedule.color'); ?></th>
                  <th><?php echo app('translator')->get('schedule.size'); ?></th>
                  <th><?php echo app('translator')->get('schedule.events'); ?></th>
                  <th><?php echo e(__('Active'), false); ?></th>
                  <th><?php echo e(__('Created at'), false); ?></th>
                  <th><?php echo e(__('Action'), false); ?></th>
               </tr>
            </thead>
         </table>
          </div>

        </div>
        <!-- /.card-body -->
        <!-- all modals here -->
        <?php echo $__env->make('schedule/includes/schedule_delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
                 ajax: '<?php echo e(route('schedule.list'), false); ?>',
                 columns: [
                          { data: 'id', name: 'id', visible: false },
                          { data: 'name', name: 'name' },
                          { data: 'region_id', name: 'region_id' },
                          { data: 'eventcolor', name: 'eventcolor', visible: false  },
                          { data: 'color', name: 'color', orderable: false, searchable: false },
                          { data: 'size.description', name: 'description'},
                          { data: 'events', name: 'events'},
                          { data: 'active', name: 'active', searchable: false },
                          { data: 'created_at', name: 'created_at'},
                          { data: 'action', name: 'action', orderable: false, searchable: false},
                       ]
              });
            });

          $(document).on('click', '#deleteSchedule', function () {
              $('#schedule_id').val($(this).data('schedule-id'));
              $('#events').html($(this).data('events'));
              $('#schedule_name').html($(this).data('schedule-name'));
              var url = "<?php echo e(route('schedule.destroy', ['schedule'=>':scheduleid:']), false); ?>";
              url = url.replace(':scheduleid:',$(this).data('schedule-id') );
              $('#confirmDeleteSchedule').attr('action', url);
              $('#modalDeleteSchedule').modal('show');
           });


</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/schedule/schedule_list.blade.php ENDPATH**/ ?>