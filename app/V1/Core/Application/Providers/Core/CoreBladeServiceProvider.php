<?php

declare(strict_types=1);

namespace App\V1\Core\Application\Providers\Core;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\DynamicComponent;

class CoreBladeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Blade::component('dynamic-component', DynamicComponent::class);
    }

    public function boot(): void
    {
        $this->declareArrayErrorDirective();
    }

    private function declareArrayErrorDirective(): void
    {
        Blade::directive('arrayerror', function (string $expression): string {
            return '
                <?php
                    $__errorArgs = [' . $expression . '];
                    $__firstExpressionKey = explode(".", $__errorArgs[0]);

                    $__bag = $errors->getBag($__errorArgs[1] ?? "default");

                    if ($__bag->has($__errorArgs[0]) || $__bag->has($__firstExpressionKey[0])) {
                        if (isset($message)) {
                            $__messageOriginal = $message;
                        }

                        $message = $__bag->first($__errorArgs[0]);

                        if($__bag->has($__firstExpressionKey[0]))
                        {
                            $message = $__bag->first($__firstExpressionKey[0]);
                        }
                ?>
            ';
        });

        Blade::directive('endarrayerror', function (): string {
            return '
                <?php
                        unset($message);

                        if (isset($__messageOriginal)) {
                            $message = $__messageOriginal;
                        }

                    }

                    unset($__errorArgs, $__bag);
                ?>
            ';
        });
    }
}
