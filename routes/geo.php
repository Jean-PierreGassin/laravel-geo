<?php

use Illuminate\Support\Facades\Route;
use JeanPierreGassin\LaravelGeo\Http\Controllers\LlmsTxtController;

Route::get(config('geo.llms_txt.path'), LlmsTxtController::class)
    ->name('geo.llms_txt');
