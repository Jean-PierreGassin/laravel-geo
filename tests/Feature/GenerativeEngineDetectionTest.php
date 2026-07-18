<?php

namespace JeanPierreGassin\LaravelGeo\Tests\Feature;

use Illuminate\Http\Request;
use JeanPierreGassin\LaravelGeo\Tests\TestCase;

final class GenerativeEngineDetectionTest extends TestCase
{
    /**
     * @param  \Illuminate\Routing\Router  $router
     */
    protected function defineRoutes($router): void
    {
        $router->middleware('web')->get('/geo-probe', fn(Request $request): array => [
            'is_engine' => $request->isFromGenerativeEngine(),
            'engine' => $request->generativeEngine()?->value,
        ]);
    }

    public function test_tags_requests_from_a_known_generative_engine(): void
    {
        $response = $this->withHeader('User-Agent', 'Mozilla/5.0 (compatible; ClaudeBot/1.0)')
            ->getJson('/geo-probe');

        $response->assertOk();
        $response->assertJson([
            'is_engine' => true,
            'engine' => 'claudebot',
        ]);
    }

    public function test_leaves_ordinary_browser_requests_untagged(): void
    {
        $response = $this->withHeader('User-Agent', 'Mozilla/5.0 (Macintosh) Chrome/125.0')
            ->getJson('/geo-probe');

        $response->assertOk();
        $response->assertJson([
            'is_engine' => false,
            'engine' => null,
        ]);
    }
}
