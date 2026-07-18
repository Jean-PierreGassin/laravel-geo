<?php

namespace JeanPierreGassin\LaravelGeo\Tests\Feature;

use JeanPierreGassin\LaravelGeo\Tests\TestCase;

final class LlmsTxtDisabledTest extends TestCase
{
    /**
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);

        $app['config']->set('geo.llms_txt.enabled', false);
    }

    public function test_does_not_register_the_route_when_the_endpoint_is_disabled(): void
    {
        $this->get('/llms.txt')->assertNotFound();
    }
}
