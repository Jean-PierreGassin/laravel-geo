<?php

namespace JeanPierreGassin\LaravelGeo\Support;

use JeanPierreGassin\LaravelGeo\Contracts\LlmsTxtRenderer;
use JeanPierreGassin\LaravelGeo\Data\SiteLink;
use JeanPierreGassin\LaravelGeo\Data\SiteProfile;
use JeanPierreGassin\LaravelGeo\Data\SiteSection;

class MarkdownLlmsTxtRenderer implements LlmsTxtRenderer
{
    public function render(SiteProfile $profile): string
    {
        $sectionBlocks = $profile->sections
            ->map(fn(SiteSection $section): string => $this->renderSection(section: $section));

        $blocks = collect(["# $profile->name"])
            ->push($profile->summary === null ? null : "> $profile->summary")
            ->push($profile->details)
            ->concat($sectionBlocks)
            ->filter(fn(?string $block): bool => $block !== null);

        return $blocks->implode("\n\n") . "\n";
    }

    private function renderSection(SiteSection $section): string
    {
        return $section->links
            ->map(fn(SiteLink $link): string => $this->renderLink(link: $link))
            ->prepend("## $section->heading")
            ->implode("\n");
    }

    private function renderLink(SiteLink $link): string
    {
        $linkLine = "- [$link->title]($link->url)";

        return $link->notes === null ? $linkLine : "$linkLine: $link->notes";
    }
}
