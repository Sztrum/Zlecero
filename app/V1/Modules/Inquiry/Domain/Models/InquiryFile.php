<?php

declare(strict_types=1);

namespace App\V1\Modules\Inquiry\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use App\V1\Modules\Customer\Domain\Models\Customer;
use App\V1\Modules\User\Domain\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $company_id
 * @property string $inquiry_id
 * @property string|null $customer_id
 * @property string|null $inquiry_message_id
 * @property string|null $uploaded_by_user_id
 * @property string $source
 * @property string $disk
 * @property string $stored_path
 * @property string $original_name
 * @property string|null $mime_type
 * @property int $size_bytes
 * @property string|null $category
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class InquiryFile extends Model
{
    protected $fillable = [
        'company_id',
        'inquiry_id',
        'customer_id',
        'inquiry_message_id',
        'uploaded_by_user_id',
        'source',
        'disk',
        'stored_path',
        'original_name',
        'mime_type',
        'size_bytes',
        'category',
        'description',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
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
     * @return BelongsTo<InquiryMessage, $this>
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(InquiryMessage::class, 'inquiry_message_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }
}
