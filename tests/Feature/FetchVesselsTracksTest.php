<?php

namespace Tests\Feature;

use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class FetchVesselsTracksTest extends TestCase
{
    private function getTestResponse(array $query = []): TestResponse
    {
        return $this->getJson(route('fetchVesselsTracks'));
    }

    public function testWillReturnAllVesselsTracks(): void
    {
       $this->getTestResponse()->assertSuccessful()->assertJsonCount(2696, 'data');
    }
}
