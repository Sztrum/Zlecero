<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\V1\Modules\Company\Domain\Enums\CompanyUserRole;
use App\V1\Modules\Company\Domain\Enums\CompanyUserStatus;
use App\V1\Modules\Company\Domain\Models\Company;
use App\V1\Modules\Customer\Domain\Enums\CustomerType;
use App\V1\Modules\Customer\Domain\Models\Customer;
use App\V1\Modules\Inquiry\Domain\Enums\InquiryMessageDirection;
use App\V1\Modules\Inquiry\Domain\Enums\InquiryPriority;
use App\V1\Modules\Inquiry\Domain\Enums\InquiryStatus;
use App\V1\Modules\Inquiry\Domain\Models\Inquiry;
use App\V1\Modules\Inquiry\Domain\Models\InquiryMessage;
use App\V1\Modules\Inquiry\Domain\Models\InquiryNote;
use App\V1\Modules\Inquiry\Infrastructure\Repositories\InquiryRepository;
use App\V1\Modules\Offer\Domain\Enums\OfferStatus;
use App\V1\Modules\Offer\Domain\Models\Offer;
use App\V1\Modules\Offer\Infrastructure\Repositories\OfferRepository;
use App\V1\Modules\User\Domain\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

/**
 * Local demo dataset for manual testing of the company workflow.
 *
 * Not registered in DatabaseSeeder on purpose: run it explicitly with
 * `php artisan db:seed --class=DemoDataSeeder`. Re-running replaces the
 * demo company and everything owned by it, and never touches other companies.
 */
class DemoDataSeeder extends Seeder
{
    private const COMPANY_SLUG = 'zlecero-demo';

    private const PASSWORD = 'password';

    public function run(): void
    {
        $this->reset();

        $company = $this->createCompany();
        [$owner, $member] = $this->createUsers($company);
        $customers = $this->createCustomers($company);
        $inquiries = $this->createInquiries($company, $owner, $member, $customers);

        $this->createMessagesAndNotes($company, $owner, $member, $inquiries);
        $this->createOffers($company, $owner, $inquiries);

        $this->report($company);
    }

    private function reset(): void
    {
        $company = Company::query()->where('slug', self::COMPANY_SLUG)->first();

        if (! $company instanceof Company) {
            return;
        }

        User::query()->where('company_id', $company->id)->delete();
        $company->delete();
    }

    private function createCompany(): Company
    {
        /** @var Company $company */
        $company = Company::query()->create([
            'name' => 'Reklama Wizual',
            'slug' => self::COMPANY_SLUG,
            'billing_name' => 'Reklama Wizual Sp. z o.o.',
            'tax_number' => '7010001234',
            'contact_email' => 'biuro@reklama-wizual.test',
            'contact_phone' => '+48 61 100 20 30',
            'address_line' => 'ul. Drukarska 14',
            'postal_code' => '61-001',
            'city' => 'Poznań',
            'country_code' => 'PL',
            'brand_color' => '#9C442D',
            'trial_days' => 14,
            'trial_started_at' => now()->subDays(3),
            'trial_ends_at' => now()->addDays(11),
            'onboarding_completed_at' => now()->subDays(2),
        ]);

        return $company;
    }

    /**
     * @return array{0: User, 1: User}
     */
    private function createUsers(Company $company): array
    {
        /** @var User $owner */
        $owner = User::query()->create([
            'company_id' => $company->id,
            'name' => 'Konrad Wiśniewski',
            'email' => 'demo@zlecero.test',
            'password' => Hash::make(self::PASSWORD),
            'role' => CompanyUserRole::OWNER->value,
            'status' => CompanyUserStatus::ACTIVE->value,
        ]);
        $owner->forceFill(['email_verified_at' => now()])->save();

        /** @var User $member */
        $member = User::query()->create([
            'company_id' => $company->id,
            'name' => 'Anna Nowak',
            'email' => 'pracownik@zlecero.test',
            'password' => Hash::make(self::PASSWORD),
            'role' => CompanyUserRole::MEMBER->value,
            'status' => CompanyUserStatus::ACTIVE->value,
        ]);
        $member->forceFill(['email_verified_at' => now()])->save();

        return [$owner, $member];
    }

