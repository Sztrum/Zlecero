<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\V1\Modules\Company\Domain\Enums\CompanyUserRole;
use App\V1\Modules\Company\Domain\Enums\CompanyUserStatus;
use App\V1\Modules\Company\Domain\Models\Company;
use App\V1\Modules\Customer\Domain\Enums\CustomerType;
use App\V1\Modules\Customer\Domain\Models\Customer;
use App\V1\Modules\Inquiry\Domain\Enums\InquiryPriority;
use App\V1\Modules\Offer\Domain\Enums\OfferDiscountType;
use App\V1\Modules\Offer\Domain\Enums\OfferStatus;
use App\V1\Modules\Order\Domain\Enums\OrderStatus;
use App\V1\Modules\User\Domain\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class OfferOrderApiContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_offer_pdf_acceptance_and_order_conversion_contract(): void
    {
        Storage::fake('local');

        [$token, $company] = $this->createVerifiedUserToken('zlecero.offer.owner@gmail.com');
        $customer = Customer::query()->create([
            'company_id' => $company->id,
            'type' => CustomerType::COMPANY->value,
            'display_name' => 'Offer Customer',
            'company_name' => 'Offer Customer sp. z o.o.',
            'email' => 'offer.customer@gmail.com',
            'country_code' => 'PL',
        ]);
        $inquiryId = $this->createInquiry($token, $customer->id);

        Auth::forgetGuards();

        $offerResponse = $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/offers', $this->offerPayload($inquiryId))
            ->assertCreated()
            ->assertJsonPath('data.status', OfferStatus::DRAFT->value)
            ->assertJsonPath('data.subtotalNetCents', 20000)
            ->assertJsonPath('data.taxCents', 4600)
            ->assertJsonPath('data.discountCents', 2560)
            ->assertJsonPath('data.totalGrossCents', 23040)
            ->assertJsonPath('data.depositCents', 4608)
            ->assertJsonPath('data.items.0.grossCents', 24600);

        $offerId = $offerResponse->json('data.id');

        self::assertIsString($offerId);

        Auth::forgetGuards();

        $pdfResponse = $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->postJson("/api/v1/offers/{$offerId}/pdf")
            ->assertOk()
            ->assertJsonPath('data.pdf.generatedAt', fn ($value) => is_string($value));

        $downloadUrl = $pdfResponse->json('data.pdf.downloadUrl');

        self::assertIsString($downloadUrl);

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->get($downloadUrl)
            ->assertOk();

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->patchJson("/api/v1/offers/{$offerId}/send")
            ->assertOk()
            ->assertJsonPath('data.status', OfferStatus::SENT->value)
            ->assertJsonPath('data.sentAt', fn ($value) => is_string($value));

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->patchJson("/api/v1/offers/{$offerId}", $this->offerPayload($inquiryId, 'Changed item'))
            ->assertConflict();

        Auth::forgetGuards();

        $acceptedResponse = $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->postJson("/api/v1/offers/{$offerId}/accept")
            ->assertOk()
            ->assertJsonPath('data.status', OfferStatus::ACCEPTED->value)
            ->assertJsonPath('data.orderId', fn ($value) => is_string($value));

        $orderId = $acceptedResponse->json('data.orderId');

        self::assertIsString($orderId);
        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'company_id' => $company->id,
            'offer_id' => $offerId,
            'status' => OrderStatus::NEW->value,
            'total_gross_cents' => 23040,
            'deposit_cents' => 4608,
        ]);
        $this->assertDatabaseCount('orders', 1);

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->postJson("/api/v1/offers/{$offerId}/accept")
            ->assertOk()
            ->assertJsonPath('data.orderId', $orderId);

        $this->assertDatabaseCount('orders', 1);

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->getJson("/api/v1/orders/{$orderId}")
            ->assertOk()
            ->assertJsonPath('data.offerId', $offerId)
            ->assertJsonPath('data.items.0.grossCents', 24600);

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->patchJson("/api/v1/orders/{$orderId}/status", ['status' => OrderStatus::IN_PROGRESS->value])
            ->assertOk()
            ->assertJsonPath('data.status', OrderStatus::IN_PROGRESS->value);

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->patchJson("/api/v1/orders/{$orderId}/status", ['status' => OrderStatus::COMPLETED->value])
            ->assertOk()
            ->assertJsonPath('data.status', OrderStatus::COMPLETED->value);

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->patchJson("/api/v1/orders/{$orderId}/status", ['status' => OrderStatus::IN_PROGRESS->value])
            ->assertConflict();
    }

    public function test_offer_access_is_scoped_to_authenticated_company(): void
    {
        [$token] = $this->createVerifiedUserToken('zlecero.offer.scope.owner@gmail.com');
        [$otherToken, $otherCompany] = $this->createVerifiedUserToken('zlecero.offer.scope.other@gmail.com');
        $otherCustomer = Customer::query()->create([
            'company_id' => $otherCompany->id,
            'type' => CustomerType::COMPANY->value,
            'display_name' => 'Other Offer Customer',
            'country_code' => 'PL',
        ]);
        $otherInquiryId = $this->createInquiry($otherToken, $otherCustomer->id);

        Auth::forgetGuards();

        $offerResponse = $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$otherToken}")
            ->postJson('/api/v1/offers', $this->offerPayload($otherInquiryId))
            ->assertCreated();

        $offerId = $offerResponse->json('data.id');

        self::assertIsString($offerId);

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$token}")
            ->getJson("/api/v1/offers/{$offerId}")
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

    private function createInquiry(string $token, string $customerId): string
    {
        $createResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/inquiries', [
                'customer_id' => $customerId,
                'title' => 'Need offer pricing',
                'priority' => InquiryPriority::NORMAL->value,
            ])
            ->assertCreated();

        $inquiryId = $createResponse->json('data.id');

        self::assertIsString($inquiryId);

        return $inquiryId;
    }

    /**
     * @return array<string, mixed>
     */
    private function offerPayload(string $inquiryId, string $itemName = 'CNC milling'): array
    {
        return [
            'inquiry_id' => $inquiryId,
            'currency' => 'PLN',
            'issue_date' => today()->toDateString(),
            'valid_until' => today()->addDays(14)->toDateString(),
            'payment_due_days' => 7,
            'delivery_cost_cents' => 1000,
            'discount_type' => OfferDiscountType::PERCENT->value,
            'discount_value' => '10',
            'deposit_percent' => '20',
            'terms' => '50% payment before pickup.',
            'notes' => 'Prepared for MVP test.',
            'items' => [
                [
                    'name' => $itemName,
                    'description' => 'Aluminium part.',
                    'quantity' => '2',
                    'unit' => 'pcs',
                    'unit_price_cents' => 10000,
                    'tax_rate' => '23',
                ],
            ],
        ];
    }
}
