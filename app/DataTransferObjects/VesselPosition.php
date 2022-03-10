<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use App\ValueObjects\Coordinates;
use App\ValueObjects\ResourceId;
use App\ValueObjects\Status;

final class VesselPosition
{
    public readonly ResourceId $vesselId;
    public readonly Status $status;
    public readonly ResourceId $stationId;
    public readonly int $speed;
    public readonly Coordinates $coordinates;
    public readonly int $course;
    public readonly int $heading;
    public readonly ?int $rateOfTurn;
    public readonly bool $hasRateOfTurnData;
    public readonly int $timestamp;

    public function __construct(array $atrributes)
    {
        foreach ($atrributes as $key => $atrribute) {
            if (property_exists($this, $key)) {
                $this->{$key} = $atrribute;
            }
        }
    }
}
