<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DataTransferObjects\FetchVesselTracksRequestData;
use App\Http\Requests\FetchVesselsTracksRequest;
use App\Http\Resources\Json\VesselPositionResource;
use App\Http\Resources\Xml\VesselsResourceCollection;
use App\Repositories\FetchVesselsPositionsRepository;
use Illuminate\Contracts\Support\Responsable;

final class FetchVesselTracksController
{
    public function __invoke(FetchVesselsTracksRequest $request, FetchVesselsPositionsRepository $repository): Responsable
    {
        $vesselsPositions = $repository->get(new FetchVesselTracksRequestData($request));

        return match ($request->getContentType()) {
            'xml' => new VesselsResourceCollection($vesselsPositions),
            default => VesselPositionResource::collection($vesselsPositions)
        };
    }
}
