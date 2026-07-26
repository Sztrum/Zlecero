<?php

declare(strict_types=1);

namespace App\V1\Core\Application\Providers;

use Illuminate\Contracts\Config\Repository as ConfigContract;
use Illuminate\Support\Str;
use ReflectionClass;
use RuntimeException;
use Symfony\Component\Finder\Finder;

abstract class ConfigServiceProvider extends ServiceProvider
{
    protected const string CONFIGS_PATH = '../../Domain/Config';

    public function boot(
        ConfigContract $configRepository
    ): void {
        $this->loadConfigs();
    }

    public function register(): void
    {
    }

    private function loadConfigs(): void
    {
        $configsPath = implode(DIRECTORY_SEPARATOR, [
            $this->getProviderPath(),
            static::CONFIGS_PATH,
        ]);

        if (file_exists($configsPath)) {
            $finder = new Finder();
            $configFiles = $finder->files()->in($configsPath)->name('*.php');

            foreach ($configFiles as $configFile) {
                $this->mergeConfigFrom(
                    $configFile->getPathname(),
                    $this->getModuleProvider()->moduleName() . '::' . Str::beforeLast($configFile->getFilename(), '.php')
                );
            }
        }
    }

    public function getModuleProvider(): ModuleServiceProvider
    {
        $configProviderReflection = new ReflectionClass($this);

        foreach ($this->app->getProviders(ModuleServiceProvider::class) as $moduleServiceProvider) {
            if (! $moduleServiceProvider instanceof ModuleServiceProvider) {
                continue;
            }

            $moduleProviderReflection = new ReflectionClass($moduleServiceProvider);

            if (str_starts_with($configProviderReflection->getNamespaceName(), $moduleProviderReflection->getNamespaceName())) {
                return $moduleServiceProvider;
            }
        }

        throw new RuntimeException('Module provider not found for config provider ' . static::class);
    }

    private function getProviderPath(): string
    {
        $fileName = (new ReflectionClass($this))->getFileName();

        if ($fileName === false) {
            throw new RuntimeException('Unable to resolve config provider path for ' . static::class);
        }

        return dirname($fileName);
    }
}
