<?php $__env->startSection('app_css'); ?>
    <?php echo $__env->yieldContent('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('classes_body', 'login-page'); ?>

<?php $__env->startSection('body'); ?>
    <div class="login-box">
        <div class="login-logo">
            <a href="<?php echo e(route('home',app()->getLocale()), false); ?>"><?php echo config('menu.logo'); ?></a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg"><?php echo e(trans('auth.password_reset_message'), false); ?></p>
                <form action="<?php echo e(route('password.update', app()->getLocale() ), false); ?>" method="post">
                    <?php echo e(csrf_field(), false); ?>

                    <?php echo method_field('POST'); ?>
                    <input type="hidden" name="token" value="<?php echo e($token, false); ?>">
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control <?php echo e($errors->has('email') ? 'is-invalid' : '', false); ?>" value="<?php echo e(old('email'), false); ?>" placeholder="<?php echo e(trans('email'), false); ?>" autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        <?php if($errors->has('email')): ?>
                            <div class="invalid-feedback">
                                <strong><?php echo e($errors->first('email'), false); ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control <?php echo e($errors->has('password') ? 'is-invalid' : '', false); ?>" placeholder="<?php echo e(trans('password'), false); ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <?php if($errors->has('password')): ?>
                            <div class="invalid-feedback">
                                <strong><?php echo e($errors->first('password'), false); ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password_confirmation" class="form-control <?php echo e($errors->has('password_confirmation') ? 'is-invalid' : '', false); ?>"
                               placeholder="<?php echo e(trans('auth.retype_password'), false); ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <?php if($errors->has('password_confirmation')): ?>
                            <div class="invalid-feedback">
                                <strong><?php echo e($errors->first('password_confirmation'), false); ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block btn-flat">
                        <?php echo e(trans('auth.reset_password'), false); ?>

                    </button>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('app_js'); ?>

    <?php echo $__env->yieldPushContent('js'); ?>
    <?php echo $__env->yieldContent('js'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/auth/passwords/reset.blade.php ENDPATH**/ ?>