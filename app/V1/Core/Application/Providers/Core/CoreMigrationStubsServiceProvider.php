<?php

declare(strict_types=1);

namespace App\V1\Core\Application\Providers\Core;

use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class CoreMigrationStubsServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register(): void
    {
        $this->app->extend(
            'migration.creator',
            fn (MigrationCreator $migrationCreator, Application $app) => new MigrationCreator(
                $migrationCreator->getFilesystem(),
                $app['config']->get('core::migration.stub_path')
            )
        );
    }
}
