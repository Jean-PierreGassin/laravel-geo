# Laravel GEO

<p>
    <a href="https://packagist.org/packages/jeanpierregassin/laravel-geo"><img src="https://img.shields.io/packagist/v/jeanpierregassin/laravel-geo.svg?style=flat-square" alt="Latest Version on Packagist"></a>
    <a href="https://packagist.org/packages/jeanpierregassin/laravel-geo"><img src="https://img.shields.io/packagist/dt/jeanpierregassin/laravel-geo.svg?style=flat-square" alt="Total Downloads"></a>
    <a href="LICENSE.md"><img src="https://img.shields.io/packagist/l/jeanpierregassin/laravel-geo.svg?style=flat-square" alt="License"></a>
</p>

**Generative Engine Optimization (GEO) for Laravel**, the answer-engine equivalent of SEO. As
users increasingly ask ChatGPT, Claude, Perplexity, and Google AI instead of typing queries into a
search box, your application needs to describe itself to generative crawlers and adapt when one is
visiting. Laravel GEO gives you three tools to do exactly that:

- **`llms.txt` endpoint**: publish a structured, Markdown site profile for AI crawlers at
  `/llms.txt`, following the [llmstxt.org](https://llmstxt.org) convention.
- **JSON-LD structured data**: emit a schema.org graph into your `<head>` with a single Blade
  directive.
- **Generative engine detection**: identify requests from known AI crawlers and branch your
  responses on `request()->isFromGenerativeEngine()`.

## Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
  - [Publishing an `llms.txt` profile](#publishing-an-llmstxt-profile)
  - [Emitting structured data](#emitting-structured-data)
  - [Detecting generative engines](#detecting-generative-engines)
  - [The `Geo` facade](#the-geo-facade)
- [Testing](#testing)
- [Contributing](#contributing)
- [Security](#security)
- [Credits](#credits)
- [License](#license)

## Requirements

| Dependency | Version                       |
|------------|-------------------------------|
| PHP        | `^8.3`                        |
| Laravel    | `^11.0` \| `^12.0` \| `^13.0` |

## Installation

Install the package via Composer:

```bash
composer require jeanpierregassin/laravel-geo
```

The service provider and `Geo` facade are registered automatically through Laravel's package
discovery, so no further wiring is required.

Publish the configuration file to customise your site profile:

```bash
php artisan vendor:publish --tag=geo-config
```

To override the JSON-LD `<head>` markup, publish the views as well:

```bash
php artisan vendor:publish --tag=geo-views
```

## Configuration

The published `config/geo.php` file is organised into four sections:

| Key                | Purpose                                                                   |
|--------------------|---------------------------------------------------------------------------|
| `site`             | The plain-language profile served at `/llms.txt`.                         |
| `llms_txt`         | Whether the endpoint is registered, and at which path.                    |
| `structured_data`  | The schema.org type and canonical URL emitted by the `@geo` directive.    |
| `engine_detection` | Whether AI-crawler detection runs globally on the `web` middleware group. |

Every option is documented inline in the published file.

## Usage

### Publishing an `llms.txt` profile

Describe your site under the `site` key in `config/geo.php`. The profile is rendered as Markdown and
served at `/llms.txt`:

```php
'site' => [
    'name' => env('APP_NAME'),
    'summary' => 'What your product does, in one plain sentence.',
    'details' => 'An optional longer paragraph with additional context.',
    'sections' => [
        [
            'heading' => 'Documentation',
            'links' => [
                ['title' => 'Getting started', 'url' => '/docs', 'notes' => 'Start here'],
                ['title' => 'API reference', 'url' => '/docs/api'],
            ],
        ],
    ],
],
```

Change the route or disable the endpoint entirely under `llms_txt`:

```php
'llms_txt' => [
    'enabled' => true,
    'path' => 'llms.txt',
],
```

The route is named `geo.llms_txt`, so you can reference it with `route('geo.llms_txt')`.

### Emitting structured data

Add the `@geo` directive inside your layout's `<head>` to emit a schema.org JSON-LD graph:

```blade
<head>
    @geo
</head>
```

The entity type and canonical URL are configurable under `structured_data`:

```php
'structured_data' => [
    'type' => 'Organization', // any schema.org type
    'url' => env('APP_URL'),
],
```

The graph's `name` and `description` are drawn from your `site` profile, keeping both endpoints in
sync.

### Detecting generative engines

Engine detection is enabled by default and runs on the `web` group. Two request macros let you
branch on the visiting crawler anywhere you have the request:

```php
use JeanPierreGassin\LaravelGeo\Enums\GenerativeEngine;

if ($request->isFromGenerativeEngine()) {
    $engine = $request->generativeEngine();

    if ($engine === GenerativeEngine::ClaudeBot) {
        // Serve a citation-friendly response.
    }
}
```

Each engine also carries its `vendor()` and a `type()` so you can branch on
crawler _behaviour_ rather than a specific product. The type is the GEO-relevant
distinction: `Search` and `Agent` fetchers can cite and link back to your page,
while `Training` crawlers only ingest content and return no attribution.

```php
use JeanPierreGassin\LaravelGeo\Enums\GenerativeEngineType;

if ($request->generativeEngine()?->type() === GenerativeEngineType::Search) {
    // Invest in a rich, citation-friendly response for answer engines.
}
```

The `GenerativeEngine` enum recognises the following crawlers out of the box.
Only tokens that appear in a real `User-Agent` header are detected; robots.txt
opt-out tokens such as `Google-Extended` and `Applebot-Extended` are excluded
because they never reach the server as a header.

| Vendor       | User-Agent token        | Type       |
|--------------|-------------------------|------------|
| OpenAI       | `GPTBot`                | Training   |
| OpenAI       | `OAI-SearchBot`         | Search     |
| OpenAI       | `ChatGPT-User`          | Agent      |
| Anthropic    | `ClaudeBot`             | Training   |
| Anthropic    | `Claude-SearchBot`      | Search     |
| Anthropic    | `Claude-User`           | Agent      |
| Google       | `Google-CloudVertexBot` | Agent      |
| Google       | `Google-NotebookLM`     | Agent      |
| Perplexity   | `PerplexityBot`         | Search     |
| Perplexity   | `Perplexity-User`       | Agent      |
| Apple        | `Applebot`              | Search     |
| Microsoft    | `bingbot`               | Search     |
| Amazon       | `Amazonbot`             | Training   |
| Meta         | `meta-externalagent`    | Training   |
| Meta         | `meta-externalfetcher`  | Agent      |
| ByteDance    | `Bytespider`            | Training   |
| Mistral      | `MistralAI-User`        | Agent      |
| DuckDuckGo   | `DuckAssistBot`         | Search     |
| Common Crawl | `CCBot`                 | Training   |
| Cohere       | `cohere-ai`             | Training   |
| You.com      | `YouBot`                | Search     |

To opt out of the global behaviour, set `engine_detection.enabled` to `false` and apply the
`geo.detect` middleware alias to specific routes instead:

```php
Route::middleware('geo.detect')->group(function () {
    // Detection runs only on these routes.
});
```

### The `Geo` facade

Every capability is also available programmatically through the `Geo` facade:

```php
use JeanPierreGassin\LaravelGeo\Facades\Geo;

Geo::siteProfile();  // SiteProfile: the structured site profile
Geo::llmsTxt();      // string:      the rendered llms.txt Markdown document
Geo::schemaGraph();  // SchemaGraph: the schema.org graph advertised to engines
Geo::renderHead();   // string:      the JSON-LD <script> markup emitted by @geo
```

## Testing

```bash
composer install
composer test          # run the PHPUnit suite
composer check-style   # check coding style (PHP-CS-Fixer, dry run)
composer fix-style     # apply coding-style fixes
```

## Contributing

Contributions are welcome. Please ensure the test suite passes and the coding style is clean
(`composer test && composer check-style`) before opening a pull request.

## Security

If you discover a security vulnerability, please email
[jeanpierre.gassin@gmail.com](mailto:jeanpierre.gassin@gmail.com) rather than using the issue
tracker.

## Credits

- [Jean-Pierre Gassin](https://github.com/jeanpierregassin)
- [All Contributors](../../contributors)

## License

GNU General Public License v3.0 or later (GPL-3.0-or-later). See [License File](LICENSE.md) for more
information.
