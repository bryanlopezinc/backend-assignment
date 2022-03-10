<?php

declare(strict_types=1);

namespace App\Http\Resources\Csv;

use App\DataTransferObjects\VesselPosition;
use League\Csv\Writer;

final class VesselsResourceCollection
{
    /**
     * @param array<VesselPosition> $vesselsPositions
     */
    public function __construct(private array $vesselsPositions)
    {
    }

    public function __invoke()
    {
        $writer = Writer::createFromPath('php://temp');

        $headers = [
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
        ];

        $writer->insertOne($headers);

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
