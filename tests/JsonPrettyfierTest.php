<?php

declare(strict_types=1);

namespace Bondalex96\Tests;

use Bondalex96\JsonAsserter\JsonPrettyfier;
use PHPUnit\Framework\TestCase;

final class JsonPrettyfierTest extends TestCase
{
    private /*JsonPrettyfier*/ $jsonPrettyfier;

    public function setUp(): void
    {
        parent::setUp();
        $this->jsonPrettyfier = new JsonPrettyfier();
    }

    public function testSuccessful(): void
    {
        $prettifiedJson = $this->jsonPrettyfier->prettifyJson('{"id": 1234}');

        $expectedContent = "{\n    \"id\": 1234\n}";
        self::assertEquals(
            $expectedContent,
            $prettifiedJson
        );
    }

    public function testJsonWithExtraWhitespaces(): void
    {
        $prettifiedJson = $this->jsonPrettyfier->prettifyJson('    {"id": 1234}        ');

        $expectedContent = "{\n    \"id\": 1234\n}";
        self::assertEquals(
            $expectedContent,
            $prettifiedJson
        );
    }

    public function testInvalidJson(): void
    {
        $this->expectExceptionObject(
            new \Exception('Content "invalidJson" is not a json!')
        );

        $this->jsonPrettyfier->prettifyJson('invalidJson');
    }
}