<?php

namespace JeanPierreGassin\LaravelGeo\Enums;

enum GenerativeEngine: string
{
    case ChatGpt = 'chatgpt';
    case GptBot = 'gptbot';
    case OpenAiSearch = 'oai-searchbot';
    case Claude = 'claudebot';
    case Perplexity = 'perplexitybot';
    case GoogleAi = 'google-extended';
    case AppleIntelligence = 'applebot-extended';

    /**
     * The case-insensitive token that identifies this engine's crawler within
     * a User-Agent header.
     */
    public function userAgentToken(): string
    {
        return match ($this) {
            self::ChatGpt => 'ChatGPT-User',
            self::GptBot => 'GPTBot',
            self::OpenAiSearch => 'OAI-SearchBot',
            self::Claude => 'ClaudeBot',
            self::Perplexity => 'PerplexityBot',
            self::GoogleAi => 'Google-Extended',
            self::AppleIntelligence => 'Applebot-Extended',
        };
    }

    /**
     * Resolve the first engine whose crawler token appears in the given
     * User-Agent string, or null when none match.
     */
    public static function fromUserAgent(?string $userAgent): ?self
    {
        if ($userAgent === null || $userAgent === '') {
            return null;
        }

        return collect(self::cases())
            ->first(fn (self $engine): bool => stripos($userAgent, $engine->userAgentToken()) !== false);
    }
}
