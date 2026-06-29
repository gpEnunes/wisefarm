<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // ──────────────────────────────────────────────────────────────
    // POST /api/auth/register
    // ──────────────────────────────────────────────────────────────

    public function test_given_valid_data_when_registering_then_returns_201_with_token_and_user(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name'                  => 'Jane Farmer',
            'email'                 => 'jane@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'token',
                'user' => ['id', 'name', 'email'],
            ]);
    }

    public function test_given_duplicate_email_when_registering_then_returns_422(): void
    {
        User::factory()->create(['email' => 'jane@example.com']);

        $this->postJson('/api/auth/register', [
            'name'                  => 'Jane Again',
            'email'                 => 'jane@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertStatus(422);
    }

    // ──────────────────────────────────────────────────────────────
    // POST /api/auth/login
    // ──────────────────────────────────────────────────────────────

    public function test_given_valid_credentials_when_logging_in_then_returns_200_with_token(): void
    {
        User::factory()->create(['email' => 'farmer@example.com']);

        $this->postJson('/api/auth/login', [
            'email'    => 'farmer@example.com',
            'password' => 'password', // UserFactory default
        ])
            ->assertStatus(200)
            ->assertJsonStructure(['token', 'user']);
    }

    public function test_given_wrong_password_when_logging_in_then_returns_422(): void
    {
        User::factory()->create(['email' => 'farmer@example.com']);

        $this->postJson('/api/auth/login', [
            'email'    => 'farmer@example.com',
            'password' => 'wrong-password',
        ])->assertStatus(422);
    }

    // ──────────────────────────────────────────────────────────────
    // POST /api/auth/logout
    // ──────────────────────────────────────────────────────────────

    public function test_given_authenticated_user_when_logging_out_then_returns_200_and_token_is_revoked(): void
    {
        $user = User::factory()->create(['email' => 'farmer@example.com']);

        // Obtain a real Bearer token so we can verify it is physically deleted after logout
        $token = $this->postJson('/api/auth/login', [
            'email'    => 'farmer@example.com',
            'password' => 'password',
        ])->json('token');

        $this->withToken($token)
            ->postJson('/api/auth/logout')
            ->assertStatus(200);

        // The token row must be gone from the database (i.e., revoked)
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);

        // Clearing the in-process auth guard cache ensures the next request
        // re-reads the DB rather than returning the already-resolved user.
        $this->app['auth']->forgetGuards();

        $this->withToken($token)
            ->getJson('/api/auth/me')
            ->assertStatus(401);
    }

    // ──────────────────────────────────────────────────────────────
    // GET /api/auth/me
    // ──────────────────────────────────────────────────────────────

    public function test_given_authenticated_user_when_calling_me_then_returns_id_name_email_only(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->getJson('/api/auth/me')
            ->assertStatus(200)
            ->assertExactJson([
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ]);
    }

    public function test_given_unauthenticated_request_when_calling_me_then_returns_401(): void
    {
        $this->getJson('/api/auth/me')
            ->assertStatus(401);
    }
}