    /**
     * @return array<string, Customer>
     */
    private function createCustomers(Company $company): array
    {
        $definitions = [
            'autoserwis' => [
                'type' => CustomerType::COMPANY->value,
                'display_name' => 'Autoserwis Kowalski',
                'company_name' => 'Autoserwis Kowalski Sp. z o.o.',
                'email' => 'biuro@autoserwis-kowalski.test',
                'phone' => '+48 601 100 200',
                'tax_number' => '7792233445',
                'address_line' => 'ul. Warsztatowa 8',
                'postal_code' => '61-234',
                'city' => 'Poznań',
            ],
            'piekarnia' => [
                'type' => CustomerType::COMPANY->value,
                'display_name' => 'Piekarnia Złoty Kłos',
                'company_name' => 'Piekarnia Złoty Kłos S.A.',
                'email' => 'zamowienia@zlotyklos.test',
                'phone' => '+48 602 300 400',
                'tax_number' => '7811122233',
                'address_line' => 'Rynek 3',
                'postal_code' => '62-100',
                'city' => 'Wągrowiec',
            ],
            'fitness' => [
                'type' => CustomerType::COMPANY->value,
                'display_name' => 'Fitness Strefa',
                'company_name' => 'Strefa Fit Sp. z o.o.',
                'email' => 'kontakt@strefafit.test',
                'phone' => '+48 603 500 600',
                'tax_number' => '7822244455',
                'address_line' => 'al. Sportowa 21',
                'postal_code' => '60-999',
                'city' => 'Poznań',
            ],
            'fitness_duplikat' => [
                'type' => CustomerType::COMPANY->value,
                'display_name' => 'Strefa Fit',
                'company_name' => 'Strefa Fit Sp. z o.o.',
                'email' => 'kontakt@strefafit.test',
                'phone' => '+48 603 500 601',
                'tax_number' => '7822244455',
                'address_line' => 'al. Sportowa 21',
                'postal_code' => '60-999',
                'city' => 'Poznań',
                'notes' => 'Rekord dodany ręcznie przez handlowca - kandydat do scalenia.',
            ],
            'jankowalski' => [
                'type' => CustomerType::INDIVIDUAL->value,
                'display_name' => 'Jan Kowalski',
                'first_name' => 'Jan',
                'last_name' => 'Kowalski',
                'email' => 'jan.kowalski@poczta.test',
                'phone' => '+48 604 700 800',
                'address_line' => 'ul. Kwiatowa 5/2',
                'postal_code' => '61-500',
                'city' => 'Poznań',
            ],
            'martanowak' => [
                'type' => CustomerType::INDIVIDUAL->value,
                'display_name' => 'Marta Zielińska',
                'first_name' => 'Marta',
                'last_name' => 'Zielińska',
                'email' => 'marta.zielinska@poczta.test',
                'phone' => '+48 605 900 100',
                'city' => 'Swarzędz',
            ],
        ];

        $customers = [];

        foreach ($definitions as $key => $attributes) {
            /** @var Customer $customer */
            $customer = Customer::query()->create($attributes + [
                'company_id' => $company->id,
                'country_code' => 'PL',
            ]);

            $customers[$key] = $customer;
        }

        return $customers;
    }

