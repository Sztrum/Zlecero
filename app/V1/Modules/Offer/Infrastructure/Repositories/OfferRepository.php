<?php

declare(strict_types=1);

namespace App\V1\Modules\Offer\Infrastructure\Repositories;

use App\V1\Core\Infrastructure\Repositories\Eloquent\EloquentModelRepository;
use App\V1\Modules\Company\Domain\Models\Company;
use App\V1\Modules\Inquiry\Domain\Enums\InquiryStatus;
use App\V1\Modules\Inquiry\Domain\Exceptions\InvalidInquiryStatusTransitionException;
use App\V1\Modules\Inquiry\Domain\Models\Inquiry;
use App\V1\Modules\Inquiry\Infrastructure\Repositories\InquiryRepository;
use App\V1\Modules\Offer\Domain\Enums\OfferDiscountType;
use App\V1\Modules\Offer\Domain\Enums\OfferStatus;
use App\V1\Modules\Offer\Domain\Exceptions\InvalidOfferStateException;
use App\V1\Modules\Offer\Domain\Exceptions\OfferNotFoundException;
use App\V1\Modules\Offer\Domain\Models\Offer;
use App\V1\Modules\Offer\Domain\Models\OfferItem;
use App\V1\Modules\Order\Domain\Enums\OrderStatus;
use App\V1\Modules\Order\Domain\Models\Order;
use App\V1\Modules\Order\Domain\Models\OrderItem;
use App\V1\Modules\User\Domain\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class OfferRepository extends EloquentModelRepository
{
    public function __construct(
        private readonly InquiryRepository $inquiryRepository,
    ) {
    }

    public function model(): Offer
    {
        return new Offer();
    }

    public function moduleName(): string
    {
        return 'offer';
    }

    /**
     * @return Builder<Offer>
     */
    private function offerQuery(): Builder
    {
        return Offer::query();
    }

    /**
     * @return Collection<int, Offer>
     */
    public function getByCompany(Company $company): Collection
    {
        return $this->offerQuery()
            ->where('company_id', $company->id)
            ->with(['inquiry', 'customer', 'owner', 'items', 'order'])
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * @throws OfferNotFoundException|Throwable
     */
    public function findCompanyOffer(Company $company, string $offerId): Offer
    {
        $offer = $this->offerQuery()
            ->where('company_id', $company->id)
            ->where('id', $offerId)
            ->with(['inquiry', 'customer', 'owner', 'items', 'order.items'])
            ->first();

        throw_if(! $offer instanceof Offer, OfferNotFoundException::class);

        return $offer;
    }

    /**
     * @param array<string, mixed> $params
     * @throws Throwable
     */
    public function createForInquiry(Company $company, Inquiry $inquiry, User $user, array $params): Offer
    {
        return DB::transaction(function () use ($company, $inquiry, $user, $params): Offer {
            $totals = $this->calculateTotals($params);

            /** @var Offer $offer */
            $offer = Offer::query()->create($this->offerPayload($company, $inquiry, $user, $params, $totals));

            $this->replaceItems($offer, $this->itemsFromParams($params));

            return $this->findCompanyOffer($company, $offer->id);
        });
    }

    /**
     * @param array<string, mixed> $params
     * @throws InvalidOfferStateException|Throwable
     */
    public function updateOffer(Company $company, Offer $offer, array $params): Offer
    {
        throw_if($offer->status !== OfferStatus::DRAFT->value, InvalidOfferStateException::class);

        return DB::transaction(function () use ($company, $offer, $params): Offer {
            $totals = $this->calculateTotals($params);

            $offer->fill($this->editablePayload($params, $totals))->save();
            $offer->items()->delete();
            $this->replaceItems($offer, $this->itemsFromParams($params));

            return $this->findCompanyOffer($company, $offer->id);
        });
    }

    /**
     * @throws InvalidOfferStateException|Throwable
     */
    public function send(Company $company, Offer $offer, User $user): Offer
    {
        throw_if($offer->status !== OfferStatus::DRAFT->value, InvalidOfferStateException::class);

        return DB::transaction(function () use ($company, $offer, $user): Offer {
            $offer->fill([
                'status' => OfferStatus::SENT->value,
                'sent_at' => now(),
            ])->save();

            $this->advanceInquiryTo($offer->inquiry, InquiryStatus::OFFER_SENT, $user);

            return $this->findCompanyOffer($company, $offer->id);
        });
    }

    public function generatePdf(Company $company, Offer $offer): Offer
    {
        $path = sprintf('companies/%s/offers/%s/%s.pdf', $company->id, $offer->id, $offer->number);

        Storage::disk('local')->put($path, $this->pdfContent($company, $offer));

        $offer->fill([
            'pdf_disk' => 'local',
            'pdf_path' => $path,
            'pdf_original_name' => sprintf('%s.pdf', str_replace(['/', '\\'], '-', $offer->number)),
            'pdf_generated_at' => now(),
        ])->save();

        return $this->findCompanyOffer($company, $offer->id);
    }

    /**
     * @throws InvalidOfferStateException|Throwable
     */
    public function accept(Company $company, Offer $offer, User $user): Offer
    {
        throw_if($offer->status === OfferStatus::REJECTED->value, InvalidOfferStateException::class);
        throw_if($offer->status === OfferStatus::DRAFT->value, InvalidOfferStateException::class);
        throw_if($offer->valid_until->isBefore(Carbon::today()), InvalidOfferStateException::class);

        if ($offer->order instanceof Order) {
            return $this->findCompanyOffer($company, $offer->id);
        }

        return DB::transaction(function () use ($company, $offer, $user): Offer {
            $offer->fill([
                'status' => OfferStatus::ACCEPTED->value,
                'accepted_at' => now(),
            ])->save();

            /** @var Order $order */
            $order = Order::query()->create([
                'company_id' => $offer->company_id,
                'inquiry_id' => $offer->inquiry_id,
                'offer_id' => $offer->id,
                'customer_id' => $offer->customer_id,
                'owner_user_id' => $offer->owner_user_id,
                'number' => $this->nextOrderNumber($company),
                'status' => OrderStatus::NEW->value,
                'currency' => $offer->currency,
                'accepted_date' => today(),
                'payment_due_date' => today()->addDays($offer->payment_due_days),
                'realization_due_date' => $offer->inquiry->realization_due_at?->toDateString(),
                'pickup_due_date' => $offer->inquiry->pickup_due_at?->toDateString(),
                'terms' => $offer->terms,
                'notes' => $offer->notes,
                'subtotal_net_cents' => $offer->subtotal_net_cents,
                'discount_cents' => $offer->discount_cents,
                'tax_cents' => $offer->tax_cents,
                'total_gross_cents' => $offer->total_gross_cents,
                'deposit_cents' => $offer->deposit_cents,
            ]);

            foreach ($offer->items as $item) {
                OrderItem::query()->create([
                    'company_id' => $offer->company_id,
                    'order_id' => $order->id,
                    'offer_item_id' => $item->id,
                    'position' => $item->position,
                    'name' => $item->name,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'unit' => $item->unit,
                    'unit_price_cents' => $item->unit_price_cents,
                    'tax_rate' => $item->tax_rate,
                    'net_cents' => $item->net_cents,
                    'tax_cents' => $item->tax_cents,
                    'gross_cents' => $item->gross_cents,
                ]);
            }

            $this->advanceInquiryTo($offer->inquiry, InquiryStatus::ACCEPTED, $user);

            return $this->findCompanyOffer($company, $offer->id);
        });
    }

    /**
     * @param array<string, mixed> $params
     * @param array{subtotal_net_cents: int, discount_cents: int, tax_cents: int, total_gross_cents: int, deposit_cents: int} $totals
     * @return array<string, mixed>
     */
    private function offerPayload(Company $company, Inquiry $inquiry, User $user, array $params, array $totals): array
    {
        return [
            'company_id' => $company->id,
            'inquiry_id' => $inquiry->id,
            'customer_id' => $inquiry->customer_id,
            'owner_user_id' => $inquiry->owner_user_id ?? $user->id,
            'number' => $params['number'] ?? $this->nextOfferNumber($company),
            'status' => OfferStatus::DRAFT->value,
        ] + $this->editablePayload($params, $totals);
    }

    /**
     * @param array<string, mixed> $params
     * @param array{subtotal_net_cents: int, discount_cents: int, tax_cents: int, total_gross_cents: int, deposit_cents: int} $totals
     * @return array<string, mixed>
     */
    private function editablePayload(array $params, array $totals): array
    {
        return [
            'currency' => $params['currency'] ?? 'PLN',
            'issue_date' => $params['issue_date'],
            'valid_until' => $params['valid_until'],
            'payment_due_days' => $params['payment_due_days'],
            'delivery_cost_cents' => $params['delivery_cost_cents'] ?? 0,
            'discount_type' => $params['discount_type'] ?? null,
            'discount_value' => $params['discount_value'] ?? 0,
            'deposit_percent' => $params['deposit_percent'] ?? 0,
            'terms' => $params['terms'] ?? null,
            'notes' => $params['notes'] ?? null,
        ] + $totals;
    }

    /**
     * @param list<array<string, mixed>> $items
     */
    private function replaceItems(Offer $offer, array $items): void
    {
        foreach ($items as $index => $item) {
            $calculatedItem = $this->calculateItem($item);

            OfferItem::query()->create([
                'company_id' => $offer->company_id,
                'offer_id' => $offer->id,
                'position' => $index + 1,
                'name' => $item['name'],
                'description' => $item['description'] ?? null,
                'quantity' => $item['quantity'],
                'unit' => $item['unit'],
                'unit_price_cents' => $item['unit_price_cents'],
                'tax_rate' => $item['tax_rate'],
            ] + $calculatedItem);
        }
    }

    /**
     * @param array<string, mixed> $params
     * @return array{subtotal_net_cents: int, discount_cents: int, tax_cents: int, total_gross_cents: int, deposit_cents: int}
     */
    private function calculateTotals(array $params): array
    {
        $subtotalNet = 0;
        $tax = 0;

        foreach ($this->itemsFromParams($params) as $item) {
            $calculatedItem = $this->calculateItem($item);
            $subtotalNet += $calculatedItem['net_cents'];
            $tax += $calculatedItem['tax_cents'];
        }

        $grossBeforeDiscount = $subtotalNet + $tax + $this->intParam($params, 'delivery_cost_cents', 0);
        $discount = $this->calculateDiscount($grossBeforeDiscount, $params);
        $totalGross = max(0, $grossBeforeDiscount - $discount);

        return [
            'subtotal_net_cents' => $subtotalNet,
            'discount_cents' => $discount,
            'tax_cents' => $tax,
            'total_gross_cents' => $totalGross,
            'deposit_cents' => $this->percentOf($totalGross, $this->stringParam($params, 'deposit_percent', '0')),
        ];
    }

    /**
     * @param array<string, mixed> $item
     * @return array{net_cents: int, tax_cents: int, gross_cents: int}
     */
    private function calculateItem(array $item): array
    {
        $net = intdiv($this->decimalToUnits($this->stringParam($item, 'quantity'), 3) * $this->intParam($item, 'unit_price_cents') + 500, 1000);
        $tax = $this->percentOf($net, $this->stringParam($item, 'tax_rate'));

        return [
            'net_cents' => $net,
            'tax_cents' => $tax,
            'gross_cents' => $net + $tax,
        ];
    }

    /**
     * @param array<string, mixed> $params
     */
    private function calculateDiscount(int $grossBeforeDiscount, array $params): int
    {
        $discountType = $params['discount_type'] ?? null;

        if ($discountType === OfferDiscountType::PERCENT->value) {
            return min($grossBeforeDiscount, $this->percentOf($grossBeforeDiscount, $this->stringParam($params, 'discount_value', '0')));
        }

        if ($discountType === OfferDiscountType::AMOUNT->value) {
            return min($grossBeforeDiscount, (int) round($this->floatParam($params, 'discount_value', 0) * 100));
        }

        return 0;
    }

    private function percentOf(int $baseCents, string $percent): int
    {
        return intdiv($baseCents * $this->decimalToUnits($percent, 2) + 5000, 10000);
    }

    private function decimalToUnits(string $value, int $precision): int
    {
        $normalized = str_replace(',', '.', $value);
        $parts = explode('.', $normalized, 2);
        $whole = (int) $parts[0];
        $fraction = str_pad(substr($parts[1] ?? '', 0, $precision), $precision, '0');

        return $whole * (10 ** $precision) + (int) $fraction;
    }

    private function nextOfferNumber(Company $company): string
    {
        return sprintf('OFF/%s/%04d', now()->format('Y'), Offer::query()->where('company_id', $company->id)->count() + 1);
    }

    private function nextOrderNumber(Company $company): string
    {
        return sprintf('ORD/%s/%04d', now()->format('Y'), Order::query()->where('company_id', $company->id)->count() + 1);
    }

    private function pdfContent(Company $company, Offer $offer): string
    {
        $lines = [
            'Zlecero offer',
            'Company: ' . $company->name,
            'Offer: ' . $offer->number,
            'Customer: ' . ($offer->customer === null ? 'No customer' : $offer->customer->display_name),
            'Issue date: ' . $offer->issue_date->toDateString(),
            'Valid until: ' . $offer->valid_until->toDateString(),
            'Total gross: ' . number_format($offer->total_gross_cents / 100, 2, '.', ' ') . ' ' . $offer->currency,
            'Terms: ' . ($offer->terms ?? '-'),
        ];

        foreach ($offer->items as $item) {
            $lines[] = sprintf('%d. %s x %s %s = %.2f %s', $item->position, $item->quantity, $item->name, $item->unit, $item->gross_cents / 100, $offer->currency);
        }

        return $this->minimalPdf(implode("\n", $lines));
    }

    private function minimalPdf(string $text): string
    {
        $escapedLines = array_map(
            static fn (string $line): string => '(' . str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $line) . ') Tj',
            explode("\n", $text),
        );
        $content = "BT /F1 12 Tf 50 780 Td 14 TL " . implode(' T* ', $escapedLines) . ' ET';
        $objects = [
            '1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj',
            '2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj',
            '3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >> endobj',
            '4 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj',
            '5 0 obj << /Length ' . strlen($content) . " >> stream\n" . $content . "\nendstream endobj",
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [0];

        foreach ($objects as $object) {
            $offsets[] = strlen($pdf);
            $pdf .= $object . "\n";
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n0000000000 65535 f \n";

        foreach (array_slice($offsets, 1) as $offset) {
            $pdf .= sprintf("%010d 00000 n \n", $offset);
        }

        return $pdf . "trailer << /Size " . (count($objects) + 1) . " /Root 1 0 R >>\nstartxref\n{$xrefOffset}\n%%EOF\n";
    }

    /**
     * Move the inquiry to $status, walking through every intermediate workflow
     * stage so the change is always recorded instead of being silently skipped.
     *
     * @throws InvalidInquiryStatusTransitionException|Throwable
     */
    private function advanceInquiryTo(Inquiry $inquiry, InquiryStatus $status, User $user): void
    {
        $path = InquiryStatus::from($inquiry->status)->transitionPathTo($status);

        throw_if($path === null, InvalidInquiryStatusTransitionException::class);

        foreach ($path as $nextStatus) {
            $this->inquiryRepository->changeStatus($inquiry, $nextStatus, $user);
        }
    }

    /**
     * @param array<string, mixed> $params
     * @return list<array<string, mixed>>
     * @throws InvalidOfferStateException
     */
    private function itemsFromParams(array $params): array
    {
        $items = $params['items'] ?? null;

        if (! is_array($items)) {
            throw new InvalidOfferStateException();
        }

        $validatedItems = [];

        foreach ($items as $item) {
            if (! is_array($item)) {
                throw new InvalidOfferStateException();
            }

            $validatedItem = [];

            foreach ($item as $key => $value) {
                if (! is_string($key)) {
                    throw new InvalidOfferStateException();
                }

                $validatedItem[$key] = $value;
            }

            $validatedItems[] = $validatedItem;
        }

        return $validatedItems;
    }

    /**
     * @param array<string, mixed> $params
     */
    private function stringParam(array $params, string $key, string $default = ''): string
    {
        $value = $params[$key] ?? $default;

        if (is_string($value) || is_int($value) || is_float($value)) {
            return (string) $value;
        }

        throw new InvalidOfferStateException();
    }

    /**
     * @param array<string, mixed> $params
     */
    private function intParam(array $params, string $key, int $default = 0): int
    {
        $value = $params[$key] ?? $default;

        if (is_int($value)) {
            return $value;
        }

        if (is_string($value) && is_numeric($value)) {
            return (int) $value;
        }

        throw new InvalidOfferStateException();
    }

    /**
     * @param array<string, mixed> $params
     */
    private function floatParam(array $params, string $key, float $default = 0): float
    {
        $value = $params[$key] ?? $default;

        if (is_float($value) || is_int($value)) {
            return (float) $value;
        }

        if (is_string($value) && is_numeric($value)) {
            return (float) $value;
        }

        throw new InvalidOfferStateException();
    }
}
