<?php

namespace JeanPierreGassin\LaravelGeo;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\View\Factory as ViewFactory;
use JeanPierreGassin\LaravelGeo\Contracts\LlmsTxtRenderer;
use JeanPierreGassin\LaravelGeo\Contracts\SchemaGraphRenderer;
use JeanPierreGassin\LaravelGeo\Data\SchemaGraph;
use JeanPierreGassin\LaravelGeo\Data\SiteProfile;
use JeanPierreGassin\LaravelGeo\Exceptions\SchemaGraphEncodingException;
use JeanPierreGassin\LaravelGeo\Support\SiteProfileFactory;

readonly class GeoManager
{
    public function __construct(
        private Repository $config,
        private ViewFactory $views,
        private LlmsTxtRenderer $llmsTxtRenderer,
        private SchemaGraphRenderer $schemaGraphRenderer,
        private SiteProfileFactory $siteProfiles,
    ) {}

    public function siteProfile(): SiteProfile
    {
        return $this->siteProfiles->fromConfig(config: $this->config->get(key: 'geo.site'));
    }

    /**
     * The site profile rendered as an llms.txt Markdown document.
     */
    public function llmsTxt(): string
    {
        return $this->llmsTxtRenderer->render(profile: $this->siteProfile());
    }

    /**
     * The schema.org graph advertised to generative engines.
     */
    public function schemaGraph(): SchemaGraph
    {
        return new SchemaGraph(
            type: $this->config->get(key: 'geo.structured_data.type'),
            name: $this->config->get(key: 'geo.site.name'),
            description: $this->config->get(key: 'geo.site.summary'),
            url: $this->config->get(key: 'geo.structured_data.url'),
        );
    }

    /**
     * Render the <head> markup (JSON-LD script tag) emitted by the @geo
     * Blade directive.
     *
     * @throws SchemaGraphEncodingException
     */
    public function renderHead(): string
    {
        return $this->views->make(view: 'geo::head', data: [
            'schemaGraphJson' => $this->schemaGraphRenderer->render(graph: $this->schemaGraph()),
        ])->render();
    }
}
