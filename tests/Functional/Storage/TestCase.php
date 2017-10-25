<?php

declare(strict_types=1);

namespace NiR\CircuitBreaker\Tests\Functional\Storage;

use NiR\CircuitBreaker\Service\Configuration;
use NiR\CircuitBreaker\Service\Status;
use NiR\CircuitBreaker\Storage;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    abstract protected function getStorage(): Storage;

    public function testSaveAndLoadStatus()
    {
        $storage = $this->getStorage();
        $config = new Configuration(__METHOD__);
        $status = new Status($config);

        $storage->saveStatus($config, $status);
        $this->assertEquals($status, $storage->loadStatus($config));
    }

    public function testLoadStatusReturnsADefaultStatusWhenTheAvailableOneIsOutdated()
    {
        $storage = $this->getStorage();
        $config = new Configuration(__METHOD__, 20, 60, 30);
        $status = new Status($config, 0, time() - 31);

        $storage->saveStatus($config, $status);
        $this->assertEquals(new Status($config), $storage->loadStatus($config));
    }

    public function testLoadStatusReturnsTheAvailableOneWhenItIsOutdatedButOpen()
    {
        $storage = $this->getStorage();
        $config = new Configuration(__METHOD__, 10, 60, 30);
        $status = new Status($config, 10, time() - 30);

        $storage->saveStatus($config, $status);
        $this->assertEquals($status, $storage->loadStatus($config));
    }

    public function testLoadNonExistentStatus()
    {
        $storage = $this->getStorage();
        $config = new Configuration(__METHOD__);

        $this->assertEquals(new Status($config), $storage->loadStatus($config));
    }
}
