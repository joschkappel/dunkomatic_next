<?php $__env->startSection('plugins.Datatables',true); ?>
<?php $__env->startSection('plugins.Moment',true); ?>
<?php $__env->startSection('plugins.TempusDominus',true); ?>



<?php $__env->startSection('content'); ?>

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->get('schedule.title.event.list', [ 'schedule'=>$schedule->name, 'eventcount'=> $eventcount]); ?></h3>
                  </div>
                  <!-- /.card-header -->

                  <div class="card-tools p-2">
            <button type="button" class="btn btn-info btn-sm mb-3" data-toggle="modal" data-target="#modalCreateEvents"<?php echo e(($eventcount > 0) ? 'disabled' : '', false); ?>><?php echo app('translator')->get('schedule.action.events.create'); ?></button>
            <button type="button" class="btn btn-info btn-sm mb-3" data-toggle="modal" data-target="#modalCloneEvents"<?php echo e(($eventcount > 0) ? 'disabled' : '', false); ?>><?php echo app('translator')->get('schedule.action.events.clone'); ?></button>
            <button type="button" class="btn btn-info btn-sm mb-3" data-toggle="modal" data-target="#modalShiftEvents"<?php echo e(($eventcount == 0) ? 'disabled' : '', false); ?>><?php echo app('translator')->get('schedule.action.events.shift'); ?></button>
            <button type="button" class="btn btn-info btn-sm mb-3" data-toggle="modal" data-target="#modalDeleteEvents"<?php echo e(($eventcount == 0) ? 'disabled' : '', false); ?>><?php echo app('translator')->get('schedule.action.events.delete'); ?></button>
          </div>
          <div class="card-body">
            <?php echo csrf_field(); ?>

         <table width="100%" class="table table-hover table-bordered table-sm" id="table">
            <thead class="thead-light">
               <tr>
                  <th>Id</th>
                  <th>Game Day Sort</th>
                  <th><?php echo app('translator')->get('game.game_day'); ?></th>
                  <th><?php echo app('translator')->get('game.game_date'); ?></th>
                  <th><?php echo app('translator')->get('game.weekend'); ?></th>
                  <th><?php echo e(__('Created at'), false); ?></th>
               </tr>
            </thead>
         </table>
          </div>
          <!-- /.card-body -->
          <!-- all modals here -->
          <?php echo $__env->make('schedule/includes/create_events', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
          <?php echo $__env->make('schedule/includes/clone_events', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
          <?php echo $__env->make('schedule/includes/shift_events', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
          <?php echo $__env->make('schedule/includes/delete_events', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
          <?php echo $__env->make('schedule/includes/edit_event', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
          <!-- all modals above -->
        </div>
      </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>

<script>
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
       ajax: '<?php echo e(route('schedule_event.list-dt',$schedule->id), false); ?>',
       columns: [
                { data: 'id', name: 'id', visible: false },
                { data: 'game_day_sort', name: 'game_day_sort', visible: false },
                { data: 'game_day', name: 'game_day', sortable: false },
                { data: 'game_date', name: 'game_date', sortable: false },
                { data: 'full_weekend', name: 'all_weekend'  },
                { data: 'created_at', name: 'created_at'},
             ]
       });


        var old_gamedate;
        let date = new Date();
        let startDate = date.setDate(date.getDate() + 30);
        let endDate = date.setDate(date.getDate() + 365);


        $('body').on('click', '#eventEditLink', function(){
            $('#game_day').val($(this).data('game-day'));
            console.log($(this).data('game-date'));
            old_gamedate = moment($(this).data('game-date')).format('l');
            if ($(this).data('weekend')=='1'){
              $('input[name="full_weekend"]').attr('checked', true);
            } else {
              $('input[name="full_weekend"]').attr('checked', false);
            }
            moment.locale("<?php echo e(app()->getLocale(), false); ?>");
            $('#game_date').datetimepicker({
                format: 'L',
                locale: '<?php echo e(app()->getLocale(), false); ?>',
                defaultDate: moment($(this).data('game-date')).format('L'),
                minDate: startDate,
                maxDate: endDate,
            });
            $('#editEventForm').attr('action', '/schedule_event/'+$(this).data('id'));
            $('#modalEditEvent').modal('show');
         });


</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/schedule/scheduleevent_list.blade.php ENDPATH**/ ?>