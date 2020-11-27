<?php $__env->startSection('plugins.Select2', true); ?>
<?php $__env->startSection('plugins.ICheck', true); ?>


<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->get('auth.title.approve'); ?></h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="<?php echo e(route('admin.user.approve', ['language'=>app()->getLocale(), 'user_id'=>$user->id]), false); ?>" method="post">
                    <div class="card-body">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('POST'); ?>
                        <input type="hidden" name="user_id" value="<?php echo e($user->id, false); ?>">
                        <?php if($errors->any()): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo app('translator')->get('Please fix the following errors'); ?>
                        </div>
                        <?php endif; ?>
                        <div class="form-group row">
                            <label for="title" class="col-sm-4 col-form-label"><?php echo app('translator')->get('auth.full_name'); ?></label>
                            <div class="col-sm-6">
                                <input type="input" readonly class="form-control" id="name" value="<?php echo e($user->name, false); ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-sm-4 col-form-label"><?php echo app('translator')->get('auth.email'); ?></label>
                            <div class="col-sm-6">
                                <input type="input" readonly class="form-control" id="email" value="<?php echo e($user->email, false); ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-sm-4 col-form-label"><?php echo app('translator')->get('auth.reason_join'); ?></label>
                            <div class="col-sm-6">
                                <input type="input" readonly class="form-control" id="reason_join" value="<?php echo e($user->reason_join, false); ?>">
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for='selClubs' class="col-sm-4 col-form-label"><?php echo e(trans_choice('club.club',2), false); ?></label>
                            <div class="col-sm-6">
                                <select class='js-clubs-placeholder-single js-states form-control select2 <?php $__errorArgs = ['club_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> /> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>' id='selClubs' name="club_ids[]">
                                </select>
                                <?php $__errorArgs = ['club_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message, false); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for='selLeagues' class="col-sm-4 col-form-label"><?php echo e(trans_choice('league.league',2), false); ?></label>
                            <div class="col-sm-6">
                                <select class='js-leagues-placeholder-single js-states form-control select2 <?php $__errorArgs = ['league_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> /> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>' id='selLeagues' name="league_ids[]">
                                </select>
                                <?php $__errorArgs = ['league_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message, false); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label"></label>
                          <div class="icheck-success icheck-inline ">
                            <input type="checkbox" id="approved" name="approved" <?php if(old('approved') == 'on'): ?> checked <?php endif; ?>>
                            <label for="approved"><?php echo e(__('Approved'), false); ?> ?</label>
                          </div>
                        </div>
                        <div class="form-group row">
                            <label for="reason_reject" class="col-sm-4 col-form-label"><?php echo app('translator')->get('auth.reason_reject'); ?></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control <?php $__errorArgs = ['reason_reject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="reason_reject" name="reason_reject" placeholder="<?php echo app('translator')->get('auth.reason_reject'); ?>" value="<?php echo e(old('reason_reject'), false); ?>">
                                <?php $__errorArgs = ['reason_reject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message, false); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                            <button type="submit" class="btn btn-primary"><?php echo e(__('Submit'), false); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    $(function() {
        $("#selClubs").select2({
            placeholder: "<?php echo app('translator')->get('club.action.select'); ?>...",
            theme: 'bootstrap4',
            multiple: true,
            allowClear: false,
            minimumResultsForSearch: 20,
            ajax: {
                    url: "<?php echo e(route('club.sb.region'), false); ?>",
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
        $("#selLeagues").select2({
            placeholder: "<?php echo app('translator')->get('league.action.select'); ?>...",
            theme: 'bootstrap4',
            multiple: true,
            allowClear: false,
            minimumResultsForSearch: 20,
            ajax: {
                    url: "<?php echo e(route('league.sb.region'), false); ?>",
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


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/auth/user_approve.blade.php ENDPATH**/ ?>