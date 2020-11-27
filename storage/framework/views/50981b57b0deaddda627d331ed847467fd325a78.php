<?php $__env->startSection('app_css'); ?>
    <?php echo $__env->yieldPushContent('css'); ?>
    <?php echo $__env->yieldContent('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('classes_body', 'login-page'); ?>

<?php ( $dashboard_url = View::getSection('dashboard_url') ?? config('dunkomatic.dashboard_url', 'home') ); ?>

<?php ( $dashboard_url = $dashboard_url ? route($dashboard_url,app()->getLocale()) : '' ); ?>

<?php $__env->startSection('body'); ?>
    <div class="login-box">
        <div class="login-logo">
            <a href="<?php echo e($dashboard_url, false); ?>"><?php echo config('menu.logo'); ?></a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg"><?php echo e(__('auth.verify_message'), false); ?></p>
                <?php if(session('resent')): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo e(__('auth.verify_email_sent'), false); ?>

                    </div>
                <?php endif; ?>

                <?php echo e(__('auth.verify_check_your_email'), false); ?>

                <?php echo e(__('auth.verify_if_not_recieved'), false); ?>,

                <form class="d-inline" method="POST" action="<?php echo e(route('verification.resend', app()->getLocale()), false); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-link p-0 m-0 align-baseline"><?php echo e(__('auth.verify_request_another'), false); ?></button>.
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('app_js'); ?>
    <?php echo $__env->yieldPushContent('js'); ?>
    <?php echo $__env->yieldContent('js'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/auth/verify.blade.php ENDPATH**/ ?>