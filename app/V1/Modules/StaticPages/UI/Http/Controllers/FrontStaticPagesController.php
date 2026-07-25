<?php

declare(strict_types=1);

namespace App\V1\Modules\StaticPages\UI\Http\Controllers;

use App\V1\Core\UI\Http\Controllers\Controller;
use App\V1\Modules\StaticPages\UI\Http\Requests\FrontContactRequest;
use App\V1\Modules\StaticPages\UI\Mail\StaticContactLeadMail;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class FrontStaticPagesController extends Controller
{
    public const LOCALES = ['pl', 'en', 'de'];

    public function redirectHome(Request $request): RedirectResponse
    {
        $locale = $request->cookie('zlecero_locale');

        return redirect('/'.(is_string($locale) && in_array($locale, self::LOCALES, true) ? $locale : 'pl'));
    }

    public function landing(Request $request): View
    {
        return $this->render($this->localeFromRequest($request), 'landing');
    }

    public function pricing(Request $request): View
    {
        return $this->render($this->localeFromRequest($request), 'pricing');
    }

    public function faq(Request $request): View
    {
        return $this->render($this->localeFromRequest($request), 'faq');
    }

    public function about(Request $request): View
    {
        return $this->render($this->localeFromRequest($request), 'about');
    }

    public function contact(Request $request): View
    {
        return $this->render($this->localeFromRequest($request), 'contact');
    }

    public function submitContact(FrontContactRequest $request): RedirectResponse
    {
        $locale = $this->localeFromRequest($request);
        $this->setLocale($locale);

        $data = $request->validated();
        unset($data['website']);

        $lead = [
            'name' => $this->validatedStringValue($data, 'name'),
            'company' => $this->validatedStringValue($data, 'company'),
            'email' => $this->validatedStringValue($data, 'email'),
            'phone' => $this->validatedNullableStringValue($data, 'phone'),
            'subject' => $this->validatedStringValue($data, 'subject'),
            'message' => $this->validatedStringValue($data, 'message'),
        ];

        $fingerprint = hash('sha256', implode('|', [$lead['email'], $lead['subject'], $lead['message']]));

        if (! Cache::add('static_contact_lead:'.$fingerprint, true, now()->addMinutes(15))) {
            return back()
                ->withInput()
                ->with('contact_status', __('static_pages::messages.contact.duplicate'));
        }

        try {
            $recipient = config('mail.from.address');

            if (is_string($recipient) && $recipient !== '') {
                Mail::to($recipient)->queue(new StaticContactLeadMail($lead));
            }
        } catch (Throwable $exception) {
            Log::error('Static contact lead dispatch failed.', [
                'email' => $lead['email'],
                'exception' => $exception,
            ]);

            Cache::forget('static_contact_lead:'.$fingerprint);

            return back()
                ->withInput()
                ->withErrors(['message' => __('static_pages::messages.contact.failed')]);
        }

        return redirect('/'.$locale.'/contact')->with('contact_status', __('static_pages::messages.contact.sent'));
    }

    private function render(string $locale, string $page): View
    {
        $this->setLocale($locale);

        $view = match ($page) {
            'landing' => 'static_pages::_frontend.landing',
            'pricing' => 'static_pages::_frontend.pricing',
            'faq' => 'static_pages::_frontend.faq',
            'about' => 'static_pages::_frontend.about',
            'contact' => 'static_pages::_frontend.contact',
            default => 'static_pages::_frontend.landing',
        };

        return view($view, [
            'locale' => $locale,
            'page' => $page,
            'locales' => self::LOCALES,
            'content' => __('static_pages::messages.pages.'.$page),
            'shared' => __('static_pages::messages.shared'),
            'meta' => __('static_pages::messages.meta.'.$page),
        ]);
    }

    private function setLocale(string $locale): void
    {
        App::setLocale($locale);
        cookie()->queue(cookie('zlecero_locale', $locale, 60 * 24 * 365));
    }

    private function localeFromRequest(Request $request): string
    {
        $locale = $request->route('locale');

        return is_string($locale) && in_array($locale, self::LOCALES, true) ? $locale : 'pl';
    }

    /**
     * @param array<string, mixed> $data
     */
    private function validatedStringValue(array $data, string $key): string
    {
        $value = $data[$key] ?? null;

        if (! is_string($value)) {
            return '';
        }

        return $value;
    }

    /**
     * @param array<string, mixed> $data
     */
    private function validatedNullableStringValue(array $data, string $key): ?string
    {
        $value = $data[$key] ?? null;

        if (! is_string($value) || $value === '') {
            return null;
        }

        return $value;
    }
}
