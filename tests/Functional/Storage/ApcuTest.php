<?php

declare(strict_types=1);

namespace NiR\CircuitBreaker\Tests\Functional\Storage;

use NiR\CircuitBreaker\Storage;

class ApcuTest extends TestCase
{
    public function tearDown()
    {
        apcu_clear_cache();
    }

    protected function getStorage(): Storage
    {
        return new Storage\Apcu();
    }
}
