<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FieldTest extends TestCase
{
    use RefreshDatabase;

    // ──────────────────────────────────────────────────────────────
    // GET /api/farms/{farmId}/fields
    // ──────────────────────────────────────────────────────────────

    public function test_given_own_farm_when_listing_fields_then_returns_that_farms_fields(): void
    {
        $user  = User::factory()->create();
        $farm  = $user->farms()->create(['name' => 'Sunrise Farm']);
        $farm->fields()->create(['name' => 'North Field']);

        Sanctum::actingAs($user);

        $response = $this->getJson("/api/farms/{$farm->id}/fields")
            ->assertStatus(200);

        $names = collect($response->json('data'))->pluck('name');
        $this->assertContains('North Field', $names->toArray());
    }

    public function test_given_another_users_farm_when_listing_fields_then_returns_404(): void
    {
        $other = User::factory()->create();
        $farm  = $other->farms()->create(['name' => 'Foreign Farm']);
        $farm->fields()->create(['name' => 'Their Field']);

        Sanctum::actingAs(User::factory()->create());

        $this->getJson("/api/farms/{$farm->id}/fields")
            ->assertStatus(404);
    }

    // ──────────────────────────────────────────────────────────────
    // POST /api/farms/{farmId}/fields
    // ──────────────────────────────────────────────────────────────

    public function test_given_valid_payload_when_creating_a_field_then_returns_201(): void
    {
        $user = User::factory()->create();
        $farm = $user->farms()->create(['name' => 'Sunrise Farm']);

        Sanctum::actingAs($user);

        $this->postJson("/api/farms/{$farm->id}/fields", [
            'name'      => 'South Field',
            'area_ha'   => 5.5,
            'soil_type' => 'clay',
            'irrigated' => true,
        ])
            ->assertStatus(201)
            ->assertJsonPath('data.name', 'South Field')
            ->assertJsonPath('data.irrigated', true);
    }

    public function test_given_missing_name_when_creating_a_field_then_returns_422(): void
    {
        $user = User::factory()->create();
        $farm = $user->farms()->create(['name' => 'Sunrise Farm']);

        Sanctum::actingAs($user);

        $this->postJson("/api/farms/{$farm->id}/fields", ['area_ha' => 3.0])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    // ──────────────────────────────────────────────────────────────
    // PUT /api/farms/{farmId}/fields/{id}
    // ──────────────────────────────────────────────────────────────

    public function test_given_own_field_when_updating_then_returns_200_with_updated_data(): void
    {
        $user  = User::factory()->create();
        $farm  = $user->farms()->create(['name' => 'Sunrise Farm']);
        $field = $farm->fields()->create(['name' => 'Old Field']);

        Sanctum::actingAs($user);

        $this->putJson("/api/farms/{$farm->id}/fields/{$field->id}", [
            'name'    => 'Renamed Field',
            'area_ha' => 10.0,
        ])
            ->assertStatus(200)
            ->assertJsonPath('data.name', 'Renamed Field');
    }

    public function test_given_field_on_another_users_farm_when_updating_then_returns_404(): void
    {
        $other = User::factory()->create();
        $farm  = $other->farms()->create(['name' => 'Foreign Farm']);
        $field = $farm->fields()->create(['name' => 'Their Field']);

        Sanctum::actingAs(User::factory()->create());

        $this->putJson("/api/farms/{$farm->id}/fields/{$field->id}", ['name' => 'Hijacked'])
            ->assertStatus(404);
    }

    // ──────────────────────────────────────────────────────────────
    // DELETE /api/farms/{farmId}/fields/{id}
    // ──────────────────────────────────────────────────────────────

    public function test_given_own_field_when_deleting_then_returns_204_and_field_is_removed(): void
    {
        $user  = User::factory()->create();
        $farm  = $user->farms()->create(['name' => 'Sunrise Farm']);
        $field = $farm->fields()->create(['name' => 'To Delete']);

        Sanctum::actingAs($user);

        $this->deleteJson("/api/farms/{$farm->id}/fields/{$field->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('fields', ['id' => $field->id]);
    }

    public function test_given_field_on_another_users_farm_when_deleting_then_returns_404(): void
    {
        $other = User::factory()->create();
        $farm  = $other->farms()->create(['name' => 'Foreign Farm']);
        $field = $farm->fields()->create(['name' => 'Their Field']);

        Sanctum::actingAs(User::factory()->create());

        $this->deleteJson("/api/farms/{$farm->id}/fields/{$field->id}")
            ->assertStatus(404);
    }
}
