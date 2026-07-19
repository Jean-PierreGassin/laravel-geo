<?php

namespace JeanPierreGassin\LaravelGeo\Tests\Feature;

use JeanPierreGassin\LaravelGeo\Contracts\LlmsTxtRenderer;
use JeanPierreGassin\LaravelGeo\Data\SiteProfile;
use JeanPierreGassin\LaravelGeo\GeoManager;
use JeanPierreGassin\LaravelGeo\Tests\TestCase;

final class RendererBindingTest extends TestCase
{
    public function test_a_consumer_can_swap_the_renderer_through_the_container(): void
    {
        config()->set('geo.site.name', 'Acme');

        $this->app->bind(LlmsTxtRenderer::class, fn(): LlmsTxtRenderer => new class implements LlmsTxtRenderer {
            public function render(SiteProfile $profile): string
            {
                return "custom: $profile->name";
            }
        });

        $this->assertSame('custom: Acme', $this->app->make(GeoManager::class)->llmsTxt());
    }
}
