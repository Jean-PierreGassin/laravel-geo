<?php

declare(strict_types=1);

namespace JeanPierreGassin\LaravelGeo\Tests\Feature;

use JeanPierreGassin\LaravelGeo\GeoManager;
use JeanPierreGassin\LaravelGeo\Tests\TestCase;

final class GeoManagerTest extends TestCase
{
    public function test_builds_the_structured_data_graph_from_config(): void
    {
        config()->set('geo.site.name', 'Acme');
        config()->set('geo.site.summary', 'We sell widgets.');
        config()->set('geo.structured_data.type', 'Corporation');
        config()->set('geo.structured_data.url', 'https://acme.test');

        $this->assertSame([
            '@context' => 'https://schema.org',
            '@type' => 'Corporation',
            'name' => 'Acme',
            'description' => 'We sell widgets.',
            'url' => 'https://acme.test',
        ], $this->app->make(GeoManager::class)->structuredData());
    }

    public function test_renders_the_structured_data_as_a_json_ld_script_tag(): void
    {
        config()->set('geo.site.name', 'Acme');

        $head = $this->app->make(GeoManager::class)->renderHead();

        $this->assertStringContainsString('application/ld+json', $head);
        $this->assertStringContainsString('"name": "Acme"', $head);
    }
}
