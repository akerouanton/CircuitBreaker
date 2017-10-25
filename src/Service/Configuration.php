<?php

declare(strict_types=1);

namespace NiR\CircuitBreaker\Service;

/**
 * Defines the configuration of a specific service (max. # failures allowed, opening delay, ...).
 *
 * @author Albin Kerouanton <albin@akerouanton.name>
 *
 * @internal
 */
/* final */ class Configuration
{
    /** @var string Name of the related service. */
    private $serviceName;

    /** @var int Number of failures allowed before circuit becomes open. */
    private $openingThreshold;

    /** @var int Number of seconds before open circuit become half open. */
    private $closingDelay;

    /** @var int Number of seconds before non-circuit-breaking errors are dismissed. */
    private $dismissDelay;

    /**
     * @param string $serviceName      Name of the related service.
     * @param int    $openingThreshold Number of failures allowed before circuit becomes open.
     * @param int    $closingDelay     Number of seconds before open circuit become half open.
     * @param int    $dismissDelay     Number of seconds before non-circuit-breaking errors are dismissed.
     *                                 There is basically no need to break the circuit if the last error was (long) time ago
     */
    public function __construct(string $serviceName, int $openingThreshold = 20, int $closingDelay = 60, int $dismissDelay = 30)
    {
        $this->serviceName = $serviceName;
        $this->openingThreshold = $openingThreshold;
        $this->closingDelay = $closingDelay;
        $this->dismissDelay = $dismissDelay;
    }

    /**
     * Get the name of this service.
     *
     * @return string
     */
    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    /**
     * Get the number of failures allowed before circuit opens up.
     *
     * @return int
     */
    public function getOpeningThreshold(): int
    {
        return $this->openingThreshold;
    }

    /**
     * Get the number of seconds before open circuit becomes half open.
     *
     * @return int
     */
    public function getClosingDelay(): int
    {
        return $this->closingDelay;
    }

    /**
     * Get the number of seconds before non-circuit-breaking errors are dismissed.
     *
     * @return int
     */
    public function getDismissDelay(): int
    {
        return $this->dismissDelay;
    }
}
