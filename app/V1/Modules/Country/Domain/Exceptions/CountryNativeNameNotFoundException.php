<?php

declare(strict_types=1);

namespace App\V1\Modules\Country\Domain\Exceptions;

use App\V1\Core\Domain\Exceptions\DomainException;

class CountryNativeNameNotFoundException extends DomainException
{
}
