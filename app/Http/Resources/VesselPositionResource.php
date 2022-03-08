<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\DataTransferObjects\VesselPosition;
use Illuminate\Http\Resources\Json\JsonResource;

final class VesselPositionResource extends JsonResource
{
    public function __construct(private VesselPosition $vesselPosition)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function toArray($request)
    {
        return [
            'mmsi'       => $this->vesselPosition->vesselId->value,
            'status'     => $this->vesselPosition->status,
            'station_id' => $this->vesselPosition->stationId->value,
            'speed'      => $this->vesselPosition->speed,
            'coordinates' => [
                'longitude' => $this->vesselPosition->coordinates->longitude,
                'latitude'  => $this->vesselPosition->coordinates->latitude,
            ],
            'course'       => $this->vesselPosition->course,
            'heading'      => $this->vesselPosition->heading,
            'rate_of_turn' => $this->when($this->vesselPosition->hasRateOfTurnData, $this->vesselPosition->rateOfTurn),
            'has_rate_of_turn_data' => $this->vesselPosition->hasRateOfTurnData,
            'timestamp'    => $this->vesselPosition->timestamp
        ];
    }
}
