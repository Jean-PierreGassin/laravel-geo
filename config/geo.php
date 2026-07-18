<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Site profile
    |--------------------------------------------------------------------------
    |
    | The plain-language description of your site that is surfaced to
    | generative engines through the llms.txt endpoint. Keep the summary
    | short and factual; list the pages worth citing under "sections".
    |
    */

    'site' => [
        'name' => env('APP_NAME', 'Laravel'),

        'summary' => 'A short, plain-language description of what this site offers.',

        'details' => null,

        'sections' => [
            [
                'heading' => 'Documentation',
                'links' => [
                    [
                        'title' => 'Getting started',
                        'url' => '/docs',
                        'notes' => 'Installation and first steps',
                    ],
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | llms.txt endpoint
    |--------------------------------------------------------------------------
    |
    | Serves the site profile above as a Markdown document at the given path,
    | following the llmstxt.org convention. Disable to stop registering the
    | route entirely.
    |
    */

    'llms_txt' => [
        'enabled' => true,
        'path' => 'llms.txt',
    ],

    /*
    |--------------------------------------------------------------------------
    | Structured data
    |--------------------------------------------------------------------------
    |
    | The JSON-LD graph emitted by the @geo Blade directive. "type" is any
    | schema.org type; the remaining fields describe the entity the site
    | represents.
    |
    */

    'structured_data' => [
        'type' => 'Organization',
        'url' => env('APP_URL', 'http://localhost'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Engine detection
    |--------------------------------------------------------------------------
    |
    | When enabled, the geo.detect middleware tags incoming requests from known
    | AI crawlers so you can branch on request()->isFromGenerativeEngine().
    |
    */

    'engine_detection' => [
        'enabled' => true,
    ],

];
