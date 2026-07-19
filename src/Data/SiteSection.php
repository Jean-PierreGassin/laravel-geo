<?php

namespace JeanPierreGassin\LaravelGeo\Data;

use JeanPierreGassin\LaravelGeo\Collections\SiteLinkCollection;

final readonly class SiteSection
{
    public function __construct(
        public string $heading,
        public SiteLinkCollection $links = new SiteLinkCollection(),
    ) {}
}
