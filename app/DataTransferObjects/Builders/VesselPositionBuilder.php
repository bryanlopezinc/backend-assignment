<?php

declare(strict_types=1);

namespace App\DataTransferObjects\Builders;

use App\DataTransferObjects\VesselPosition;
use App\Models\VesselPosition as Model;
use App\ValueObjects\Latitude;
use App\ValueObjects\Longitude;
use App\ValueObjects\ResourceId;
use App\ValueObjects\Status;

final class VesselPositionBuilder
{
    public function __construct(private array $attributes = [])
    {
    }

    public static function fromModel(Model $model): self
    {
        $attributes = [];

        $attributes['vesselId'] = new ResourceId($model['vessel_id']);
        $attributes['status'] = new Status($model['status']);
        $attributes['stationId'] = new ResourceId($model['station_id']);
        $attributes['speed'] = $model['speed'];
        $attributes['latitude'] = new Latitude($model['latitude']);
        $attributes['longitude'] = new Longitude($model['longitude']);
        $attributes['course'] = $model['course'];
        $attributes['heading'] = $model['heading'];
        $attributes['rateOfTurn'] = $model['rate_of_turn'];
        $attributes['hasRateOfTurnData'] = filled($model['rate_of_turn']);
        $attributes['timestamp'] = $model['timestamp'];

        return new self($attributes);
    }

    public function build(): VesselPosition
    {
        return new VesselPosition($this->attributes);
    }
}
