<div class="modal fade right" id="modalCloneEvents" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-side modal-bottom-right modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-info">
                <p class="heading"><?php echo app('translator')->get('schedule.title.event.clone', ['schedule'=>$schedule->name]); ?></p>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body">
                <div class="card card-info">

                    <form class="form-horizontal" action="<?php echo e(route('schedule_event.clone', ['schedule'=>$schedule]), false); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="card-body">
                            <div class="form-group row ">
                              <label class="col-sm-4 col-form-label" for='selSchedule'><?php echo e(trans_choice('schedule.schedule',1), false); ?></label>
                              <div class="col-sm-6">
                                <select class='js-schedule-single js-states form-control select2' id='selSchedule' name='clone_from_schedule'>
                                </select>
                              </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                <button type="submit" class="btn btn-info"><?php echo e(__('Submit'), false); ?></button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <!--/.Content-->
    </div>
</div>
<!--Modal: modalRelatedContent-->
<?php $__env->startPush('js'); ?>

<script>
    $(function() {
      $(".js-schedule-single").select2({
          placeholder: "<?php echo app('translator')->get('schedule.action.select'); ?>...",
          theme: 'bootstrap4',
          multiple: false,
          allowClear: false,
          minimumResultsForSearch: -1,
          ajax: {
                  url: "<?php echo e(route('schedule.sb.size',['schedule'=>$schedule,'league_size' => $schedule->league_size_id]), false); ?>",
                  type: "get",
                  delay: 250,
                  processResults: function (response) {
                    return {
                      results: response
                    };
                  },
                  cache: true
                }
      });
    });
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH /var/www/dunkonxt/resources/views/schedule/includes/clone_events.blade.php ENDPATH**/ ?>