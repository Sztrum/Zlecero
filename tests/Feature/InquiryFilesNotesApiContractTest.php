<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\V1\Modules\Company\Domain\Enums\CompanyUserRole;
use App\V1\Modules\Company\Domain\Enums\CompanyUserStatus;
use App\V1\Modules\Company\Domain\Models\Company;
use App\V1\Modules\Customer\Domain\Enums\CustomerType;
use App\V1\Modules\Customer\Domain\Models\Customer;
use App\V1\Modules\Inquiry\Domain\Enums\InquiryPriority;
use App\V1\Modules\Inquiry\Domain\Enums\InquiryStatus;
use App\V1\Modules\User\Domain\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class InquiryFilesNotesApiContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_inquiry_files_notes_download_and_owner_assignment_contract(): void
    {
        Storage::fake('local');

        [$token, $company, $owner] = $this->createVerifiedUserToken('zlecero.inquiry.files.owner@gmail.com');
        $assignee = $this->createCompanyUser($company, 'zlecero.inquiry.files.assignee@gmail.com', CompanyUserRole::MEMBER);
        $customer = Customer::query()->create([
            'company_id' => $company->id,
            'type' => CustomerType::COMPANY->value,
            'display_name' => 'Files Customer',
            'company_name' => 'Files Customer sp. z o.o.',
            'email' => 'files.customer@gmail.com',
            'country_code' => 'PL',
        ]);

        $inquiryId = $this->createInquiry($token, $customer->id);

        Auth::forgetGuards();

        $uploadResponse = $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->post("/api/v1/inquiries/{$inquiryId}/files", [
                'file' => UploadedFile::fake()->create('drawing.pdf', 32, 'application/pdf'),
                'category' => 'drawing',
                'description' => 'Customer drawing for pricing.',
            ])
            ->assertCreated()
            ->assertJsonPath('data.files.0.originalName', 'drawing.pdf')
            ->assertJsonPath('data.files.0.category', 'drawing')
            ->assertJsonPath('data.files.0.uploadedByUserId', $owner->id);

        $fileId = $uploadResponse->json('data.files.0.id');
        $downloadUrl = $uploadResponse->json('data.files.0.downloadUrl');

        self::assertIsString($fileId);
        self::assertIsString($downloadUrl);
        $this->assertDatabaseHas('inquiry_files', [
            'id' => $fileId,
            'company_id' => $company->id,
            'inquiry_id' => $inquiryId,
            'customer_id' => $customer->id,
            'source' => 'manual',
            'original_name' => 'drawing.pdf',
        ]);

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->get($downloadUrl)
            ->assertOk();

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->postJson("/api/v1/inquiries/{$inquiryId}/notes", [
                'body' => 'Customer prefers pickup next Friday.',
            ])
            ->assertOk()
            ->assertJsonPath('data.notes.0.body', 'Customer prefers pickup next Friday.')
            ->assertJsonPath('data.notes.0.isInternal', true)
            ->assertJsonPath('data.notes.0.author.id', $owner->id);

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->patchJson("/api/v1/inquiries/{$inquiryId}/owner", [
                'owner_user_id' => $assignee->id,
            ])
            ->assertOk()
            ->assertJsonPath('data.owner.id', $assignee->id);

        $this->assertDatabaseHas('inquiry_notes', [
            'company_id' => $company->id,
            'inquiry_id' => $inquiryId,
            'author_user_id' => $owner->id,
            'body' => "Owner changed from unassigned to {$assignee->id}.",
            'is_internal' => true,
        ]);

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->patchJson("/api/v1/inquiries/{$inquiryId}/owner", [
                'owner_user_id' => null,
            ])
            ->assertOk()
            ->assertJsonPath('data.owner', null);
    }

    public function test_inquiry_file_validation_and_role_restrictions_are_enforced(): void
    {
        [$token, $company] = $this->createVerifiedUserToken('zlecero.inquiry.files.admin@gmail.com');
        $member = $this->createCompanyUser($company, 'zlecero.inquiry.files.member@gmail.com', CompanyUserRole::MEMBER);
        $memberToken = $member->createToken('test-token')->plainTextToken;
        $customer = Customer::query()->create([
            'company_id' => $company->id,
            'type' => CustomerType::COMPANY->value,
            'display_name' => 'Restricted Files Customer',
            'country_code' => 'PL',
        ]);

        $inquiryId = $this->createInquiry($token, $customer->id);

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->post("/api/v1/inquiries/{$inquiryId}/files", [
                'file' => UploadedFile::fake()->create('unsafe.exe', 1, 'application/x-msdownload'),
            ])
            ->assertUnprocessable();

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$memberToken}")
            ->patchJson("/api/v1/inquiries/{$inquiryId}/owner", [
                'owner_user_id' => $member->id,
            ])
            ->assertForbidden();
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

        $user = $this->createCompanyUser($company, $email, CompanyUserRole::OWNER);

        return [
            $user->createToken('test-token')->plainTextToken,
            $company,
            $user,
        ];
    }

    private function createCompanyUser(Company $company, string $email, CompanyUserRole $role): User
    {
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

        return $user;
    }

    private function createInquiry(string $token, string $customerId): string
    {
        $createResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/inquiries', [
                'customer_id' => $customerId,
                'title' => 'Need file pricing',
                'priority' => InquiryPriority::NORMAL->value,
            ])
            ->assertCreated()
            ->assertJsonPath('data.status', InquiryStatus::NEW->value);

        $inquiryId = $createResponse->json('data.id');

        self::assertIsString($inquiryId);

        return $inquiryId;
    }
}
