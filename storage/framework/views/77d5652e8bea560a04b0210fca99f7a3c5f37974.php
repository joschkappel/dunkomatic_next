<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale()), false); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>
          <?php echo $__env->yieldContent('title_prefix', config('dunkomatic.title_prefix', '')); ?>
          <?php echo $__env->yieldContent('title', config('dunkomatic.title', 'dunkomatic')); ?>
          <?php echo $__env->yieldContent('title_postfix', config('dunkomatic.title_postfix', '')); ?>
        </title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo e(asset('vendor/flag-icon-css/css/flag-icon.min.css'), false); ?>">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <?php if(Route::has('login')): ?>
                <div class="top-right links">
                      <a href="<?php echo e(route('welcome', 'en'), false); ?>" ><i class="flag-icon flag-icon-gb"></i></a>
                      <a href="<?php echo e(route('welcome', 'de'), false); ?>" ><i class="flag-icon flag-icon-de"></i></a>
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('home', ['language'=> app()->getLocale()]), false); ?>">Home</a>
                    <?php else: ?>

                        <a href="<?php echo e(route('login', app()->getLocale()), false); ?>"><?php echo e(__('auth.sign_in'), false); ?></a>

                        <?php if(Route::has('register')): ?>
                            <a href="<?php echo e(route('register', app()->getLocale()), false); ?>"><?php echo e(__('auth.register'), false); ?></a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="content">
                <div class="title m-b-md">
                  <?php echo $__env->yieldContent('title_prefix', config('dunkomatic.title_prefix', '')); ?>
                  <?php echo $__env->yieldContent('title', config('dunkomatic.title', 'dunkomatic')); ?>
                  <?php echo $__env->yieldContent('title_postfix', config('dunkomatic.title_postfix', '')); ?>
                </div>

                <div class="links">
                    <a href="https://laravel.com/docs">Docs</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://blog.laravel.com">Blog</a>
                    <a href="https://nova.laravel.com">Nova</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://vapor.laravel.com">Vapor</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div>
            </div>
        </div>
    </body>
</html>
<?php /**PATH /var/www/dunkonxt/resources/views/welcome.blade.php ENDPATH**/ ?>