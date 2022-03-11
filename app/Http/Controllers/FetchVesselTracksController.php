<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DataTransferObjects\VesselTracksRequestData;
use App\Http\Requests\FetchVesselsTracksRequest;
use App\Repositories\FetchVesselsPositionsRepository;
use App\ResponseFactory;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class FetchVesselTracksController
{
    public function __invoke(FetchVesselsTracksRequest $request, FetchVesselsPositionsRepository $repository): Responsable|StreamedResponse
    {
        return (new ResponseFactory($request))->response($repository->get(new VesselTracksRequestData($request)));
    }
}
