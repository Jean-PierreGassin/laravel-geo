<?php

declare(strict_types=1);

namespace JeanPierreGassin\LaravelGeo\Tests\Unit;

use JeanPierreGassin\LaravelGeo\Enums\GenerativeEngine;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class GenerativeEngineTest extends TestCase
{
    #[DataProvider('userAgents')]
    public function test_resolves_the_engine_whose_token_appears_in_the_user_agent(
        ?string $userAgent,
        ?GenerativeEngine $expected,
    ): void {
        $this->assertSame($expected, GenerativeEngine::fromUserAgent($userAgent));
    }

    /**
     * @return array<string, array{0: string|null, 1: GenerativeEngine|null}>
     */
    public static function userAgents(): array
    {
        return [
            'gptbot token' => ['Mozilla/5.0 (compatible; GPTBot/1.1; +https://openai.com/gptbot)', GenerativeEngine::GptBot],
            'claudebot token' => ['Mozilla/5.0 (compatible; ClaudeBot/1.0)', GenerativeEngine::Claude],
            'match is case-insensitive' => ['perplexitybot/1.0', GenerativeEngine::Perplexity],
            'regular browser' => ['Mozilla/5.0 (Macintosh) Chrome/125.0', null],
            'empty string' => ['', null],
            'null' => [null, null],
        ];
    }
}
