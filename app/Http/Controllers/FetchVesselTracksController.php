<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DataTransferObjects\VesselTracksRequestData;
use App\Http\Requests\FetchVesselsTracksRequest;
use App\Http\Resources\Csv\ResourceCollection as CsvResourceCollection;
use App\Http\Resources\Json\VesselPositionResource;
use App\Http\Resources\Xml\ResourceCollection as XmlResourceCollection;
use App\Repositories\FetchVesselsPositionsRepository;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class FetchVesselTracksController
{
    public function __invoke(FetchVesselsTracksRequest $request, FetchVesselsPositionsRepository $repository): Responsable|StreamedResponse
    {
        $vesselsPositions = $repository->get(new VesselTracksRequestData($request));

        return match ($this->getAcceptableContentTypeFrom($request)) {
            'xml' => new XmlResourceCollection($vesselsPositions),
            'csv' => $this->getCsvResponse($vesselsPositions),
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

    private function getCsvResponse(array $vesselsPositions): StreamedResponse
    {
        return response()->streamDownload(
            \Closure::fromCallable(new CsvResourceCollection($vesselsPositions)),
            'vesselsPositions.csv',
            ['Content-Type' => 'text/csv']
        );
    }
}
