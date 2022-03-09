<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\FetchVesselsTracksRequest;
use App\Http\Resources\VesselPositionResource;
use App\Repositories\FetchVesselsPositionsRepository;
use App\ValueObjects\ResourceId;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class FetchVesselTracksController
{
    public function __invoke(FetchVesselsTracksRequest $request, FetchVesselsPositionsRepository $repository): AnonymousResourceCollection
    {
        $vesselIds = collect($request->validated('mmsi'))->map(fn (int $vesselId) => new ResourceId($vesselId))->all();

        return VesselPositionResource::collection($repository->get($vesselIds));
    }
}
