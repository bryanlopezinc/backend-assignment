<?php

declare(strict_types=1);

namespace App\ValueObjects;

final class Coordinates
{
    public function __construct(public readonly string $latitude, public readonly string $longitude)
    {
        /** @see https://github.com/mattkingshott/axiom/blob/master/src/Rules/LocationCoordinates.php */
        $pattern = '/^[-]?((([0-8]?[0-9])(\.(\d{1,8}))?)|(90(\.0+)?)),\s?[-]?((((1[0-7][0-9])|([0-9]?[0-9]))(\.(\d{1,8}))?)|180(\.0+)?)$/';

        if (preg_match($pattern, implode(',', [$latitude, $longitude])) < 0) {
            throw new \Exception('Invalid cordinates');
        }
    }
}
