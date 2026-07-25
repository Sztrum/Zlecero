<?php

declare(strict_types=1);

namespace App\V1\Modules\Inquiry\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use App\V1\Modules\User\Domain\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $company_id
 * @property string $inquiry_id
 * @property string|null $changed_by_user_id
 * @property string|null $from_status
 * @property string $to_status
 * @property \Illuminate\Support\Carbon $changed_at
 */
class InquiryStatusChange extends Model
{
    protected $fillable = [
        'company_id',
        'inquiry_id',
        'changed_by_user_id',
        'from_status',
        'to_status',
        'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<Inquiry, $this>
     */
    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(Inquiry::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }
}
