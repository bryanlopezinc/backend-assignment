<?php

declare(strict_types=1);

namespace App\Http\Resources\Json;

use App\DataTransferObjects\VesselPosition;
use Illuminate\Http\Resources\Json\JsonResource;

final class VesselPositionResource extends JsonResource
{
    public function __construct(private VesselPosition $vesselPosition)
    {
        parent::__construct($vesselPosition);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray($request)
    {
        return [
            'type'  => 'vessel_position',
            'attributes' => [
                'mmsi'       => $this->vesselPosition->vesselId->value,
                'status'     => $this->vesselPosition->status->value,
                'station_id' => $this->vesselPosition->stationId->value,
                'speed'      => $this->vesselPosition->speed,
                'coordinates' => [
                    'longitude' => $this->vesselPosition->longitude,
                    'latitude'  => $this->vesselPosition->latitude,
                ],
                'course'       => $this->vesselPosition->course,
                'heading'      => $this->vesselPosition->heading,
                'rate_of_turn' => $this->when($this->vesselPosition->hasRateOfTurnData, $this->vesselPosition->rateOfTurn),
                'has_rate_of_turn_data' => $this->vesselPosition->hasRateOfTurnData,
                'timestamp'    => $this->vesselPosition->timestamp
            ]
        ];
    }
}
