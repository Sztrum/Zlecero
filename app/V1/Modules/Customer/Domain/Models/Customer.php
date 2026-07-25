<?php

declare(strict_types=1);

namespace App\V1\Modules\Customer\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use App\V1\Modules\Company\Domain\Models\Company;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $company_id
 * @property string $type
 * @property string $display_name
 * @property string|null $company_name
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $tax_number
 * @property string|null $address_line
 * @property string|null $postal_code
 * @property string|null $city
 * @property string $country_code
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property Company|null $company
 */
class Customer extends Model
{
    protected $fillable = [
        'id',
        'company_id',
        'type',
        'display_name',
        'company_name',
        'first_name',
        'last_name',
        'email',
        'phone',
        'tax_number',
        'address_line',
        'postal_code',
        'city',
        'country_code',
        'notes',
    ];

    /**
     * @return BelongsTo<Company, $this>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
