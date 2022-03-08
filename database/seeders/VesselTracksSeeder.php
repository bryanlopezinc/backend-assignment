<?php

namespace Database\Seeders;

use App\Models\VesselTrack;
use Illuminate\Database\Seeder;
use Illuminate\Support\LazyCollection;

class VesselTracksSeeder extends Seeder
{
    public function run(): void
    {
        $shipsPositionsData = (new LazyCollection(function () {
            $file = __DIR__ . DIRECTORY_SEPARATOR . 'ships_positions.json';

            return yield from json_decode(file_get_contents($file), true);
        }));

        $shipsPositionsData->chunk(100)->each(function (LazyCollection $chunk): void {
            $shipsPositions = $chunk->map(fn (array $shipPosition): array => [
                'mmsi'         => $shipPosition['mmsi'],
                'status'       => $shipPosition['status'],
                'station_id'   => $shipPosition['stationId'],
                'speed'        => $shipPosition['speed'],
                'longitude'    => $shipPosition['lon'],
                'latitude'     => $shipPosition['lat'],
                'course'       => $shipPosition['course'],
                'heading'      => $shipPosition['heading'],
                'rate_of_turn' => blank($shipPosition['rot']) ? null : $shipPosition['rot'],
                'timestamp'    => $shipPosition['timestamp']
            ]);

            VesselTrack::insert($shipsPositions->all());
        });
    }
}
