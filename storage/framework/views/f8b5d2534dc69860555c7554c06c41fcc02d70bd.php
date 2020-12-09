<div class="modal fade right" id="modalShiftEvents" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-side modal-bottom-right modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-info">
                <p class="heading"><?php echo app('translator')->get('schedule.title.event.shift', ['schedule'=>$schedule->name]); ?></p>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body">
                <div class="card card-info">

                    <form class="form-horizontal" action="<?php echo e(route('schedule_event.shift', ['schedule'=>$schedule]), false); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="card-body">
                            <div class="form-group row ">
                                <label for="radios" class="col-sm-4 col-form-label"><?php echo app('translator')->get('schedule.event.direction'); ?></label>
                                <div class="col-sm-6">
                                    <label for="radio1" class="col-form-label radio-inline">
                                        <?php echo e(Form::radio('direction', '+', true, ['id' => 'radio1']), false); ?> <?php echo app('translator')->get('schedule.event.forward'); ?>
                                    </label>
                                    <label for="radio2" class="col-form-label radio-inline">
                                        <?php echo e(Form::radio('direction', '-', false, ['id' => 'radio2']), false); ?> <?php echo app('translator')->get('schedule.event.backward'); ?>
                                    </label>

                                </div>
                            </div>
                            <div class="form-group row ">
                              <label for="unitRange" class="col-sm-4 col-form-label"><?php echo app('translator')->get('schedule.event.range'); ?></label>
                                <div class="col-sm-6">
                                    <input name='unitRange' type="range" class="custom-range" min="1" max="12" id="unitRange">
                                    <span class="font-weight-bold text-primary ml-2 mt-1 valueSpan"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="radios3" class="col-sm-4 col-form-label"><?php echo app('translator')->get('schedule.event.unit'); ?></label>
                                <div class="col-sm-6">
                                    <label for="radio10" class="col-form-label radio-inline">
                                        <?php echo e(Form::radio('unit', 'DAY', true, ['id' => 'radio10']), false); ?> <?php echo app('translator')->get('schedule.event.unit.day'); ?>
                                    </label>
                                    <label for="radio11" class="col-form-label radio-inline">
                                        <?php echo e(Form::radio('unit', 'WEEK', false, ['id' => 'radio11']), false); ?> <?php echo app('translator')->get('schedule.event.unit.week'); ?>
                                    </label>
                                    <label for="radio12" class="col-form-label radio-inline">
                                        <?php echo e(Form::radio('unit', 'MONTH', false, ['id' => 'radio12']), false); ?> <?php echo app('translator')->get('schedule.event.unit.month'); ?>
                                    </label>
                                    <label for="radio13" class="col-form-label radio-inline">
                                        <?php echo e(Form::radio('unit', 'YEAR', false, ['id' => 'radio13']), false); ?> <?php echo app('translator')->get('schedule.event.unit.year'); ?>
                                    </label>
                                </div>

                            </div>
                            <div class="card-footer">
                                <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                    <button type="submit" class="btn btn-info"><?php echo e(__('Submit'), false); ?></button>
                                </div>
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
    const $valueSpan = $('.valueSpan');
    const $value = $('#unitRange');
    $valueSpan.html($value.val());
    $value.on('input change', () => {
        $valueSpan.html($value.val());
    });
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH /var/www/dunkonxt/resources/views/schedule/includes/shift_events.blade.php ENDPATH**/ ?>