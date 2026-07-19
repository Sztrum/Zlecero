<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_sanctum_csrf_endpoint_returns_no_content(): void
    {
        $response = $this->get('sanctum/csrf-cookie');

        $response->assertNoContent();
    }
}
