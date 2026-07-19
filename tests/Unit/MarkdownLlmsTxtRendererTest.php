<?php

namespace JeanPierreGassin\LaravelGeo\Tests\Unit;

use JeanPierreGassin\LaravelGeo\Collections\SiteLinkCollection;
use JeanPierreGassin\LaravelGeo\Collections\SiteSectionCollection;
use JeanPierreGassin\LaravelGeo\Data\SiteLink;
use JeanPierreGassin\LaravelGeo\Data\SiteProfile;
use JeanPierreGassin\LaravelGeo\Data\SiteSection;
use JeanPierreGassin\LaravelGeo\Support\MarkdownLlmsTxtRenderer;
use PHPUnit\Framework\TestCase;

final class MarkdownLlmsTxtRendererTest extends TestCase
{
    public function test_renders_the_full_profile_as_llms_txt_markdown(): void
    {
        $profile = new SiteProfile(
            name: 'Acme',
            summary: 'We sell widgets.',
            details: 'Acme has shipped widgets since 1998.',
            sections: new SiteSectionCollection([
                new SiteSection(
                    heading: 'Docs',
                    links: new SiteLinkCollection([
                        new SiteLink(title: 'Guide', url: '/guide', notes: 'Start here'),
                        new SiteLink(title: 'API', url: '/api'),
                    ]),
                ),
            ]),
        );

        $expected = <<<'MARKDOWN'
        # Acme

        > We sell widgets.

        Acme has shipped widgets since 1998.

        ## Docs
        - [Guide](/guide): Start here
        - [API](/api)

        MARKDOWN;

        $this->assertSame($expected, (new MarkdownLlmsTxtRenderer())->render(profile: $profile));
    }

    public function test_omits_optional_blocks_when_the_profile_only_has_a_name(): void
    {
        $profile = new SiteProfile(name: 'Acme');

        $this->assertSame("# Acme\n", (new MarkdownLlmsTxtRenderer())->render(profile: $profile));
    }
}
