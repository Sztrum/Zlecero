<?php

declare(strict_types=1);

namespace App\V1\Core\Application\Command\Pipes;

use Closure;
use Illuminate\Database\ConnectionResolverInterface;
use Throwable;

class TransactionPipe
{
    public function __construct(
        private ConnectionResolverInterface $connectionResolver
    ) {
    }

    /**
     * @param  mixed     $job
     * @param  Closure(mixed): mixed $next
     * @throws Throwable
     */
    public function handle(mixed $job, Closure $next): mixed
    {
        return $this->connectionResolver->connection()
            ->transaction(fn () => $next($job));
    }
}
