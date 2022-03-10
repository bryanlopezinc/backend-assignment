<?php

namespace Database\Factories;

use App\Models\VesselPosition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VesselPosition>
 */
class VesselPositionFactory extends Factory
{
    protected $model = VesselPosition::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'vessel_id'    => rand(1, 5000),
            'status'       => rand(0, 5),
            'station_id'   => rand(1, 5000),
            'speed'        => 101,
            'longitude'    => (string) $this->faker->longitude,
            'latitude'     => (string) $this->faker->latitude,
            'course'       => rand(1, 5000),
            'heading'      => rand(100, 150),
            'rate_of_turn' => null,
            'timestamp'    => now()->timestamp
        ];
    }
}
