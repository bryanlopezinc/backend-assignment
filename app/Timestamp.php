<?php

declare(strict_types=1);

namespace App;

use Carbon\Carbon;

final class Timestamp
{
    public function __construct(private readonly int $value)
    {
    }

    public function toDate(): string
    {
        return Carbon::createFromTimestamp($this->value)->toDateTimeString();
    }
}
