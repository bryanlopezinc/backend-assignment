<?php

namespace Tests\Unit\Http\Resources\Csv;

use App\DataTransferObjects\Builders\VesselPositionBuilder;
use App\Http\Resources\Csv\ResourceCollection;
use Database\Factories\VesselPositionFactory;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ResourceCollectionTest extends TestCase
{
    public function testWillOutputCsvInCorrectFormat(): void
    {
        $vesselPosition = VesselPositionBuilder::fromModel(VesselPositionFactory::new()->make())->build();

        $response = new TestResponse(response()->streamDownload(new ResourceCollection([$vesselPosition])));

        $values = implode(',', [
            $vesselPosition->vesselId->value,
            $vesselPosition->status->value,
            $vesselPosition->stationId->value,
            $vesselPosition->speed,
            $vesselPosition->longitude->value,
            $vesselPosition->latitude->value,
            $vesselPosition->course,
            $vesselPosition->heading,
            $vesselPosition->rateOfTurn,
            'false',
            $vesselPosition->timestamp->toDate()
        ]);

        $this->assertStringMatchesFormat(
            "MMSI,Status,StationId,Speed,Longitude,Latitude,Course,Heading,RateOfTurn,HasRateOfTurnData,Timestamp" . PHP_EOL . $values,
            $response->streamedContent()
        );
    }
}
