<?php

namespace Tests;

use Tests\Support\MigrateFreshSeedOnce;
use Tests\Support\Authentication;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    use CreatesApplication, Authentication, MigrateFreshSeedOnce;

    /**
     * Boot the testing helper traits.
     *
     * @return array
     */
    protected function setUpTraits()
    {
        $uses = parent::setUpTraits();
        if (isset($uses[Authentication::class])) {
            $this->setUpUser();
        }
    }

}
