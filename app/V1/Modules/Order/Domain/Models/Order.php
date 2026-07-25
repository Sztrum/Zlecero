<?php

declare(strict_types=1);

namespace App\V1\Modules\Order\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use App\V1\Modules\Customer\Domain\Models\Customer;
use App\V1\Modules\Inquiry\Domain\Models\Inquiry;
use App\V1\Modules\Offer\Domain\Models\Offer;
use App\V1\Modules\User\Domain\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $company_id
 * @property string|null $inquiry_id
 * @property string $offer_id
 * @property string|null $customer_id
 * @property string|null $owner_user_id
 * @property string $number
 * @property string $status
 * @property string $currency
 * @property \Illuminate\Support\Carbon $accepted_date
 * @property \Illuminate\Support\Carbon|null $payment_due_date
 * @property \Illuminate\Support\Carbon|null $realization_due_date
 * @property \Illuminate\Support\Carbon|null $pickup_due_date
 * @property string|null $terms
 * @property string|null $notes
 * @property int $subtotal_net_cents
 * @property int $discount_cents
 * @property int $tax_cents
 * @property int $total_gross_cents
 * @property int $deposit_cents
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property Offer $offer
 * @property Inquiry|null $inquiry
 * @property Customer|null $customer
 * @property User|null $owner
 * @property \Illuminate\Database\Eloquent\Collection<int, OrderItem> $items
 */
class Order extends Model
{
    protected $fillable = [
        'company_id',
        'inquiry_id',
        'offer_id',
        'customer_id',
        'owner_user_id',
        'number',
        'status',
        'currency',
        'accepted_date',
        'payment_due_date',
        'realization_due_date',
        'pickup_due_date',
        'terms',
        'notes',
        'subtotal_net_cents',
        'discount_cents',
        'tax_cents',
        'total_gross_cents',
        'deposit_cents',
    ];

    protected $casts = [
        'accepted_date' => 'date',
        'payment_due_date' => 'date',
        'realization_due_date' => 'date',
        'pickup_due_date' => 'date',
        'subtotal_net_cents' => 'integer',
        'discount_cents' => 'integer',
        'tax_cents' => 'integer',
        'total_gross_cents' => 'integer',
        'deposit_cents' => 'integer',
    ];

    /**
     * @return BelongsTo<Offer, $this>
     */
    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    /**
     * @return BelongsTo<Inquiry, $this>
     */
    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(Inquiry::class);
    }

    /**
     * @return BelongsTo<Customer, $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class)->orderBy('position');
    }
}
