<?php

declare(strict_types=1);

namespace Bondalex96\JsonAsserter;

use Coduo\PHPMatcher\Matcher;
use Exception;
use PHPUnit\Framework\Assert;

final class JsonAsserter extends Assert
{
    private Matcher $matcher;
    private JsonPrettyfier $jsonPrettyfier;

    public function __construct()
    {
        //TODO: use dependency injection
        $this->matcher = (new CoduoMatcherFactory())->create();
        $this->jsonPrettyfier = new JsonPrettyfier();
    }

    /**
     * Asserts actual content satisfy expected content.
     *
     * @param $actualContent
     * @param $expectedContent
     * @throws Exception
     */
    public function assertJsonContent(string $actualContent, string $expectedContent): void
    {
        $actualContent = $this->jsonPrettyfier->prettifyJson($actualContent);
        $expectedContent = $this->jsonPrettyfier->prettifyJson($expectedContent);

        $result = $this->matcher->match($actualContent, $expectedContent);

        if (!$result) {
            self::reportAboutFail($actualContent, $expectedContent, $this->matcher->getError());
        }
    }

    private static function reportAboutFail(
        string $actualContent,
        string $expectedContent,
        string $error
    ): void {
        $diff = new \Diff(
            explode(PHP_EOL, $expectedContent),
            explode(PHP_EOL, $actualContent),
            []
        );

        self::fail($error . PHP_EOL . $diff->render(new \Diff_Renderer_Text_Unified()));
    }
}