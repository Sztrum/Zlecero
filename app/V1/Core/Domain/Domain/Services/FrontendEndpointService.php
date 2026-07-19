<?php

declare(strict_types=1);

namespace App\V1\Core\Domain\Domain\Services;

use App\V1\Core\Domain\Enums\FrontEndRouteEnum;
use App\V1\Core\Domain\Models\Model;
use App\V1\Shared\VO\UrlVO;
use Illuminate\Config\Repository as ConfigInterface;
use RuntimeException;
use Throwable;

readonly class FrontendEndpointService
{
    public function __construct(
        private ConfigInterface $config
    ) {
    }

    /**
     * @throws Throwable
     */
    public function getUrl(): UrlVO
    {
        $url = $this->config->get('core::frontend.url');

        if (!is_string($url)) {
            throw new RuntimeException('Config core::frontend.url must be a string.');
        }

        return new UrlVO($url);
    }

    /**
     * @param array<string, bool|float|int|string|Model|null> $data
     * @throws Throwable
     */
    public function route(
        FrontEndRouteEnum $route,
        array $data = [],
        bool $missingToQuery = true
    ): string {
        return join('/', [
            $this->getUrl()->value,
            $this->prepareRoute($route, $data, $missingToQuery),
        ]);
    }

    /**
     * @param array<string, bool|float|int|string|Model|null> $data
     */
    private function prepareRoute(
        FrontEndRouteEnum $route,
        array $data,
        bool $missingToQuery = true
    ): string {
        $url = $route->value;
        $query = [];

        foreach ($data as $paramName => $paramValue) {
            $pattern = "{{$paramName}}";
            $routeValue = $this->routeValue($paramValue);

            if (strpos($url, $pattern) !== false) {
                $url = str_replace($pattern, $routeValue, $url);
            } elseif ($missingToQuery) {
                $query[$paramName] = $routeValue;
            }
        }

        return ltrim($url . (!empty($query) ? '?' . http_build_query($query) : ''), '/');
    }

    private function routeValue(bool|float|int|string|Model|null $value): string
    {
        if ($value instanceof Model) {
            $routeKey = $value->getRouteKey();

            if (is_string($routeKey) || is_int($routeKey)) {
                return (string) $routeKey;
            }

            throw new RuntimeException('Model route key must be a string or integer.');
        }

        if ($value === null) {
            return '';
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        return (string) $value;
    }
}
