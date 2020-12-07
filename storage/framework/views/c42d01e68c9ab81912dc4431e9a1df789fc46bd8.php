<?php $__env->startSection('plugins.ICheck',true); ?>
<?php $__env->startSection('plugins.Colorpicker',true); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->get('schedule.title.modify', ['schedule' => $schedule->name] ); ?></h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="<?php echo e(route('schedule.update',['schedule' => $schedule]), false); ?>" method="POST">
                <div class="card-body">
                  <?php echo method_field('PUT'); ?>
                  <?php echo csrf_field(); ?>
                        <?php if($errors->any()): ?>
                        <div class="alert alert-danger" role="alert">
                          <?php echo app('translator')->get('Please fix the following errors'); ?>
                        </div>
                        <?php endif; ?>
                        <div class="form-group row ">
                            <label for="title" class="col-sm-4 col-form-label">Name</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" name="name" placeholder="Name" value="<?php echo e($schedule->name, false); ?>">
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
                        <div class="form-group row ">
                          <label for="region_id" class="col-sm-4 col-form-label"><?php echo app('translator')->get('club.region'); ?></label>
                          <div class="col-sm-6">
                              <input type="text" class="form-control" readonly id="region_id" name="region_id" placeholder="<?php echo app('translator')->get('club.region'); ?>" value="<?php echo e($schedule->region_id, false); ?>">
                            </div>
                        </div>
                        <div class="form-group row ">
                              <label for="eventcolor" class="col-sm-4 col-form-label"><?php echo app('translator')->get('schedule.color'); ?></label>
                              <div class="col-sm-6">
                                <div id="cp2" class="input-group">
                                  <input type="text" class="form-control input-lg <?php $__errorArgs = ['eventcolor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="eventcolor" name="eventcolor" placeholder="<?php echo app('translator')->get('schedule.color'); ?>" value="<?php echo e($schedule->eventcolor, false); ?>">
                                  <span class="input-group-append">
                                       <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                     </span>
                                </div>
                              <?php $__errorArgs = ['eventcolor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                              <div class="invalid-feedback"><?php echo e($message, false); ?></div>
                              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                              <!-- /.input group -->
                            </div>
                        </div>
                        <div class="form-group row ">
                          <div class="icheck-info">
                            <input type="checkbox" id="active" name="active"
                            <?php if($schedule->active): ?> checked <?php endif; ?>>
                            <label for="active"><?php echo e(__('Active'), false); ?> ?</label>
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
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>

<script>
  $(function() {
      $('#cp2').colorpicker();
  });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/schedule/schedule_edit.blade.php ENDPATH**/ ?>