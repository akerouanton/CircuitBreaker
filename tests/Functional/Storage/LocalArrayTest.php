<?php

declare(strict_types=1);

namespace NiR\CircuitBreaker\Tests\Functional\Storage;

use NiR\CircuitBreaker\Storage;
use NiR\CircuitBreaker\Storage\LocalArray;

class LocalArrayTest extends TestCase
{
    protected function getStorage(): Storage
    {
        return new LocalArray();
    }
}
