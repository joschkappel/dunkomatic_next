<?php $menuItemHelper = app('App\Helpers\MenuItemHelper'); ?>

<?php if($menuItemHelper->isHeader($item)): ?>

    
    <li <?php if(isset($item['id'])): ?> id="<?php echo e($item['id'], false); ?>" <?php endif; ?> class="nav-header">
        <?php echo e(is_string($item) ? $item : $item['header'], false); ?>

    </li>

<?php elseif($menuItemHelper->isSearchBar($item)): ?>

    
    <?php echo $__env->make('layouts.partials.sidebar.menu-item-search-form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php elseif($menuItemHelper->isSubmenu($item)): ?>

    
    <?php echo $__env->make('layouts.partials.sidebar.menu-item-treeview-menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php elseif($menuItemHelper->isLink($item)): ?>

    
    <?php echo $__env->make('layouts.partials.sidebar.menu-item-link', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php endif; ?>
<?php /**PATH /var/www/dunkonxt/resources/views/layouts/partials/sidebar/menu-item.blade.php ENDPATH**/ ?>