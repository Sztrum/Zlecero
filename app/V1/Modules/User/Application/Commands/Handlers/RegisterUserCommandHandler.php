<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Application\Commands\Handlers;

use App\V1\Core\Application\Command\CommandHandlerInterface;
use App\V1\Core\Application\Command\CommandInterface;
use App\V1\Modules\Company\Domain\Enums\CompanyUserRole;
use App\V1\Modules\Company\Domain\Enums\CompanyUserStatus;
use App\V1\Modules\Company\Domain\Models\Company;
use App\V1\Modules\Company\Infrastructure\Repositories\CompanyRepository;
use App\V1\Modules\User\Application\Commands\RegisterUserCommand;
use App\V1\Modules\User\Domain\Aggregates\UserAggregate;
use App\V1\Modules\User\Infrastructure\Repositories\UserRepository;
use Illuminate\Contracts\Hashing\Hasher;
use Ramsey\Uuid\Uuid;
use RuntimeException;
use Throwable;

readonly class RegisterUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private UserAggregate $userAggregate,
        private CompanyRepository $companyRepository,
        private Hasher $hasher,
    ) {
    }

    /**
     * @param  RegisterUserCommand $command
     * @throws Throwable
     */
    public function handle(CommandInterface $command): void
    {
        $this->userAggregate->checkUserWithEmailAlreadyExist($command->email);

        $company = $this->companyRepository->create([
            'name' => $command->companyName,
        ]);

        if (! $company instanceof Company) {
            throw new RuntimeException('Registered company must be a Company model.');
        }

        $this->userRepository->create([
            'company_id' => $company->id,
            'name' => $command->name,
            'email' => $command->email,
            'password' => $this->hasher->make($command->password),
            'role' => CompanyUserRole::OWNER->value,
            'status' => CompanyUserStatus::ACTIVE->value,
            'remember_token' => Uuid::uuid4(),
        ]);
    }
}
