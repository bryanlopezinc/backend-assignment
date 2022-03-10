<?php

namespace Tests\Feature;

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
        $this->getTestResponse()->assertSuccessful()->assertJsonCount(2696, 'data');
    }

    public function testWillReturnVesselsTracksInXmlFormart(): void
    {
        $this->getTestResponse(headers: ['accept' => 'application/xml'])
            ->assertSuccessful()
            ->assertHeader('Content-Type', 'application/xml');
    }

    public function testWillReturnVesselsTracksInCsvFormart(): void
    {
        $this->getTestResponse(headers: ['accept' => 'text/csv;'])
            ->assertSuccessful()
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
        $this->getTestResponse(['lon' => '15.44150000'])->assertUnprocessable()->assertJsonValidationErrorFor('lat');
        $this->getTestResponse(['lat' => '15.44150000'])->assertUnprocessable()->assertJsonValidationErrorFor('lon');
    }

    public function testWillReturnValidationErrorsIfTimeAttributesAreInvalid(): void
    {
        $this->getTestResponse(['from' => 1372700520])->assertUnprocessable()->assertJsonValidationErrorFor('to');
        $this->getTestResponse(['to' => 1372700520])->assertUnprocessable()->assertJsonValidationErrorFor('from');
        $this->getTestResponse(['from' => now()->timestamp, 'to' => now()->subDay()->timestamp])->assertUnprocessable()->assertJsonValidationErrorFor('to');
    }

    public function testWillReturnVesselTracksWithSpecifiedMmsi(): void
    {
        $this->getTestResponse(['mmsi' => '247039300'])->assertSuccessful()->assertJsonCount(869, 'data');
        $this->getTestResponse(['mmsi' => '50'])->assertSuccessful()->assertJsonCount(0, 'data');
        $this->getTestResponse(['mmsi' => '247039300,311486000'])->assertSuccessful()->assertJsonCount(1729, 'data');
    }

    public function testWillReturnVesselTracksWithinCoordinates(): void
    {
        $this->getTestResponse(['lat' => '42.75178000', 'lon' => '15.44150000'])->assertSuccessful()->assertJsonCount(176, 'data');
    }

    public function testWillReturnVesselTracksWithinTimeInterval(): void
    {
        $this->getTestResponse(['from' => 1372700520, 'to' => 1372700580])->assertSuccessful()->assertJsonCount(496, 'data');
    }
}
