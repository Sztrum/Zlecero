<?php

declare(strict_types=1);

namespace App\V1\Modules\Offer\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use App\V1\Modules\Customer\Domain\Models\Customer;
use App\V1\Modules\Inquiry\Domain\Models\Inquiry;
use App\V1\Modules\Order\Domain\Models\Order;
use App\V1\Modules\User\Domain\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string $id
 * @property string $company_id
 * @property string $inquiry_id
 * @property string|null $customer_id
 * @property string|null $owner_user_id
 * @property string $number
 * @property string $status
 * @property string $currency
 * @property \Illuminate\Support\Carbon $issue_date
 * @property \Illuminate\Support\Carbon $valid_until
 * @property int $payment_due_days
 * @property int $delivery_cost_cents
 * @property string|null $discount_type
 * @property string $discount_value
 * @property string $deposit_percent
 * @property string|null $terms
 * @property string|null $notes
 * @property int $subtotal_net_cents
 * @property int $discount_cents
 * @property int $tax_cents
 * @property int $total_gross_cents
 * @property int $deposit_cents
 * @property string|null $pdf_disk
 * @property string|null $pdf_path
 * @property string|null $pdf_original_name
 * @property \Illuminate\Support\Carbon|null $pdf_generated_at
 * @property \Illuminate\Support\Carbon|null $sent_at
 * @property \Illuminate\Support\Carbon|null $accepted_at
 * @property \Illuminate\Support\Carbon|null $rejected_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property Inquiry $inquiry
 * @property Customer|null $customer
 * @property User|null $owner
 * @property \Illuminate\Database\Eloquent\Collection<int, OfferItem> $items
 * @property Order|null $order
 */
class Offer extends Model
{
    protected $fillable = [
        'company_id',
        'inquiry_id',
        'customer_id',
        'owner_user_id',
        'number',
        'status',
        'currency',
        'issue_date',
        'valid_until',
        'payment_due_days',
        'delivery_cost_cents',
        'discount_type',
        'discount_value',
        'deposit_percent',
        'terms',
        'notes',
        'subtotal_net_cents',
        'discount_cents',
        'tax_cents',
        'total_gross_cents',
        'deposit_cents',
        'pdf_disk',
        'pdf_path',
        'pdf_original_name',
        'pdf_generated_at',
        'sent_at',
        'accepted_at',
        'rejected_at',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'valid_until' => 'date',
        'payment_due_days' => 'integer',
        'delivery_cost_cents' => 'integer',
        'subtotal_net_cents' => 'integer',
        'discount_cents' => 'integer',
        'tax_cents' => 'integer',
        'total_gross_cents' => 'integer',
        'deposit_cents' => 'integer',
        'pdf_generated_at' => 'datetime',
        'sent_at' => 'datetime',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

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
     * @return HasMany<OfferItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OfferItem::class)->orderBy('position');
    }

    /**
     * @return HasOne<Order, $this>
     */
    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }
}
