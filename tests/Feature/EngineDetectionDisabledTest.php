<?php

declare(strict_types=1);

namespace JeanPierreGassin\LaravelGeo\Tests\Feature;

use Illuminate\Http\Request;
use JeanPierreGassin\LaravelGeo\Tests\TestCase;

final class EngineDetectionDisabledTest extends TestCase
{
    /**
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);

        $app['config']->set('geo.engine_detection.enabled', false);
    }

    /**
     * @param  \Illuminate\Routing\Router  $router
     */
    protected function defineRoutes($router): void
    {
        $router->middleware('web')->get('/geo-probe', fn (Request $request): array => [
            'is_engine' => $request->isFromGenerativeEngine(),
        ]);
    }

    public function test_leaves_bot_requests_untagged_when_detection_is_disabled(): void
    {
        $response = $this->withHeader('User-Agent', 'Mozilla/5.0 (compatible; ClaudeBot/1.0)')
            ->getJson('/geo-probe');

        $response->assertOk();
        $response->assertJson(['is_engine' => false]);
    }
}
