<?php

declare(strict_types=1);

namespace App\V1\Core\Application\Providers\Routes;

use App\V1\Core\Application\Providers\ModuleServiceProvider;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Str;
use ReflectionClass;
use RuntimeException;

abstract class ApiRouteServiceProvider extends RouteServiceProvider
{
    public const VERSION = 'v1';

    public const PREFIX = 'api';

    protected bool $pluralPrefix = true;

    protected bool $prefix = true;
    protected bool $pluralRouteName = true;

    protected string $prefixRouteName = 'api.';

    public function getModuleProvider(): ModuleServiceProvider
    {
        $routeProviderReflection = new ReflectionClass($this);

        foreach ($this->app->getProviders(ModuleServiceProvider::class) as $moduleServiceProvider) {
            if (! $moduleServiceProvider instanceof ModuleServiceProvider) {
                continue;
            }

            $routeProviderNamespace = $routeProviderReflection->getNamespaceName();
            $moduleProviderNamespace = (new ReflectionClass($moduleServiceProvider))->getNamespaceName();

            $routeProviderSegments = explode('\\', $routeProviderNamespace);
            $moduleProviderSegments = explode('\\', $moduleProviderNamespace);

            foreach ($moduleProviderSegments as $index => $segment) {
                if (!isset($routeProviderSegments[$index]) || $routeProviderSegments[$index] !== $segment) {
                    continue 2;
                }
            }

            return $moduleServiceProvider;
        }

        throw new RuntimeException('Module provider not found for route provider ' . static::class);
    }

    public static function getRoutePrefix(): string
    {
        return self::PREFIX . '/' . self::VERSION;
    }

    public static function getBaseUrl(): string
    {
        $appUrl = config('app.url');

        if (! is_string($appUrl)) {
            throw new RuntimeException('Configured app.url must be a string.');
        }

        return implode('/', [$appUrl, trim(static::getRoutePrefix(), '/')]);
    }

    public function getModulePrefix(): string
    {
        $moduleName = $this->getModuleProvider()
            ->moduleName();

        return $this->pluralPrefix ? Str::plural($moduleName) : $moduleName;
    }

    public function getModuleRouteName(): string
    {
        $moduleName = $this->getModuleProvider()
            ->moduleName();

        return $this->pluralRouteName ? Str::plural($moduleName) : $moduleName;
    }

    public function getPrefix(): string
    {
        return $this->hasPrefix()
            ? static::getRoutePrefix() . '/' . $this->getModulePrefix()
            : static::getRoutePrefix();
    }

    public function map(Registrar $router): void
    {
        $router->group([
            'middleware' => $this->middlewares(),
            'prefix' => $this->getPrefix(),
            'as' => $this->prefixRouteName !== '' ? $this->prefixRouteName . $this->getModuleRouteName() . '.' : ''
        ], fn () => $this->registerRoutes($router));
    }

    abstract protected function registerRoutes(Registrar $router): void;

    /**
     * @return list<string>
     */
    protected function middlewares(): array
    {
        return ['api'];
    }

    final protected function hasPrefix(): bool
    {
        return $this->prefix;
    }
}
