<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\UI\Http\Rules\Factories;

use App\V1\Modules\Auth\UI\Http\Rules\IsValidPasswordRule;

class IsValidPasswordRuleMessageFactory
{
    public static function render(IsValidPasswordRule $passwordRule): string
    {
        switch (true) {
            case !$passwordRule->getUppercasePasses()
            && $passwordRule->getNumericPasses()
            && $passwordRule->getSpecialCharacterPasses():
                return __('core::validation.password.uppercase');

            case !$passwordRule->getNumericPasses()
            && $passwordRule->getUppercasePasses()
            && $passwordRule->getSpecialCharacterPasses():
                return __('core::validation.password.numeric');

            case !$passwordRule->getSpecialCharacterPasses()
            && $passwordRule->getUppercasePasses()
            && $passwordRule->getNumericPasses():
                return __('core::validation.password.special');

            case !$passwordRule->getUppercasePasses()
            && !$passwordRule->getNumericPasses()
            && $passwordRule->getSpecialCharacterPasses():
                return __('core::validation.password.uppercase_numeric');

            case !$passwordRule->getUppercasePasses()
            && !$passwordRule->getSpecialCharacterPasses()
            && $passwordRule->getNumericPasses():
                return __('core::validation.password.uppercase_special');

            case !$passwordRule->getUppercasePasses()
            && !$passwordRule->getNumericPasses()
            && !$passwordRule->getSpecialCharacterPasses():
                return __('core::validation.password.numeric_special');

            case !$passwordRule->getUncompromisedPasses():
                return __('core::validation.password.compromised');

            default:
                return __('core::validation.password.default');
        }
    }
}
