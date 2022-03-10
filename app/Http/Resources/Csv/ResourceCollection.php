<?php

declare(strict_types=1);

namespace App\Http\Resources\Csv;

use App\DataTransferObjects\VesselPosition;
use League\Csv\Writer;

final class ResourceCollection
{
    /**
     * @param array<VesselPosition> $vesselsPositions
     */
    public function __construct(private readonly array $vesselsPositions)
    {
    }

    public function __invoke(): void
    {
        $writer = Writer::createFromPath('php://temp');

        $writer->insertOne([
            'MMSI',
            'Status',
            'StationId',
            'Speed',
            'Longitude',
            'Latitude',
            'Course',
            'Heading',
            'RateOfTurn',
            'HasRateOfTurnData',
            'Timestamp'
        ]);

        foreach ($this->vesselsPositions as $vesselPosition) {
            $writer->insertOne([
                'MMSI'         => $vesselPosition->vesselId->value,
                'Status'       => $vesselPosition->status->value,
                'StationId'    => $vesselPosition->stationId->value,
                'Speed'        => $vesselPosition->speed,
                'Longitude'    => $vesselPosition->coordinates->longitude,
                'Latitude'     => $vesselPosition->coordinates->latitude,
                'Course'       => $vesselPosition->course,
                'Heading'      => $vesselPosition->heading,
                'RateOfTurn'   => $vesselPosition->rateOfTurn,
                'HasRateOfTurnData' => $vesselPosition->hasRateOfTurnData ? 'true' : 'false',
                'Timestamp'    => $vesselPosition->timestamp
            ]);
        }

        echo $writer->toString();

        flush();
    }
}
