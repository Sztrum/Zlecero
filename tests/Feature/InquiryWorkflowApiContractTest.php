<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\V1\Modules\Company\Domain\Enums\CompanyUserRole;
use App\V1\Modules\Company\Domain\Enums\CompanyUserStatus;
use App\V1\Modules\Company\Domain\Models\Company;
use App\V1\Modules\Customer\Domain\Enums\CustomerType;
use App\V1\Modules\Customer\Domain\Models\Customer;
use App\V1\Modules\Inquiry\Domain\Enums\InquiryMessageDirection;
use App\V1\Modules\Inquiry\Domain\Enums\InquiryPriority;
use App\V1\Modules\Inquiry\Domain\Enums\InquiryStatus;
use App\V1\Modules\User\Domain\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class InquiryWorkflowApiContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_inquiry_workflow_queue_archive_and_messages_contract(): void
    {
        [$token, $company, $owner] = $this->createVerifiedUserToken('zlecero.inquiry.owner@gmail.com');
        $customer = Customer::query()->create([
            'company_id' => $company->id,
            'type' => CustomerType::COMPANY->value,
            'display_name' => 'Inquiry Customer',
            'company_name' => 'Inquiry Customer sp. z o.o.',
            'email' => 'inquiry.customer@gmail.com',
            'country_code' => 'PL',
        ]);

        $createResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/inquiries', [
                'customer_id' => $customer->id,
                'owner_user_id' => $owner->id,
                'title' => 'Need CNC pricing',
                'description' => 'Customer needs CNC pricing this week.',
                'priority' => InquiryPriority::URGENT->value,
                'response_due_at' => now()->subHour()->toIso8601String(),
                'realization_due_at' => now()->addDays(3)->toIso8601String(),
                'pickup_due_at' => now()->addDays(5)->toIso8601String(),
            ])
            ->assertCreated()
            ->assertJsonPath('data.status', InquiryStatus::NEW->value)
            ->assertJsonPath('data.priority', InquiryPriority::URGENT->value)
            ->assertJsonPath('data.customer.displayName', 'Inquiry Customer')
            ->assertJsonPath('data.statusChanges.0.toStatus', InquiryStatus::NEW->value);

        $inquiryId = $createResponse->json('data.id');

        self::assertIsString($inquiryId);

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->patchJson("/api/v1/inquiries/{$inquiryId}/status", [
                'status' => InquiryStatus::OFFER_SENT->value,
            ])
            ->assertConflict();

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->patchJson("/api/v1/inquiries/{$inquiryId}/status", [
                'status' => InquiryStatus::TRIAGE->value,
            ])
            ->assertOk()
            ->assertJsonPath('data.status', InquiryStatus::TRIAGE->value)
            ->assertJsonCount(2, 'data.statusChanges');

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->postJson("/api/v1/inquiries/{$inquiryId}/messages", [
                'direction' => InquiryMessageDirection::OUTBOUND->value,
                'sender_name' => 'Zlecero Team',
                'sender_email' => 'zlecero.team@gmail.com',
                'recipient_email' => 'inquiry.customer@gmail.com',
                'subject' => 'Need CNC pricing',
                'body' => 'We need one more drawing before pricing.',
                'external_thread_id' => 'thread-1',
                'sent_at' => now()->toIso8601String(),
            ])
            ->assertOk()
            ->assertJsonPath('data.status', InquiryStatus::WAITING_FOR_CUSTOMER->value)
            ->assertJsonPath('data.messages.0.externalThreadId', 'thread-1');

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/inquiries?queue=overdue')
            ->assertOk()
            ->assertJsonCount(1, 'data.inquiries');

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->patchJson("/api/v1/inquiries/{$inquiryId}/archive")
            ->assertOk()
            ->assertJsonPath('data.archivedAt', fn ($value) => is_string($value));

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/inquiries')
            ->assertOk()
            ->assertJsonCount(0, 'data.inquiries');

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/inquiries?archived=1')
            ->assertOk()
            ->assertJsonCount(1, 'data.inquiries');
    }

    public function test_inquiry_access_is_scoped_to_authenticated_company(): void
    {
        [$token] = $this->createVerifiedUserToken('zlecero.inquiry.scope.owner@gmail.com');
        [$otherToken, $otherCompany] = $this->createVerifiedUserToken('zlecero.inquiry.scope.other@gmail.com');

        $otherCustomer = Customer::query()->create([
            'company_id' => $otherCompany->id,
            'type' => CustomerType::COMPANY->value,
            'display_name' => 'Other Inquiry Customer',
            'country_code' => 'PL',
        ]);

        Auth::forgetGuards();

        $createResponse = $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$otherToken}")
            ->postJson('/api/v1/inquiries', [
                'customer_id' => $otherCustomer->id,
                'title' => 'Other inquiry',
                'priority' => InquiryPriority::NORMAL->value,
            ])
            ->assertCreated();

        $inquiryId = $createResponse->json('data.id');

        self::assertIsString($inquiryId);

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->getJson("/api/v1/inquiries/{$inquiryId}")
            ->assertUnprocessable();
    }

    /**
     * @return array{string, Company, User}
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
            $user,
        ];
    }
}
