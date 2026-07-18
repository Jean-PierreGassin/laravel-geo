# Laravel GEO

Generative Engine Optimization (GEO) for Laravel — the SEO equivalent for AI answer
engines like ChatGPT, Claude, Perplexity, and Google AI. The package helps your app
describe itself to generative crawlers and adapt responses when one is visiting.

## Features

- **`llms.txt` endpoint** — serves a Markdown site profile at `/llms.txt` following the
  [llmstxt.org](https://llmstxt.org) convention.
- **JSON-LD structured data** — a `@geo` Blade directive that emits a schema.org graph.
- **Generative engine detection** — middleware that tags requests from known AI crawlers,
  exposed via `request()->isFromGenerativeEngine()` and `request()->generativeEngine()`.

## Installation

```bash
composer require jeanpierregassin/laravel-geo
```

The service provider and `Geo` facade are auto-discovered. Publish the config to customise
your site profile:

```bash
php artisan vendor:publish --tag=geo-config
```

## Usage

### llms.txt

Describe your site in `config/geo.php` under `site`; it is served at `/llms.txt`:

```php
'site' => [
    'name' => env('APP_NAME'),
    'summary' => 'What your product does, in one sentence.',
    'sections' => [
        [
            'heading' => 'Documentation',
            'links' => [
                ['title' => 'Getting started', 'url' => '/docs', 'notes' => 'Start here'],
            ],
        ],
    ],
],
```

### Structured data

Add the directive inside your layout's `<head>` to emit a JSON-LD graph:

```blade
<head>
    @geo
</head>
```

### Detecting generative engines

Engine detection is enabled by default and runs on the `web` group. Branch on it anywhere
you have the request:

```php
if ($request->isFromGenerativeEngine()) {
    $engine = $request->generativeEngine(); // GenerativeEngine enum, e.g. GenerativeEngine::Claude
}
```

To opt out of the global behaviour, set `engine_detection.enabled` to `false` and apply the
`geo.detect` middleware alias to specific routes instead.

## Testing

```bash
composer install
vendor/bin/phpunit
```

## License

MIT.
