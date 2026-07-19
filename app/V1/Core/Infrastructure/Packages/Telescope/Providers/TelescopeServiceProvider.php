<?php

declare(strict_types=1);

namespace App\V1\Core\Infrastructure\Packages\Telescope\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Laravel\Telescope\Contracts\EntriesRepository;
use Laravel\Telescope\EntryType;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    public function register(): void
    {
        $this->hideSensitiveRequestDetails();

        Telescope::filter(static function (IncomingEntry $entry): bool {
            return true;
        });
    }

    public function isJob(IncomingEntry $entry, ?string $jobClass = null): bool
    {
        if ($entry->type !== EntryType::JOB) {
            return false;
        }

        if (!$jobClass) {
            return true;
        }

        return $this->contentString($entry, 'name') === $jobClass;
    }

    public function isWebsocketRequest(IncomingEntry $entry): bool
    {
        return $this->isRequest($entry, 'POST') && Str::startsWith($this->contentString($entry, 'uri'), '/broadcasting');
    }

    public function isApiRequest(IncomingEntry $entry): bool
    {
        return Str::startsWith($this->contentString($entry, 'uri'), '/api');
    }

    public function isEvent(IncomingEntry $entry, ?string $eventClass = null): bool
    {
        if ($entry->type !== 'event') {
            return false;
        }

        if (!$eventClass) {
            return true;
        }

        return $this->contentString($entry, 'name') === $eventClass;
    }

    public function isSuccessRequest(IncomingEntry $entry): bool
    {
        return $entry->type === EntryType::REQUEST && $this->contentInt($entry, 'response_status', 0) === 200;
    }

    public function isFailedRequest(IncomingEntry $entry): bool
    {
        $status = $this->contentInt($entry, 'response_status', 200);

        return $entry->type === EntryType::REQUEST && $status >= 500 && $status !== 503;
    }

    public function isFailedJob(IncomingEntry $entry): bool
    {
        return $entry->type === EntryType::JOB && $this->contentString($entry, 'status') === 'failed';
    }

    public function isProcessedJob(IncomingEntry $entry): bool
    {
        return $entry->type === EntryType::JOB && $this->contentString($entry, 'status') === 'processed';
    }

    public function isScheduledTask(IncomingEntry $entry): bool
    {
        return $entry->type === EntryType::SCHEDULED_TASK;
    }

    public function hasMonitoredTag(IncomingEntry $entry): bool
    {
        if (!empty($entry->tags)) {
            return app(EntriesRepository::class)->isMonitoring($entry->tags);
        }

        return false;
    }

    public function isException(IncomingEntry $entry): bool
    {
        return $entry->type === EntryType::EXCEPTION;
    }

    public function isRequestWithStatus(IncomingEntry $entry, ?string $method = null, ?int $status = null): bool
    {
        if ($entry->type !== EntryType::REQUEST) {
            return false;
        }

        if ($method && $status) {
            return $this->contentString($entry, 'method') === $method && $this->contentInt($entry, 'response_status', 200) === $status;
        }

        if ($method) {
            return $this->contentString($entry, 'method') === $method;
        }

        if ($status) {
            return $this->contentInt($entry, 'response_status', 200) === $status;
        }

        return true;
    }

    public function isRequest(IncomingEntry $entry, ?string $method = null): bool
    {
        if ($entry->type !== EntryType::REQUEST) {
            return false;
        }

        if ($method) {
            return $this->contentString($entry, 'method') === $method;
        }

        return true;
    }

    public function isLivewireRequest(IncomingEntry $entry, string $method = 'POST'): bool
    {
        return $this->isRequest($entry, $method) && Str::startsWith($this->contentString($entry, 'uri'), '/livewire/');
    }

    public function isLivewireFailedRequest(IncomingEntry $entry, string $method = 'POST'): bool
    {
        return $this->isLivewireRequest($entry, $method) && $this->isFailedRequest($entry);
    }

    public function isLoginRequest(IncomingEntry $entry, string $method = 'POST'): bool
    {
        $uri = $this->contentString($entry, 'uri');

        return $this->isRequest($entry, $method)
            && (Str::startsWith($uri, '/login') || (Str::startsWith($uri, '/2fa/') && Str::endsWith($uri, '/login')));
    }

    public function isLogoutRequest(IncomingEntry $entry, string $method = 'POST'): bool
    {
        return $this->isRequest($entry, $method) && Str::startsWith($this->contentString($entry, 'uri'), '/logout');
    }

    protected function hideSensitiveRequestDetails(): void
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    protected function gate(): void
    {
        Gate::define('viewTelescope', static function (): bool {
            return false;
        });
    }

    private function contentString(IncomingEntry $entry, string $key): string
    {
        $value = $entry->content[$key] ?? '';

        return is_string($value) ? $value : '';
    }

    private function contentInt(IncomingEntry $entry, string $key, int $default): int
    {
        $value = $entry->content[$key] ?? $default;

        return is_int($value) ? $value : $default;
    }
}
