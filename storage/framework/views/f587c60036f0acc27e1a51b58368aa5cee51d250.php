<div class="modal fade right" id="modalDeleteEvents" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-side modal-bottom-right modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-danger">
                <p class="heading"><?php echo app('translator')->get('schedule.title.event.delete', ['schedule'=>$schedule->name]); ?></p>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body">
                <div class="card card-info">

                    <form class="form-horizontal" action="<?php echo e(route('schedule_event.list-destroy', ['schedule'=>$schedule] ), false); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <div class="card-body">
                            <?php echo app('translator')->get('schedule.confirm.event.delete'); ?>
                        </div>
                        <div class="card-footer">
                            <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                <button type="submit" class="btn btn-danger"><?php echo e(__('Submit'), false); ?></button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <!--/.Content-->
        </div>
    </div>
    <!--Modal: modalRelatedContent-->
</div>
<?php /**PATH /var/www/dunkonxt/resources/views/schedule/includes/delete_events.blade.php ENDPATH**/ ?>