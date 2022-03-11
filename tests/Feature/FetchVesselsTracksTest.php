<?php

namespace Tests\Feature;

use App\Timestamp;
use Carbon\Carbon;
use Illuminate\Testing\AssertableJsonString;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class FetchVesselsTracksTest extends TestCase
{
    private function getTestResponse(array $query = [], array $headers = []): TestResponse
    {
        return $this->getJson(route('fetchVesselsTracks', $query), $headers);
    }

    public function testWillReturnAllVesselsTracks(): void
    {
        $response = $this->getTestResponse()
            ->assertSuccessful()
            ->assertJsonCount(2696, 'data')
            ->assertJsonCount('9', 'data.0.attributes')
            ->assertJson(function (AssertableJson $json) {
                $json->where('data.0.attributes.has_rate_of_turn_data', false);
                $json->etc();
            });

        $response->assertJsonStructure([
            'type',
            'attributes' => [
                'mmsi',
                'status',
                'station_id',
                'speed',
                'coordinates' => [
                    'longitude',
                    'latitude',
                ],
                'course',
                'heading',
                'has_rate_of_turn_data',
                'timestamp'
            ]
        ], $response->json('data.0'));
    }

    public function testWillReturnVesselsTracksInXmlFormat(): void
    {
        $response = $this->getTestResponse(headers: ['accept' => 'application/xml'])
            ->assertSuccessful()
            ->assertHeader('Content-Type', 'application/xml');

        $this->assertStringStartsWith('<?xml version="1.0"?>', $response->baseResponse->content());

        $xmlString = simplexml_load_string($response->baseResponse->content());

        $data = json_decode(json_encode($xmlString), true);

        $assert = new AssertableJsonString($data['vesselPosition']);

        $assert->assertCount(2696)->assertStructure([
            'mmsi',
            'status',
            'statationId',
            'speed',
            'coordinates' => [
                'longitude',
                'latitude',
            ],
            'course',
            'heading',
            'hasRateOfTurn',
            'timestamp',
        ], $data['vesselPosition'][0]);
    }

    public function testWillReturnVesselsTracksInCsvFormat(): void
    {
        $this->getTestResponse(headers: ['accept' => 'text/csv;'])
            ->assertSuccessful()
            ->assertDownload('vesselsPositions.csv')
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function testWillReturnValidationErrorIfMmsiAttributeIsInvalid(): void
    {
        $this->getTestResponse(['mmsi'])->assertUnprocessable()->assertJsonValidationErrorFor('mmsi');
        $this->getTestResponse(['mmsi' => [22, 23]])->assertUnprocessable()->assertJsonValidationErrorFor('mmsi');
        $this->getTestResponse(['mmsi' => '23,-1'])->assertUnprocessable()->assertJsonValidationErrorFor('mmsi.1');
    }

    public function testWillReturnValidationErrorsIfCoordinatesAttributesAreInvalid(): void
    {
        $attributes = ['lon_min', 'lon_max', 'lat_min', 'lat_max'];

        foreach ($attributes as $key => $attribute) {
            $new = $attributes;

            $response = $this->getTestResponse([$attribute => '15.44150000'])->assertUnprocessable();

            unset($new[$key]);

            foreach ($new as $value) {
                $response->assertJsonValidationErrorFor($value);
            }
        }
    }

    public function testWillReturnValidationErrorsIfTimeAttributesAreInvalid(): void
    {
        $this->getTestResponse(['from' => 1372700520])->assertUnprocessable()->assertJsonValidationErrorFor('to');
        $this->getTestResponse(['to' => 1372700520])->assertUnprocessable()->assertJsonValidationErrorFor('from');
        $this->getTestResponse(['from' => now()->toDateString(), 'to' => now()->subDay()->toDateString()])->assertUnprocessable()->assertJsonValidationErrorFor('to');
    }

    public function testWillReturnVesselTracksWithSpecifiedMmsi(): void
    {
        $this->getTestResponse(['mmsi' => '247039300'])->assertSuccessful()->assertJsonCount(869, 'data');
        $this->getTestResponse(['mmsi' => '50'])->assertSuccessful()->assertJsonCount(0, 'data');
        $this->getTestResponse(['mmsi' => '247039300,311486000'])->assertSuccessful()->assertJsonCount(1729, 'data');
    }

    public function testWillReturnVesselTracksWithinCoordinates(): void
    {
        $this->getTestResponse([
            'lat_min' => '40.68598000',
            'lat_max' => '41.45607000',
            'lon_min' => '14.35212000',
            'lon_max' => '18.99567000'
        ])->assertSuccessful()->assertJsonCount(269, 'data');
    }

    public function testWillReturnVesselTracksWithinTimeInterval(): void
    {
        $this->getTestResponse([
            'from' => Carbon::createFromTimestamp(1372700520)->toDateTimeString(),
            'to' => Carbon::createFromTimestamp(1372700580)->toDateTimeString()
        ])->assertSuccessful()->assertJsonCount(496, 'data');
    }
}
