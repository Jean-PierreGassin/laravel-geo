<?php

declare(strict_types=1);

namespace JeanPierreGassin\LaravelGeo\Data;

final class SiteSection
{
    /**
     * @param array<int, SiteLink> $links
     */
    public function __construct(
        public readonly string $heading,
        public readonly array $links = [],
    ) {}

    /**
     * @param array{heading: string, links?: array<int, array<string, mixed>>} $section
     */
    public static function fromArray(array $section): self
    {
        return new self(
            heading: $section['heading'],
            links: array_map(
                fn (array $link): SiteLink => SiteLink::fromArray($link),
                $section['links'] ?? [],
            ),
        );
    }
}
