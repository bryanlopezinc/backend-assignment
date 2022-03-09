<?php

declare(strict_types=1);

namespace App\Repositories;

use App\ValueObjects\ResourceId;
use App\Models\VesselPosition as Model;
use App\DataTransferObjects\VesselPosition;
use App\DataTransferObjects\Builders\VesselPositionBuilder;

final class FetchVesselsPositionsRepository
{
    /**
     * @param array<ResourceId> $vesselIds
     *
     * @return array<VesselPosition>
     */
    public function get(array $vesselIds = [])
    {
        $query = Model::query();

        if (filled($vesselIds)) {
            $query->whereIn('vessel_id', array_map(fn (ResourceId $vesselId) => $vesselId->value, $vesselIds));
        }

        return $query->get()
            ->map(fn (Model $model) => VesselPositionBuilder::fromModel($model)->build())
            ->all();
    }
}
