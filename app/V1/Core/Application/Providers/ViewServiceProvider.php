<?php

declare(strict_types=1);

namespace App\V1\Core\Application\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use ReflectionClass;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

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
        $reflectionClass = new ReflectionClass($this);

        $viewsPath = implode(DIRECTORY_SEPARATOR, [
            dirname($reflectionClass->getFileName()),
            static::VIEWS_PATH,
        ]);

        if (file_exists($viewsPath)) {
            $this->loadViewsFrom($viewsPath, $this->moduleName());
        }
    }

    protected function registerBladeComponents(): void
    {
        $reflectionClass = new ReflectionClass($this);

        $path = implode(DIRECTORY_SEPARATOR, [
            dirname($reflectionClass->getFileName()),
            static::VIEWS_PATH,
        ]);

        if (!is_dir($path)) {
            return;
        }

        $this->app['view']->addNamespace($this->moduleName(), $path);

        if (is_dir($path . '/components')) {
            $finder = new Finder();
            $components = $finder->files()->in($path . '/components')->name('*.blade.php');

            foreach ($components as $component) {
                $tag = $this->getComponentTag($component);
                $componentPath = $this->moduleName() . '::components.' . $tag;

                if (!is_null($tag)) {
                    Blade::component($componentPath, $this->moduleName() . '::' . $tag);
                }
            }
        }
    }

    protected function getComponentTag(SplFileInfo $file): string
    {
        $relativePath = $file->getRelativePath();
        $componentName = pathinfo($file->getFilename(), PATHINFO_FILENAME);
        $tag = Str::beforeLast(($relativePath ? str_replace('/', '.', $relativePath) . '.' : '') . $componentName, '.blade');

        return strtolower($tag);
    }
}
