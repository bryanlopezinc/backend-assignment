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
     * @param array<ResourceId> $vesselIds
     *
     * @return array<VesselPosition>
     */
    public function get(VesselTracksRequestData $filters)
    {
        $query = Model::query();

        if (filled($filters->vesselsIds)) {
            $query->whereIn('vessel_id', array_map(fn (ResourceId $vesselId) => $vesselId->value, $filters->vesselsIds));
        }

        if ($filters->hasRange) {
            $query->where('latitude', '>=', $filters->range->latitude)->where('longitude', '<=', $filters->range->longitude);
        }

        if ($filters->hasTimeInterval) {
            $query->whereBetween('timestamp', [$filters->fromTime, $filters->toTime]);
        }

        return $query->get()->map(fn (Model $model) => VesselPositionBuilder::fromModel($model)->build())->all();
    }
}
