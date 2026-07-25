<?php

declare(strict_types=1);

namespace App\V1\Modules\Company\Infrastructure\Repositories;

use App\V1\Core\Domain\Models\Model;
use App\V1\Core\Infrastructure\Repositories\Eloquent\EloquentModelRepository;
use App\V1\Modules\Company\Domain\Exceptions\CompanyNotFoundException;
use App\V1\Modules\Company\Domain\Models\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Throwable;

class CompanyRepository extends EloquentModelRepository
{
    public function model(): Company
    {
        return new Company();
    }

    public function moduleName(): string
    {
        return 'company';
    }

    /**
     * @param array<string, mixed> $params
     */
    public function create(array $params): Model
    {
        if (!isset($params['slug']) && isset($params['name']) && is_string($params['name'])) {
            $params['slug'] = $this->uniqueSlug($params['name']);
        }

        if (!isset($params['trial_started_at'])) {
            $params['trial_started_at'] = Carbon::now();
        }

        if (!isset($params['trial_ends_at'])) {
            $params['trial_ends_at'] = Carbon::now()->addDays(14);
        }

        return parent::create($params);
    }

    /**
     * @return Builder<Company>
     */
    private function companyQuery(): Builder
    {
        return Company::query();
    }

    /**
     * @throws CompanyNotFoundException|Throwable
     */
    public function findById(string $id): Company
    {
        $company = $this->companyQuery()->find($id);

        throw_if(! $company instanceof Company, CompanyNotFoundException::class);

        return $company;
    }

    private function uniqueSlug(string $name): string
    {
        $baseSlug = Str::slug($name);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'company';
        $slug = $baseSlug;
        $suffix = 2;

        while ($this->companyQuery()->where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
