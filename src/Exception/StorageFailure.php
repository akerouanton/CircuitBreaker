<?php

declare(strict_types=1);

namespace NiR\CircuitBreaker\Exception;

final class StorageFailure extends \RuntimeException
{
    public static function failedToLoad(string $storage, string $serviceName): self
    {
        return new self(sprintf(
            'Circuit breaker storage (%s) failed to fetch status of "%s".',
            $storage,
            $serviceName
        ));
    }

    public static function failedToSave(string $storage, string $serviceName): self
    {
        return new self(sprintf(
            'Circuit breaker storage (%s) failed to save status of "%s".',
            $storage,
            $serviceName
        ));
    }
}
