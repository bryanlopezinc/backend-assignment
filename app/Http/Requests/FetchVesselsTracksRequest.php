<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Exceptions\InvalidCoordinatesException;
use App\ValueObjects\Coordinates;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

final class FetchVesselsTracksRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mmsi.*' => ['int', 'min:1'],
            'lon'    => [new RequiredIf($this->has('lat')), 'string'],
            'lat'    => [new RequiredIf($this->has('lon')), 'string'],
            'from'   => [new RequiredIf($this->has('to')), 'int'],
            'to'     => [new RequiredIf($this->has('from')), 'int', 'gt:from']
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

    /**
     * @param Validator $validator
     */
    public function withValidator($validator): void
    {
        $validator->after(function (Validator $validator) {
            if (filled($validator->failed())) {
                return;
            }

            if (!$this->has(['lon', 'lat'])) {
                return;
            }

            try {
                new Coordinates($this->validated('lat'), $this->validated('lon'));
            } catch (InvalidCoordinatesException) {
                $validator->errors()->add('lon', 'the lon attribute must be valid')->add('lat', 'The lat attribute must be valid');
            }
        });
    }
}
