<?php

declare(strict_types=1);

namespace App\V1\Core\UI\Http\Controllers;

use App\V1\Core\Application\Command\CommandBusInterface;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use JetBrains\PhpStorm\NoReturn;
use Throwable;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * @throws Throwable
     */
    #[NoReturn]
    public function __construct(
        protected CommandBusInterface $commandBus,
        protected ConfigRepository $configRepository,
    ) {
    }
}
