<?php

declare(strict_types=1);

namespace JeanPierreGassin\LaravelGeo\Data;

final class SiteProfile
{
    /**
     * @param array<int, SiteSection> $sections
     */
    public function __construct(
        public readonly string $name,
        public readonly ?string $summary = null,
        public readonly ?string $details = null,
        public readonly array $sections = [],
    ) {
    }

    /**
     * @param array{
     *     name: string,
     *     summary?: string|null,
     *     details?: string|null,
     *     sections?: array<int, array<string, mixed>>
     * } $site
     */
    public static function fromConfig(array $site): self
    {
        return new self(
            name: $site['name'],
            summary: $site['summary'] ?? null,
            details: $site['details'] ?? null,
            sections: array_map(
                fn (array $section): SiteSection => SiteSection::fromArray($section),
                $site['sections'] ?? [],
            ),
        );
    }
}
