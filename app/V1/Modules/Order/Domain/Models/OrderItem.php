<?php

declare(strict_types=1);

namespace App\V1\Modules\Order\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use App\V1\Modules\Offer\Domain\Models\OfferItem;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $company_id
 * @property string $order_id
 * @property string|null $offer_item_id
 * @property int $position
 * @property string $name
 * @property string|null $description
 * @property string $quantity
 * @property string $unit
 * @property int $unit_price_cents
 * @property string $tax_rate
 * @property int $net_cents
 * @property int $tax_cents
 * @property int $gross_cents
 */
class OrderItem extends Model
{
    protected $fillable = [
        'company_id',
        'order_id',
        'offer_item_id',
        'position',
        'name',
        'description',
        'quantity',
        'unit',
        'unit_price_cents',
        'tax_rate',
        'net_cents',
        'tax_cents',
        'gross_cents',
    ];

    protected $casts = [
        'position' => 'integer',
        'unit_price_cents' => 'integer',
        'net_cents' => 'integer',
        'tax_cents' => 'integer',
        'gross_cents' => 'integer',
    ];

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return BelongsTo<OfferItem, $this>
     */
    public function offerItem(): BelongsTo
    {
        return $this->belongsTo(OfferItem::class);
    }
}
