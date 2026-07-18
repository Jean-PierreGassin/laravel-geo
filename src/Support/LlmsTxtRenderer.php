<?php

declare(strict_types=1);

namespace JeanPierreGassin\LaravelGeo\Support;

use JeanPierreGassin\LaravelGeo\Data\SiteLink;
use JeanPierreGassin\LaravelGeo\Data\SiteProfile;
use JeanPierreGassin\LaravelGeo\Data\SiteSection;

/**
 * Renders a SiteProfile as an llms.txt Markdown document following the
 * structure described at https://llmstxt.org.
 */
final class LlmsTxtRenderer
{
    public function render(SiteProfile $profile): string
    {
        $sectionBlocks = collect($profile->sections)
            ->map(fn (SiteSection $section): string => $this->renderSection($section));

        $blocks = collect(["# $profile->name"])
            ->push($profile->summary === null ? null : "> $profile->summary")
            ->push($profile->details)
            ->concat($sectionBlocks)
            ->filter(fn (?string $block): bool => $block !== null);

        return $blocks->implode("\n\n") . "\n";
    }

    private function renderSection(SiteSection $section): string
    {
        return collect($section->links)
            ->map(fn (SiteLink $link): string => $this->renderLink($link))
            ->prepend("## $section->heading")
            ->implode("\n");
    }

    private function renderLink(SiteLink $link): string
    {
        $linkLine = "- [$link->title]($link->url)";

        return $link->notes === null ? $linkLine : "$linkLine: $link->notes";
    }
}
