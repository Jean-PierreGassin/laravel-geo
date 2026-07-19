<?php

namespace JeanPierreGassin\LaravelGeo\Tests\Feature;

use Illuminate\Support\Facades\Blade;
use JeanPierreGassin\LaravelGeo\Exceptions\SchemaGraphEncodingException;
use JeanPierreGassin\LaravelGeo\GeoManager;
use JeanPierreGassin\LaravelGeo\Tests\TestCase;

final class GeoManagerTest extends TestCase
{
    public function test_builds_the_schema_graph_from_config(): void
    {
        config()->set('geo.site.name', 'Acme');
        config()->set('geo.site.summary', 'We sell widgets.');
        config()->set('geo.structured_data.type', 'Corporation');
        config()->set('geo.structured_data.url', 'https://acme.test');

        $schemaGraph = $this->app->make(GeoManager::class)->schemaGraph();

        $this->assertSame('Corporation', $schemaGraph->type);
        $this->assertSame('Acme', $schemaGraph->name);
        $this->assertSame('We sell widgets.', $schemaGraph->description);
        $this->assertSame('https://acme.test', $schemaGraph->url);
        $this->assertSame([
            '@context' => 'https://schema.org',
            '@type' => 'Corporation',
            'name' => 'Acme',
            'description' => 'We sell widgets.',
            'url' => 'https://acme.test',
        ], $schemaGraph->jsonSerialize());
    }

    public function test_renders_the_schema_graph_as_a_json_ld_script_tag(): void
    {
        config()->set('geo.site.name', 'Acme');

        $head = $this->app->make(GeoManager::class)->renderHead();

        $this->assertStringContainsString('application/ld+json', $head);
        $this->assertStringContainsString('"name":"Acme"', $head);
    }

    public function test_the_geo_blade_directive_emits_the_structured_data(): void
    {
        config()->set('geo.site.name', 'Acme');

        $rendered = Blade::render('<head>@geo</head>');

        $this->assertStringContainsString('application/ld+json', $rendered);
        $this->assertStringContainsString('"name":"Acme"', $rendered);
    }

    public function test_escapes_markup_in_config_so_it_cannot_break_out_of_the_script_tag(): void
    {
        config()->set('geo.site.name', 'Acme </script><script>alert(1)</script>');

        $head = $this->app->make(GeoManager::class)->renderHead();

        $this->assertStringNotContainsString('</script><script>', $head);
        $this->assertStringContainsString('</script>', $head);
    }

    public function test_throws_when_the_schema_graph_cannot_be_encoded(): void
    {
        config()->set('geo.site.name', "\xB1\x31");

        $this->expectException(SchemaGraphEncodingException::class);

        $this->app->make(GeoManager::class)->renderHead();
    }
}
