<tr>
<td class="header">
<a href="<?php echo e($url, false); ?>" style="display: inline-block;">
<?php if(trim($slot) === 'Laravel'): ?>
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
<?php else: ?>
<?php echo e($slot, false); ?>

<?php endif; ?>
</a>
</td>
</tr>
<?php /**PATH /var/www/dunkonxt/vendor/laravel/framework/src/Illuminate/Mail/resources/views/html/header.blade.php ENDPATH**/ ?>