<?php

declare(strict_types=1);

namespace JeanPierreGassin\LaravelGeo\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use JeanPierreGassin\LaravelGeo\Enums\GenerativeEngine;
use Symfony\Component\HttpFoundation\Response;

class DetectGenerativeEngine
{
    /**
     * The request attribute under which the resolved engine is stored.
     */
    public const ATTRIBUTE = 'geo.generative_engine';

    public function handle(Request $request, Closure $next): Response
    {
        $engine = GenerativeEngine::fromUserAgent($request->userAgent());
        if ($engine !== null) {
            $request->attributes->set(self::ATTRIBUTE, $engine);
        }

        return $next($request);
    }
}
