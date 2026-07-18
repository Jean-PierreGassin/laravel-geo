<?php

declare(strict_types=1);

namespace JeanPierreGassin\LaravelGeo;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\View\Factory as ViewFactory;
use JeanPierreGassin\LaravelGeo\Data\SiteProfile;
use JeanPierreGassin\LaravelGeo\Support\LlmsTxtRenderer;

class GeoManager
{
    public function __construct(
        private readonly Repository $config,
        private readonly ViewFactory $views,
        private readonly LlmsTxtRenderer $renderer,
    ) {}

    public function siteProfile(): SiteProfile
    {
        return SiteProfile::fromConfig($this->config->get('geo.site'));
    }

    /**
     * The site profile rendered as an llms.txt Markdown document.
     */
    public function llmsTxt(): string
    {
        return $this->renderer->render($this->siteProfile());
    }

    /**
     * The JSON-LD graph advertised to generative engines.
     *
     * @return array<string, mixed>
     */
    public function structuredData(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => $this->config->get('geo.structured_data.type'),
            'name' => $this->config->get('geo.site.name'),
            'description' => $this->config->get('geo.site.summary'),
            'url' => $this->config->get('geo.structured_data.url'),
        ];
    }

    /**
     * Render the <head> markup (JSON-LD script tag) emitted by the @geo
     * Blade directive.
     */
    public function renderHead(): string
    {
        return $this->views->make('geo::head', [
            'structuredData' => $this->structuredData(),
        ])->render();
    }
}
