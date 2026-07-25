<?php

declare(strict_types=1);

namespace App\V1\Modules\Offer\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $company_id
 * @property string $offer_id
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
class OfferItem extends Model
{
    protected $fillable = [
        'company_id',
        'offer_id',
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
     * @return BelongsTo<Offer, $this>
     */
    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }
}
