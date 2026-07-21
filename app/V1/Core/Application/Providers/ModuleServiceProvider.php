<?php

declare(strict_types=1);

namespace App\V1\Core\Application\Providers;

use InvalidArgumentException;
use ReflectionClass;
use RuntimeException;

abstract class ModuleServiceProvider extends ServiceProvider
{
    protected const TRANSLATIONS_PATH = 'Application/Translations';

    public const TRANSLATIONS_MODULE_SEPARATOR = '::';

    /**
     * @var list<array{name: string, path: string}>
     */
    private static array $registeredModules = [];

    abstract public function moduleName(): string;

    public function register(): void
    {
        $modulePath = $this->getModulePath();
        $this->checkModuleName($this->moduleName(), $modulePath);

        self::$registeredModules[] = [
            'name' => $this->moduleName(),
            'path' => $modulePath,
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
        $translationsPath = implode(DIRECTORY_SEPARATOR, [
            $this->getModulePath(),
            self::TRANSLATIONS_PATH,
        ]);

        if (file_exists($translationsPath)) {
            $this->loadTranslationsFrom($translationsPath, $this->moduleName());
        }
    }

    private function getModulePath(): string
    {
        $fileName = (new ReflectionClass($this))->getFileName();

        if ($fileName === false) {
            throw new RuntimeException('Unable to resolve module provider path for ' . static::class);
        }

        return dirname($fileName);
    }
}
