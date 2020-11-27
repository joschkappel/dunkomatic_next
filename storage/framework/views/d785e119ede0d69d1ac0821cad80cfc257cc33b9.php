<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale()), false); ?>">

<head>

    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token(), false); ?>">

    
    <?php echo $__env->yieldContent('meta_tags'); ?>

    
    <title>
        <?php echo $__env->yieldContent('title_prefix', config('dunkomatic.title_prefix', '')); ?>
        <?php echo $__env->yieldContent('title', config('dunkomatic.title', 'dunkomatic')); ?>
        <?php echo $__env->yieldContent('title_postfix', config('dunkomatic.title_postfix', '')); ?>
    </title>

    
    <?php echo $__env->yieldContent('app_css_pre'); ?>

    
    <link rel="stylesheet" href="<?php echo e(asset('vendor/fontawesome-free/css/all.min.css'), false); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css'), false); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('vendor/adminlte/dist/css/adminlte.min.css'), false); ?>">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel="stylesheet" href="<?php echo e(asset('vendor/flag-icon-css/css/flag-icon.min.css'), false); ?>">
    
    <?php echo $__env->make('layouts.plugins', ['type' => 'css'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <?php echo $__env->yieldContent('app_css'); ?>

    
    <?php if(config('dunkomatic.use_ico_only')): ?>
        <link rel="shortcut icon" href="<?php echo e(asset('favicons/favicon.ico'), false); ?>" />
    <?php elseif(config('dunkomatic.use_full_favicon')): ?>
        <link rel="shortcut icon" href="<?php echo e(asset('favicons/favicon.ico'), false); ?>" />
        <link rel="apple-touch-icon" sizes="57x57" href="<?php echo e(asset('favicons/apple-icon-57x57.png'), false); ?>">
        <link rel="apple-touch-icon" sizes="60x60" href="<?php echo e(asset('favicons/apple-icon-60x60.png'), false); ?>">
        <link rel="apple-touch-icon" sizes="72x72" href="<?php echo e(asset('favicons/apple-icon-72x72.png'), false); ?>">
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo e(asset('favicons/apple-icon-76x76.png'), false); ?>">
        <link rel="apple-touch-icon" sizes="114x114" href="<?php echo e(asset('favicons/apple-icon-114x114.png'), false); ?>">
        <link rel="apple-touch-icon" sizes="120x120" href="<?php echo e(asset('favicons/apple-icon-120x120.png'), false); ?>">
        <link rel="apple-touch-icon" sizes="144x144" href="<?php echo e(asset('favicons/apple-icon-144x144.png'), false); ?>">
        <link rel="apple-touch-icon" sizes="152x152" href="<?php echo e(asset('favicons/apple-icon-152x152.png'), false); ?>">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('favicons/apple-icon-180x180.png'), false); ?>">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('favicons/favicon-16x16.png'), false); ?>">
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(asset('favicons/favicon-32x32.png'), false); ?>">
        <link rel="icon" type="image/png" sizes="96x96" href="<?php echo e(asset('favicons/favicon-96x96.png'), false); ?>">
        <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo e(asset('favicons/android-icon-192x192.png'), false); ?>">
        <link rel="manifest" href="<?php echo e(asset('favicons/manifest.json'), false); ?>">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="<?php echo e(asset('favicon/ms-icon-144x144.png'), false); ?>">
    <?php endif; ?>



</head>

<body class="<?php echo $__env->yieldContent('classes_body'); ?>" <?php echo $__env->yieldContent('body_data'); ?>>

    
    <?php echo $__env->yieldContent('body'); ?>

    
    <script src="<?php echo e(asset('vendor/jquery/jquery.min.js'), false); ?>"></script>
    <script src="<?php echo e(asset('vendor/bootstrap/js/bootstrap.bundle.min.js'), false); ?>"></script>
    <script src="<?php echo e(asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js'), false); ?>"></script>
    
    <?php echo $__env->make('layouts.plugins', ['type' => 'js'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <script src="<?php echo e(asset('vendor/adminlte/dist/js/adminlte.min.js'), false); ?>"></script>

    
    <?php echo $__env->yieldContent('app_js'); ?>

</body>

</html>
<?php /**PATH /var/www/dunkonxt/resources/views/layouts/master.blade.php ENDPATH**/ ?>