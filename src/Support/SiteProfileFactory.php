<?php

namespace JeanPierreGassin\LaravelGeo\Support;

use JeanPierreGassin\LaravelGeo\Collections\SiteLinkCollection;
use JeanPierreGassin\LaravelGeo\Collections\SiteSectionCollection;
use JeanPierreGassin\LaravelGeo\Data\SiteLink;
use JeanPierreGassin\LaravelGeo\Data\SiteProfile;
use JeanPierreGassin\LaravelGeo\Data\SiteSection;

class SiteProfileFactory
{
    public function fromConfig(array $config): SiteProfile
    {
        return new SiteProfile(
            name: $config['name'],
            summary: $config['summary'] ?? null,
            details: $config['details'] ?? null,
            sections: $this->buildSections($config['sections'] ?? []),
        );
    }

    private function buildSections(array $sections): SiteSectionCollection
    {
        return new SiteSectionCollection(
            array_map(
                fn(array $section): SiteSection => $this->buildSection(section: $section),
                $sections,
            ),
        );
    }

    private function buildSection(array $section): SiteSection
    {
        return new SiteSection(
            heading: $section['heading'],
            links: $this->buildLinks($section['links'] ?? []),
        );
    }

    private function buildLinks(array $links): SiteLinkCollection
    {
        return new SiteLinkCollection(
            array_map(
                fn(array $link): SiteLink => $this->buildLink(link: $link),
                $links,
            ),
        );
    }

    private function buildLink(array $link): SiteLink
    {
        return new SiteLink(
            title: $link['title'],
            url: $link['url'],
            notes: $link['notes'] ?? null,
        );
    }
}
