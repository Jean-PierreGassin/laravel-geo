<?php

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
    public const string ATTRIBUTE = 'geo.generative_engine';

    public function handle(Request $request, Closure $next): Response
    {
        $engine = GenerativeEngine::fromUserAgent(userAgent: $request->userAgent());
        if ($engine !== null) {
            $request->attributes->set(key: self::ATTRIBUTE, value: $engine);
        }

        return $next($request);
    }
}
