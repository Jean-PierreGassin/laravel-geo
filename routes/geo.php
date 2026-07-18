<?php

use Illuminate\Support\Facades\Route;
use JeanPierreGassin\LaravelGeo\Http\Controllers\LlmsTxtController;

Route::get(uri: config('geo.llms_txt.path'), action: LlmsTxtController::class)
    ->name('geo.llms_txt');
