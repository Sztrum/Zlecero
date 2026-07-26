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

    public function test_user_email_can_be_verified_with_signed_mail_hash(): void
    {
        config(['core::languages.enabled_system_languages' => ['pl', 'en', 'de']]);

        $user = new User();
        $user->forceFill([
            'id' => (string) Str::uuid(),
            'name' => 'Konrad Nowicki',
            'email' => 'zlecero.verify.user@gmail.com',
            'password' => Hash::make('secret-password'),
        ]);
        $user->saveQuietly();

        $this->postJson(
            sprintf(
                '/api/v1/auth/verify-email/%s/email/verify/%s',
                $user->id,
                $user->generateHashToEmailVerification(),
            ),
        )
            ->assertOk()
            ->assertJsonPath('status', 200)
            ->assertJsonPath('data.user_id', $user->id)
            ->assertJsonStructure([
                'data' => [
                    'remember_token',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'zlecero.verify.user@gmail.com',
        ]);

        self::assertNotNull($user->fresh()?->email_verified_at);
    }

    public function test_verified_user_email_verification_is_idempotent_for_same_valid_hash(): void
    {
        config(['core::languages.enabled_system_languages' => ['pl', 'en', 'de']]);

        $user = new User();
        $user->forceFill([
            'id' => (string) Str::uuid(),
            'name' => 'Konrad Nowicki',
            'email' => 'zlecero.verified.user@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('secret-password'),
        ]);
        $user->saveQuietly();

        $this->postJson(
            sprintf(
                '/api/v1/auth/verify-email/%s/email/verify/%s',
                $user->id,
                $user->generateHashToEmailVerification(),
            ),
        )
            ->assertOk()
            ->assertJsonPath('status', 200)
            ->assertJsonPath('data.user_id', $user->id);
    }

    public function test_bearer_token_user_can_read_profile_and_logout(): void
    {
        config(['core::languages.enabled_system_languages' => ['pl', 'en', 'de']]);

        $user = new User();
        $user->forceFill([
            'id' => (string) Str::uuid(),
            'name' => 'Konrad Nowicki',
            'email' => 'zlecero.contract.user@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('secret-password'),
        ]);
        $user->saveQuietly();

        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'email' => 'zlecero.contract.user@gmail.com',
            'password' => 'secret-password',
        ])
            ->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'token',
                ],
            ]);

        $token = $loginResponse->json('data.token');

        self::assertIsString($token);

        $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/auth/profile')
            ->assertOk()
            ->assertJsonPath('data.id', $user->id)
            ->assertJsonPath('data.name', 'Konrad Nowicki')
            ->assertJsonPath('data.email', 'zlecero.contract.user@gmail.com');

        $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/auth/logout')
            ->assertOk()
            ->assertJsonPath('status', 200);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);
    }

    public function test_login_accepts_normalized_rfc_email_without_dns_requirement(): void
    {
        config(['core::languages.enabled_system_languages' => ['pl', 'en', 'de']]);

        $user = new User();
        $user->forceFill([
            'id' => (string) Str::uuid(),
            'name' => 'Konrad Nowicki',
            'email' => 'owner@example.invalid',
            'email_verified_at' => now(),
            'password' => Hash::make('secret-password'),
        ]);
        $user->saveQuietly();

        $this->postJson('/api/v1/auth/login', [
            'email' => '  OWNER@EXAMPLE.INVALID  ',
            'password' => 'secret-password',
        ])
            ->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'token',
                ],
            ]);
    }
}
