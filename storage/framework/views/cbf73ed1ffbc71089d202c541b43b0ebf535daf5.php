<div class="modal fade right" id="modalCreateEvents" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-side modal-bottom-right modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-info">
                <p class="heading"><?php echo app('translator')->get('schedule.title.event.create', ['schedule'=>$schedule->name]); ?></p>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body">
                <div class="card card-info">

                    <form class="form-horizontal" action="<?php echo e(route('schedule_event.store', ['schedule'=>$schedule]), false); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="card-body">
                            <div class="form-group row ">
                                <label for="startdate" class="col-sm-2 col-form-label">Start Date</label>
                                <div class="col-sm-10">
                                    <div class="input-group date" id="startdate" data-target-input="nearest">
                                        <input type="text" name='startdate' id='startdate' class="form-control datetimepicker-input" data-target="#startdate" />
                                        <div class="input-group-append" data-target="#startdate" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                <button type="submit" class="btn btn-info">Submit</button>
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
        let date = new Date();
        let startDate = date.setDate(date.getDate() + 30);
        let endDate = date.setDate(date.getDate() + 365);

        $('#startdate').datetimepicker({
            format: 'L',
            locale: '<?php echo e(app()->getLocale(), false); ?>',
            useCurrent: true,
            minDate: startDate,
            maxDate: endDate,
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH /var/www/dunkonxt/resources/views/schedule/includes/create_events.blade.php ENDPATH**/ ?>