<?php $__env->startSection('title', 'DunkOmatic'); ?>

<?php $__env->startSection('content_header'); ?>
    <?php if(!Auth::user()->member()->first()->is_complete): ?> <h3 class="m-0 text-danger"><?php echo app('translator')->get('auth.complete.profile'); ?> </h3>
      <a href="<?php echo e(route(@config('dunkomatic.profile_url'), ['language'=>app()->getLocale(),'user'=>Auth::user()]), false); ?>" class="text-center btn btn-danger btn-sm mb-3"><?php echo app('translator')->get('auth.action.complete.profile'); ?></a>
    <?php else: ?> <h1 class="m-0 text-dark"><?php echo e(trans_choice('message.message',2), false); ?></h1>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p class="mb-0"><?php echo e(__('You are logged in!'), false); ?></p>
                    <?php echo $__env->make('message.includes.message_timeline', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script> console.log('Hi!'); </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/home.blade.php ENDPATH**/ ?>