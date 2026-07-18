<?php

declare(strict_types=1);

namespace JeanPierreGassin\LaravelGeo\Http\Controllers;

use Illuminate\Http\Response;
use JeanPierreGassin\LaravelGeo\GeoManager;

final class LlmsTxtController
{
    public function __construct(
        private readonly GeoManager $geo,
    ) {
    }

    public function __invoke(): Response
    {
        return new Response(
            content: $this->geo->llmsTxt(),
            status: Response::HTTP_OK,
            headers: ['Content-Type' => 'text/markdown; charset=UTF-8'],
        );
    }
}
