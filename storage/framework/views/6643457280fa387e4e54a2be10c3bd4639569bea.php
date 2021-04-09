<?php $layoutHelper = app('App\Helpers\LayoutHelper'); ?>

<?php ( $dashboard_url = View::getSection('dashboard_url') ?? config('dunkomatic.dashboard_url', 'home') ); ?>

<?php ( $dashboard_url = $dashboard_url ? route($dashboard_url, app()->getLocale()) : '' ); ?>

<a href="<?php echo e($dashboard_url, false); ?>"
    <?php if($layoutHelper->isLayoutTopnavEnabled()): ?>
        class="navbar-brand <?php echo e(config('menu.classes_brand'), false); ?>"
    <?php else: ?>
        class="brand-link <?php echo e(config('menu.classes_brand'), false); ?>"
    <?php endif; ?>>

    
    <img src="<?php echo e(asset(config('menu.logo_img')), false); ?>"
         alt="<?php echo e(config('menu.logo_img_alt', ''), false); ?>"
         class="<?php echo e(config('menu.logo_img_class', 'brand-image img-circle elevation-3'), false); ?>"
         style="opacity:.8">

    
    <span class="brand-text font-weight-light <?php echo e(config('menu.classes_brand_text'), false); ?>">
        <?php echo config('menu.logo'); ?>

    </span>

</a>
<?php /**PATH /var/www/dunkonxt/resources/views/layouts/partials/common/brand-logo-xs.blade.php ENDPATH**/ ?>