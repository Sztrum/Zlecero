<?php

declare(strict_types=1);

namespace App\V1\Core\Application\Providers;

use InvalidArgumentException;
use ReflectionClass;

abstract class ModuleServiceProvider extends ServiceProvider
{
    protected const TRANSLATIONS_PATH = 'Application/Translations';
    public const TRANSLATIONS_MODULE_SEPARATOR = '::';

    private static array $registeredModules = [];

    abstract public function moduleName(): string;

    public function register(): void
    {
        $modulePath = $this->getModulePath();
        $this->checkModuleName($this->moduleName(), $modulePath);

        self::$registeredModules[] = [
            'name' => $this->moduleName(),
            'path' => $modulePath
        ];

        $this->loadTranslations();
    }

    private function checkModuleName(string $moduleName, string $modulePath): void
    {
        foreach (self::$registeredModules as $registeredModule) {
            if ($registeredModule['name'] === $moduleName && $registeredModule['path'] !== $modulePath) {
                throw new InvalidArgumentException(
                    "Module name '{$moduleName}' is already registered with a different provider path.\n"
                    . "Module 1: Path - {$registeredModule['path']}\n"
                    . "Module 2: Path - {$modulePath}"
                );
            }
        }
    }

    private function loadTranslations(): void
    {
        $reflectionClass = new ReflectionClass($this);

        $translationsPath = join(DIRECTORY_SEPARATOR, [
            dirname($reflectionClass->getFileName()),
            static::TRANSLATIONS_PATH,
        ]);

        if (file_exists($translationsPath)) {
            $this->loadTranslationsFrom($translationsPath, $this->moduleName());
        }
    }

    private function getModulePath(): string
    {
        $reflectionClass = new ReflectionClass($this);

        return dirname($reflectionClass->getFileName());
    }
}
