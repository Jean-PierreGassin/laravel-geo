<?php

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
        $this->assertSame($expected, GenerativeEngine::fromUserAgent(userAgent: $userAgent));
    }

    /**
     * @return array<string, array{0: string|null, 1: GenerativeEngine|null}>
     */
    public static function userAgents(): array
    {
        return [
            'chatgpt-user token' => ['Mozilla/5.0 (compatible; ChatGPT-User/1.0)', GenerativeEngine::ChatGpt],
            'gptbot token' => ['Mozilla/5.0 (compatible; GPTBot/1.1)', GenerativeEngine::GptBot],
            'oai-searchbot token' => ['Mozilla/5.0 (compatible; OAI-SearchBot/1.0)', GenerativeEngine::OpenAiSearch],
            'claudebot token' => ['Mozilla/5.0 (compatible; ClaudeBot/1.0)', GenerativeEngine::Claude],
            'google-extended token' => ['Mozilla/5.0 (compatible; Google-Extended)', GenerativeEngine::GoogleAi],
            'applebot-extended token' => ['Mozilla/5.0 (Applebot-Extended)', GenerativeEngine::AppleIntelligence],
            'match is case-insensitive' => ['perplexitybot/1.0', GenerativeEngine::Perplexity],
            'regular browser' => ['Mozilla/5.0 (Macintosh) Chrome/125.0', null],
            'empty string' => ['', null],
            'null' => [null, null],
        ];
    }
}
