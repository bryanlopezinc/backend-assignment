<?php

declare(strict_types=1);

namespace App\ValueObjects;

final class ResourceId
{
    public function __construct(public readonly int $value)
    {
        if ($value < 1) {
            throw new \Exception('Invalid resource id ' . $value);
        }
    }
}
