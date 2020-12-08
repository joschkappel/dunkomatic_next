<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->get('club.title.edit', ['club'=>$club->shortname]); ?></h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="<?php echo e(route('club.update',['language'=>app()->getLocale(),'club' => $club]), false); ?>" method="POST">
                    <div class="card-body">
                        <input type="hidden" name="_method" value="PUT">
                        <?php echo csrf_field(); ?>
                        <?php if($errors->any()): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo app('translator')->get('Please fix the following errors'); ?>
                        </div>
                        <?php endif; ?>
                        <div class="form-group row">
                            <label for="region" class="col-sm-4 col-form-label"><?php echo app('translator')->get('club.region'); ?></label>
                            <div class="col-sm-6">
                                <input type="text" readonly class="form-control <?php $__errorArgs = ['region'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="region" name="region" placeholder="<?php echo app('translator')->get('club.region'); ?>" value="<?php echo e($club->region->code, false); ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="club_no" class="col-sm-4 col-form-label"><?php echo app('translator')->get('club.club_no'); ?></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control <?php $__errorArgs = ['club_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="club_no" name="club_no" placeholder="<?php echo app('translator')->get('club.club_no'); ?>" value="<?php echo e($club->club_no, false); ?>">
                                <?php $__errorArgs = ['club_no'];
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
                            <label for="shortname" class="col-sm-4 col-form-label"><?php echo app('translator')->get('club.shortname'); ?></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control <?php $__errorArgs = ['shortname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="shortname" name="shortname" placeholder="<?php echo app('translator')->get('club.shortname'); ?>" value="<?php echo e($club->shortname, false); ?>">
                                <?php $__errorArgs = ['shortname'];
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
                            <label for="name" class="col-sm-4 col-form-label"><?php echo app('translator')->get('club.name'); ?></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" name="name" placeholder="<?php echo app('translator')->get('club.name'); ?>" value="<?php echo e($club->name, false); ?>">
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
                            <label for="url" class="col-sm-4 col-form-label">URL</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control <?php $__errorArgs = ['url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="url" name="url" placeholder="URL" value="<?php echo e($club->url, false); ?>">
                                <?php $__errorArgs = ['url'];
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
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/club/club_edit.blade.php ENDPATH**/ ?>