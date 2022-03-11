<?php

declare(strict_types=1);

namespace App\Rules;

use App\Exceptions\InvalidCoordinatesException;
use App\ValueObjects\Coordinate;
use Illuminate\Contracts\Validation\Rule;

final class CoordinateRule implements Rule
{
    private string $message;

    /**
     * {@inheritdoc}
     */
    public function passes($attribute, $value)
    {
        try {
            new class($value) extends Coordinate
            {
            };

            return true;
        } catch (InvalidCoordinatesException) {
            $this->message = 'Invalid coordinate value';

            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function message()
    {
        return $this->message;
    }
}
