<?php

declare(strict_types=1);

namespace App\V1\Core\Application\Providers;

use Illuminate\Contracts\Config\Repository as ConfigContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

abstract class ConfigServiceProvider extends ServiceProvider
{
    protected const string CONFIGS_PATH = '../../Domain/Config';

    public function boot(
        ConfigContract $configRepository
    ) {
        $this->loadConfigs();
    }

    public function register(): void
    {
    }

    private function loadConfigs()
    {
        $reflectionClass = new ReflectionClass($this);

        $configsPath = join(DIRECTORY_SEPARATOR, [
            dirname($reflectionClass->getFileName()),
            static::CONFIGS_PATH,
        ]);

        if (file_exists($configsPath)) {
            $finder = new Finder();
            $configFiles = $finder->files()->in($configsPath)->name('*.php');

            foreach ($configFiles as $configFile) {
                $this->mergeConfigFrom($configFile->getPathname(), $this->getModuleProvider()->moduleName() . '::' . Str::beforeLast($configFile->getFilename(), '.php'));
            }
        }
    }

    public function getModuleProvider(): ModuleServiceProvider
    {
        $routeProviderReflection = new ReflectionClass($this);

        return Collection::make($this->app->getProviders(ModuleServiceProvider::class))
            ->filter(function (ModuleServiceProvider $moduleServiceProvider) use ($routeProviderReflection) {
                $reflection = new ReflectionClass($moduleServiceProvider);

                return str_starts_with($routeProviderReflection->getNamespaceName(), $reflection->getNamespaceName());
            })->first();
    }
}
