<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FarmTest extends TestCase
{
    use RefreshDatabase;

    // ──────────────────────────────────────────────────────────────
    // GET /api/farms
    // ──────────────────────────────────────────────────────────────

    public function test_given_two_users_when_listing_farms_then_only_authenticated_user_farms_are_returned(): void
    {
        $user  = User::factory()->create();
        $other = User::factory()->create();

        $user->farms()->create(['name' => 'My Farm']);
        $other->farms()->create(['name' => 'Their Farm']);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/farms')->assertStatus(200);

        $names = collect($response->json('data'))->pluck('name');
        $this->assertContains('My Farm', $names->toArray());
        $this->assertNotContains('Their Farm', $names->toArray());
    }

    // ──────────────────────────────────────────────────────────────
    // POST /api/farms
    // ──────────────────────────────────────────────────────────────

    public function test_given_valid_payload_when_creating_a_farm_then_returns_201(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/farms', [
            'name'          => 'Sunrise Farm',
            'location'      => 'Alentejo',
            'total_area_ha' => 120.5,
        ])
            ->assertStatus(201)
            ->assertJsonPath('data.name', 'Sunrise Farm')
            ->assertJsonPath('data.location', 'Alentejo');
    }

    public function test_given_missing_name_when_creating_a_farm_then_returns_422(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/farms', ['location' => 'Alentejo'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    // ──────────────────────────────────────────────────────────────
    // GET /api/farms/{id}
    // ──────────────────────────────────────────────────────────────

    public function test_given_own_farm_when_showing_then_returns_farm_with_fields_key(): void
    {
        $user = User::factory()->create();
        $farm = $user->farms()->create(['name' => 'Sunrise Farm']);

        Sanctum::actingAs($user);

        $this->getJson("/api/farms/{$farm->id}")
            ->assertStatus(200)
            ->assertJsonPath('data.id', $farm->id)
            ->assertJsonStructure(['data' => ['id', 'name', 'fields']]);
    }

    public function test_given_another_users_farm_when_showing_then_returns_404(): void
    {
        $other = User::factory()->create();
        $farm  = $other->farms()->create(['name' => 'Foreign Farm']);

        Sanctum::actingAs(User::factory()->create());

        $this->getJson("/api/farms/{$farm->id}")
            ->assertStatus(404);
    }

    // ──────────────────────────────────────────────────────────────
    // PUT /api/farms/{id}
    // ──────────────────────────────────────────────────────────────

    public function test_given_own_farm_when_updating_then_returns_200_with_updated_data(): void
    {
        $user = User::factory()->create();
        $farm = $user->farms()->create(['name' => 'Old Name']);

        Sanctum::actingAs($user);

        $this->putJson("/api/farms/{$farm->id}", ['name' => 'New Name'])
            ->assertStatus(200)
            ->assertJsonPath('data.name', 'New Name');
    }

    public function test_given_another_users_farm_when_updating_then_returns_404(): void
    {
        $other = User::factory()->create();
        $farm  = $other->farms()->create(['name' => 'Foreign Farm']);

        Sanctum::actingAs(User::factory()->create());

        $this->putJson("/api/farms/{$farm->id}", ['name' => 'Hijacked'])
            ->assertStatus(404);
    }

    // ──────────────────────────────────────────────────────────────
    // DELETE /api/farms/{id}
    // ──────────────────────────────────────────────────────────────

    public function test_given_own_farm_when_deleting_then_returns_204_and_farm_is_removed(): void
    {
        $user = User::factory()->create();
        $farm = $user->farms()->create(['name' => 'To Delete']);

        Sanctum::actingAs($user);

        $this->deleteJson("/api/farms/{$farm->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('farms', ['id' => $farm->id]);
    }

    public function test_given_another_users_farm_when_deleting_then_returns_404(): void
    {
        $other = User::factory()->create();
        $farm  = $other->farms()->create(['name' => 'Foreign Farm']);

        Sanctum::actingAs(User::factory()->create());

        $this->deleteJson("/api/farms/{$farm->id}")
            ->assertStatus(404);
    }
}
