<?php

namespace JeanPierreGassin\LaravelGeo\Enums;

enum GenerativeEngine: string
{
    // OpenAI
    case GptBot = 'gptbot';
    case OpenAiSearch = 'oai-searchbot';
    case ChatGpt = 'chatgpt-user';

    // Anthropic
    case ClaudeBot = 'claudebot';
    case ClaudeSearch = 'claude-searchbot';
    case ClaudeUser = 'claude-user';

    // Google
    case GoogleCloudVertex = 'google-cloudvertexbot';
    case GoogleNotebookLm = 'google-notebooklm';

    // Perplexity
    case PerplexityBot = 'perplexitybot';
    case PerplexityUser = 'perplexity-user';

    // Apple
    case Applebot = 'applebot';

    // Microsoft
    case BingBot = 'bingbot';

    // Amazon
    case Amazonbot = 'amazonbot';

    // Meta
    case MetaExternalAgent = 'meta-externalagent';
    case MetaExternalFetcher = 'meta-externalfetcher';

    // ByteDance
    case Bytespider = 'bytespider';

    // Mistral
    case MistralUser = 'mistralai-user';

    // DuckDuckGo
    case DuckAssist = 'duckassistbot';

    // Common Crawl
    case CommonCrawl = 'ccbot';

    // Cohere
    case Cohere = 'cohere-ai';

    // You.com
    case You = 'youbot';

    /**
     * The case-insensitive token that identifies this engine's crawler within
     * a User-Agent header. Only tokens that actually appear in a User-Agent
     * string are listed; robots.txt-only opt-out tokens (Google-Extended,
     * Applebot-Extended) are deliberately excluded because they never reach
     * the server as a header.
     */
    public function userAgentToken(): string
    {
        return match ($this) {
            self::GptBot => 'GPTBot',
            self::OpenAiSearch => 'OAI-SearchBot',
            self::ChatGpt => 'ChatGPT-User',
            self::ClaudeBot => 'ClaudeBot',
            self::ClaudeSearch => 'Claude-SearchBot',
            self::ClaudeUser => 'Claude-User',
            self::GoogleCloudVertex => 'Google-CloudVertexBot',
            self::GoogleNotebookLm => 'Google-NotebookLM',
            self::PerplexityBot => 'PerplexityBot',
            self::PerplexityUser => 'Perplexity-User',
            self::Applebot => 'Applebot',
            self::BingBot => 'bingbot',
            self::Amazonbot => 'Amazonbot',
            self::MetaExternalAgent => 'meta-externalagent',
            self::MetaExternalFetcher => 'meta-externalfetcher',
            self::Bytespider => 'Bytespider',
            self::MistralUser => 'MistralAI-User',
            self::DuckAssist => 'DuckAssistBot',
            self::CommonCrawl => 'CCBot',
            self::Cohere => 'cohere-ai',
            self::You => 'YouBot',
        };
    }

    /**
     * The vendor operating this crawler.
     */
    public function vendor(): string
    {
        return match ($this) {
            self::GptBot, self::OpenAiSearch, self::ChatGpt => 'OpenAI',
            self::ClaudeBot, self::ClaudeSearch, self::ClaudeUser => 'Anthropic',
            self::GoogleCloudVertex, self::GoogleNotebookLm => 'Google',
            self::PerplexityBot, self::PerplexityUser => 'Perplexity',
            self::Applebot => 'Apple',
            self::BingBot => 'Microsoft',
            self::Amazonbot => 'Amazon',
            self::MetaExternalAgent, self::MetaExternalFetcher => 'Meta',
            self::Bytespider => 'ByteDance',
            self::MistralUser => 'Mistral',
            self::DuckAssist => 'DuckDuckGo',
            self::CommonCrawl => 'Common Crawl',
            self::Cohere => 'Cohere',
            self::You => 'You.com',
        };
    }

    /**
     * How this crawler behaves: whether it feeds model training, backs a live
     * answer-engine index, or fetches on demand for a single user prompt.
     */
    public function type(): GenerativeEngineType
    {
        return match ($this) {
            self::GptBot,
            self::ClaudeBot,
            self::Amazonbot,
            self::MetaExternalAgent,
            self::Bytespider,
            self::CommonCrawl,
            self::Cohere => GenerativeEngineType::Training,

            self::OpenAiSearch,
            self::ClaudeSearch,
            self::PerplexityBot,
            self::Applebot,
            self::BingBot,
            self::DuckAssist,
            self::You => GenerativeEngineType::Search,

            self::ChatGpt,
            self::ClaudeUser,
            self::GoogleCloudVertex,
            self::GoogleNotebookLm,
            self::PerplexityUser,
            self::MetaExternalFetcher,
            self::MistralUser => GenerativeEngineType::Agent,
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

        foreach (self::cases() as $engine) {
            if (stripos($userAgent, $engine->userAgentToken()) !== false) {
                return $engine;
            }
        }

        return null;
    }
}
