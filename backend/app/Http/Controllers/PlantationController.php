<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlantationRequest;
use App\Models\Field;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Plantations are scoped to the authenticated user via the farm ownership chain.
 * Field resolution always verifies user_id on the parent farm.
 */
class PlantationController extends Controller
{
    /**
     * Resolve a field that belongs to a farm owned by the authenticated user.
     *
     * @param  Request $request
     * @param  int     $fieldId  Field primary key from route
     * @return Field
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException When field not found or not owned
     */
    private function resolveField(Request $request, int $fieldId): Field
    {
        return Field::whereHas('farm', fn ($q) =>
            $q->where('user_id', $request->user()->id)
        )->findOrFail($fieldId);
    }

    /**
     * List all plantations for a field owned by the authenticated user.
     *
     * @param  Request $request
     * @param  int     $field   Field primary key
     * @return JsonResponse
     */
    public function index(Request $request, int $field): JsonResponse
    {
        $field       = $this->resolveField($request, $field);
        $plantations = $field->plantations()->with('crop')->get();

        return response()->json(['data' => $plantations]);
    }

    /**
     * Create a new plantation within a field.
     *
     * @param  PlantationRequest $request
     * @param  int               $field   Field primary key
     * @return JsonResponse               201 with created plantation and crop
     */
    public function store(PlantationRequest $request, int $field): JsonResponse
    {
        $field      = $this->resolveField($request, $field);
        $plantation = $field->plantations()->create($request->validated());

        return response()->json(['data' => $plantation->load('crop')], 201);
    }

    /**
     * Return a single plantation with its crop relationship.
     *
     * @param  Request $request
     * @param  int     $field      Field primary key
     * @param  int     $plantation Plantation primary key
     * @return JsonResponse
     */
    public function show(Request $request, int $field, int $plantation): JsonResponse
    {
        $field      = $this->resolveField($request, $field);
        $plantation = $field->plantations()->with('crop')->findOrFail($plantation);

        return response()->json(['data' => $plantation]);
    }

    /**
     * Update a plantation within a field.
     * Accepts partial payloads (e.g., recording a harvest without resending crop/dates).
     *
     * @param  PlantationRequest $request
     * @param  int               $field      Field primary key
     * @param  int               $plantation Plantation primary key
     * @return JsonResponse
     */
    public function update(PlantationRequest $request, int $field, int $plantation): JsonResponse
    {
        $field      = $this->resolveField($request, $field);
        $plantation = $field->plantations()->findOrFail($plantation);
        $plantation->update($request->validated());

        return response()->json(['data' => $plantation->fresh()->load('crop')]);
    }

    /**
     * Delete a plantation within a field.
     *
     * @param  Request $request
     * @param  int     $field      Field primary key
     * @param  int     $plantation Plantation primary key
     * @return JsonResponse        204 No Content
     */
    public function destroy(Request $request, int $field, int $plantation): JsonResponse
    {
        $field = $this->resolveField($request, $field);
        $field->plantations()->findOrFail($plantation)->delete();

        return response()->json(null, 204);
    }
}