    /**
     * @param array<string, Customer> $customers
     * @return array<string, Inquiry>
     */
    private function createInquiries(Company $company, User $owner, User $member, array $customers): array
    {
        $definitions = [
            'oklejanie' => [
                'customer' => 'autoserwis',
                'owner' => $owner,
                'title' => 'Oklejenie 4 busów serwisowych folią',
                'description' => 'Klient prosi o wycenę oklejenia czterech busów Renault Master. Potrzebne logo, dane kontaktowe i pas boczny w kolorach firmowych.',
                'status' => InquiryStatus::NEW,
                'priority' => InquiryPriority::URGENT,
                'source' => 'email',
                'response_due_at' => now()->subDay(),
                'realization_due_at' => now()->addDays(21),
            ],
            'banery' => [
                'customer' => 'piekarnia',
                'owner' => $member,
                'title' => 'Banery reklamowe na dożynki - 6 sztuk',
                'description' => 'Banery 300x100 cm z oczkami, druk jednostronny, montaż po stronie klienta.',
                'status' => InquiryStatus::TRIAGE,
                'priority' => InquiryPriority::HIGH,
                'source' => 'email',
                'response_due_at' => now()->addDay(),
                'realization_due_at' => now()->addDays(10),
            ],
            'kaseton' => [
                'customer' => 'fitness',
                'owner' => $owner,
                'title' => 'Kaseton podświetlany nad wejściem',
                'description' => 'Kaseton 250x80 cm, LED, montaż na elewacji. Klient czeka na wizualizację.',
                'status' => InquiryStatus::PREPARING_OFFER,
                'priority' => InquiryPriority::NORMAL,
                'source' => 'phone',
                'response_due_at' => now()->addDays(2),
                'realization_due_at' => now()->addDays(30),
            ],
            'witryna' => [
                'customer' => 'jankowalski',
                'owner' => $member,
                'title' => 'Naklejki na witrynę lokalu',
                'description' => 'Folia mrożona z wycinanym logo, powierzchnia ok. 6 m2.',
                'status' => InquiryStatus::WAITING_FOR_CUSTOMER,
                'priority' => InquiryPriority::LOW,
                'source' => 'manual',
                'response_due_at' => now()->addDays(4),
            ],
            'tablice' => [
                'customer' => 'piekarnia',
                'owner' => $owner,
                'title' => 'Tablice informacyjne do 3 sklepów',
                'description' => 'Dibond 3 mm z nadrukiem, format 100x70 cm.',
                'status' => InquiryStatus::NEW,
                'priority' => InquiryPriority::URGENT,
                'source' => 'email',
                'response_due_at' => now()->subDays(2),
            ],
            'roll_up' => [
                'customer' => 'martanowak',
                'owner' => $member,
                'title' => 'Roll-up na targi ślubne',
                'description' => 'Roll-up 85x200 cm wraz z torbą transportową.',
                'status' => InquiryStatus::NEW,
                'priority' => InquiryPriority::HIGH,
                'source' => 'manual',
                'response_due_at' => now()->addDays(5),
                'realization_due_at' => now()->addDays(12),
            ],
        ];

        $inquiries = [];

        foreach ($definitions as $key => $definition) {
            /** @var Inquiry $inquiry */
            $inquiry = Inquiry::query()->create([
                'company_id' => $company->id,
                'customer_id' => $customers[$definition['customer']]->id,
                'owner_user_id' => $definition['owner']->id,
                'source' => $definition['source'],
                'title' => $definition['title'],
                'description' => $definition['description'],
                'status' => $definition['status']->value,
                'priority' => $definition['priority']->value,
                'response_due_at' => $definition['response_due_at'],
                'realization_due_at' => $definition['realization_due_at'] ?? null,
            ]);

            $inquiries[$key] = $inquiry;
        }

        return $inquiries;
    }

