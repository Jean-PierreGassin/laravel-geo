<?php

namespace JeanPierreGassin\LaravelGeo\Data;

final readonly class SiteLink
{
    public function __construct(
        public string $title,
        public string $url,
        public ?string $notes = null,
    ) {}
}
