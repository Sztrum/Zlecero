<?php

declare(strict_types=1);

namespace App\V1\Modules\StaticPages\UI\Http\Controllers;

use App\V1\Core\UI\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

class FrontStaticPageController extends Controller
{
    private const string LOCALE_COOKIE = 'zlecero_locale';

    /**
     * @var list<string>
     */
    private const array FALLBACK_LOCALES = ['pl', 'en', 'de'];

    public function redirectToPreferredLocale(): RedirectResponse
    {
        $locale = $this->normalizeLocale(request()->cookie(self::LOCALE_COOKIE));

        return redirect()->route('static-pages.home', [
            'locale' => $locale,
        ]);
    }

    public function home(string $locale): View
    {
        App::setLocale($locale);
        Cookie::queue(Cookie::forever(self::LOCALE_COOKIE, $locale));

        return view('static-pages::home', [
            'meta' => __('static-pages::home.meta'),
            'currentLocale' => $locale,
            'languageItems' => $this->languageItems($locale),

            'navigationItems' => __('static-pages::home.navigation'),
            'heroStats' => __('static-pages::home.hero.stats'),
            'problemItems' => __('static-pages::home.problems.items'),
            'processSteps' => __('static-pages::home.process.steps'),
            'currentWorkflow' => __('static-pages::home.comparison.current.items'),
            'zleceroWorkflow' => __('static-pages::home.comparison.zlecero.items'),
            'pilotFields' => __('static-pages::home.pilot.fields'),
            'faqItems' => __('static-pages::home.faq.items'),
            'footerColumns' => __('static-pages::home.footer.columns'),
        ]);
    }

    private function normalizeLocale(mixed $locale): string
    {
        if (is_string($locale) && in_array($locale, $this->enabledLocales(), true)) {
            return $locale;
        }

        return 'pl';
    }

    /**
     * @return list<array{locale: string, label: string, href: string, active: bool}>
     */
    private function languageItems(string $currentLocale): array
    {
        return array_map(
            fn (string $locale): array => [
                'locale' => $locale,
                'label' => strtoupper($locale),
                'href' => route('static-pages.home', ['locale' => $locale]),
                'active' => $locale === $currentLocale,
            ],
            $this->enabledLocales(),
        );
    }

    /**
     * @return list<string>
     */
    private function enabledLocales(): array
    {
        $enabledLocales = config('core::languages.enabled_system_languages', self::FALLBACK_LOCALES);

        if (!is_array($enabledLocales)) {
            return self::FALLBACK_LOCALES;
        }

        return array_values(array_filter($enabledLocales, is_string(...)));
    }
}
