<?php

namespace App\Http\Controllers;

use App\Http\Requests\FieldRequest;
use App\Models\Farm;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Manages CRUD operations for the Field resource, nested under Farm.
 * Ownership is enforced by resolving the parent farm through the authenticated user.
 */
class FieldController extends Controller
{
    /**
     * Resolve a farm that belongs to the authenticated user.
     *
     * @param  Request $request
     * @param  int     $farmId  Farm primary key from route
     * @return Farm
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException When farm not found or not owned
     */
    private function resolveFarm(Request $request, int $farmId): Farm
    {
        return $request->user()->farms()->findOrFail($farmId);
    }

    /**
     * List all fields belonging to a specific farm.
     *
     * @param  Request $request
     * @param  int     $farm    Farm primary key
     * @return JsonResponse
     */
    public function index(Request $request, int $farm): JsonResponse
    {
        $farm = $this->resolveFarm($request, $farm);

        return response()->json(['data' => $farm->fields]);
    }

    /**
     * Create a new field within a farm.
     *
     * @param  FieldRequest $request
     * @param  int          $farm    Farm primary key
     * @return JsonResponse          201 with created field data
     */
    public function store(FieldRequest $request, int $farm): JsonResponse
    {
        $farm  = $this->resolveFarm($request, $farm);
        $field = $farm->fields()->create($request->validated());

        return response()->json(['data' => $field], 201);
    }

    /**
     * Return a single field within a farm.
     *
     * @param  Request $request
     * @param  int     $farm   Farm primary key
     * @param  int     $field  Field primary key
     * @return JsonResponse
     */
    public function show(Request $request, int $farm, int $field): JsonResponse
    {
        $farm  = $this->resolveFarm($request, $farm);
        $field = $farm->fields()->findOrFail($field);

        return response()->json(['data' => $field]);
    }

    /**
     * Update a field within a farm.
     *
     * @param  FieldRequest $request
     * @param  int          $farm   Farm primary key
     * @param  int          $field  Field primary key
     * @return JsonResponse
     */
    public function update(FieldRequest $request, int $farm, int $field): JsonResponse
    {
        $farm  = $this->resolveFarm($request, $farm);
        $field = $farm->fields()->findOrFail($field);
        $field->update($request->validated());

        return response()->json(['data' => $field->fresh()]);
    }

    /**
     * Delete a field within a farm.
     *
     * @param  Request $request
     * @param  int     $farm   Farm primary key
     * @param  int     $field  Field primary key
     * @return JsonResponse    204 No Content
     */
    public function destroy(Request $request, int $farm, int $field): JsonResponse
    {
        $farm  = $this->resolveFarm($request, $farm);
        $farm->fields()->findOrFail($field)->delete();

        return response()->json(null, 204);
    }
}
