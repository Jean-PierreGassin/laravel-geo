<?php

declare(strict_types=1);

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
}
