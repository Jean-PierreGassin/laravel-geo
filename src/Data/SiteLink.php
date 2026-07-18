<?php

namespace JeanPierreGassin\LaravelGeo\Data;

final class SiteLink
{
    public function __construct(
        public readonly string $title,
        public readonly string $url,
        public readonly ?string $notes = null,
    ) {
    }

    /**
     * @param array{title: string, url: string, notes?: string|null} $link
     */
    public static function fromArray(array $link): self
    {
        return new self(
            title: $link['title'],
            url: $link['url'],
            notes: $link['notes'] ?? null,
        );
    }
}
