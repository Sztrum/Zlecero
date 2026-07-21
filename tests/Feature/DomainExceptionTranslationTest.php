<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\V1\Core\Domain\Exceptions\InvalidUrlException;
use App\V1\Core\Domain\Exceptions\ReadModelNotSupportMethodException;
use App\V1\Modules\Auth\Domain\Exceptions\AuthException;
use App\V1\Modules\Country\Domain\Exceptions\CountryNotFoundException;
use App\V1\Modules\User\Domain\Exceptions\UserNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class DomainExceptionTranslationTest extends TestCase
{
    public function test_domain_exceptions_resolve_backend_translation_messages(): void
    {
        app()->setLocale('en');

        $this->assertSame('User not found.', (new UserNotFoundException)->getMessage());
        $this->assertSame('Country not found.', (new CountryNotFoundException)->getMessage());
        $this->assertSame('Invalid URL.', (new InvalidUrlException)->getMessage());
        $this->assertSame(
            'Calling the [save] method on the read-only model [ReportReadModel] is not supported.',
            (new ReadModelNotSupportMethodException(replace: [
                'methodName' => 'save',
                'modelClass' => 'ReportReadModel',
            ]))->getMessage()
        );
    }

    public function test_auth_exception_resolves_backend_translation_and_keeps_forbidden_status(): void
    {
        app()->setLocale('en');

        $exception = new AuthException;

        $this->assertSame('Login failed.', $exception->getMessage());
        $this->assertSame(Response::HTTP_FORBIDDEN, $exception->getStatusCode());
    }
}
