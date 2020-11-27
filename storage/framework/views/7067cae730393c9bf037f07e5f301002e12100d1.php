<div class="modal fade right" id="modalDeleteUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-side modal-bottom-right modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-danger">
                <p class="heading" id="dheader"><?php echo app('translator')->get('auth.title.delete'); ?>
                </p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body">
                <div class="card card-info">
                    <form id="confirmDeleteUser" class="form-horizontal" action="" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <input type="hidden" name="user_id" id="user_id" value="">
                        <div class="card-body">
                            <p class="text-left"><?php echo app('translator')->get('auth.confirm.delete'); ?></p>
                            <h4 class="text-left text-danger">
                              <span id="user_name"></span></p>
                            </h4>
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
<?php /**PATH /var/www/dunkonxt/resources/views/auth/includes/user_delete.blade.php ENDPATH**/ ?>