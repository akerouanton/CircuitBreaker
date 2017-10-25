<?php

declare(strict_types=1);

namespace NiR\CircuitBreaker;

use NiR\CircuitBreaker\Exception\ServiceNotDefined;
use NiR\CircuitBreaker\Exception\StorageFailure;
use NiR\CircuitBreaker\Service\Configuration;

/**
 * Circuit breaker mechanism.
 *
 * @author Albin Kerouanton <albin@akerouanton.name>
 */
final class CircuitBreaker
{
    /** @var Storage */
    private $storage;

    /** @var Service\Configuration[] */
    private $configs;

    /**
     * @param Storage         $storage
     * @param Configuration[] ...$configurations Configuration of the services this CB supports.
     */
    public function __construct(Storage $storage, Configuration ...$configurations)
    {
        $this->storage = $storage;
        $this->addConfigurations(...$configurations);
    }

    /**
     * Add service configurations to be used by this CB.
     *
     * @param Configuration[] ...$configurations
     */
    public function addConfigurations(Configuration ...$configurations)
    {
        foreach ($configurations as $configuration) {
            $this->configs[$configuration->getServiceName()] = $configuration;
        }
    }

    /**
     * Is service available and could be used?
     *
     * @param string $service Name of the service.
     *
     * @throws ServiceNotDefined If the service is not registered.
     * @throws StorageFailure
     *
     * @return bool
     */
    public function isAvailable(string $service): bool
    {
        $config = $this->getConfig($service);
        $status = $this->storage->loadStatus($config);

        return $status->isClose() || $status->isHalfOpen();
    }

    /**
     * Report a successful interaction with a service.
     *
     * @param string $service Name of the service.
     *
     * @throws ServiceNotDefined If the service is not registered.
     * @throws StorageFailure
     */
    public function reportSuccess(string $service)
    {
        $config = $this->getConfig($service);
        $status = $this->storage->loadStatus($config);

        if ($status->isClose() === true) {
            return;
        }

        $this->storage->saveStatus($config, $status->close());
    }

    /**
     * Report a failed interaction with a service.
     *
     * @param string $service Name of the service.
     *
     * @throws ServiceNotDefined If the service is not registered.
     * @throws StorageFailure
     */
    public function reportFailure(string $service)
    {
        $config = $this->getConfig($service);
        $status = $this->storage->loadStatus($config);

        $this->storage->saveStatus($config, $status->failed());
    }

    private function getConfig(string $service): Configuration
    {
        if (!isset($this->configs[$service])) {
            throw ServiceNotDefined::named($service);
        }

        return $this->configs[$service];
    }
}
