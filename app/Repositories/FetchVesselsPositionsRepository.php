<?php

declare(strict_types=1);

namespace App\Repositories;

use App\ValueObjects\ResourceId;
use App\Models\VesselPosition as Model;
use App\DataTransferObjects\VesselPosition;
use App\DataTransferObjects\Builders\VesselPositionBuilder;
use App\DataTransferObjects\VesselTracksRequestData;
use Carbon\Carbon;

final class FetchVesselsPositionsRepository
{
    /**
     * @return array<VesselPosition>
     */
    public function get(VesselTracksRequestData $filters): array
    {
        $query = Model::query();

        if (filled($filters->vesselsIds)) {
            $query->whereIn('vessel_id', array_map(fn (ResourceId $vesselId) => $vesselId->value, $filters->vesselsIds));
        }

        if ($filters->hasRange) {
            $query->whereBetween('latitude', [$filters->minLatitude->value, $filters->maxLatitude->value]);
            $query->whereBetween('longitude', [$filters->minLongitude->value, $filters->maxLongitude->value]);
        }

        if ($filters->hasTimeInterval) {
            $query->whereBetween('timestamp', [$filters->fromTime->timestamp, $filters->toTime->timestamp]);
        }

        return $query->get()->map(fn (Model $model) => VesselPositionBuilder::fromModel($model)->build())->all();
    }
}
