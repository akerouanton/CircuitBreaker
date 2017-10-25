<?php

declare(strict_types=1);

namespace NiR\CircuitBreaker\Exception;

final class ServiceNotDefined extends \InvalidArgumentException
{
    public static function named(string $name): self
    {
        return new self(sprintf('There\'s not configuration defined for service "%s".', $name));
    }
}
