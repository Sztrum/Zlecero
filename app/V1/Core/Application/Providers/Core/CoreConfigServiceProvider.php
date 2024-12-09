<?php

declare(strict_types=1);

namespace App\V1\Core\Application\Providers\Core;

use App\V1\Core\Application\Providers\ConfigServiceProvider;

class CoreConfigServiceProvider extends ConfigServiceProvider
{
    protected const string CONFIGS_PATH = '../../../Domain/Config';
}
