<?php

declare(strict_types=1);

namespace NiR\CircuitBreaker\Storage;

use NiR\CircuitBreaker\Service\Configuration;
use NiR\CircuitBreaker\Service\Status;
use NiR\CircuitBreaker\Storage;

class LocalArray implements Storage
{
    /** @var array Service status store. */
    private $store = [];

    public function loadStatus(Configuration $config): Status
    {
        $serviceName = $config->getServiceName();

        if (!isset($this->store[$serviceName])) {
            return new Status($config);
        }

        /** @var $status Status */
        list($ttl, $status) = array_values($this->store[$serviceName]);

        // If a ttl was stored with the status and it is outdated, the default status (closed breaker) is returned
        if ($ttl !== 0 && $status->getLastUpdate() + $ttl < time()) {
            $status = new Status($config);
        }

        return $status;
    }

    public function saveStatus(Configuration $config, Status $status)
    {
        $this->store[$config->getServiceName()] = [
            'ttl' => $status->isClose() ? $config->getDismissDelay() : 0,
            'status' => $status,
        ];
    }
}
