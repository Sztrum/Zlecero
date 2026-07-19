<?php

declare(strict_types=1);

namespace App\V1\Core\Application\Providers\Routes;

use App\V1\Core\Application\Providers\ModuleServiceProvider;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;

abstract class ApiRouteServiceProvider extends RouteServiceProvider
{
    public const VERSION = 'v1';

    public const PREFIX = '';

    protected const PREFIX_PATTERN = '%s/%s';

    protected const PATTERN = '%s';

    protected bool $pluralPrefix = true;

    protected bool $prefix = true;
    protected bool $pluralRouteName = true;

    protected string $prefixRouteName = 'api.';

    public function getModuleProvider(): ModuleServiceProvider
    {
        $routeProviderReflection = new ReflectionClass($this);

        return Collection::make($this->app->getProviders(ModuleServiceProvider::class))
            ->filter(function (ModuleServiceProvider $moduleServiceProvider) use ($routeProviderReflection) {
                $routeProviderNamespace = $routeProviderReflection->getNamespaceName();
                $moduleProviderNamespace = (new ReflectionClass($moduleServiceProvider))->getNamespaceName();

                $routeProviderSegments = explode('\\', $routeProviderNamespace);
                $moduleProviderSegments = explode('\\', $moduleProviderNamespace);

                foreach ($moduleProviderSegments as $index => $segment) {
                    if (!isset($routeProviderSegments[$index]) || $routeProviderSegments[$index] !== $segment) {
                        return false;
                    }
                }

                return true;
            })->first();
    }

    public static function getRoutePrefix(): string
    {
        return implode('/', [self::PREFIX, static::VERSION]);
    }

    public static function getBaseUrl(): string
    {
        return implode('/', [config('app.url'), trim(static::getRoutePrefix(), '/')]);
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
            ? sprintf(static::PREFIX_PATTERN, static::getRoutePrefix(), $this->getModulePrefix())
            : sprintf(static::PATTERN, static::getRoutePrefix());
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

    protected function middlewares(): array
    {
        return ['api'];
    }

    final protected function hasPrefix(): bool
    {
        return $this->prefix;
    }
}
