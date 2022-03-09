<?php

declare(strict_types=1);

namespace App\Http\Resources\Xml;

use App\DataTransferObjects\VesselPosition;
use Illuminate\Contracts\Support\Responsable;

final class VesselsResourceCollection implements Responsable
{
    /**
     * @param array<VesselPosition> $vesselsPositions
     */
    public function __construct(private array $vesselsPositions)
    {
    }

    public function toResponse($request)
    {
        return response($this->toXml()->asXML())->withHeaders(['Content-Type' => 'application/xml']);
    }

    public function toXml(): \SimpleXMLElement
    {
        $xml = new \SimpleXMLElement('<root></root>');

        $xml->addChild('data');

        foreach ($this->vesselsPositions as $vesselPosition) {
            $xml->data->addChild('type', 'vessel_position');
            $xml->data->addChild('attributes');
            $xml->data->attributes->addChild('mmsi', (string) $vesselPosition->vesselId->value);
            $xml->data->attributes->addChild('status', (string) $vesselPosition->status);
            $xml->data->attributes->addChild('statationId', (string) $vesselPosition->stationId->value);
            $xml->data->attributes->addChild('speed', (string) $vesselPosition->speed);
            $xml->data->attributes->addChild('coordinates');
            $xml->data->attributes->coordinates->addChild('longitude', $vesselPosition->coordinates->longitude);
            $xml->data->attributes->coordinates->addChild('latitude', $vesselPosition->coordinates->latitude);
            $xml->data->attributes->addChild('course', (string) $vesselPosition->course);
            $xml->data->attributes->addChild('heading', (string) $vesselPosition->heading);

            if ($vesselPosition->hasRateOfTurnData) {
                $xml->data->attributes->addChild('rateOfTurn', (string) $vesselPosition->rateOfTurn);
            }

            $xml->data->attributes->addChild('hasRateOfTurn', $vesselPosition->hasRateOfTurnData ? 'true' : 'false');
            $xml->data->attributes->addChild('timestamp', (string) $vesselPosition->timestamp);
        }

        return $xml;
    }
}
