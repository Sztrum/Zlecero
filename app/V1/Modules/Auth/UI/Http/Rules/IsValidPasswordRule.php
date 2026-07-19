<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\UI\Http\Rules;

use App\V1\Modules\Auth\UI\Http\Rules\Factories\IsValidPasswordRuleMessageFactory;
use Illuminate\Container\Container;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\UncompromisedVerifier;
use Illuminate\Support\Str;

class IsValidPasswordRule implements Rule
{
    private bool $lengthPasses = true;

    private bool $uppercasePasses = true;

    private bool $numericPasses = true;

    private bool $specialCharacterPasses = true;

    private bool $uncompromised = true;

    private int $compromisedThreshold = 0;

    public function passes($attribute, $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        $this->lengthPasses = Str::length($value) >= 8;
        $this->uppercasePasses = Str::lower($value) !== $value;
        $this->numericPasses = (bool) preg_match('/[0-9]/', $value);
        $this->specialCharacterPasses = (bool) preg_match('/[^A-Za-z0-9]/', $value);

        $this->uncompromised = Container::getInstance()->make(UncompromisedVerifier::class)->verify([
            'value' => $value,
            'threshold' => $this->compromisedThreshold,
        ]);

        return $this->lengthPasses && $this->uppercasePasses && $this->numericPasses && $this->specialCharacterPasses && $this->uncompromised;
    }

    public function getLengthPasses(): bool
    {
        return $this->lengthPasses;
    }

    public function getUppercasePasses(): bool
    {
        return $this->uppercasePasses;
    }

    public function getNumericPasses(): bool
    {
        return $this->numericPasses;
    }

    public function getSpecialCharacterPasses(): bool
    {
        return $this->specialCharacterPasses;
    }

    public function getUncompromisedPasses(): bool
    {
        return $this->uncompromised;
    }

    public function message(): string
    {
        return IsValidPasswordRuleMessageFactory::render($this);
    }
}
