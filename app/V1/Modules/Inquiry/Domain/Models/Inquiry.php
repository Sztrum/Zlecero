<?php

declare(strict_types=1);

namespace App\V1\Modules\Inquiry\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use App\V1\Modules\Company\Domain\Models\Company;
use App\V1\Modules\Customer\Domain\Models\Customer;
use App\V1\Modules\User\Domain\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $company_id
 * @property string|null $customer_id
 * @property string|null $owner_user_id
 * @property string $source
 * @property string $title
 * @property string|null $description
 * @property string $status
 * @property string $priority
 * @property \Illuminate\Support\Carbon|null $response_due_at
 * @property \Illuminate\Support\Carbon|null $realization_due_at
 * @property \Illuminate\Support\Carbon|null $pickup_due_at
 * @property \Illuminate\Support\Carbon|null $archived_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property Customer|null $customer
 * @property User|null $owner
 * @property \Illuminate\Database\Eloquent\Collection<int, InquiryStatusChange> $statusChanges
 * @property \Illuminate\Database\Eloquent\Collection<int, InquiryMessage> $messages
 * @property \Illuminate\Database\Eloquent\Collection<int, InquiryFile> $files
 * @property \Illuminate\Database\Eloquent\Collection<int, InquiryNote> $notes
 */
class Inquiry extends Model
{
    protected $fillable = [
        'company_id',
        'customer_id',
        'owner_user_id',
        'source',
        'title',
        'description',
        'status',
        'priority',
        'response_due_at',
        'realization_due_at',
        'pickup_due_at',
        'archived_at',
    ];

    protected $casts = [
        'response_due_at' => 'datetime',
        'realization_due_at' => 'datetime',
        'pickup_due_at' => 'datetime',
        'archived_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<Company, $this>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
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
     * @return HasMany<InquiryStatusChange, $this>
     */
    public function statusChanges(): HasMany
    {
        return $this->hasMany(InquiryStatusChange::class);
    }

    /**
     * @return HasMany<InquiryMessage, $this>
     */
    public function messages(): HasMany
    {
        return $this->hasMany(InquiryMessage::class);
    }

    /**
     * @return HasMany<InquiryFile, $this>
     */
    public function files(): HasMany
    {
        return $this->hasMany(InquiryFile::class);
    }

    /**
     * @return HasMany<InquiryNote, $this>
     */
    public function notes(): HasMany
    {
        return $this->hasMany(InquiryNote::class);
    }
}
