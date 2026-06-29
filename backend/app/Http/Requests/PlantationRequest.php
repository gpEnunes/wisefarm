<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates create/update requests for the Plantation resource.
 *
 * On POST (store) crop_id and planted_at are required.
 * On PUT/PATCH (update) all fields are optional so partial updates
 * — such as recording a harvest — work without re-sending the full payload.
 */
class PlantationRequest extends FormRequest
{
    /**
     * All authenticated users may manage plantations on their own fields.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for plantation data.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $isUpdate = in_array($this->method(), ['PUT', 'PATCH']);

        if ($isUpdate) {
            return [
                'crop_id'             => ['sometimes', 'nullable', 'exists:crops,id'],
                'planted_at'          => ['sometimes', 'nullable', 'date'],
                'expected_harvest_at' => ['sometimes', 'nullable', 'date'],
                'area_ha'             => ['sometimes', 'nullable', 'numeric', 'min:0.01'],
                'notes'               => ['sometimes', 'nullable', 'string', 'max:1000'],
                'harvested_at'        => ['sometimes', 'nullable', 'date'],
                'yield_kg'            => ['sometimes', 'nullable', 'numeric', 'min:0'],
            ];
        }

        return [
            'crop_id'             => ['required', 'exists:crops,id'],
            'planted_at'          => ['required', 'date'],
            'expected_harvest_at' => ['nullable', 'date', 'after:planted_at'],
            'area_ha'             => ['nullable', 'numeric', 'min:0.01'],
            'notes'               => ['nullable', 'string', 'max:1000'],
            'harvested_at'        => ['nullable', 'date', 'after_or_equal:planted_at'],
            'yield_kg'            => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
