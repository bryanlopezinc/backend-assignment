<?php

declare(strict_types=1);

namespace App\ValueObjects;

final class Status
{
    public function __construct(public readonly int $value)
    {
        if ($value < 0 || $value > 5) {
            throw new \InvalidArgumentException(
                sprintf('status cannot be greater than 5 or less than 1 %s given', $value)
            );
        }
    }
}
