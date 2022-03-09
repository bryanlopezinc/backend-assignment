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
    public readonly bool $wantsVesselsWithinSpecificRange;

    public function __construct(private FetchVesselsTracksRequest $request)
    {
        $wantsVesselsWithinSpecificRange = $request->has(['lon', 'lat']);

        $this->vesselIds = collect($request->validated('mmsi'))->map(fn (int $vesselId) => new ResourceId($vesselId))->all();
        $this->wantsVesselsWithinSpecificRange = $wantsVesselsWithinSpecificRange;

        if ($wantsVesselsWithinSpecificRange) {
            $this->range = new Coordinates($request->validated('lat'), $request->validated('lon'));
        }
    }
}
