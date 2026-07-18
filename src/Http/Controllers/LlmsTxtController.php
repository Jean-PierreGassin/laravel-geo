<?php

declare(strict_types=1);

namespace JeanPierreGassin\LaravelGeo\Http\Controllers;

use Illuminate\Http\Response;
use JeanPierreGassin\LaravelGeo\GeoManager;

final class LlmsTxtController
{
    public function __construct(
        private readonly GeoManager $geo,
    ) {}

    public function __invoke(): Response
    {
        return new Response(
            $this->geo->llmsTxt(),
            Response::HTTP_OK,
            ['Content-Type' => 'text/markdown; charset=UTF-8'],
        );
    }
}