    /**
     * @param array<string, Inquiry> $inquiries
     */
    private function createMessagesAndNotes(Company $company, User $owner, User $member, array $inquiries): void
    {
        InquiryMessage::query()->create([
            'company_id' => $company->id,
            'inquiry_id' => $inquiries['oklejanie']->id,
            'customer_id' => $inquiries['oklejanie']->customer_id,
            'direction' => InquiryMessageDirection::INBOUND->value,
            'sender_name' => 'Autoserwis Kowalski',
            'sender_email' => 'biuro@autoserwis-kowalski.test',
            'recipient_email' => 'biuro@reklama-wizual.test',
            'subject' => 'Wycena oklejenia busów',
            'body' => "Dzień dobry,\n\nprosimy o wycenę oklejenia czterech busów. Zależy nam na terminie do końca miesiąca.\n\nPozdrawiam,\nMarek Kowalski",
            'sent_at' => now()->subDays(2),
        ]);

        InquiryMessage::query()->create([
            'company_id' => $company->id,
            'inquiry_id' => $inquiries['oklejanie']->id,
            'customer_id' => $inquiries['oklejanie']->customer_id,
            'created_by_user_id' => $owner->id,
            'direction' => InquiryMessageDirection::OUTBOUND->value,
            'sender_name' => $owner->name,
            'sender_email' => 'biuro@reklama-wizual.test',
            'recipient_email' => 'biuro@autoserwis-kowalski.test',
            'subject' => 'Re: Wycena oklejenia busów',
            'body' => "Dzień dobry,\n\ndziękujemy za zapytanie. Potrzebujemy wymiarów bocznych paneli i logo w krzywych.\n\nPozdrawiam",
            'sent_at' => now()->subDay(),
        ]);

        InquiryMessage::query()->create([
            'company_id' => $company->id,
            'inquiry_id' => $inquiries['banery']->id,
            'customer_id' => $inquiries['banery']->customer_id,
            'direction' => InquiryMessageDirection::INBOUND->value,
            'sender_name' => 'Piekarnia Złoty Kłos',
            'sender_email' => 'zamowienia@zlotyklos.test',
            'recipient_email' => 'biuro@reklama-wizual.test',
            'subject' => 'Banery na dożynki',
            'body' => 'Prosimy o wycenę sześciu banerów 300x100 cm z oczkami.',
            'sent_at' => now()->subDays(4),
        ]);

        InquiryNote::query()->create([
            'company_id' => $company->id,
            'inquiry_id' => $inquiries['oklejanie']->id,
            'author_user_id' => $owner->id,
            'body' => 'Klient wraca do nas trzeci raz w tym roku - proponujemy 5% rabatu przy całej flocie.',
            'is_internal' => true,
        ]);

        InquiryNote::query()->create([
            'company_id' => $company->id,
            'inquiry_id' => $inquiries['kaseton']->id,
            'author_user_id' => $member->id,
            'body' => 'Trzeba sprawdzić zgodę wspólnoty na montaż na elewacji przed wysłaniem oferty.',
            'is_internal' => true,
        ]);
    }

