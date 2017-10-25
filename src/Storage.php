<?php

namespace NiR\CircuitBreaker;

use NiR\CircuitBreaker\Exception\StorageFailure;
use NiR\CircuitBreaker\Service\Configuration;
use NiR\CircuitBreaker\Service\Status;

interface Storage
{
    /**
     * Load service status.
     *
     * @param Configuration $config
     *
     * @throws StorageFailure
     *
     * @return Status Breaker status, initialized as "closed" if it's not found.
     */
    public function loadStatus(Configuration $config): Status;

    /**
     * Save service status.
     *
     * @param Configuration $config
     * @param Status        $status
     *
     * @throws StorageFailure
     */
    public function saveStatus(Configuration $config, Status $status);
}
