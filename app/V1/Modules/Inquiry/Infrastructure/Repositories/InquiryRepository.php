<?php

declare(strict_types=1);

namespace App\V1\Modules\Inquiry\Infrastructure\Repositories;

use App\V1\Core\Infrastructure\Repositories\Eloquent\EloquentModelRepository;
use App\V1\Modules\Company\Domain\Models\Company;
use App\V1\Modules\Inquiry\Domain\Enums\InquiryPriority;
use App\V1\Modules\Inquiry\Domain\Enums\InquiryStatus;
use App\V1\Modules\Inquiry\Domain\Exceptions\InquiryNotFoundException;
use App\V1\Modules\Inquiry\Domain\Exceptions\InvalidInquiryStatusTransitionException;
use App\V1\Modules\Inquiry\Domain\Models\Inquiry;
use App\V1\Modules\Inquiry\Domain\Models\InquiryFile;
use App\V1\Modules\Inquiry\Domain\Models\InquiryMessage;
use App\V1\Modules\Inquiry\Domain\Models\InquiryNote;
use App\V1\Modules\Inquiry\Domain\Models\InquiryStatusChange;
use App\V1\Modules\User\Domain\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Throwable;

class InquiryRepository extends EloquentModelRepository
{
    public function model(): Inquiry
    {
        return new Inquiry();
    }

    public function moduleName(): string
    {
        return 'inquiry';
    }

    /**
     * @return Builder<Inquiry>
     */
    private function inquiryQuery(): Builder
    {
        return Inquiry::query();
    }

