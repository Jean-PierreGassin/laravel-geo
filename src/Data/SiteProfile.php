<?php

namespace JeanPierreGassin\LaravelGeo\Data;

use JeanPierreGassin\LaravelGeo\Collections\SiteSectionCollection;

final readonly class SiteProfile
{
    public function __construct(
        public string $name,
        public ?string $summary = null,
        public ?string $details = null,
        public SiteSectionCollection $sections = new SiteSectionCollection(),
    ) {}
}
