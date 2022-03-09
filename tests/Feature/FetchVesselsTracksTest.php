<?php

namespace Tests\Feature;

use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class FetchVesselsTracksTest extends TestCase
{
    private function getTestResponse(array $query = []): TestResponse
    {
        return $this->getJson(route('fetchVesselsTracks', $query));
    }

    public function testWillReturnAllVesselsTracks(): void
    {
       $this->getTestResponse()->assertSuccessful()->assertJsonCount(2696, 'data');
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
}
