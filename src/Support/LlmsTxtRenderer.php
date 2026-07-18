<?php

declare(strict_types=1);

namespace JeanPierreGassin\LaravelGeo\Support;

use JeanPierreGassin\LaravelGeo\Data\SiteLink;
use JeanPierreGassin\LaravelGeo\Data\SiteProfile;

/**
 * Renders a SiteProfile as an llms.txt Markdown document following the
 * structure described at https://llmstxt.org.
 */
final class LlmsTxtRenderer
{
    public function render(SiteProfile $profile): string
    {
        $lines = ["# $profile->name"];

        if ($profile->summary !== null) {
            $lines[] = '';
            $lines[] = "> $profile->summary";
        }

        if ($profile->details !== null) {
            $lines[] = '';
            $lines[] = $profile->details;
        }

        foreach ($profile->sections as $section) {
            $lines[] = '';
            $lines[] = "## $section->heading";

            foreach ($section->links as $link) {
                $lines[] = $this->renderLink($link);
            }
        }

        return implode("\n", $lines)."\n";
    }

    private function renderLink(SiteLink $link): string
    {
        $linkLine = "- [$link->title]($link->url)";

        return $link->notes === null ? $linkLine : "$linkLine: $link->notes";
    }
}