    /**
     * @param array{status?: string|null, priority?: string|null, queue?: string|null, archived?: string|null, owner?: string|null, owner_user_id?: string|null} $filters
     * @return Collection<int, Inquiry>
     */
    public function getByCompany(Company $company, array $filters = []): Collection
    {
        return $this->inquiryQuery()
            ->where('company_id', $company->id)
            ->with(['customer', 'owner', 'messages', 'statusChanges', 'files', 'notes.author'])
            ->when(($filters['archived'] ?? null) === '1', static function (Builder $builder): void {
                $builder->whereNotNull('archived_at');
            }, static function (Builder $builder): void {
                $builder->whereNull('archived_at');
            })
            ->when($filters['status'] ?? null, static function (Builder $builder, string $status): void {
                $builder->where('status', $status);
            })
            ->when($filters['priority'] ?? null, static function (Builder $builder, string $priority): void {
                $builder->where('priority', $priority);
            })
            ->when(($filters['owner'] ?? null) === 'me', static function (Builder $builder) use ($filters): void {
                $builder->where('owner_user_id', $filters['owner_user_id'] ?? null);
            })
            ->when($filters['queue'] ?? null, function (Builder $builder, string $queue): void {
                $this->applyQueueFilter($builder, $queue);
            })
            ->orderByRaw(
                "CASE priority
                    WHEN 'urgent' THEN 0
                    WHEN 'high' THEN 1
                    WHEN 'normal' THEN 2
                    WHEN 'low' THEN 3
                    ELSE 4
                END"
            )
            ->orderByRaw('response_due_at IS NULL')
            ->orderBy('response_due_at')
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * @throws InquiryNotFoundException|Throwable
     */
    public function findCompanyInquiry(Company $company, string $inquiryId): Inquiry
    {
        $inquiry = $this->inquiryQuery()
            ->where('company_id', $company->id)
            ->where('id', $inquiryId)
            ->with(['customer', 'owner', 'messages', 'statusChanges', 'files', 'notes.author'])
            ->first();

        throw_if(! $inquiry instanceof Inquiry, InquiryNotFoundException::class);

        return $inquiry;
    }

    /**
     * @param array<string, mixed> $params
     */
    public function createInquiry(array $params, User $changedBy): Inquiry
    {
        /** @var Inquiry $inquiry */
        $inquiry = $this->create($params);

        $this->recordStatusChange($inquiry, null, $inquiry->status, $changedBy);

        return $inquiry;
    }

    /**
     * @param array<string, mixed> $params
     */
    public function updateInquiry(Inquiry $inquiry, array $params): Inquiry
    {
        /** @var Inquiry $updatedInquiry */
        $updatedInquiry = $this->update($inquiry, $params);

        return $updatedInquiry;
    }

    /**
     * @throws InvalidInquiryStatusTransitionException|Throwable
     */
    public function changeStatus(Inquiry $inquiry, InquiryStatus $nextStatus, User $changedBy): Inquiry
    {
        $currentStatus = InquiryStatus::from($inquiry->status);

        throw_if(
            $currentStatus !== $nextStatus && ! $currentStatus->canTransitionTo($nextStatus),
            InvalidInquiryStatusTransitionException::class
        );

        if ($currentStatus === $nextStatus) {
            return $inquiry;
        }

        $inquiry->fill(['status' => $nextStatus->value])->save();
        $this->recordStatusChange($inquiry, $currentStatus->value, $nextStatus->value, $changedBy);

        return $inquiry;
    }

    public function archive(Inquiry $inquiry): Inquiry
    {
        $inquiry->fill(['archived_at' => now()])->save();

        return $inquiry;
    }

    public function restore(Inquiry $inquiry): Inquiry
    {
        $inquiry->fill(['archived_at' => null])->save();

        return $inquiry;
    }

    /**
     * @param array<string, mixed> $params
     */
    public function createMessage(Inquiry $inquiry, array $params): InquiryMessage
    {
        /** @var InquiryMessage $message */
        $message = InquiryMessage::query()->create([
            'company_id' => $inquiry->company_id,
            'inquiry_id' => $inquiry->id,
            'customer_id' => $inquiry->customer_id,
        ] + $params);

        return $message;
    }

    /**
     * @param array<string, mixed> $params
     */
    public function createFile(Inquiry $inquiry, array $params): InquiryFile
    {
        /** @var InquiryFile $file */
        $file = InquiryFile::query()->create([
            'company_id' => $inquiry->company_id,
            'inquiry_id' => $inquiry->id,
            'customer_id' => $inquiry->customer_id,
        ] + $params);

        return $file;
    }

    /**
     * @param array<string, mixed> $params
     */
    public function createNote(Inquiry $inquiry, array $params): InquiryNote
    {
        /** @var InquiryNote $note */
        $note = InquiryNote::query()->create([
            'company_id' => $inquiry->company_id,
            'inquiry_id' => $inquiry->id,
            'is_internal' => true,
        ] + $params);

        return $note;
    }

    public function assignOwner(Inquiry $inquiry, ?User $owner, User $changedBy): Inquiry
    {
        $previousOwnerUserId = $inquiry->owner_user_id;
        $nextOwnerUserId = $owner?->id;

        $inquiry->fill(['owner_user_id' => $nextOwnerUserId])->save();

        if ($previousOwnerUserId !== $nextOwnerUserId) {
            $this->createNote($inquiry, [
                'author_user_id' => $changedBy->id,
                'body' => sprintf('Owner changed from %s to %s.', $previousOwnerUserId ?? 'unassigned', $nextOwnerUserId ?? 'unassigned'),
            ]);
        }

        return $inquiry;
    }

    /**
     * @throws InquiryNotFoundException|Throwable
     */
    public function findCompanyInquiryFile(Company $company, Inquiry $inquiry, string $fileId): InquiryFile
    {
        $file = InquiryFile::query()
            ->where('company_id', $company->id)
            ->where('inquiry_id', $inquiry->id)
            ->where('id', $fileId)
            ->first();

        throw_if(! $file instanceof InquiryFile, InquiryNotFoundException::class);

        return $file;
    }

    private function recordStatusChange(
        Inquiry $inquiry,
        ?string $fromStatus,
        string $toStatus,
        User $changedBy,
    ): void {
        InquiryStatusChange::query()->create([
            'company_id' => $inquiry->company_id,
            'inquiry_id' => $inquiry->id,
            'changed_by_user_id' => $changedBy->id,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'changed_at' => now(),
        ]);
    }

    /**
     * @param Builder<Inquiry> $builder
     */
    private function applyQueueFilter(Builder $builder, string $queue): void
    {
        match ($queue) {
            'new' => $builder->where('status', InquiryStatus::NEW->value),
            'waiting' => $builder->where('status', InquiryStatus::WAITING_FOR_CUSTOMER->value),
            'overdue' => $builder->whereNotNull('response_due_at')->where('response_due_at', '<', Carbon::now()),
            'unassigned' => $builder->whereNull('owner_user_id'),
            'urgent' => $builder->where('priority', InquiryPriority::URGENT->value),
            default => null,
        };
    }
}
