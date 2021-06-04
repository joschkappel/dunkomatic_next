<?php $layoutHelper = app('App\Helpers\LayoutHelper'); ?>

<?php if($layoutHelper->isLayoutTopnavEnabled()): ?>
    <?php ( $def_container_class = 'container' ); ?>
<?php else: ?>
    <?php ( $def_container_class = 'container-fluid' ); ?>
<?php endif; ?>

<?php $__env->startSection('app_css'); ?>
    <?php echo $__env->yieldPushContent('css'); ?>
    <?php echo $__env->yieldContent('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('classes_body', $layoutHelper->makeBodyClasses()); ?>

<?php $__env->startSection('body_data', $layoutHelper->makeBodyData()); ?>

<?php $__env->startSection('body'); ?>
    <div class="wrapper">

        
        <?php if($layoutHelper->isLayoutTopnavEnabled()): ?>
            <?php echo $__env->make('layouts.partials.navbar.navbar-layout-topnav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php else: ?>
            <?php echo $__env->make('layouts.partials.navbar.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>

        
        <?php if(!$layoutHelper->isLayoutTopnavEnabled()): ?>
            <?php echo $__env->make('layouts.partials.sidebar.left-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>

        
        <div class="content-wrapper <?php echo e(config('menu.classes_content_wrapper') ?? '', false); ?>"
             
             >

            
            <div class="content-header">
                <div class="<?php echo e(config('menu.classes_content_header') ?: $def_container_class, false); ?>">
                    <?php echo $__env->yieldContent('content_header'); ?>
                </div>
            </div>

            
            <div class="content">
                <div class="<?php echo e(config('menu.classes_content') ?: $def_container_class, false); ?>">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>

        </div>

        
        
        <?php echo $__env->make('layouts.partials.footer.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        

        
        <?php if(config('menu.right_sidebar')): ?>
            <?php echo $__env->make('layouts.partials.sidebar.right-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('app_js'); ?>
    <?php echo $__env->yieldPushContent('js'); ?>
    <?php echo $__env->yieldContent('js'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/layouts/page.blade.php ENDPATH**/ ?>