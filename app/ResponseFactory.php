<?php

declare(strict_types=1);

namespace App;

use Illuminate\Http\Request;
use App\DataTransferObjects\VesselPosition;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Resources\Xml\ResourceCollection as XmlResourceCollection;
use App\Http\Resources\Json\VesselPositionResource;
use App\Http\Resources\Csv\ResourceCollection as CsvResourceCollection;

final class ResponseFactory
{
    public function __construct(private Request $request)
    {
    }

    /**
     * @param array<VesselPosition> $vesselsPositions
     */
    public function response(array $vesselsPositions): Responsable|StreamedResponse
    {
        return match ($this->getAcceptableContentType()) {
            'xml' => new XmlResourceCollection($vesselsPositions),
            'csv' => $this->getCsvResponse($vesselsPositions),
            default => VesselPositionResource::collection($vesselsPositions)
        };
    }

    private function getAcceptableContentType(): string
    {
        $accept = $this->request->getAcceptableContentTypes();

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
