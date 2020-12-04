<?php

declare(strict_types=1);

namespace Bondalex96\JsonAsserter;

use Coduo\PHPMatcher\Backtrace;
use Coduo\PHPMatcher\Lexer;
use Coduo\PHPMatcher\Matcher;
use Coduo\PHPMatcher\Matcher\ArrayMatcher;
use Coduo\PHPMatcher\Matcher\BooleanMatcher;
use Coduo\PHPMatcher\Matcher\ChainMatcher;
use Coduo\PHPMatcher\Matcher\DoubleMatcher;
use Coduo\PHPMatcher\Matcher\IntegerMatcher;
use Coduo\PHPMatcher\Matcher\JsonMatcher;
use Coduo\PHPMatcher\Matcher\NullMatcher;
use Coduo\PHPMatcher\Matcher\NumberMatcher;
use Coduo\PHPMatcher\Matcher\OrMatcher;
use Coduo\PHPMatcher\Matcher\ScalarMatcher;
use Coduo\PHPMatcher\Matcher\StringMatcher;
use Coduo\PHPMatcher\Matcher\UuidMatcher;
use Coduo\PHPMatcher\Matcher\WildcardMatcher;
use Coduo\PHPMatcher\Parser;

final class CoduoMatcherFactory
{
    private Backtrace $backtrace;

    public function __construct()
    {
        $this->backtrace = new Backtrace\VoidBacktrace();
    }

    public function create(): Matcher
    {
        $scalarMatchers = self::buildScalarMatchers();
        $orMatcher = new OrMatcher($this->backtrace, $scalarMatchers);
        $arrayMatcher = new ArrayMatcher(
            new ChainMatcher(
                'name',
                $this->backtrace,
                [
                    $orMatcher,
                    $scalarMatchers,
                ]
            ),
            $this->backtrace,
            self::buildParser($this->backtrace)
        );

        $chainMatcher = new ChainMatcher('name', $this->backtrace, [
            new JsonMatcher($arrayMatcher, $this->backtrace),
        ]);

        return new Matcher($chainMatcher, $this->backtrace);
    }

    /**
     * Инициализирует расширения-паттерны для проверки на совпадения
     * в json-строке.
     */
    private function buildScalarMatchers(): ChainMatcher
    {
        $backTrace = $this->backtrace;

        $parser = self::buildParser($backTrace);

        return new ChainMatcher(
            'name',
            $backTrace,
            [
                new NullMatcher($backTrace),
                new StringMatcher($backTrace, $parser),
                new IntegerMatcher($backTrace, $parser),
                new BooleanMatcher($backTrace, $parser),
                new DoubleMatcher($backTrace, $parser),
                new NumberMatcher($backTrace, $parser),
                new ScalarMatcher($backTrace),
                new WildcardMatcher($backTrace),
                new UuidMatcher($backTrace, $parser),
            ]
        );
    }

    /**
     * Инициализирует парсер для разбора строки.
     * @param Backtrace $backtrace
     * @return Parser
     */
    private static function buildParser(Backtrace $backtrace): Parser
    {
        return new Parser(new Lexer(), new Parser\ExpanderInitializer($backtrace));
    }
}