    /**
     * @param array<string, Inquiry> $inquiries
     */
    private function createOffers(Company $company, User $owner, array $inquiries): void
    {
        /** @var OfferRepository $offers */
        $offers = app(OfferRepository::class);
        /** @var InquiryRepository $inquiryRepository */
        $inquiryRepository = app(InquiryRepository::class);

        $prepare = static function (Inquiry $inquiry) use ($inquiryRepository, $owner): void {
            $inquiryRepository->changeStatus($inquiry, InquiryStatus::PREPARING_OFFER, $owner);
        };

        $draft = $offers->createForInquiry($company, $inquiries['kaseton'], $owner, [
            'currency' => 'PLN',
            'issue_date' => now()->toDateString(),
            'valid_until' => now()->addDays(21)->toDateString(),
            'payment_due_days' => 14,
            'delivery_cost_cents' => 0,
            'deposit_percent' => '30',
            'terms' => 'Montaż w cenie. Wymagana zaliczka 30% przed produkcją.',
            'items' => [
                [
                    'name' => 'Kaseton podświetlany LED 250x80 cm',
                    'description' => 'Konstrukcja aluminiowa, plexi mleczne, moduły LED.',
                    'quantity' => '1',
                    'unit' => 'szt.',
                    'unit_price_cents' => 480000,
                    'tax_rate' => '23',
                ],
                [
                    'name' => 'Projekt graficzny i wizualizacja',
                    'quantity' => '1',
                    'unit' => 'usł.',
                    'unit_price_cents' => 60000,
                    'tax_rate' => '23',
                ],
            ],
        ]);
        $offers->generatePdf($company, $draft);

        $prepare($inquiries['banery']);
        $sent = $offers->createForInquiry($company, $inquiries['banery'], $owner, [
            'currency' => 'PLN',
            'issue_date' => now()->subDays(3)->toDateString(),
            'valid_until' => now()->addDays(11)->toDateString(),
            'payment_due_days' => 7,
            'delivery_cost_cents' => 4500,
            'discount_type' => 'percent',
            'discount_value' => '5',
            'deposit_percent' => '0',
            'terms' => 'Odbiór osobisty lub kurier. Płatność przelewem 7 dni.',
            'items' => [
                [
                    'name' => 'Baner 300x100 cm z oczkami',
                    'description' => 'Druk jednostronny, materiał 510 g/m2.',
                    'quantity' => '6',
                    'unit' => 'szt.',
                    'unit_price_cents' => 12000,
                    'tax_rate' => '23',
                ],
            ],
        ]);
        $offers->generatePdf($company, $sent);
        $offers->send($company, $sent, $owner);

        $prepare($inquiries['oklejanie']);
        $accepted = $offers->createForInquiry($company, $inquiries['oklejanie'], $owner, [
            'currency' => 'PLN',
            'issue_date' => now()->subDays(6)->toDateString(),
            'valid_until' => now()->addDays(24)->toDateString(),
            'payment_due_days' => 14,
            'delivery_cost_cents' => 0,
            'discount_type' => 'percent',
            'discount_value' => '5',
            'deposit_percent' => '40',
            'terms' => 'Oklejanie w siedzibie klienta. Gwarancja 3 lata na folię.',
            'items' => [
                [
                    'name' => 'Oklejenie busa - zestaw pełny',
                    'description' => 'Folia Oracal 751C, laminat, montaż.',
                    'quantity' => '4',
                    'unit' => 'szt.',
                    'unit_price_cents' => 320000,
                    'tax_rate' => '23',
                ],
                [
                    'name' => 'Przygotowanie plików produkcyjnych',
                    'quantity' => '4',
                    'unit' => 'szt.',
                    'unit_price_cents' => 15000,
                    'tax_rate' => '23',
                ],
            ],
        ]);
        $offers->generatePdf($company, $accepted);
        $offers->send($company, $accepted, $owner);
        $offers->accept($company, $accepted, $owner);

        $prepare($inquiries['witryna']);
        $rejected = $offers->createForInquiry($company, $inquiries['witryna'], $owner, [
            'currency' => 'PLN',
            'issue_date' => now()->subDays(20)->toDateString(),
            'valid_until' => now()->subDays(6)->toDateString(),
            'payment_due_days' => 7,
            'items' => [
                [
                    'name' => 'Folia mrożona z wycinanym logo',
                    'quantity' => '6',
                    'unit' => 'm2',
                    'unit_price_cents' => 9000,
                    'tax_rate' => '23',
                ],
            ],
        ]);
        $offers->send($company, $rejected, $owner);
        $rejected->forceFill([
            'status' => OfferStatus::REJECTED->value,
            'rejected_at' => now()->subDays(4),
        ])->save();
        $inquiries['witryna']->refresh();
        $inquiryRepository->changeStatus($inquiries['witryna'], InquiryStatus::REJECTED, $owner);

        $prepare($inquiries['roll_up']);
        $expired = $offers->createForInquiry($company, $inquiries['roll_up'], $owner, [
            'currency' => 'PLN',
            'issue_date' => now()->subDays(30)->toDateString(),
            'valid_until' => now()->subDays(9)->toDateString(),
            'payment_due_days' => 7,
            'items' => [
                [
                    'name' => 'Roll-up 85x200 cm z torbą',
                    'quantity' => '1',
                    'unit' => 'szt.',
                    'unit_price_cents' => 24000,
                    'tax_rate' => '23',
                ],
            ],
        ]);
        $offers->send($company, $expired, $owner);
    }

    private function report(Company $company): void
    {
        $command = $this->command;

        $command->newLine();
        $command->info('Demo data ready.');
        $command->table(
            ['Field', 'Value'],
            [
                ['Company', $company->name],
                ['Owner login', 'demo@zlecero.test'],
                ['Member login', 'pracownik@zlecero.test'],
                ['Password', self::PASSWORD],
                ['Customers', (string) Customer::query()->where('company_id', $company->id)->count()],
                ['Inquiries', (string) Inquiry::query()->where('company_id', $company->id)->count()],
                ['Offers', (string) Offer::query()->where('company_id', $company->id)->count()],
            ],
        );
    }
}
