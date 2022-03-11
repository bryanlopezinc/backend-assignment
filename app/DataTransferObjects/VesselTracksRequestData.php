<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use App\Http\Requests\FetchVesselsTracksRequest;
use App\ValueObjects\Latitude;
use App\ValueObjects\Longitude;
use App\ValueObjects\ResourceId;
use Carbon\Carbon;

final class VesselTracksRequestData
{
    /** @var array<ResourceId> */
    public readonly array $vesselsIds;
    public readonly Latitude $maxLatitude;
    public readonly Latitude $minLatitude;
    public readonly Longitude $minLongitude;
    public readonly Longitude $maxLongitude;
    public readonly bool $hasRange;
    public readonly bool $hasTimeInterval;
    public readonly Carbon $fromTime;
    public readonly Carbon $toTime;

    public function __construct(FetchVesselsTracksRequest $request)
    {
        $this->vesselsIds = array_map(fn (int $vesselId) => new ResourceId($vesselId), $request->validated('mmsi', []));

        $this->hasRange = $request->has(['lon_min', 'lon_max', 'lat_min', 'lat_max']);
        $this->hasTimeInterval = $request->has(['from', 'to']);

        if ($this->hasRange) {
            $this->minLatitude = new Latitude($request->validated('lat_min'));
            $this->maxLatitude = new Latitude($request->validated('lat_max'));
            $this->minLongitude = new Longitude($request->validated('lon_min'));
            $this->maxLongitude = new Longitude($request->validated('lon_max'));
        }

        if ($this->hasTimeInterval) {
            $this->fromTime = Carbon::parse($request->validated('from'));
            $this->toTime = Carbon::parse($request->validated('to'));
        }
    }
}
