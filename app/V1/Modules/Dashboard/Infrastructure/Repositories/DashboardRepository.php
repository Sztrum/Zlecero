<?php

declare(strict_types=1);

namespace App\V1\Modules\Dashboard\Infrastructure\Repositories;

use App\V1\Core\Infrastructure\Repositories\Eloquent\EloquentModelRepository;
use App\V1\Modules\Company\Domain\Enums\CompanyUserRole;
use App\V1\Modules\Company\Domain\Models\Company;
use App\V1\Modules\Inquiry\Domain\Enums\InquiryStatus;
use App\V1\Modules\Inquiry\Domain\Models\Inquiry;
use App\V1\Modules\Inquiry\Domain\Models\InquiryStatusChange;
use App\V1\Modules\Offer\Domain\Enums\OfferStatus;
use App\V1\Modules\Offer\Domain\Models\Offer;
use App\V1\Modules\Order\Domain\Enums\OrderStatus;
use App\V1\Modules\Order\Domain\Models\Order;
use App\V1\Modules\User\Domain\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class DashboardRepository extends EloquentModelRepository
{
    public function model(): Inquiry
    {
        return new Inquiry();
    }

    public function moduleName(): string
    {
        return 'dashboard';
    }

    /**
     * @return array<string, mixed>
     */
    public function getCompanyDashboard(Company $company, User $user, ?string $ownerFilter): array
    {
        $ownerUserId = $ownerFilter === 'me' ? $user->id : null;
        $now = Carbon::now();
        $todayEnd = $now->copy()->endOfDay();
        $weekEnd = $now->copy()->addDays(7)->endOfDay();

        $newInquiriesCount = $this->inquiryBaseQuery($company, $ownerUserId)
            ->where('status', InquiryStatus::NEW->value)
            ->count();
        $waitingInquiriesCount = $this->inquiryBaseQuery($company, $ownerUserId)
            ->where('status', InquiryStatus::WAITING_FOR_CUSTOMER->value)
            ->count();
        $offerActionCount = $this->offerBaseQuery($company, $ownerUserId)
            ->where('status', OfferStatus::SENT->value)
            ->count();
        $overdueOrdersCount = $this->orderBaseQuery($company, $ownerUserId)
            ->whereNotIn('status', [OrderStatus::COMPLETED->value])
            ->whereNotNull('realization_due_date')
            ->where('realization_due_date', '<', $now->toDateString())
            ->count();

        $attentionItems = $this->getAttentionItems($company, $ownerUserId, $now);
        $todaysTasks = $this->getTodaysTasks($company, $ownerUserId, $todayEnd);
        $upcomingDeadlines = $this->getUpcomingDeadlines($company, $ownerUserId, $now, $weekEnd);

        return [
            'filter' => ['owner' => $ownerFilter ?? 'all'],
            'cards' => [
                [
                    'id' => 'new-inquiries',
                    'label' => 'Nowe zapytania',
                    'value' => $newInquiriesCount,
                    'tone' => 'info',
                    'href' => '/app/inquiries?queue=new',
                ],
                [
                    'id' => 'waiting-inquiries',
                    'label' => 'Sprawy oczekujące',
                    'value' => $waitingInquiriesCount,
                    'tone' => 'warning',
                    'href' => '/app/inquiries?queue=waiting',
                ],
                [
                    'id' => 'offer-actions',
                    'label' => 'Oferty do reakcji',
                    'value' => $offerActionCount,
                    'tone' => 'primary',
                    'href' => '/app/offers',
                ],
                [
                    'id' => 'overdue-orders',
                    'label' => 'Opóźnione zlecenia',
                    'value' => $overdueOrdersCount,
                    'tone' => 'danger',
                    'href' => '/app/orders',
                ],
            ],
            'tasksToday' => $todaysTasks,
            'attentionItems' => $attentionItems,
            'upcomingDeadlines' => $upcomingDeadlines,
            'stats' => [
                'activeInquiries' => $this->inquiryBaseQuery($company, $ownerUserId)
                    ->whereNotIn('status', [InquiryStatus::CLOSED->value, InquiryStatus::REJECTED->value])
                    ->count(),
                'sentOffersGrossCents' => (int) $this->offerBaseQuery($company, $ownerUserId)
                    ->where('status', OfferStatus::SENT->value)
                    ->sum('total_gross_cents'),
                'acceptedOffersGrossCents' => (int) $this->offerBaseQuery($company, $ownerUserId)
                    ->where('status', OfferStatus::ACCEPTED->value)
                    ->sum('total_gross_cents'),
                'activeOrders' => $this->orderBaseQuery($company, $ownerUserId)
                    ->whereIn('status', [OrderStatus::NEW->value, OrderStatus::IN_PROGRESS->value])
                    ->count(),
            ],
            'recentActivity' => $this->getRecentActivity($company),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getAdminDashboard(): array
    {
        $now = Carbon::now();

        return [
            'cards' => [
                [
                    'id' => 'active-companies',
                    'label' => 'Aktywne firmy',
                    'value' => Company::query()->count(),
                    'tone' => 'primary',
                ],
                [
                    'id' => 'trial-companies',
                    'label' => 'Konta w okresie próbnym',
                    'value' => Company::query()
                        ->whereNotNull('trial_ends_at')
                        ->where('trial_ends_at', '>=', $now)
                        ->count(),
                    'tone' => 'info',
                ],
                [
                    'id' => 'limited-companies',
                    'label' => 'Konta z ograniczeniami',
                    'value' => Company::query()
                        ->whereNotNull('trial_ends_at')
                        ->where('trial_ends_at', '<', $now)
                        ->count(),
                    'tone' => 'danger',
                ],
                [
                    'id' => 'admin-actions',
                    'label' => 'Wymagane działania',
                    'value' => Company::query()
                        ->whereNull('onboarding_completed_at')
                        ->count(),
                    'tone' => 'warning',
                ],
            ],
            'recentCompanies' => Company::query()
                ->latest()
                ->limit(6)
                ->get()
                ->map(static fn (Company $company): array => [
                    'id' => $company->id,
                    'name' => $company->name,
                    'slug' => $company->slug,
                    'trialEndsAt' => $company->trial_ends_at?->toIso8601String(),
                    'onboardingCompletedAt' => $company->onboarding_completed_at?->toIso8601String(),
                    'createdAt' => $company->created_at?->toIso8601String(),
                ])
                ->all(),
            'alerts' => Company::query()
                ->whereNull('onboarding_completed_at')
                ->latest()
                ->limit(5)
                ->get()
                ->map(static fn (Company $company): array => [
                    'id' => 'company-onboarding-'.$company->id,
                    'severity' => 'warning',
                    'label' => 'Firma nie zakończyła onboardingu',
                    'companyName' => $company->name,
                    'createdAt' => $company->created_at?->toIso8601String(),
                ])
                ->all(),
        ];
    }

    public function canViewAdminDashboard(User $user): bool
    {
        return $user->hasAnyCompanyRole([CompanyUserRole::OWNER, CompanyUserRole::ADMIN]);
    }

    /**
     * @return Builder<Inquiry>
     */
    private function inquiryBaseQuery(Company $company, ?string $ownerUserId): Builder
    {
        return Inquiry::query()
            ->where('company_id', $company->id)
            ->whereNull('archived_at')
            ->when($ownerUserId !== null, static fn (Builder $builder) => $builder->where('owner_user_id', $ownerUserId));
    }

    /**
     * @return Builder<Offer>
     */
    private function offerBaseQuery(Company $company, ?string $ownerUserId): Builder
    {
        return Offer::query()
            ->where('company_id', $company->id)
            ->when($ownerUserId !== null, static fn (Builder $builder) => $builder->where('owner_user_id', $ownerUserId));
    }

    /**
     * @return Builder<Order>
     */
    private function orderBaseQuery(Company $company, ?string $ownerUserId): Builder
    {
        return Order::query()
            ->where('company_id', $company->id)
            ->when($ownerUserId !== null, static fn (Builder $builder) => $builder->where('owner_user_id', $ownerUserId));
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function getAttentionItems(Company $company, ?string $ownerUserId, Carbon $now): array
    {
        $overdueInquiries = $this->inquiryBaseQuery($company, $ownerUserId)
            ->with(['customer', 'owner'])
            ->whereNotNull('response_due_at')
            ->where('response_due_at', '<', $now)
            ->orderBy('response_due_at')
            ->limit(4)
            ->get()
            ->map(fn (Inquiry $inquiry): array => $this->inquiryItem($inquiry, 'danger', 'Termin odpowiedzi minął'));

        $sentOffers = $this->offerBaseQuery($company, $ownerUserId)
            ->with(['customer', 'owner'])
            ->where('status', OfferStatus::SENT->value)
            ->orderBy('valid_until')
            ->limit(4)
            ->get()
            ->map(fn (Offer $offer): array => $this->offerItem($offer, 'warning', 'Oferta czeka na decyzję'));

        return array_values($overdueInquiries
            ->toBase()
            ->merge($sentOffers->toBase())
            ->sortBy('dueAt')
            ->take(6)
            ->values()
            ->all());
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function getTodaysTasks(Company $company, ?string $ownerUserId, Carbon $todayEnd): array
    {
        return array_values($this->inquiryBaseQuery($company, $ownerUserId)
            ->with(['customer', 'owner'])
            ->whereNotNull('response_due_at')
            ->where('response_due_at', '<=', $todayEnd)
            ->orderBy('response_due_at')
            ->limit(6)
            ->get()
            ->map(fn (Inquiry $inquiry): array => $this->inquiryItem($inquiry, 'info', 'Odpowiedz klientowi'))
            ->all());
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function getUpcomingDeadlines(Company $company, ?string $ownerUserId, Carbon $now, Carbon $weekEnd): array
    {
        return array_values($this->orderBaseQuery($company, $ownerUserId)
            ->with(['customer', 'owner'])
            ->whereNotNull('realization_due_date')
            ->whereBetween('realization_due_date', [$now->toDateString(), $weekEnd->toDateString()])
            ->orderBy('realization_due_date')
            ->limit(6)
            ->get()
            ->map(fn (Order $order): array => $this->orderItem($order, 'primary', 'Termin realizacji'))
            ->all());
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function getRecentActivity(Company $company): array
    {
        return array_values(InquiryStatusChange::query()
            ->where('company_id', $company->id)
            ->with(['inquiry.customer'])
            ->latest('changed_at')
            ->limit(6)
            ->get()
            ->map(static fn (InquiryStatusChange $change): array => [
                'id' => $change->id,
                'type' => 'inquiry_status',
                'label' => 'Zmieniono status zapytania',
                'description' => $change->inquiry?->title,
                'status' => $change->to_status,
                'occurredAt' => $change->changed_at->toIso8601String(),
                'href' => $change->inquiry ? '/app/inquiries?inquiry='.$change->inquiry->id : '/app/inquiries',
            ])
            ->all());
    }

    /**
     * @return array<string, mixed>
     */
    private function inquiryItem(Inquiry $inquiry, string $tone, string $label): array
    {
        return [
            'id' => 'inquiry-'.$inquiry->id,
            'type' => 'inquiry',
            'label' => $label,
            'title' => $inquiry->title,
            'customerName' => $inquiry->customer?->display_name,
            'ownerName' => $inquiry->owner?->name,
            'status' => $inquiry->status,
            'tone' => $tone,
            'dueAt' => $inquiry->response_due_at?->toIso8601String(),
            'href' => '/app/inquiries?inquiry='.$inquiry->id,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function offerItem(Offer $offer, string $tone, string $label): array
    {
        return [
            'id' => 'offer-'.$offer->id,
            'type' => 'offer',
            'label' => $label,
            'title' => $offer->number,
            'customerName' => $offer->customer?->display_name,
            'ownerName' => $offer->owner?->name,
            'status' => $offer->status,
            'tone' => $tone,
            'dueAt' => $offer->valid_until->toDateString(),
            'href' => '/app/offers/'.$offer->id,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function orderItem(Order $order, string $tone, string $label): array
    {
        return [
            'id' => 'order-'.$order->id,
            'type' => 'order',
            'label' => $label,
            'title' => $order->number,
            'customerName' => $order->customer?->display_name,
            'ownerName' => $order->owner?->name,
            'status' => $order->status,
            'tone' => $tone,
            'dueAt' => $order->realization_due_date?->toDateString(),
            'href' => '/app/orders/'.$order->id,
        ];
    }
}
