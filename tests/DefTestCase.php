<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Support\Authentication;
use Tests\Support\MigrateFreshSeedOnce;

abstract class DefTestCase extends BaseTestCase
{
    use CreatesApplication, Authentication, MigrateFreshSeedOnce;

    /**
     * Boot the testing helper traits.
     *
     * @return array
     */
    public function setUpTraits()
    {
        $uses = parent::setUpTraits();
        if (isset($uses[Authentication::class])) {
            $this->setUpUser();
        }

        info('[TEST STARTING] ['.implode(' - ', $this->getGroups()).'] '.$this->getName());

        return [];
    }

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        info('[TEST STOPPING] ['.implode(' - ', $this->getGroups()).'] '.$this->getName());
        parent::tearDown();
    }
}
