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
        $xml = new \SimpleXMLElement('<data></data>');

        foreach ($this->vesselsPositions as $vesselPosition) {
            $attributes = $xml->addChild('vesselPosition');

            $attributes->addChild('mmsi', (string) $vesselPosition->vesselId->value);
            $attributes->addChild('status', (string) $vesselPosition->status);
            $attributes->addChild('statationId', (string) $vesselPosition->stationId->value);
            $attributes->addChild('speed', (string) $vesselPosition->speed);

            $coordinates = $attributes->addChild('coordinates');
            $coordinates->addChild('longitude', $vesselPosition->coordinates->longitude);
            $coordinates->addChild('latitude', $vesselPosition->coordinates->latitude);

            $attributes->addChild('course', (string) $vesselPosition->course);
            $attributes->addChild('heading', (string) $vesselPosition->heading);

            if ($vesselPosition->hasRateOfTurnData) {
                $attributes->addChild('rateOfTurn', (string) $vesselPosition->rateOfTurn);
            }

            $attributes->addChild('hasRateOfTurn', $vesselPosition->hasRateOfTurnData ? 'true' : 'false');
            $attributes->addChild('timestamp', (string) $vesselPosition->timestamp);
        }

        return $xml;
    }
}
