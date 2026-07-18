<?php

namespace JeanPierreGassin\LaravelGeo\Tests\Feature;

use JeanPierreGassin\LaravelGeo\Tests\TestCase;

final class LlmsTxtEndpointTest extends TestCase
{
    public function test_serves_the_site_profile_as_a_markdown_document(): void
    {
        config()->set('geo.site.name', 'Acme');
        config()->set('geo.site.summary', 'We sell widgets.');
        config()->set('geo.site.sections', []);

        $response = $this->get('/llms.txt');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/markdown; charset=UTF-8');
        $this->assertSame("# Acme\n\n> We sell widgets.\n", $response->getContent());
    }

    public function test_maps_configured_sections_and_links_into_the_document(): void
    {
        config()->set('geo.site.name', 'Acme');
        config()->set('geo.site.summary', null);
        config()->set('geo.site.sections', [
            [
                'heading' => 'Docs',
                'links' => [
                    ['title' => 'Guide', 'url' => '/guide', 'notes' => 'Start here'],
                    ['title' => 'API', 'url' => '/api'],
                ],
            ],
        ]);

        $response = $this->get('/llms.txt');

        $response->assertOk();
        $this->assertSame(
            "# Acme\n\n## Docs\n- [Guide](/guide): Start here\n- [API](/api)\n",
            $response->getContent(),
        );
    }
}
