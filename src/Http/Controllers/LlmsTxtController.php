<?php

namespace JeanPierreGassin\LaravelGeo\Http\Controllers;

use Illuminate\Http\Response;
use JeanPierreGassin\LaravelGeo\GeoManager;

readonly class LlmsTxtController
{
    private const string CONTENT_TYPE = 'text/markdown; charset=UTF-8';

    public function __construct(
        private GeoManager $geo,
    ) {}

    public function __invoke(): Response
    {
        return new Response(
            content: $this->geo->llmsTxt(),
            status: Response::HTTP_OK,
            headers: ['Content-Type' => self::CONTENT_TYPE],
        );
    }
}
