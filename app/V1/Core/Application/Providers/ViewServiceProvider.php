<?php

declare(strict_types=1);

namespace App\V1\Core\Application\Providers;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use ReflectionClass;
use RuntimeException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

abstract class ViewServiceProvider extends ServiceProvider
{
    protected const VIEWS_PATH = '../../UI/Http/Views';

    abstract public function moduleName(): string;

    public function register(): void
    {
        $this->loadViews();
        $this->registerBladeComponents();
    }

    private function loadViews(): void
    {
        $viewsPath = implode(DIRECTORY_SEPARATOR, [
            $this->getProviderPath(),
            $this->viewsPathSegment(),
        ]);

        if (file_exists($viewsPath)) {
            $this->loadViewsFrom($viewsPath, $this->moduleName());
        }
    }

    protected function registerBladeComponents(): void
    {
        $path = implode(DIRECTORY_SEPARATOR, [
            $this->getProviderPath(),
            $this->viewsPathSegment(),
        ]);

        if (!is_dir($path)) {
            return;
        }

        $viewFactory = $this->app->make(ViewFactory::class);
        $viewFactory->addNamespace($this->moduleName(), $path);

        if (is_dir($path . '/components')) {
            $finder = new Finder();
            $components = $finder->files()->in($path . '/components')->name('*.blade.php');

            foreach ($components as $component) {
                $tag = $this->getComponentTag($component);
                $componentPath = $this->moduleName() . '::components.' . $tag;

                Blade::component($componentPath, $this->moduleName() . '::' . $tag);
            }
        }
    }

    protected function getComponentTag(SplFileInfo $file): string
    {
        $relativePath = $file->getRelativePath();
        $componentName = pathinfo($file->getFilename(), PATHINFO_FILENAME);
        $tag = Str::beforeLast(($relativePath !== '' ? str_replace('/', '.', $relativePath) . '.' : '') . $componentName, '.blade');

        return strtolower($tag);
    }

    private function viewsPathSegment(): string
    {
        $viewsPath = static::VIEWS_PATH;

        if (! is_string($viewsPath)) {
            throw new RuntimeException('View path constant must be a string for ' . static::class);
        }

        return $viewsPath;
    }

    private function getProviderPath(): string
    {
        $fileName = (new ReflectionClass($this))->getFileName();

        if ($fileName === false) {
            throw new RuntimeException('Unable to resolve view provider path for ' . static::class);
        }

        return dirname($fileName);
    }
}
