<?php

declare(strict_types=1);

namespace App\V1\Modules\StaticPages\UI\Http\Controllers;

use App\V1\Core\Domain\Domain\Services\FrontendEndpointService;
use App\V1\Core\Domain\Enums\FrontEndRouteEnum;
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

    public function __construct(
        private readonly FrontendEndpointService $frontendEndpointService
    ) {
    }

    public function redirectHome(Request $request): RedirectResponse
    {
        $locale = $request->cookie('zlecero_locale');

        return redirect('/'.(is_string($locale) && in_array($locale, self::LOCALES, true) ? $locale : 'pl'));
    }

    /**
     * @throws Throwable
     */
    public function redirectLogin(): RedirectResponse
    {
        return redirect()->away($this->frontendEndpointService->route(FrontEndRouteEnum::AUTH_LOGIN));
    }

    /**
     * @throws Throwable
     */
    public function redirectRegister(): RedirectResponse
    {
        return redirect()->away($this->frontendEndpointService->route(FrontEndRouteEnum::AUTH_REGISTER));
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
            'authLinks' => [
                'login' => $this->frontendEndpointService->route(FrontEndRouteEnum::AUTH_LOGIN),
                'register' => $this->frontendEndpointService->route(FrontEndRouteEnum::AUTH_REGISTER),
            ],
            'landingReference' => $this->landingReferenceContent(),
            'referencePricingComparison' => $this->referencePricingComparison(),
            'referenceContactCards' => $this->referenceContactCards(),
            'pricingPreview' => __('static_pages::messages.pages.pricing'),
            'faqPreview' => __('static_pages::messages.pages.faq'),
        ]);
    }

    /**
     * @return array{
     *     timeline: list<array{label: string, title: string, desc: string, status: string, icon: string}>,
     *     benefits: list<array{title: string, text: string}>,
     *     pricing: list<array{name: string, price: ?string, period: string, desc: string, features: list<string>, missing: list<string>, cta: string, highlight: bool}>,
     *     faqs: list<array{q: string, a: string}>
     * }
     */
    private function landingReferenceContent(): array
    {
        return [
            'timeline' => [
                ['label' => 'E-mail', 'title' => 'Wiadomość trafia do wspólnej skrzynki', 'desc' => 'Zlecero rozpoznaje nadawcę, temat i załączniki bez ręcznego przepisywania.', 'status' => 'Nowa wiadomość', 'icon' => 'mail'],
                ['label' => 'Zapytanie', 'title' => 'E-mail zamienia się w uporządkowane zapytanie', 'desc' => 'Klient, termin i kontekst sprawy są już w jednym miejscu.', 'status' => 'Nowe', 'icon' => 'inbox'],
                ['label' => 'Oferta', 'title' => 'Zespół przygotowuje ofertę na bazie danych', 'desc' => 'AI tworzy szkic, a zespół dopracowuje zakres i warunki.', 'status' => 'Szkic oferty', 'icon' => 'file'],
                ['label' => 'Akceptacja', 'title' => 'Klient akceptuje ofertę online', 'desc' => 'Decyzja klienta natychmiast trafia do historii sprawy.', 'status' => 'Zaakceptowana', 'icon' => 'check'],
                ['label' => 'Zlecenie', 'title' => 'Zaakceptowana oferta tworzy zlecenie', 'desc' => 'Zespół otrzymuje konkretny następny krok bez ręcznego przekazywania danych.', 'status' => 'Zlecenie utworzone', 'icon' => 'doc'],
                ['label' => 'Realizacja', 'title' => 'Realizacja pozostaje widoczna dla zespołu', 'desc' => 'Statusy, pliki i komunikacja są zawsze przypisane do jednego procesu.', 'status' => 'W realizacji', 'icon' => 'flow'],
            ],
            'benefits' => [
                ['title' => 'Wszystkie dane przy jednej sprawie', 'text' => 'Klient, wiadomości, oferta i pliki pozostają połączone od początku do końca.'],
                ['title' => 'Jasny następny krok dla zespołu', 'text' => 'Status i opiekun pokazują, kto odpowiada za dalszą pracę.'],
                ['title' => 'Pełna historia bez szukania w e-mailach', 'text' => 'Każda decyzja i zmiana jest widoczna w kontekście tej samej sprawy.'],
            ],
            'pricing' => [
                [
                    'name' => 'Light',
                    'price' => '20',
                    'period' => '/msc',
                    'desc' => 'Dla freelancerów — prosta ewidencja zapytań i ofert.',
                    'features' => ['1 użytkownik', 'Do 20 zapytań / msc', '1 skrzynka e-mail (IMAP)', 'Edytor ofert (PDF)', 'Baza klientów', 'Wsparcie e-mail'],
                    'missing' => ['AI asystent', 'Automatyzacje', 'Szablony', 'API', 'Raporty'],
                    'cta' => 'Wybierz plan',
                    'highlight' => false,
                ],
                [
                    'name' => 'Starter',
                    'price' => '99',
                    'period' => '/msc',
                    'desc' => 'Dla małych firm — kompletny proces od zapytania do oferty.',
                    'features' => ['Do 3 użytkowników', 'Do 100 zapytań / msc', '1 skrzynka e-mail', 'AI — automatyczny szkic oferty po zapytaniu', 'AI — wykrywanie brakujących danych', 'Edytor ofert + PDF + wersjonowanie', 'Baza klientów', 'Szablony wiadomości i ofert', 'Wsparcie e-mail'],
                    'missing' => ['Automatyzacje workflow', 'API', 'Raporty zaawansowane', 'Wiele skrzynek'],
                    'cta' => 'Wybierz plan',
                    'highlight' => false,
                ],
                [
                    'name' => 'Professional',
                    'price' => '249',
                    'period' => '/msc',
                    'desc' => 'Dla rosnących firm, które potrzebują AI i automatyzacji.',
                    'features' => ['Do 10 użytkowników', 'Nieograniczone zapytania', '3 skrzynki e-mail', 'AI asystent + szkice ofert', 'Automatyzacje i workflow', 'Warianty ofert', 'Integracje Gmail / Outlook', 'Raporty i statystyki', 'Dostęp do API', 'Wsparcie priorytetowe'],
                    'missing' => [],
                    'cta' => 'Wybierz plan',
                    'highlight' => true,
                ],
                [
                    'name' => 'Enterprise',
                    'price' => null,
                    'period' => '',
                    'desc' => 'Dla dużych organizacji z indywidualnymi wymaganiami.',
                    'features' => ['Nieograniczona liczba użytkowników', 'Dedykowany opiekun klienta', 'SLA i gwarancja dostępności', 'Własna domena i branding', 'SSO / Active Directory', 'Zaawansowane role i uprawnienia', 'Wdrożenie i szkolenia', 'Integracje na zamówienie', 'Backup on-premise'],
                    'missing' => [],
                    'cta' => 'Skontaktuj się',
                    'highlight' => false,
                ],
            ],
            'faqs' => [
                ['q' => 'Czy mogę przetestować Zlecero przed zakupem?', 'a' => 'Tak, oferujemy 14-dniowy okres próbny ze wszystkimi funkcjami planu Professional. Nie wymagamy karty kredytowej ani zobowiązania.'],
                ['q' => 'Jak działa integracja z e-mailem?', 'a' => 'Zlecero obsługuje Gmail, Outlook, Microsoft 365 oraz dowolny serwer IMAP/SMTP. Wszystkie wiadomości są widoczne bezpośrednio w systemie — z pełną historią rozmowy i automatycznym przypisywaniem do zapytań.'],
                ['q' => 'Czy AI asystent wymaga dodatkowej konfiguracji?', 'a' => 'Nie. AI jest gotowy od razu po zalogowaniu — analizuje przychodzące zapytania, tworzy podsumowania, wykrywa brakujące dane i proponuje wyceny na podstawie Twojego cennika lub wcześniejszych realizacji.'],
                ['q' => 'Jak bezpieczne są moje dane?', 'a' => 'Dane są przechowywane na serwerach w UE (RODO), szyfrowane AES-256. Oferujemy 2FA dla każdego konta, pełny dziennik aktywności oraz regularne kopie zapasowe.'],
                ['q' => 'Czy mogę zmienić plan w trakcie subskrypcji?', 'a' => 'Tak — zmiana planu jest możliwa w każdej chwili, zarówno w górę, jak i w dół. Rozliczenie jest proporcjonalne do wykorzystanego okresu.'],
                ['q' => 'Czy Zlecero obsługuje wiele oddziałów firmy?', 'a' => 'Tak, w planie Enterprise możliwe jest zarządzanie wieloma jednostkami organizacyjnymi z jednego konta administracyjnego.'],
                ['q' => 'Co się dzieje po zakończeniu okresu próbnego?', 'a' => 'Po 14 dniach możesz wybrać płatny plan lub konto przejdzie w tryb odczytu. Dane są zachowane przez 30 dni.'],
            ],
        ];
    }

    /**
     * @return list<array{feature: string, light: string, starter: string, professional: string, enterprise: string}>
     */
    private function referencePricingComparison(): array
    {
        return [
            ['feature' => 'Użytkownicy', 'light' => '1', 'starter' => '3', 'professional' => '10', 'enterprise' => 'Nielimitowani'],
            ['feature' => 'Zapytania / miesiąc', 'light' => '20', 'starter' => '100', 'professional' => 'Nieograniczone', 'enterprise' => 'Nieograniczone'],
            ['feature' => 'Skrzynki e-mail', 'light' => '1', 'starter' => '1', 'professional' => '3', 'enterprise' => 'Nieograniczone'],
            ['feature' => 'IMAP / SMTP', 'light' => '✓', 'starter' => '✓', 'professional' => '✓', 'enterprise' => '✓'],
            ['feature' => 'Gmail / Outlook', 'light' => '—', 'starter' => '—', 'professional' => '✓', 'enterprise' => '✓'],
            ['feature' => 'Baza klientów', 'light' => '✓', 'starter' => '✓', 'professional' => '✓', 'enterprise' => '✓'],
            ['feature' => 'Edytor ofert i PDF', 'light' => '✓', 'starter' => '✓', 'professional' => '✓', 'enterprise' => '✓'],
            ['feature' => 'Wersjonowanie ofert', 'light' => '—', 'starter' => '✓', 'professional' => '✓', 'enterprise' => '✓'],
            ['feature' => 'Szablony wiadomości i ofert', 'light' => '—', 'starter' => '✓', 'professional' => '✓', 'enterprise' => '✓'],
            ['feature' => 'Asystent AI', 'light' => '—', 'starter' => '✓', 'professional' => '✓', 'enterprise' => '✓'],
            ['feature' => 'AI — szkic oferty po zapytaniu', 'light' => '—', 'starter' => '✓', 'professional' => '✓', 'enterprise' => '✓'],
            ['feature' => 'AI — wykrywanie brakujących danych', 'light' => '—', 'starter' => '✓', 'professional' => '✓', 'enterprise' => '✓'],
            ['feature' => 'Warianty ofert', 'light' => '—', 'starter' => '—', 'professional' => '✓', 'enterprise' => '✓'],
            ['feature' => 'Automatyzacje workflow', 'light' => '—', 'starter' => '—', 'professional' => '✓', 'enterprise' => '✓'],
            ['feature' => 'Raporty i statystyki', 'light' => '—', 'starter' => '—', 'professional' => '✓', 'enterprise' => '✓'],
            ['feature' => 'Dostęp do API', 'light' => '—', 'starter' => '—', 'professional' => '✓', 'enterprise' => '✓'],
            ['feature' => 'Role i uprawnienia', 'light' => 'Podstawowe', 'starter' => 'Podstawowe', 'professional' => 'Zaawansowane', 'enterprise' => 'Zaawansowane'],
            ['feature' => 'Własna domena i branding', 'light' => '—', 'starter' => '—', 'professional' => '—', 'enterprise' => '✓'],
            ['feature' => 'SSO / Active Directory', 'light' => '—', 'starter' => '—', 'professional' => '—', 'enterprise' => '✓'],
            ['feature' => 'SLA i gwarancja dostępności', 'light' => '—', 'starter' => '—', 'professional' => '—', 'enterprise' => '✓'],
            ['feature' => 'Dedykowany opiekun klienta', 'light' => '—', 'starter' => '—', 'professional' => '—', 'enterprise' => '✓'],
            ['feature' => 'Wdrożenie i szkolenia', 'light' => '—', 'starter' => '—', 'professional' => 'Opcjonalnie', 'enterprise' => '✓'],
            ['feature' => 'Backup on-premise', 'light' => '—', 'starter' => '—', 'professional' => '—', 'enterprise' => '✓'],
        ];
    }

    /**
     * @return list<array{icon: string, label: string, value: string}>
     */
    private function referenceContactCards(): array
    {
        return [
            ['icon' => '✉', 'label' => 'E-mail', 'value' => 'kontakt@zlecero.pl'],
            ['icon' => '☎', 'label' => 'Telefon', 'value' => '+48 22 300 40 50'],
            ['icon' => '⌖', 'label' => 'Adres', 'value' => 'ul. Inflancka 4, 00-189 Warszawa'],
        ];
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
