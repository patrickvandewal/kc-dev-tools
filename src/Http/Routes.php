<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'dev-tools', 'middleware' => ['web']], static function () {
    Route::get('overview', ['uses' => 'KingsCode\\DevTools\\Http\\Controllers\\DevToolsController@overview']);
    Route::post('process', ['uses' => 'KingsCode\\DevTools\\Http\\Controllers\\DevToolsController@process']);
});
