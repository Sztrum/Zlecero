<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function test_string_contains_expected_value(): void
    {
        $value = sprintf('%sstan', 'php');

        $this->assertStringContainsString('php', $value);
    }
}
