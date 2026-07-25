<?php

declare(strict_types=1);

namespace App\V1\Core\Application\Providers\Core;

use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

class CoreMigrationStubsServiceProvider extends ServiceProvider
{
    private const DEFAULT_STUB_PATH = 'V1/Shared/Migrations/Stubs';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->app->extend(
            'migration.creator',
            fn (MigrationCreator $migrationCreator, Application $app) => new MigrationCreator(
                $migrationCreator->getFilesystem(),
                $this->migrationStubPath($app)
            )
        );
    }

    private function migrationStubPath(Application $app): string
    {
        $stubPath = $app['config']->get('core::migration.stub_path');

        if ($stubPath === null) {
            return $app->path(self::DEFAULT_STUB_PATH);
        }

        if (! is_string($stubPath)) {
            throw new RuntimeException('Configured migration stub path must be a string.');
        }

        return $stubPath;
    }
}
