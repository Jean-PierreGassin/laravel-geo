<?php

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
    private const string CONFIG_PATH = __DIR__ . '/../config/geo.php';

    public function register(): void
    {
        $this->mergeConfigFrom(path: self::CONFIG_PATH, key: 'geo');

        $this->app->singleton(GeoManager::class);
    }

    public function boot(): void
    {
        $this->loadViewsFrom(path: __DIR__ . '/../resources/views', namespace: 'geo');

        $this->registerRoutes();
        $this->registerBladeDirective();
        $this->registerRequestMacros();
        $this->registerEngineDetection();
        $this->registerPublishing();
    }

    private function registerRoutes(): void
    {
        if (!config('geo.llms_txt.enabled')) {
            return;
        }

        Route::middleware('web')->group(__DIR__ . '/../routes/geo.php');
    }

    private function registerBladeDirective(): void
    {
        Blade::directive(
            name: 'geo',
            handler: fn(): string => "<?php echo app('" . GeoManager::class . "')->renderHead(); ?>",
        );
    }

    private function registerRequestMacros(): void
    {
        Request::macro(
            name: 'generativeEngine',
            macro: fn(): ?GenerativeEngine => $this->attributes->get(DetectGenerativeEngine::ATTRIBUTE),
        );

        Request::macro(
            name: 'isFromGenerativeEngine',
            macro: fn(): bool => $this->attributes->get(DetectGenerativeEngine::ATTRIBUTE) !== null,
        );
    }

    /**
     * Registers the geo.detect middleware alias and, when engine detection is
     * enabled, appends the middleware to the web group.
     *
     * The middleware is appended through the HTTP kernel rather than the
     * router because the kernel re-syncs its own middleware groups onto the
     * router when it boots, which would otherwise drop a group pushed directly
     * onto the router.
     */
    private function registerEngineDetection(): void
    {
        $this->app->make(Router::class)
            ->aliasMiddleware(name: 'geo.detect', class: DetectGenerativeEngine::class);

        if (!config('geo.engine_detection.enabled')) {
            return;
        }

        $kernel = $this->app->make(HttpKernelContract::class);
        if ($kernel instanceof HttpKernel) {
            $kernel->appendMiddlewareToGroup(group: 'web', middleware: DetectGenerativeEngine::class);
        }
    }

    private function registerPublishing(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->publishes(paths: [
            self::CONFIG_PATH => config_path('geo.php'),
        ], groups: 'geo-config');

        $this->publishes(paths: [
            __DIR__ . '/../resources/views' => resource_path('views/vendor/geo'),
        ], groups: 'geo-views');
    }
}
