<?php

declare(strict_types=1);

namespace Bondalex96\JsonAsserter;

use Exception;

final class JsonPrettyfier
{
    /**
     * Форматирует данные в формате json, делая их более читаемыми.
     *
     * @param $content
     * @return string
     * @throws Exception
     */
    public function prettifyJson(string $content): string
    {
        $this->ensureValidJson($content);
        return json_encode(
            json_decode(trim($content)),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    private function ensureValidJson(string $content): void
    {
        if (!$this->isJson($content)) {
            throw new Exception('Content "invalidJson" is not a json!');
        }
    }
    private function isJson(string $content): bool {
        json_decode($content);
        return (json_last_error() == JSON_ERROR_NONE);
    }

}