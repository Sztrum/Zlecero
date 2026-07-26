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
use App\V1\Modules\Inquiry\Domain\Models\Inquiry;
use App\V1\Modules\Offer\Domain\Enums\OfferStatus;
use App\V1\Modules\Offer\Domain\Models\Offer;
use App\V1\Modules\Order\Domain\Enums\OrderStatus;
use App\V1\Modules\Order\Domain\Models\Order;
use App\V1\Modules\StaticPages\UI\Mail\StaticContactLeadMail;
use App\V1\Modules\User\Domain\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class DashboardStaticPagesContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_dashboard_returns_company_scoped_operational_metrics(): void
    {
        [$token, $company, $user] = $this->createVerifiedUserToken('zlecero.dashboard.owner@gmail.com');
        $customer = $this->createCustomer($company);
        $inquiry = $this->createInquiry($company, $customer, $user, [
            'status' => InquiryStatus::NEW->value,
            'response_due_at' => now()->subHour(),
        ]);
        $offer = $this->createOffer($company, $customer, $inquiry, $user, OfferStatus::SENT);
        $this->createOrder($company, $customer, $inquiry, $offer, $user, [
            'status' => OrderStatus::IN_PROGRESS->value,
            'realization_due_date' => today()->subDay(),
        ]);

        [$otherToken, $otherCompany, $otherUser] = $this->createVerifiedUserToken('zlecero.dashboard.other@gmail.com');
        $otherCustomer = $this->createCustomer($otherCompany);
        $otherInquiry = $this->createInquiry($otherCompany, $otherCustomer, $otherUser);
        $this->createOffer($otherCompany, $otherCustomer, $otherInquiry, $otherUser, OfferStatus::SENT);

        $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/dashboard')
            ->assertOk()
            ->assertJsonPath('data.cards.0.value', 1)
            ->assertJsonPath('data.cards.2.value', 1)
            ->assertJsonPath('data.cards.3.value', 1)
            ->assertJsonCount(2, 'data.attentionItems');

        Auth::forgetGuards();

        $this->flushHeaders()
            ->withHeader('Authorization', "Bearer {$otherToken}")
            ->getJson('/api/v1/dashboard')
            ->assertOk()
            ->assertJsonPath('data.cards.2.value', 1)
            ->assertJsonPath('data.cards.3.value', 0);
    }

    public function test_admin_dashboard_exposes_platform_metrics_without_business_records(): void
    {
        [$token] = $this->createVerifiedUserToken('zlecero.dashboard.admin@gmail.com');
        $this->createVerifiedUserToken('zlecero.dashboard.admin.other@gmail.com');

        $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/dashboard/admin')
            ->assertOk()
            ->assertJsonPath('data.cards.0.value', 2)
            ->assertJsonStructure([
                'data' => [
                    'cards',
                    'recentCompanies' => [['id', 'name', 'slug']],
                    'alerts',
                ],
            ]);
    }

    public function test_public_pages_render_with_localized_paths(): void
    {
        $this->get('/')->assertRedirect('/pl');
        $this->get('/pl')
            ->assertOk()
            ->assertSee('Każde zapytanie z maila', false)
            ->assertSee('href="/login"', false)
            ->assertSee('href="/auth/register"', false);
        $this->get('/pl/pricing')->assertOk()->assertSee('Prosty cennik', false);
        $this->get('/pl/faq')->assertOk()->assertSee('FAQPage', false);
        $this->get('/pl/about')->assertOk()->assertSee('Zlecero powstaje', false);
        $this->get('/pl/contact')->assertOk()->assertSee('Wyślij zgłoszenie', false);
    }

    public function test_contact_form_validates_deduplicates_and_queues_message(): void
    {
        Mail::fake();

        $payload = [
            'name' => 'Konrad Nowicki',
            'company' => 'Replika Drukarnia',
            'email' => 'konrad@example.com',
            'phone' => '+48 600 000 000',
            'subject' => 'Pilot',
            'message' => 'Chcemy sprawdzić Zlecero w naszym procesie ofertowania.',
        ];

        $this->post('/pl/contact', $payload)
            ->assertRedirect('/pl/contact')
            ->assertSessionHas('contact_status');

        Mail::assertQueued(StaticContactLeadMail::class);

        $this->post('/pl/contact', $payload)
            ->assertSessionHas('contact_status');
    }

    /**
     * @return array{string, Company, User}
     */
    private function createVerifiedUserToken(string $email, CompanyUserRole $role = CompanyUserRole::OWNER): array
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
            'role' => $role->value,
            'status' => CompanyUserStatus::ACTIVE->value,
        ]);
        $user->saveQuietly();

        return [
            $user->createToken('test-token')->plainTextToken,
            $company,
            $user,
        ];
    }

    private function createCustomer(Company $company): Customer
    {
        return Customer::query()->create([
            'id' => (string) Str::uuid(),
            'company_id' => $company->id,
            'type' => CustomerType::COMPANY->value,
            'display_name' => 'Dashboard Customer',
            'company_name' => 'Dashboard Customer',
            'email' => 'dashboard.customer@example.com',
            'country_code' => 'PL',
        ]);
    }

    /**
     * @param array<string, mixed> $overrides
     */
    private function createInquiry(Company $company, Customer $customer, User $user, array $overrides = []): Inquiry
    {
        return Inquiry::query()->create(array_merge([
            'id' => (string) Str::uuid(),
            'company_id' => $company->id,
            'customer_id' => $customer->id,
            'owner_user_id' => $user->id,
            'source' => 'manual',
            'title' => 'Dashboard inquiry',
            'status' => InquiryStatus::WAITING_FOR_CUSTOMER->value,
            'priority' => InquiryPriority::NORMAL->value,
            'response_due_at' => now()->addHour(),
        ], $overrides));
    }

    private function createOffer(
        Company $company,
        Customer $customer,
        Inquiry $inquiry,
        User $user,
        OfferStatus $status
    ): Offer {
        return Offer::query()->create([
            'id' => (string) Str::uuid(),
            'company_id' => $company->id,
            'inquiry_id' => $inquiry->id,
            'customer_id' => $customer->id,
            'owner_user_id' => $user->id,
            'number' => 'OFF/'.Str::random(8),
            'status' => $status->value,
            'currency' => 'PLN',
            'issue_date' => today(),
            'valid_until' => today()->addDays(3),
            'payment_due_days' => 7,
            'total_gross_cents' => 120000,
        ]);
    }

    /**
     * @param array<string, mixed> $overrides
     */
    private function createOrder(
        Company $company,
        Customer $customer,
        Inquiry $inquiry,
        Offer $offer,
        User $user,
        array $overrides = []
    ): Order {
        return Order::query()->create(array_merge([
            'id' => (string) Str::uuid(),
            'company_id' => $company->id,
            'inquiry_id' => $inquiry->id,
            'offer_id' => $offer->id,
            'customer_id' => $customer->id,
            'owner_user_id' => $user->id,
            'number' => 'ORD/'.Str::random(8),
            'status' => OrderStatus::NEW->value,
            'currency' => 'PLN',
            'accepted_date' => today(),
            'realization_due_date' => today()->addDays(3),
            'total_gross_cents' => 120000,
        ], $overrides));
    }
}
