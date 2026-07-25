<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\V1\Modules\Company\Domain\Enums\CompanyUserRole;
use App\V1\Modules\Company\Domain\Enums\CompanyUserStatus;
use App\V1\Modules\Company\Domain\Models\Company;
use App\V1\Modules\User\Domain\Events\UserHasBeenCreatedEvent;
use App\V1\Modules\User\Domain\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class CompanyAccessApiContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_registration_creates_company_owner_and_profile_contract(): void
    {
        Event::fake([
            UserHasBeenCreatedEvent::class,
        ]);

        $this->postJson('/api/v1/auth/register', [
            'name' => 'Konrad Owner',
            'email' => 'zlecero.owner@gmail.com',
            'password' => 'ZleceroTest123!',
            'password_confirmation' => 'ZleceroTest123!',
            'company_name' => 'Zlecero Studio',
            'terms_accepted' => true,
        ])->assertOk();

        $this->assertDatabaseHas('companies', [
            'name' => 'Zlecero Studio',
            'slug' => 'zlecero-studio',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'zlecero.owner@gmail.com',
            'role' => CompanyUserRole::OWNER->value,
            'status' => CompanyUserStatus::ACTIVE->value,
        ]);

        /** @var User $user */
        $user = User::query()
            ->where('email', 'zlecero.owner@gmail.com')
            ->firstOrFail();
        $user->forceFill(['email_verified_at' => now()])->saveQuietly();

        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'email' => 'zlecero.owner@gmail.com',
            'password' => 'ZleceroTest123!',
        ])->assertOk();

        $token = $loginResponse->json('data.token');

        self::assertIsString($token);

        $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/auth/profile')
            ->assertOk()
            ->assertJsonPath('data.role', CompanyUserRole::OWNER->value)
            ->assertJsonPath('data.status', CompanyUserStatus::ACTIVE->value)
            ->assertJsonPath('data.company.name', 'Zlecero Studio');

        $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/companies/current')
            ->assertOk()
            ->assertJsonPath('data.name', 'Zlecero Studio')
            ->assertJsonPath('data.slug', 'zlecero-studio');
    }

    public function test_company_user_management_is_limited_to_manager_roles_and_current_company(): void
    {
        Event::fake([
            UserHasBeenCreatedEvent::class,
        ]);

        [$ownerToken, $ownerCompany] = $this->createVerifiedUserToken(
            email: 'zlecero.owner.access@gmail.com',
            role: CompanyUserRole::OWNER,
        );

        [$memberToken] = $this->createVerifiedUserToken(
            email: 'zlecero.member.access@gmail.com',
            role: CompanyUserRole::MEMBER,
            company: $ownerCompany,
        );

        [$otherOwnerToken] = $this->createVerifiedUserToken(
            email: 'zlecero.other.owner@gmail.com',
            role: CompanyUserRole::OWNER,
        );

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$memberToken}")
            ->postJson('/api/v1/companies/users', [
                'name' => 'Blocked User',
                'email' => 'zlecero.blocked@gmail.com',
                'role' => CompanyUserRole::MEMBER->value,
            ])
            ->assertForbidden();

        Auth::forgetGuards();

        $inviteResponse = $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$ownerToken}")
            ->postJson('/api/v1/companies/users', [
                'name' => 'Invited Member',
                'email' => 'zlecero.invited.member@gmail.com',
                'role' => CompanyUserRole::MEMBER->value,
            ])
            ->assertCreated()
            ->assertJsonPath('data.email', 'zlecero.invited.member@gmail.com')
            ->assertJsonPath('data.status', CompanyUserStatus::INVITED->value);

        $invitedUserId = $inviteResponse->json('data.id');

        self::assertIsString($invitedUserId);

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$otherOwnerToken}")
            ->patchJson("/api/v1/companies/users/{$invitedUserId}/deactivate")
            ->assertForbidden();

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$ownerToken}")
            ->patchJson("/api/v1/companies/users/{$invitedUserId}/deactivate")
            ->assertOk()
            ->assertJsonPath('data.status', CompanyUserStatus::DEACTIVATED->value);

        /** @var User $ownerUser */
        $ownerUser = User::query()
            ->where('email', 'zlecero.owner.access@gmail.com')
            ->firstOrFail();

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$ownerToken}")
            ->patchJson("/api/v1/companies/users/{$ownerUser->id}/deactivate")
            ->assertConflict();
    }

    /**
     * @return array{string, Company}
     */
    private function createVerifiedUserToken(
        string $email,
        CompanyUserRole $role,
        ?Company $company = null,
    ): array {
        $company ??= Company::query()->create([
            'id' => (string) Str::uuid(),
            'name' => "Company {$email}",
            'slug' => Str::slug("Company {$email}"),
            'trial_started_at' => now(),
            'trial_ends_at' => now()->addDays(14),
        ]);

        /** @var Company $company */
        $user = new User();
        $user->forceFill([
            'id' => (string) Str::uuid(),
            'company_id' => $company->id,
            'name' => "User {$email}",
            'email' => $email,
            'email_verified_at' => now(),
            'password' => Hash::make('ZleceroTest123!'),
            'role' => $role->value,
            'status' => CompanyUserStatus::ACTIVE->value,
        ]);
        $user->saveQuietly();

        return [
            $user->createToken('test-token')->plainTextToken,
            $company,
        ];
    }
}
