  <!-- Main node for this component -->
  <div class="timeline">

    <?php $__currentLoopData = $msglist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msgdate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <!-- Timeline time label -->
    <div class="time-label">
      <span class="bg-green"><?php echo e(\Carbon\CarbonImmutable::parse($msgdate['valid_from'])->locale( app()->getLocale() )->isoFormat('ll'), false); ?></span>
    </div>
      <?php $__currentLoopData = $msgdate['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div>
    <!-- Before each timeline item corresponds to one icon on the left scale -->
      <i class="fas fa-envelope bg-info "></i>
      <!-- Timeline item -->
      <div class="timeline-item">
      <!-- Time -->
        <span class="time"><i class="fas fa-clock"></i> <?php echo e(\Carbon\CarbonImmutable::parse($msg['created_at'])->locale( app()->getLocale() )->isoFormat('HH:mm:ss'), false); ?></span>
        <!-- Header. Optional -->
        <h3 class="timeline-header"><strong><?php echo e($msg['author'], false); ?></strong>: <?php echo e($msg['subject'], false); ?></h3>
        <!-- Body -->
        <div class="timeline-body">
          <?php echo $msg['body']; ?>

        </div>
      </div>
    </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <!-- The last icon means the story is complete -->
    <div>
      <i class="fas fa-clock bg-gray"></i>
    </div>
  </div>
  Extra style
<?php /**PATH /var/www/dunkonxt/resources/views/message/includes/message_timeline.blade.php ENDPATH**/ ?>