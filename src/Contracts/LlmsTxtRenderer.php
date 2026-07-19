<?php

namespace JeanPierreGassin\LaravelGeo\Contracts;

use JeanPierreGassin\LaravelGeo\Data\SiteProfile;

interface LlmsTxtRenderer
{
    /**
     * Render the site profile as an llms.txt Markdown document following the
     * structure described at https://llmstxt.org.
     */
    public function render(SiteProfile $profile): string;
}
