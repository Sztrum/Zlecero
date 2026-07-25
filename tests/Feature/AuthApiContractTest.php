<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\V1\Modules\User\Domain\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthApiContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_bearer_token_user_can_read_profile_and_logout(): void
    {
        config(['core::languages.enabled_system_languages' => ['pl', 'en', 'de']]);

        $user = new User();
        $user->forceFill([
            'id' => (string) Str::uuid(),
            'name' => 'Konrad Nowicki',
            'email' => 'konrad@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('secret-password'),
        ]);
        $user->saveQuietly();

        $token = $user->createToken('test-token')->plainTextToken;

        $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/auth/profile')
            ->assertOk()
            ->assertJsonPath('data.id', $user->id)
            ->assertJsonPath('data.name', 'Konrad Nowicki')
            ->assertJsonPath('data.email', 'konrad@example.com');

        $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/auth/logout')
            ->assertOk()
            ->assertJsonPath('status', 200);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);
    }
}
