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
                    <h3 class="card-title"><?php echo app('translator')->get('auth.title.edit'); ?></h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="<?php echo e(route('admin.user.allowance', ['language'=>app()->getLocale(), 'user'=>$user]), false); ?>" method="post">
                    <div class="card-body">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
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
unset($__errorArgs, $__bag); ?>' multiple="multiple"  id='selClubs' name="club_ids[]">
                                  <?php $__currentLoopData = $user['clubs']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                       <option value="<?php echo e($v, false); ?>" selected><?php echo e($k, false); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
unset($__errorArgs, $__bag); ?>' multiple="multiple" id='selLeagues' name="league_ids[]">
                                  <?php $__currentLoopData = $user['leagues']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($v, false); ?>" selected><?php echo e($k, false); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/auth/user_edit.blade.php ENDPATH**/ ?>