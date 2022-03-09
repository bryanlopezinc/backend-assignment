<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use App\Http\Requests\FetchVesselsTracksRequest;
use App\ValueObjects\Coordinates;
use App\ValueObjects\ResourceId;

final class FetchVesselTracksRequestData
{
    /** @var array<ResourceId> */
    public readonly array $vesselIds;
    public readonly Coordinates $range;
    public readonly bool $hasRange;
    public readonly bool $hasTimeInterval;
    public readonly int $fromTime;
    public readonly int $toTime;

    public function __construct(private FetchVesselsTracksRequest $request)
    {
        $this->vesselIds = array_map(fn (int $vesselId) => new ResourceId($vesselId), $request->validated('mmsi', []));

        $this->hasRange = $request->has(['lon', 'lat']);
        $this->hasTimeInterval = $request->has(['from', 'to']);

        if ($this->hasRange) {
            $this->range = new Coordinates($request->validated('lat'), $request->validated('lon'));
        }

        if ($this->hasTimeInterval) {
            [$this->fromTime, $this->toTime] = [(int)$request->validated('from'), (int)$request->validated('to')];
        }
    }
}
