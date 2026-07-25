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
 * @property string|null $created_by_user_id
 * @property string $direction
 * @property string|null $sender_name
 * @property string|null $sender_email
 * @property string|null $recipient_email
 * @property string|null $subject
 * @property string $body
 * @property string|null $external_message_id
 * @property string|null $external_thread_id
 * @property \Illuminate\Support\Carbon|null $sent_at
 * @property \Illuminate\Support\Carbon|null $created_at
 */
class InquiryMessage extends Model
{
    protected $fillable = [
        'company_id',
        'inquiry_id',
        'customer_id',
        'created_by_user_id',
        'direction',
        'sender_name',
        'sender_email',
        'recipient_email',
        'subject',
        'body',
        'external_message_id',
        'external_thread_id',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
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
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
