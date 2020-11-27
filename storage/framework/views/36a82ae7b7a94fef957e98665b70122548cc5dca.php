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
                <form class="form-horizontal" action="<?php echo e(route('admin.user.update', ['user_id' => Auth::user()->id]), false); ?>" method="POST">
                    <div class="card-body">
                        <input type="hidden" name="_method" value="PUT">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <?php if($errors->any()): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo app('translator')->get('Please fix the following errors'); ?>
                        </div>
                        <?php endif; ?>
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 col-form-label"><?php echo app('translator')->get('auth.full_name'); ?></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" name="name" value="<?php echo e(Auth::user()->name, false); ?>">
                                <?php $__errorArgs = ['name'];
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
                        <div class="form-group row">
                            <label for="email" class="col-sm-4 col-form-label"><?php echo app('translator')->get('auth.email'); ?></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="email" name="email" value="<?php echo e(Auth::user()->email, false); ?>">
                                <?php $__errorArgs = ['email'];
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
                        <button type="submit" class="btn btn-info"><?php echo e(__('Submit'), false); ?></button>
                    </div>
                </form>
            </div>
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->get('role.title.edit', ['member'=> $member->firstname.' '.$member->lastname] ); ?></h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="<?php echo e(route('member.update', $member), false); ?>" method="POST">
                    <div class="card-body">
                        <?php echo method_field('PUT'); ?>
                        <?php echo csrf_field(); ?>
                        <?php if($errors->any()): ?>
                        <div class="alert alert-danger" role="alert">
                           <?php echo app('translator')->get('Please fix the following errors'); ?>
                        </div>
                        <?php endif; ?>
                            <?php echo $__env->make('member.includes.member_edit', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <button type="submit" class="btn btn-info"><?php echo e(__('Submit'), false); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/auth/user_profile.blade.php ENDPATH**/ ?>