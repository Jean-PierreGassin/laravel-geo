<?php

namespace JeanPierreGassin\LaravelGeo\Facades;

use Illuminate\Support\Facades\Facade;
use JeanPierreGassin\LaravelGeo\Data\SiteProfile;
use JeanPierreGassin\LaravelGeo\GeoManager;

/**
 * @method static SiteProfile siteProfile()
 * @method static string llmsTxt()
 * @method static array<string, mixed> structuredData()
 * @method static string renderHead()
 *
 * @see GeoManager
 */
class Geo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return GeoManager::class;
    }
}
