<?php

declare(strict_types=1);

namespace App\Repositories;

use App\ValueObjects\ResourceId;
use App\Models\VesselPosition as Model;
use App\DataTransferObjects\VesselPosition;
use App\DataTransferObjects\Builders\VesselPositionBuilder;
use App\DataTransferObjects\FetchVesselTracksRequestData;

final class FetchVesselsPositionsRepository
{
    /**
     * @param array<ResourceId> $vesselIds
     *
     * @return array<VesselPosition>
     */
    public function get(FetchVesselTracksRequestData $filters)
    {
        $query = Model::query();

        if (filled($filters->vesselIds)) {
            $query->whereIn('vessel_id', array_map(fn (ResourceId $vesselId) => $vesselId->value, $filters->vesselIds));
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
