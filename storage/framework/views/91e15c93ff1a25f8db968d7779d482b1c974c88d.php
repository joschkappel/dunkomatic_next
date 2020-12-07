<div class="card-body" id="pivottable">
    <?php if(isset($events)): ?>
    <table width="100%" class="table table-hover table-striped table-bordered table-sm" id="table">
        <thead class="thead-light">
            <tr>
                <?php $__currentLoopData = $events[0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <th class="text-center">
                    <h6><?php echo e($key, false); ?></h6>
                </th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <?php $__currentLoopData = $gd; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $values): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <td class="text-center">
                    <h5>
                        <?php if($values == ' '): ?>
                        <?php elseif($key == __('game.game_date')): ?><?php echo e($values, false); ?>

                        <?php else: ?><span class="badge badge-pill badge-info"><?php echo e($values, false); ?></span>
                        <?php endif; ?>
                    </h5>
                </td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php endif; ?>
    <?php if(empty($events)): ?>

    <?php endif; ?>
</div>
<?php /**PATH /var/www/dunkonxt/resources/views/schedule/includes/scheduleevent_pivot.blade.php ENDPATH**/ ?>