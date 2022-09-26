<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Mews\Captcha\Captcha;
use Mockery;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        /**
         * Override captcha binding with a double that always passes.
         */
        $app->bind('captcha', function ($app) {
            $mockCaptcha = Mockery::mock(Captcha::class);

            $mockCaptcha
                ->allows()
                ->img('math')
                ->andReturn('');

            $mockCaptcha
                ->allows()
                ->check('mockcaptcha=12345')
                ->andReturn(true);

            return $mockCaptcha;
        });

        return $app;
    }
}
