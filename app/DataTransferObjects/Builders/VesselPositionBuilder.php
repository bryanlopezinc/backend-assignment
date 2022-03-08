<?php

declare(strict_types=1);

namespace App\DataTransferObjects\Builders;

use App\DataTransferObjects\VesselPosition;
use App\Models\VesselPosition as Model;
use App\ValueObjects\Coordinates;
use App\ValueObjects\ResourceId;

final class VesselPositionBuilder
{
    public function __construct(private array $attributes = [])
    {
    }

    public static function fromModel(Model $model): self
    {
        $attributes = [];

        $attributes['vesselId'] = new ResourceId($model['vessel_id']);
        $attributes['status'] = $model['status'];
        $attributes['stationId'] = new ResourceId($model['station_id']);
        $attributes['speed'] = $model['speed'];
        $attributes['coordinates'] = new Coordinates($model['latitude'], $model['longitude']);
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
