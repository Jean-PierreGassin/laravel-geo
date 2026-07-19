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
            'gptbot token' => ['Mozilla/5.0 (compatible; GPTBot/1.4)', GenerativeEngine::GptBot],
            'oai-searchbot token' => ['Mozilla/5.0 (compatible; OAI-SearchBot/1.4)', GenerativeEngine::OpenAiSearch],
            'claudebot token' => ['Mozilla/5.0 (compatible; ClaudeBot/1.0)', GenerativeEngine::ClaudeBot],
            'claude-searchbot token' => ['Mozilla/5.0 (compatible; Claude-SearchBot/1.0)', GenerativeEngine::ClaudeSearch],
            'claude-user token' => ['Claude-User (claude-code/2.1.0; +https://support.anthropic.com/)', GenerativeEngine::ClaudeUser],
            'perplexity-user token' => ['Mozilla/5.0 (compatible; Perplexity-User/1.0)', GenerativeEngine::PerplexityUser],
            'google-cloudvertexbot token' => ['Mozilla/5.0 (compatible; Google-CloudVertexBot/1.0)', GenerativeEngine::GoogleCloudVertex],
            'applebot token' => ['Mozilla/5.0 (compatible; Applebot/0.1)', GenerativeEngine::Applebot],
            'amazonbot token' => ['Mozilla/5.0 (compatible; Amazonbot/0.1)', GenerativeEngine::Amazonbot],
            'meta-externalagent token' => ['meta-externalagent/1.1 (+https://developers.facebook.com/docs/sharing/webmasters/crawler)', GenerativeEngine::MetaExternalAgent],
            'bytespider token' => ['Mozilla/5.0 (Linux; Android 5.0) (compatible; Bytespider; https://zhanzhang.toutiao.com/)', GenerativeEngine::Bytespider],
            'ccbot token' => ['CCBot/2.0 (https://commoncrawl.org/faq/)', GenerativeEngine::CommonCrawl],
            'mistralai-user token' => ['Mozilla/5.0 (compatible; MistralAI-User/1.0)', GenerativeEngine::MistralUser],
            'match is case-insensitive' => ['perplexitybot/1.0', GenerativeEngine::PerplexityBot],
            'regular browser' => ['Mozilla/5.0 (Macintosh) Chrome/125.0', null],
            'empty string' => ['', null],
            'null' => [null, null],
        ];
    }
}
