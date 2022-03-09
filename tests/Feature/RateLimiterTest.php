<?php

namespace Tests\Feature;

use Tests\TestCase;

class RateLimiterTest extends TestCase
{
    public function testAllowsOnlyTenRequestsPerHour(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->getJson(route('fetchVesselsTracks', ['mmsi' => '330']))->assertSuccessful();
        }

        $this->getJson(route('fetchVesselsTracks', ['mmsi' => '330']))->assertStatus(429);
    }
}
