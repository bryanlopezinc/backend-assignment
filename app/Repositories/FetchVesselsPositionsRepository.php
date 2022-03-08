<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DataTransferObjects\Builders\VesselPositionBuilder;
use App\DataTransferObjects\VesselPosition;
use App\Models\VesselPosition as Model;

final class FetchVesselsPositionsRepository
{
    /**
     * @return array<VesselPosition>
     */
    public function get()
    {
        return Model::query()
            ->get()
            ->map(fn (Model $model) => VesselPositionBuilder::fromModel($model)->build())
            ->all();
    }
}
