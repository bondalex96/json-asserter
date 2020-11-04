<?php

declare(strict_types=1);

namespace Bondalex96\Tests;

use PHPUnit\Framework\TestCase;

final class SomeTest extends TestCase
{
    public function testSuccessful(): void
    {
        self::assertEquals(1, 1);
    }
}