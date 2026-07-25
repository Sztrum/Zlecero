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
 * @property string|null $author_user_id
 * @property string $body
 * @property bool $is_internal
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class InquiryNote extends Model
{
    protected $fillable = [
        'company_id',
        'inquiry_id',
        'author_user_id',
        'body',
        'is_internal',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
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
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_user_id');
    }
}
