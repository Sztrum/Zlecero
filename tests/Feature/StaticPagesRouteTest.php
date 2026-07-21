<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Vite;
use Illuminate\Support\HtmlString;
use Tests\TestCase;

class StaticPagesRouteTest extends TestCase
{
    public function test_root_redirects_to_default_locale(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/pl');
    }

    public function test_root_redirects_to_locale_from_cookie(): void
    {
        $response = $this
            ->withCookie('zlecero_locale', 'de')
            ->get('/');

        $response->assertRedirect('/de');
    }

    public function test_home_page_renders_static_pages_landing(): void
    {
        $this->fakeVite();

        $response = $this->get('/pl');

        $response
            ->assertOk()
            ->assertCookie('zlecero_locale', 'pl')
            ->assertSee('<html lang="pl">', false)
            ->assertSee('Każde zapytanie z maila', false)
            ->assertSee('uporządkowane zlecenie.', false)
            ->assertSee('Czy tak wygląda praca w Twojej firmie?', false)
            ->assertSee('Jak to działa?', false)
            ->assertSee('Dziś vs Zlecero', false)
            ->assertSee('href="' . route('static-pages.home', ['locale' => 'en']) . '"', false)
            ->assertSee('href="' . route('static-pages.home', ['locale' => 'de']) . '"', false);
    }

    public function test_home_page_renders_selected_language(): void
    {
        $this->fakeVite();

        $response = $this->get('/en');

        $response
            ->assertOk()
            ->assertCookie('zlecero_locale', 'en')
            ->assertSee('<html lang="en">', false)
            ->assertSee('Turn every email inquiry into an', false)
            ->assertSee('organized order.', false)
            ->assertSee('Today vs Zlecero', false);
    }

    public function test_unsupported_locale_returns_not_found(): void
    {
        $this->get('/es')->assertNotFound();
    }

    private function fakeVite(): void
    {
        $vite = new class extends Vite
        {
            public function __invoke($entrypoints, $buildDirectory = null): HtmlString
            {
                return new HtmlString('');
            }
        };

        $this->app->instance('vite', $vite);
        $this->app->instance(Vite::class, $vite);
    }
}
