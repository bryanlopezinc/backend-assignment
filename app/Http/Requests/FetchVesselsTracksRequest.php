<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\CoordinateRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

final class FetchVesselsTracksRequest extends FormRequest
{
    public function rules(): array
    {
        $coordinateRules = ['string', new CoordinateRule];

        return [
            'mmsi.*'   => ['int', 'min:1'],
            'lon_min'  => [...$coordinateRules, 'required_with:lon_max,lat_min,lat_max'],
            'lon_max'  => [...$coordinateRules, 'required_with:lon_min,lat_min,lat_max'],
            'lat_min'  => [...$coordinateRules, 'required_with:lon_min,lon_max,lat_max',],
            'lat_max'  => [...$coordinateRules, 'required_with:lon_min,lon_max,lat_min',],
            'from'     => [new RequiredIf($this->has('to')), 'int'],
            'to'       => [new RequiredIf($this->has('from')), 'int', 'gt:from']
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareForValidation()
    {
        $this->validate([
            'mmsi' => ['nullable', 'string', 'filled']
        ]);

        if ($this->has('mmsi')) {
            $this->merge(['mmsi' => explode(',', $this->input('mmsi'))]);
        }
    }
}
