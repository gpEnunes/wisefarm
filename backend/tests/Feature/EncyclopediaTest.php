<?php

namespace Tests\Feature;

use App\Models\Crop;
use App\Models\CropProfile;
use App\Models\CropSoilSuitability;
use App\Models\CropTip;
use App\Models\CropYieldBenchmark;
use App\Models\Farm;
use App\Models\User;
use App\Services\FaostatService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Mockery;
use Tests\TestCase;

class EncyclopediaTest extends TestCase
{
    use RefreshDatabase;

    // ──────────────────────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────────────────────

    private function actingAsUser(): User
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        return $user;
    }

    private function mockSearch(array $items): void
    {
        $mock = Mockery::mock(FaostatService::class);
        $mock->shouldReceive('searchItems')->andReturn($items);
        $this->app->instance(FaostatService::class, $mock);
    }

    private function mockImport(array $benchmarks = []): void
    {
        $mock = Mockery::mock(FaostatService::class);
        $mock->shouldReceive('searchItems')->andReturn([]);
        $mock->shouldReceive('fetchYieldBenchmarks')->andReturn($benchmarks);
        $this->app->instance(FaostatService::class, $mock);
    }

    // ──────────────────────────────────────────────────────────────
    // Search
    // ──────────────────────────────────────────────────────────────

    public function test_given_query_when_searching_encyclopedia_then_returns_faostat_results(): void
    {
        $this->actingAsUser();
        $this->mockSearch([
            ['code' => '388', 'name' => 'Tomatoes'],
            ['code' => '544', 'name' => 'Tomatoes, cherry'],
        ]);

        $this->getJson('/api/encyclopedia/search?q=tomato')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.faostat_code', '388')
            ->assertJsonPath('data.0.name', 'Tomatoes');
    }

    public function test_given_already_imported_crop_when_searching_then_flags_already_imported(): void
    {
        $this->actingAsUser();

        $crop = Crop::factory()->create(['name' => 'Tomato']);
        CropProfile::create(['crop_id' => $crop->id, 'faostat_item_code' => '388']);

        $this->mockSearch([
            ['code' => '388', 'name' => 'Tomatoes'],
            ['code' => '544', 'name' => 'Tomatoes, cherry'],
        ]);

        $this->getJson('/api/encyclopedia/search?q=tomato')
            ->assertOk()
            ->assertJsonPath('data.0.already_imported', true)
            ->assertJsonPath('data.1.already_imported', false);
    }

    public function test_given_missing_query_when_searching_then_returns_422(): void
    {
        $this->actingAsUser();
        $this->getJson('/api/encyclopedia/search')->assertUnprocessable();
    }

    public function test_given_short_query_when_searching_then_returns_422(): void
    {
        $this->actingAsUser();
        $this->getJson('/api/encyclopedia/search?q=t')->assertUnprocessable();
    }

    // ──────────────────────────────────────────────────────────────
    // Import
    // ──────────────────────────────────────────────────────────────

    public function test_given_valid_payload_when_importing_then_creates_profile_and_benchmarks(): void
    {
        $this->actingAsUser();
        $this->mockImport([
            ['year' => 2022, 'yield_kg_ha' => 35000.00],
            ['year' => 2023, 'yield_kg_ha' => 36500.00],
        ]);

        $this->postJson('/api/encyclopedia/import', [
            'faostat_item_code' => '388',
            'name'              => 'Tomatoes',
        ])
        ->assertCreated()
        ->assertJsonPath('data.name', 'Tomatoes')
        ->assertJsonPath('data.profile.faostat_item_code', '388');

        $this->assertDatabaseHas('crop_profiles', ['faostat_item_code' => '388']);
        $this->assertDatabaseHas('crop_yield_benchmarks', ['year' => 2022, 'yield_kg_ha' => 35000.00]);
        $this->assertDatabaseHas('crop_yield_benchmarks', ['year' => 2023, 'yield_kg_ha' => 36500.00]);
    }

    public function test_given_already_imported_code_when_importing_then_returns_409(): void
    {
        $this->actingAsUser();

        $crop = Crop::factory()->create(['name' => 'Tomato']);
        CropProfile::create(['crop_id' => $crop->id, 'faostat_item_code' => '388']);

        $this->mockImport();

        $this->postJson('/api/encyclopedia/import', [
            'faostat_item_code' => '388',
            'name'              => 'Tomatoes',
        ])
        ->assertStatus(409)
        ->assertJsonPath('message', 'This crop has already been imported.');
    }

    public function test_given_existing_crop_name_when_importing_then_enriches_existing_crop(): void
    {
        $this->actingAsUser();
        $this->mockImport();

        $existing = Crop::factory()->create(['name' => 'Tomatoes']);

        $this->postJson('/api/encyclopedia/import', [
            'faostat_item_code' => '388',
            'name'              => 'Tomatoes',
        ])
        ->assertCreated()
        ->assertJsonPath('data.id', $existing->id);

        $this->assertDatabaseCount('crops', 1);
    }

    // ──────────────────────────────────────────────────────────────
    // Crop show with profile
    // ──────────────────────────────────────────────────────────────

    public function test_given_crop_with_profile_when_showing_then_returns_full_data(): void
    {
        $this->actingAsUser();

        $crop = Crop::factory()->create(['name' => 'Wheat']);
        CropProfile::create([
            'crop_id'           => $crop->id,
            'faostat_item_code' => '15',
            'optimal_temp_min'  => 10,
            'optimal_temp_max'  => 24,
        ]);
        CropSoilSuitability::create(['crop_id' => $crop->id, 'soil_type' => 'loam', 'suitability' => 'ideal']);
        CropYieldBenchmark::create(['crop_id' => $crop->id, 'country_code' => 'WLD', 'year' => 2023, 'yield_kg_ha' => 3500.00]);

        $this->getJson("/api/crops/{$crop->id}")
            ->assertOk()
            ->assertJsonPath('data.name', 'Wheat')
            ->assertJsonPath('data.profile.faostat_item_code', '15')
            ->assertJsonPath('data.profile.optimal_temp_min', 10)
            ->assertJsonCount(1, 'data.soil_suitabilities')
            ->assertJsonCount(1, 'data.yield_benchmarks');
    }

    public function test_given_crop_without_profile_when_showing_then_profile_is_null(): void
    {
        $this->actingAsUser();
        $crop = Crop::factory()->create();

        $this->getJson("/api/crops/{$crop->id}")
            ->assertOk()
            ->assertJsonPath('data.profile', null)
            ->assertJsonCount(0, 'data.soil_suitabilities')
            ->assertJsonCount(0, 'data.yield_benchmarks');
    }

    // ──────────────────────────────────────────────────────────────
    // Crop tips
    // ──────────────────────────────────────────────────────────────

    public function test_given_crop_with_tips_when_listing_all_tips_then_returns_all(): void
    {
        $this->actingAsUser();
        $crop = Crop::factory()->create();
        CropTip::create(['crop_id' => $crop->id, 'type' => 'general',    'soil_type' => null,   'body' => 'Generic tip']);
        CropTip::create(['crop_id' => $crop->id, 'type' => 'soil',       'soil_type' => 'clay', 'body' => 'Clay tip']);
        CropTip::create(['crop_id' => $crop->id, 'type' => 'irrigation', 'soil_type' => 'loam', 'body' => 'Loam tip']);

        $this->getJson("/api/crops/{$crop->id}/tips")
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_given_soil_type_filter_when_listing_tips_then_returns_scoped_and_generic(): void
    {
        $this->actingAsUser();
        $crop = Crop::factory()->create();
        CropTip::create(['crop_id' => $crop->id, 'type' => 'general', 'soil_type' => null,   'body' => 'Generic tip']);
        CropTip::create(['crop_id' => $crop->id, 'type' => 'soil',    'soil_type' => 'clay', 'body' => 'Clay tip']);
        CropTip::create(['crop_id' => $crop->id, 'type' => 'soil',    'soil_type' => 'loam', 'body' => 'Loam tip']);

        $this->getJson("/api/crops/{$crop->id}/tips?soil_type=clay")
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    // ──────────────────────────────────────────────────────────────
    // Field recommendations
    // ──────────────────────────────────────────────────────────────

    public function test_given_loam_field_when_requesting_recommendations_then_returns_suitable_crops_ordered(): void
    {
        $user  = $this->actingAsUser();
        $farm  = Farm::factory()->create(['user_id' => $user->id]);
        $field = $farm->fields()->create(['name' => 'Test Field', 'soil_type' => 'loam']);

        $wheat  = Crop::factory()->create(['name' => 'Wheat']);
        $potato = Crop::factory()->create(['name' => 'Potato']);
        $tomato = Crop::factory()->create(['name' => 'Tomato']);

        CropSoilSuitability::create(['crop_id' => $wheat->id,  'soil_type' => 'loam', 'suitability' => 'ideal']);
        CropSoilSuitability::create(['crop_id' => $tomato->id, 'soil_type' => 'loam', 'suitability' => 'suitable']);
        CropSoilSuitability::create(['crop_id' => $potato->id, 'soil_type' => 'clay', 'suitability' => 'unsuitable']);

        $this->getJson("/api/farms/{$farm->id}/fields/{$field->id}/recommendations")
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.name', 'Wheat')
            ->assertJsonPath('data.0.suitability', 'ideal')
            ->assertJsonPath('data.1.suitability', 'suitable')
            ->assertJsonPath('meta.soil_type', 'loam');
    }

    public function test_given_field_with_no_suitabilities_when_requesting_recommendations_then_returns_empty(): void
    {
        $user  = $this->actingAsUser();
        $farm  = Farm::factory()->create(['user_id' => $user->id]);
        $field = $farm->fields()->create(['name' => 'Test Field', 'soil_type' => 'silt']);

        $this->getJson("/api/farms/{$farm->id}/fields/{$field->id}/recommendations")
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }
}
