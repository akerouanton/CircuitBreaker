<?php

declare(strict_types=1);

namespace NiR\CircuitBreaker\Service;

/**
 * Represents the current status of a service.
 *
 * @author Albin Kerouanton <albin@akerouanton.name>
 *
 * @internal
 */
/* final */ class Status
{
    const CLOSED = 'closed';
    const HALF_OPEN = 'half_open';
    const OPEN = 'open';

    /** @var Configuration Configuration of the related service. */
    private $config;

    /** @var int Current number of failures. */
    private $failures;

    /** @var int Last time status has been updated. */
    private $lastUpdate;

    /**
     * @param Configuration $config     Configuration of the related service.
     * @param int           $failures   Current number of failures.
     * @param int           $lastUpdate Last time status has been updated, as timezone-independent unix timestamp (default to current time).
     */
    public function __construct(Configuration $config, int $failures = 0, int $lastUpdate = 0)
    {
        $this->config = $config;
        $this->failures = $failures;
        $this->lastUpdate = $lastUpdate ?: time();
    }

    /**
     * Creates a new Status with cleared failures and update timestamp.
     *
     * @return Status
     */
    public function close(): self
    {
        return new Status($this->config);
    }

    /**
     * Creates a new Status with the number of failures incremented.
     *
     * @return Status
     */
    public function failed(): self
    {
        return new Status($this->config, $this->failures + 1, time());
    }

    /**
     * Is the service circuit close?
     *
     * @return bool
     */
    public function isClose(): bool
    {
        return $this->getStatus() === self::CLOSED;
    }

    /**
     * Is the service circuit half open?
     *
     * @return bool
     */
    public function isHalfOpen(): bool
    {
        return $this->getStatus() === self::HALF_OPEN;
    }

    /**
     * Is the service circuit open?
     *
     * @return bool
     */
    public function isOpen(): bool
    {
        return $this->getStatus() === self::OPEN;
    }

    /**
     * Get the current number of failures.
     *
     * @return int
     */
    public function getFailures(): int
    {
        return $this->failures;
    }

    /**
     * Get the timestamp of the last update.
     *
     * @return int
     */
    public function getLastUpdate(): int
    {
        return $this->lastUpdate;
    }

    private function getStatus(): string
    {
        if ($this->failures < $this->config->getOpeningThreshold()) {
            return self::CLOSED;
        } elseif ($this->lastUpdate + $this->config->getClosingDelay() < time()) {
            return self::HALF_OPEN;
        }

        return self::OPEN;
    }
}
