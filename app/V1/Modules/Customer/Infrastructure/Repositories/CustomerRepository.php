<?php

declare(strict_types=1);

namespace App\V1\Modules\Customer\Infrastructure\Repositories;

use App\V1\Core\Infrastructure\Repositories\Eloquent\EloquentModelRepository;
use App\V1\Modules\Company\Domain\Models\Company;
use App\V1\Modules\Customer\Domain\Exceptions\CustomerNotFoundException;
use App\V1\Modules\Customer\Domain\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Throwable;

class CustomerRepository extends EloquentModelRepository
{
    public function model(): Customer
    {
        return new Customer();
    }

    public function moduleName(): string
    {
        return 'customer';
    }

    /**
     * @return Builder<Customer>
     */
    private function customerQuery(): Builder
    {
        return Customer::query();
    }

    /**
     * @return Collection<int, Customer>
     */
    public function getByCompany(Company $company, ?string $search = null): Collection
    {
        $trimmedSearch = is_string($search) ? trim($search) : '';

        return $this->customerQuery()
            ->where('company_id', $company->id)
            ->when($trimmedSearch !== '', function (Builder $builder) use ($trimmedSearch): void {
                $term = '%' . $trimmedSearch . '%';

                $builder->where(static function (Builder $builder) use ($term): void {
                    $builder
                        ->where('display_name', 'like', $term)
                        ->orWhere('company_name', 'like', $term)
                        ->orWhere('first_name', 'like', $term)
                        ->orWhere('last_name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('phone', 'like', $term)
                        ->orWhere('tax_number', 'like', $term);
                });
            })
            ->orderBy('display_name')
            ->get();
    }

    /**
     * @throws CustomerNotFoundException|Throwable
     */
    public function findCompanyCustomer(Company $company, string $customerId): Customer
    {
        $customer = $this->customerQuery()
            ->where('company_id', $company->id)
            ->where('id', $customerId)
            ->first();

        throw_if(! $customer instanceof Customer, CustomerNotFoundException::class);

        return $customer;
    }

    /**
     * @return Collection<int, Customer>
     */
    public function getPotentialDuplicates(Company $company, Customer $customer): Collection
    {
        if (! $this->customerHasDuplicateSignals($customer)) {
            return new Collection();
        }

        return $this->customerQuery()
            ->where('company_id', $company->id)
            ->where('id', '!=', $customer->id)
            ->where(static function (Builder $builder) use ($customer): void {
                if (is_string($customer->email) && $customer->email !== '') {
                    $builder->orWhere('email', $customer->email);
                }

                if (is_string($customer->tax_number) && $customer->tax_number !== '') {
                    $builder->orWhere('tax_number', $customer->tax_number);
                }

                if (is_string($customer->company_name) && $customer->company_name !== '') {
                    $builder->orWhere('company_name', $customer->company_name);
                }
            })
            ->orderBy('display_name')
            ->get();
    }

    private function customerHasDuplicateSignals(Customer $customer): bool
    {
        return (is_string($customer->email) && $customer->email !== '')
            || (is_string($customer->tax_number) && $customer->tax_number !== '')
            || (is_string($customer->company_name) && $customer->company_name !== '');
    }
}
