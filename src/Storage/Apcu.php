<?php

declare(strict_types=1);

namespace NiR\CircuitBreaker\Storage;

use NiR\CircuitBreaker\Exception\StorageFailure;
use NiR\CircuitBreaker\Service\Configuration;
use NiR\CircuitBreaker\Service\Status;
use NiR\CircuitBreaker\Storage;

class Apcu implements Storage
{
    /** @var string */
    private $keyPrefix;

    /**
     * @param string $keyPrefix Prefix used for the apcu key of stored statuses.
     *                          Can be useful to mitigate conflicts between multiple projects.
     */
    public function __construct(string $keyPrefix = 'nir.circuit_breaker.')
    {
        if (!extension_loaded('apcu')) {
            throw new \RuntimeException('You need to install/enable APCu extension if you want to use Apcu storage.');
        }

        if (php_sapi_name() === 'cli' && ini_get('apc.enable_cli') === '0') {
            throw new \RuntimeException('APCu is not enabled in cli environment. You should either enable it ("apc.enable_cli=1" in your php.ini) or use another storage.');
        }

        $this->keyPrefix = $keyPrefix;
    }

    public function loadStatus(Configuration $config): Status
    {
        $key = $this->getItemKey($config);

        if (!apcu_exists($key)) {
            return new Status($config);
        }

        if (false === $fetched = apcu_fetch($key)) {
            throw StorageFailure::failedToLoad(__CLASS__, $config->getServiceName());
        }

        list($ttl, $status) = array_values($fetched);

        // If a ttl was stored with the status and it is outdated, the default status (closed breaker) is returned
        if ($ttl !== 0 && $status->getLastUpdate() + $ttl < time()) {
            $status = new Status($config);
        }

        return $status;
    }

    public function saveStatus(Configuration $config, Status $status)
    {
        $key = $this->getItemKey($config);
        $ttl = $status->isClose() ? $config->getDismissDelay() : 0;
        $item = ['ttl' => $ttl, 'status' => $status];

        if (apcu_store($key, $item, $ttl) === false) {
            throw StorageFailure::failedToSave(__CLASS__, $config->getServiceName());
        }
    }

    private function getItemKey(Configuration $config): string
    {
        return sprintf('%s%s', $this->keyPrefix, $config->getServiceName());
    }
}
