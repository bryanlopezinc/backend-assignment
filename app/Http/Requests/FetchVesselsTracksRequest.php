<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class FetchVesselsTracksRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mmsi.*' => ['int', 'min:1']
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
