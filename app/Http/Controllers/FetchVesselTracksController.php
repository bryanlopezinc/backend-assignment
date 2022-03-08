<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\VesselPositionResource;
use App\Repositories\FetchVesselsPositionsRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class FetchVesselTracksController
{
    public function __invoke(FetchVesselsPositionsRepository $repository): AnonymousResourceCollection
    {
        return VesselPositionResource::collection($repository->get());
    }
}
