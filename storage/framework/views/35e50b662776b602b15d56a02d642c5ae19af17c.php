<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-10">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->get('club.title.gamehome.import', ['club'=>$club->shortname]); ?></h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="<?php echo e(route('club.import.homegame',['language'=> app()->getLocale(),'club' => $club]), false); ?>" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                        <?php echo csrf_field(); ?>
                        <?php if($errors->any()): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo app('translator')->get('Please fix the following errors'); ?>
                        </div>
                        <?php endif; ?>
                        <div class="form-group row">
                            <label for="gfile" class="col-sm-4 col-form-label">Chose a file to upload</label>
                            <div class="col-sm-6">
                                <input type="file" class="form-control-file" accept=".xlsx,application/msexcel" id="gfile" name="gfile" ></input>
                            </div>
                        </div>
                        <?php if($errors->any()): ?>
                        <div class="form-group row">
                            <div class="col-sm-10">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <div class="text-danger"><?php echo e($message, false); ?></div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          </div>
                      </div>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-info"><?php echo e(__('Submit'), false); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/club/club_homegame_upload.blade.php ENDPATH**/ ?>