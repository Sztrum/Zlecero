<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\V1\Modules\Company\Domain\Enums\CompanyUserRole;
use App\V1\Modules\Company\Domain\Enums\CompanyUserStatus;
use App\V1\Modules\Company\Domain\Models\Company;
use App\V1\Modules\Customer\Domain\Enums\CustomerType;
use App\V1\Modules\Customer\Domain\Models\Customer;
use App\V1\Modules\User\Domain\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class CustomerApiContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_user_can_create_search_update_and_view_customer_profile(): void
    {
        [$token, $company] = $this->createVerifiedUserToken('zlecero.customer.owner@gmail.com');

        $createResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/customers', [
                'type' => CustomerType::COMPANY->value,
                'display_name' => 'Acme Manufacturing',
                'company_name' => 'Acme Manufacturing sp. z o.o.',
                'first_name' => 'Anna',
                'last_name' => 'Nowak',
                'email' => 'anna.customer@gmail.com',
                'phone' => '+48 500 100 200',
                'tax_number' => 'PL1234567890',
                'address_line' => 'Prosta 1',
                'postal_code' => '00-001',
                'city' => 'Warszawa',
                'country_code' => 'PL',
                'notes' => 'Key customer for MVP verification.',
            ])
            ->assertCreated()
            ->assertJsonPath('data.displayName', 'Acme Manufacturing')
            ->assertJsonPath('data.history.inquiries', [])
            ->assertJsonPath('data.potentialDuplicates', []);

        $customerId = $createResponse->json('data.id');

        self::assertIsString($customerId);

        Customer::query()->create([
            'company_id' => $company->id,
            'type' => CustomerType::COMPANY->value,
            'display_name' => 'Acme Duplicate',
            'company_name' => 'Acme Manufacturing sp. z o.o.',
            'email' => 'duplicate.customer@gmail.com',
            'tax_number' => 'PL1234567890',
            'country_code' => 'PL',
        ]);

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/customers?search=Acme')
            ->assertOk()
            ->assertJsonCount(2, 'data.customers')
            ->assertJsonPath('data.customers.0.displayName', 'Acme Duplicate')
            ->assertJsonPath('data.customers.1.displayName', 'Acme Manufacturing');

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->getJson("/api/v1/customers/{$customerId}")
            ->assertOk()
            ->assertJsonPath('data.potentialDuplicates.0.displayName', 'Acme Duplicate')
            ->assertJsonPath('data.history.offers', []);

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->patchJson("/api/v1/customers/{$customerId}", [
                'type' => CustomerType::INDIVIDUAL->value,
                'display_name' => 'Anna Nowak',
                'company_name' => null,
                'first_name' => 'Anna',
                'last_name' => 'Nowak',
                'email' => 'anna.customer.updated@gmail.com',
                'phone' => '+48 500 100 201',
                'tax_number' => null,
                'address_line' => 'Prosta 2',
                'postal_code' => '00-002',
                'city' => 'Warszawa',
                'country_code' => 'PL',
                'notes' => 'Updated profile.',
            ])
            ->assertOk()
            ->assertJsonPath('data.type', CustomerType::INDIVIDUAL->value)
            ->assertJsonPath('data.displayName', 'Anna Nowak')
            ->assertJsonPath('data.potentialDuplicates', []);
    }

    public function test_customer_access_is_scoped_to_authenticated_company(): void
    {
        [$ownerToken] = $this->createVerifiedUserToken('zlecero.customer.scope.owner@gmail.com');
        [$otherToken, $otherCompany] = $this->createVerifiedUserToken('zlecero.customer.scope.other@gmail.com');

        $otherCustomer = Customer::query()->create([
            'company_id' => $otherCompany->id,
            'type' => CustomerType::COMPANY->value,
            'display_name' => 'Other Company Customer',
            'company_name' => 'Other Company Customer',
            'email' => 'other.customer@gmail.com',
            'country_code' => 'PL',
        ]);

        $this->withHeader('Authorization', "Bearer {$ownerToken}")
            ->getJson("/api/v1/customers/{$otherCustomer->id}")
            ->assertUnprocessable();

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$otherToken}")
            ->getJson("/api/v1/customers/{$otherCustomer->id}")
            ->assertOk()
            ->assertJsonPath('data.displayName', 'Other Company Customer');
    }

    /**
     * @return array{string, Company}
     */
    private function createVerifiedUserToken(string $email): array
    {
        $company = Company::query()->create([
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
            'role' => CompanyUserRole::OWNER->value,
            'status' => CompanyUserStatus::ACTIVE->value,
        ]);
        $user->saveQuietly();

        return [
            $user->createToken('test-token')->plainTextToken,
            $company,
        ];
    }
}
