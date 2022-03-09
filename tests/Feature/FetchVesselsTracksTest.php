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

    public function testWillReturnVesselTracksWithSpecifiedMmsi(): void
    {
        $this->getTestResponse(['mmsi' => '247039300'])->assertSuccessful()->assertJsonCount(869, 'data');
        $this->getTestResponse(['mmsi' => '50'])->assertSuccessful()->assertJsonCount(0, 'data');
        $this->getTestResponse(['mmsi' => '247039300,311486000'])->assertSuccessful()->assertJsonCount(1729, 'data');
    }
}
