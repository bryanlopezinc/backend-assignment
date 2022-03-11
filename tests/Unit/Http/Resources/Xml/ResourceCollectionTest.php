<?php

namespace Tests\Unit\Http\Resources\Xml;

use App\DataTransferObjects\Builders\VesselPositionBuilder;
use App\Http\Resources\Xml\ResourceCollection;
use Database\Factories\VesselPositionFactory;
use Tests\TestCase;

class ResourceCollectionTest extends TestCase
{
    public function testWillOutputXmlInExpectedFormat(): void
    {
        $vesselPosition = VesselPositionBuilder::fromModel(VesselPositionFactory::new()->make())->build();

        $response = (new ResourceCollection([$vesselPosition]))->toResponse(request())->getContent();

        $expected = <<<XML
        <?xml version="1.0"?>
        <data>
            <vesselPosition>
                <mmsi>{$vesselPosition->vesselId->value}</mmsi>
                <status>{$vesselPosition->status->value}</status>
                <statationId>{$vesselPosition->stationId->value}</statationId>
                <speed>$vesselPosition->speed</speed>
                <coordinates>
                    <longitude>{$vesselPosition->longitude->value}</longitude>
                    <latitude>{$vesselPosition->latitude->value}</latitude>
                </coordinates>
                <course>$vesselPosition->course</course>
                <heading>$vesselPosition->heading</heading>
                <hasRateOfTurn>false</hasRateOfTurn>
                <timestamp>$vesselPosition->timestamp</timestamp>
            </vesselPosition>
        </data>
        XML;

        $this->assertXmlStringEqualsXmlString($expected, $response);
    }
}
