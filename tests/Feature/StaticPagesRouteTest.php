<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Vite;
use Illuminate\Support\HtmlString;
use Tests\TestCase;

class StaticPagesRouteTest extends TestCase
{
    public function test_home_page_renders_static_pages_landing(): void
    {
        app()->setLocale('pl');

        $vite = new class extends Vite
        {
            public function __invoke($entrypoints, $buildDirectory = null): HtmlString
            {
                return new HtmlString('');
            }
        };

        $this->app->instance('vite', $vite);
        $this->app->instance(Vite::class, $vite);

        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('Każde zapytanie z maila', false)
            ->assertSee('uporządkowane zlecenie.', false)
            ->assertSee('Czy tak wygląda praca w Twojej firmie?', false)
            ->assertSee('Jak to działa?', false)
            ->assertSee('Dziś vs Zlecero', false);
    }
}
