<?php

namespace Tests\Feature;

use App\Models\Crop;
use App\Models\Farm;
use App\Models\Field;
use App\Models\Plantation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PlantationTest extends TestCase
{
    use RefreshDatabase;

    // ──────────────────────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────────────────────

    private function makeOwnerChain(): array
    {
        $user  = User::factory()->create();
        $farm  = $user->farms()->create(['name' => 'Test Farm']);
        $field = $farm->fields()->create(['name' => 'Test Field']);
        $crop  = Crop::create([
            'name'            => 'Wheat',
            'category'        => 'cereal',
            'avg_growth_days' => 90,
            'icon'            => 'fa-solid fa-wheat-awn',
        ]);

        return compact('user', 'farm', 'field', 'crop');
    }

    private function makePlantation(Field $field, Crop $crop, array $overrides = []): Plantation
    {
        return $field->plantations()->create(array_merge([
            'crop_id'    => $crop->id,
            'planted_at' => '2026-01-01',
        ], $overrides));
    }

    // ──────────────────────────────────────────────────────────────
    // GET /api/fields/{field}/plantations  (index)
    // ──────────────────────────────────────────────────────────────

    public function test_given_own_field_when_listing_plantations_then_returns_plantations(): void
    {
        ['user' => $user, 'field' => $field, 'crop' => $crop] = $this->makeOwnerChain();
        $this->makePlantation($field, $crop);

        Sanctum::actingAs($user);

        $response = $this->getJson("/api/fields/{$field->id}/plantations")
            ->assertStatus(200);

        $this->assertCount(1, $response->json('data'));
    }

    public function test_given_another_users_field_when_listing_plantations_then_returns_404(): void
    {
        ['field' => $field] = $this->makeOwnerChain();

        Sanctum::actingAs(User::factory()->create());

        $this->getJson("/api/fields/{$field->id}/plantations")
            ->assertStatus(404);
    }

    // ──────────────────────────────────────────────────────────────
    // POST /api/fields/{field}/plantations  (store)
    // ──────────────────────────────────────────────────────────────

    public function test_given_valid_payload_when_creating_plantation_then_returns_201_with_crop(): void
    {
        ['user' => $user, 'field' => $field, 'crop' => $crop] = $this->makeOwnerChain();

        Sanctum::actingAs($user);

        $response = $this->postJson("/api/fields/{$field->id}/plantations", [
            'crop_id'             => $crop->id,
            'planted_at'          => '2026-03-01',
            'expected_harvest_at' => '2026-06-01',
            'area_ha'             => 2.5,
        ])
            ->assertStatus(201)
            ->assertJsonPath('data.crop.id', $crop->id);

        // planted_at may serialize as date or full ISO datetime depending on Laravel version
        $this->assertStringStartsWith('2026-03-01', $response->json('data.planted_at'));
    }

    public function test_given_missing_crop_id_when_creating_plantation_then_returns_422(): void
    {
        ['user' => $user, 'field' => $field] = $this->makeOwnerChain();

        Sanctum::actingAs($user);

        $this->postJson("/api/fields/{$field->id}/plantations", [
            'planted_at' => '2026-03-01',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['crop_id']);
    }

    // ──────────────────────────────────────────────────────────────
    // GET /api/fields/{field}/plantations/{plantation}  (show)
    // ──────────────────────────────────────────────────────────────

    public function test_given_own_plantation_when_showing_then_returns_plantation_with_crop(): void
    {
        ['user' => $user, 'field' => $field, 'crop' => $crop] = $this->makeOwnerChain();
        $plantation = $this->makePlantation($field, $crop);

        Sanctum::actingAs($user);

        $this->getJson("/api/fields/{$field->id}/plantations/{$plantation->id}")
            ->assertStatus(200)
            ->assertJsonPath('data.id', $plantation->id)
            ->assertJsonStructure(['data' => ['id', 'crop']]);
    }

    public function test_given_another_users_field_when_showing_plantation_then_returns_404(): void
    {
        ['field' => $field, 'crop' => $crop] = $this->makeOwnerChain();
        $plantation = $this->makePlantation($field, $crop);

        Sanctum::actingAs(User::factory()->create());

        $this->getJson("/api/fields/{$field->id}/plantations/{$plantation->id}")
            ->assertStatus(404);
    }

    // ──────────────────────────────────────────────────────────────
    // PUT /api/fields/{field}/plantations/{plantation}  (update)
    // ──────────────────────────────────────────────────────────────

    public function test_given_own_plantation_when_updating_then_returns_200_with_updated_data(): void
    {
        ['user' => $user, 'field' => $field, 'crop' => $crop] = $this->makeOwnerChain();
        $plantation = $this->makePlantation($field, $crop);

        Sanctum::actingAs($user);

        $this->putJson("/api/fields/{$field->id}/plantations/{$plantation->id}", [
            'crop_id'    => $crop->id,
            'planted_at' => '2026-04-01',
            'notes'      => 'Updated notes',
        ])
            ->assertStatus(200)
            ->assertJsonPath('data.notes', 'Updated notes');
    }

    public function test_given_another_users_field_when_updating_plantation_then_returns_404(): void
    {
        ['field' => $field, 'crop' => $crop] = $this->makeOwnerChain();
        $plantation = $this->makePlantation($field, $crop);

        Sanctum::actingAs(User::factory()->create());

        $this->putJson("/api/fields/{$field->id}/plantations/{$plantation->id}", [
            'notes' => 'Hijacked',
        ])
            ->assertStatus(404);
    }

    public function test_given_plantation_when_recording_harvest_then_returns_200(): void
    {
        ['user' => $user, 'field' => $field, 'crop' => $crop] = $this->makeOwnerChain();
        $plantation = $this->makePlantation($field, $crop);

        Sanctum::actingAs($user);

        $this->putJson("/api/fields/{$field->id}/plantations/{$plantation->id}", [
            'harvested_at' => '2026-06-15',
            'yield_kg'     => 4200,
        ])
            ->assertStatus(200)
            ->assertJsonPath('data.yield_kg', 4200);
    }

    // ──────────────────────────────────────────────────────────────
    // DELETE /api/fields/{field}/plantations/{plantation}  (destroy)
    // ──────────────────────────────────────────────────────────────

    public function test_given_own_plantation_when_deleting_then_returns_204_and_record_is_removed(): void
    {
        ['user' => $user, 'field' => $field, 'crop' => $crop] = $this->makeOwnerChain();
        $plantation = $this->makePlantation($field, $crop);

        Sanctum::actingAs($user);

        $this->deleteJson("/api/fields/{$field->id}/plantations/{$plantation->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('plantations', ['id' => $plantation->id]);
    }

    public function test_given_another_users_field_when_deleting_plantation_then_returns_404(): void
    {
        ['field' => $field, 'crop' => $crop] = $this->makeOwnerChain();
        $plantation = $this->makePlantation($field, $crop);

        Sanctum::actingAs(User::factory()->create());

        $this->deleteJson("/api/fields/{$field->id}/plantations/{$plantation->id}")
            ->assertStatus(404);
    }

    public function test_given_another_users_field_when_creating_plantation_then_returns_404(): void
    {
        ['field' => $field, 'crop' => $crop] = $this->makeOwnerChain();

        Sanctum::actingAs(User::factory()->create());

        $this->postJson("/api/fields/{$field->id}/plantations", [
            'crop_id'    => $crop->id,
            'planted_at' => '2026-03-01',
        ])->assertStatus(404);
    }
}
