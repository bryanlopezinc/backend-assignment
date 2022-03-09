<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DataTransferObjects\FetchVesselTracksRequestData;
use App\Http\Requests\FetchVesselsTracksRequest;
use App\Http\Resources\VesselPositionResource;
use App\Repositories\FetchVesselsPositionsRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class FetchVesselTracksController
{
    public function __invoke(FetchVesselsTracksRequest $request, FetchVesselsPositionsRepository $repository): AnonymousResourceCollection
    {
        return VesselPositionResource::collection($repository->get(new FetchVesselTracksRequestData($request)));
    }
}
