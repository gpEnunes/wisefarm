<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates create/update requests for the Field resource.
 */
class FieldRequest extends FormRequest
{
    /**
     * All authenticated users may manage fields on their own farms.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for field data.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255',
            'area_ha'   => 'nullable|numeric|min:0',
            'soil_type' => 'nullable|string|max:100',
            'irrigated' => 'boolean',
        ];
    }
}
