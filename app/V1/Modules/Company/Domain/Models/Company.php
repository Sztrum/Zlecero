<?php

declare(strict_types=1);

namespace App\V1\Modules\Company\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use App\V1\Modules\User\Domain\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property string|null $billing_name
 * @property string|null $tax_number
 * @property string|null $contact_email
 * @property string|null $contact_phone
 * @property string|null $address_line
 * @property string|null $postal_code
 * @property string|null $city
 * @property string $country_code
 * @property string $brand_color
 * @property int $trial_days
 * @property \Illuminate\Support\Carbon|null $trial_started_at
 * @property \Illuminate\Support\Carbon|null $trial_ends_at
 * @property \Illuminate\Support\Carbon|null $onboarding_completed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Company extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'billing_name',
        'tax_number',
        'contact_email',
        'contact_phone',
        'address_line',
        'postal_code',
        'city',
        'country_code',
        'brand_color',
        'trial_days',
        'trial_started_at',
        'trial_ends_at',
        'onboarding_completed_at',
    ];

    protected $casts = [
        'trial_days' => 'integer',
        'trial_started_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'onboarding_completed_at' => 'datetime',
    ];

    /**
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
