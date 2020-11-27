<li <?php if(isset($item['id'])): ?> id="<?php echo e($item['id'], false); ?>" <?php endif; ?> class="nav-item">

    <a class="nav-link <?php echo e($item['class'], false); ?> <?php if(isset($item['shift'])): ?> <?php echo e($item['shift'], false); ?> <?php endif; ?>"
       href="<?php echo e($item['href'], false); ?>" <?php if(isset($item['target'])): ?> target="<?php echo e($item['target'], false); ?>" <?php endif; ?>
       <?php echo $item['data-compiled'] ?? ''; ?>>

        <i class="<?php echo e($item['icon'] ?? 'far fa-fw fa-circle', false); ?> <?php echo e(isset($item['icon_color']) ? 'text-'.$item['icon_color'] : '', false); ?>"></i>

        <p>
            <?php echo e($item['text'], false); ?>


            <?php if(isset($item['label'])): ?>
                <span class="badge badge-<?php echo e($item['label_color'] ?? 'primary', false); ?> right">
                    <?php echo e($item['label'], false); ?>

                </span>
            <?php endif; ?>
        </p>

    </a>

</li><?php /**PATH /var/www/dunkonxt/resources/views/layouts/partials/sidebar/menu-item-link.blade.php ENDPATH**/ ?>