<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates create/update requests for the Farm resource.
 */
class FarmRequest extends FormRequest
{
    /**
     * All authenticated users may manage their own farms.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for farm data.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'name'          => 'required|string|max:255',
            'location'      => 'nullable|string|max:255',
            'total_area_ha' => 'nullable|numeric|min:0',
        ];
    }
}
