<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DataTransferObjects\FetchVesselTracksRequestData;
use App\Http\Requests\FetchVesselsTracksRequest;
use App\Http\Resources\Csv\VesselsResourceCollection as CsvResourceCollection;
use App\Http\Resources\Json\VesselPositionResource;
use App\Http\Resources\Xml\VesselsResourceCollection;
use App\Repositories\FetchVesselsPositionsRepository;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class FetchVesselTracksController
{
    public function __invoke(FetchVesselsTracksRequest $request, FetchVesselsPositionsRepository $repository): Responsable|StreamedResponse
    {
        $vesselsPositions = $repository->get(new FetchVesselTracksRequestData($request));

        return match ($this->getAcceptableContentTypeFrom($request)) {
            'xml' => new VesselsResourceCollection($vesselsPositions),
            'csv' => response()->streamDownload(new CsvResourceCollection($vesselsPositions), 'vesselsPositions.csv', headers: ['Content-Type' => 'text/csv']),
            default => VesselPositionResource::collection($vesselsPositions)
        };
    }

    private function getAcceptableContentTypeFrom(FetchVesselsTracksRequest $request): string
    {
        $accept = $request->getAcceptableContentTypes();

        if (count($accept) > 1 || blank($accept)) {
            return '';
        }

        return match ($accept[0]) {
            'text/csv' => 'csv',
            'application/xml' => 'xml',
            default => ''
        };
    }
}
