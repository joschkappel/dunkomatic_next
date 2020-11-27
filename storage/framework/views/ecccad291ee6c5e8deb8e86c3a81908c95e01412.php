<li <?php if(isset($item['id'])): ?> id="<?php echo e($item['id'], false); ?>" <?php endif; ?> class="nav-item has-treeview <?php echo e($item['submenu_class'], false); ?>">

    
    <a class="nav-link <?php echo e($item['class'], false); ?> <?php if(isset($item['shift'])): ?> <?php echo e($item['shift'], false); ?> <?php endif; ?>"
       href="" <?php echo $item['data-compiled'] ?? ''; ?>>

        <i class="<?php echo e($item['icon'] ?? 'far fa-fw fa-circle', false); ?> <?php echo e(isset($item['icon_color']) ? 'text-'.$item['icon_color'] : '', false); ?>"></i>

        <p>
            <?php echo e($item['text'], false); ?>

            <i class="fas fa-angle-left right"></i>

            <?php if(isset($item['label'])): ?>
                <span class="badge badge-<?php echo e($item['label_color'] ?? 'primary', false); ?> right">
                    <?php echo e($item['label'], false); ?>

                </span>
            <?php endif; ?>
        </p>

    </a>

    
    <ul class="nav nav-treeview">
        <?php echo $__env->renderEach('layouts.partials.sidebar.menu-item', $item['submenu'], 'item'); ?>
    </ul>

</li>
<?php /**PATH /var/www/dunkonxt/resources/views/layouts/partials/sidebar/menu-item-treeview-menu.blade.php ENDPATH**/ ?>