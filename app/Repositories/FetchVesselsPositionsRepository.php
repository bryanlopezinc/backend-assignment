<?php

declare(strict_types=1);

namespace App\Repositories;

use App\ValueObjects\ResourceId;
use App\Models\VesselPosition as Model;
use App\DataTransferObjects\VesselPosition;
use App\DataTransferObjects\Builders\VesselPositionBuilder;
use App\DataTransferObjects\VesselTracksRequestData;

final class FetchVesselsPositionsRepository
{
    /**
     * @return array<VesselPosition>
     */
    public function get(VesselTracksRequestData $filters): array
    {
        $builder = Model::query();

        $builder->when(filled($filters->vesselsIds), function ($builder) use ($filters) {
            $builder->whereIn('vessel_id', array_map(fn (ResourceId $vesselId) => $vesselId->value, $filters->vesselsIds));
        });

        $builder->when($filters->hasRange, function ($builder) use ($filters) {
            $builder->whereBetween('latitude', [$filters->minLatitude->value, $filters->maxLatitude->value]);
            $builder->whereBetween('longitude', [$filters->minLongitude->value, $filters->maxLongitude->value]);
        });

        $builder->when($filters->hasTimeInterval, function ($builder) use ($filters) {
            $builder->whereBetween('timestamp', [$filters->fromTime->timestamp, $filters->toTime->timestamp]);
        });

        return $builder->get()->map(fn (Model $model) => VesselPositionBuilder::fromModel($model)->build())->all();
    }
}
