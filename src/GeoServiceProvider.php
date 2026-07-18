<?php

declare(strict_types=1);

namespace JeanPierreGassin\LaravelGeo;

use Illuminate\Contracts\Http\Kernel as HttpKernelContract;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use JeanPierreGassin\LaravelGeo\Enums\GenerativeEngine;
use JeanPierreGassin\LaravelGeo\Http\Middleware\DetectGenerativeEngine;

class GeoServiceProvider extends ServiceProvider
{
    private const CONFIG_PATH = __DIR__.'/../config/geo.php';

    public function register(): void
    {
        $this->mergeConfigFrom(self::CONFIG_PATH, 'geo');

        $this->app->singleton(GeoManager::class);
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'geo');

        $this->registerRoutes();
        $this->registerBladeDirective();
        $this->registerRequestMacros();
        $this->registerEngineDetection();
        $this->registerPublishing();
    }

    private function registerRoutes(): void
    {
        if (! config('geo.llms_txt.enabled')) {
            return;
        }

        Route::middleware('web')->group(__DIR__.'/../routes/geo.php');
    }

    private function registerBladeDirective(): void
    {
        Blade::directive('geo', fn (): string => "<?php echo app('".GeoManager::class."')->renderHead(); ?>");
    }

    private function registerRequestMacros(): void
    {
        Request::macro(
            'generativeEngine',
            fn (): ?GenerativeEngine => $this->attributes->get(DetectGenerativeEngine::ATTRIBUTE),
        );

        Request::macro(
            'isFromGenerativeEngine',
            fn (): bool => $this->attributes->get(DetectGenerativeEngine::ATTRIBUTE) !== null,
        );
    }

    private function registerEngineDetection(): void
    {
        $this->app->make(Router::class)
            ->aliasMiddleware('geo.detect', DetectGenerativeEngine::class);

        if (! config('geo.engine_detection.enabled')) {
            return;
        }

        // Append through the HTTP kernel rather than the router: the kernel
        // re-syncs its own middleware groups onto the router when it boots,
        // which would otherwise drop a group pushed directly onto the router.
        $kernel = $this->app->make(HttpKernelContract::class);
        if ($kernel instanceof HttpKernel) {
            $kernel->appendMiddlewareToGroup('web', DetectGenerativeEngine::class);
        }
    }

    private function registerPublishing(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            self::CONFIG_PATH => config_path('geo.php'),
        ], 'geo-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/geo'),
        ], 'geo-views');
    }
}
