<aside class="main-sidebar <?php echo e(config('menu.classes_sidebar', 'sidebar-dark-primary elevation-4'), false); ?>">

    
    <?php if(config('dunkomatic.logo_img_xl')): ?>
        <?php echo $__env->make('layouts.partials.common.brand-logo-xl', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php else: ?>
        <?php echo $__env->make('layouts.partials.common.brand-logo-xs', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

    
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column <?php echo e(config('menu.classes_sidebar_nav', ''), false); ?>"
                data-widget="treeview" role="menu"
                <?php if(config('menu.sidebar_nav_animation_speed') != 300): ?>
                    data-animation-speed="<?php echo e(config('menu.sidebar_nav_animation_speed'), false); ?>"
                <?php endif; ?>
                <?php if(!config('menu.sidebar_nav_accordion')): ?>
                    data-accordion="false"
                <?php endif; ?>>
                
                <?php echo $__env->renderEach('layouts.partials.sidebar.menu-item', app(\App\Menu::class)->menu('sidebar'), 'item'); ?>
            </ul>
        </nav>
    </div>

</aside>
<?php /**PATH /var/www/dunkonxt/resources/views/layouts/partials/sidebar/left-sidebar.blade.php ENDPATH**/ ?>