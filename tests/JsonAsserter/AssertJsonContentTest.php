<?php

declare(strict_types=1);

namespace Bondalex96\Tests\JsonAsserter;

use Bondalex96\JsonAsserter\JsonAsserter;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Bondalex96\JsonAsserter\JsonAsserter::assertJsonContent
 */
final class AssertJsonContentTest extends TestCase
{
    private JsonAsserter $jsonAsserter;

    public function setUp(): void
    {
        $this->jsonAsserter = new JsonAsserter();
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testUUIDPatternWithSatisfiableContent(): void
    {
        $this->jsonAsserter->assertJsonContent(
            '{"id": "e309cf72-e288-473f-9c5a-e2da3eaf3653"}',
            '{"id": "@uuid@"}'
        );
    }

    public function testUUIDPatternWithNotSatisfiableContent(): void
    {
        $expectedExceptionMessage = <<<EOF
Value {"id":"invalid-id"} does not match pattern {"id":"@uuid@"}
@@ -1,3 +1,3 @@
 {
-    "id": "@uuid@"
+    "id": "invalid-id"
 }
EOF;

        $this->expectExceptionObject(
            new AssertionFailedError($expectedExceptionMessage)
        );
        $this->jsonAsserter->assertJsonContent(
            '{"id": "invalid-id"}',
            '{"id": "@uuid@"}'
        );
    }


    /**
     * @doesNotPerformAssertions
     */
    public function testStringPatternWithSatisfiableContent(): void
    {
        $this->jsonAsserter->assertJsonContent(
            '{"item": "string"}',
            '{"item": "@string@"}'
        );
    }

    public function testStringPatternWithNotSatisfiableContent(): void
    {
        $expectedExceptionMessage = <<<EOF
Value {"item":1234} does not match pattern {"item":"@string@"}
@@ -1,3 +1,3 @@
 {
-    "item": "@string@"
+    "item": 1234
 }
EOF;

        $this->expectExceptionObject(
            new AssertionFailedError($expectedExceptionMessage)
        );

        $this->jsonAsserter->assertJsonContent(
            '{"item": 1234}',
            '{"item": "@string@"}'
        );
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testIntegerPatternWithSatisfiableContent(): void
    {
        $this->jsonAsserter->assertJsonContent(
            '{"item": 1234}',
            '{"item": "@integer@"}'
        );
    }

    public function testIntegerNotPatternWithSatisfiableContent(): void
    {
        $expectedExceptionMessage = <<<EOF
Value {"item":1234} does not match pattern {"item":"@string@"}
@@ -1,3 +1,3 @@
 {
-    "item": "@string@"
+    "item": 1234
 }
EOF;

        $this->expectExceptionObject(
            new AssertionFailedError($expectedExceptionMessage)
        );

        $this->jsonAsserter->assertJsonContent(
            '{"item": 1234}',
            '{"item": "@string@"}'
        );
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testDoublePatternWithSatisfiableContent(): void
    {
        $this->jsonAsserter->assertJsonContent(
            '{"item": 12.34}',
            '{"item": "@double@"}'
        );
    }

    public function testDoublePatternWithNotSatisfiableContent(): void
    {
        $expectedExceptionMessage = <<<EOF
Value {"item":1234} does not match pattern {"item":"@double@"}
@@ -1,3 +1,3 @@
 {
-    "item": "@double@"
+    "item": 1234
 }
EOF;

        $this->expectExceptionObject(
            new AssertionFailedError($expectedExceptionMessage)
        );

        $this->jsonAsserter->assertJsonContent(
            '{"item": 1234}',
            '{"item": "@double@"}'
        );
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testBooleanPatternWithSatisfiableContent(): void
    {
        $this->jsonAsserter->assertJsonContent(
            '{"item": true}',
            '{"item": "@boolean@"}'
        );
    }

    public function testBooleanPatternWithNotSatisfiableContent(): void
    {
        $expectedExceptionMessage = <<<EOF
Value {"item":"notBoolean"} does not match pattern {"item":"@boolean@"}
@@ -1,3 +1,3 @@
 {
-    "item": "@boolean@"
+    "item": "notBoolean"
 }
EOF;

        $this->expectExceptionObject(
            new AssertionFailedError($expectedExceptionMessage)
        );

        $this->jsonAsserter->assertJsonContent(
            '{"item": "notBoolean"}',
            '{"item": "@boolean@"}'
        );
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testArrayPatternWithSatisfiableContent(): void
    {
        $this->jsonAsserter->assertJsonContent(
            '{"item": [1,2,3]}',
            '{"item": "@array@"}'
        );
    }

    public function testArrayPatternWithNotSatisfiableContent(): void
    {
        $expectedExceptionMessage = <<<EOF
Value {"item":"notArray"} does not match pattern {"item":"@array@"}
@@ -1,3 +1,3 @@
 {
-    "item": "@array@"
+    "item": "notArray"
 }
EOF;

        $this->expectExceptionObject(
            new AssertionFailedError($expectedExceptionMessage)
        );

        $this->jsonAsserter->assertJsonContent(
            '{"item": "notArray"}',
            '{"item": "@array@"}'
        );
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testUnboundedArrayPatternWithSatisfiableContent(): void
    {
        $this->jsonAsserter->assertJsonContent(
            '{"item": [1,2,3]}',
            '{"item": "@...@"}'
        );
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testWildcardPatternWithSatisfiableContent(): void
    {
        $this->jsonAsserter->assertJsonContent(
            '{"item": "something"}',
            '{"item": "@*@"}'
        );
    }
}