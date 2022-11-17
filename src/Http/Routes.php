<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'dev-tools', 'middleware' => ['api']], static function () {
    Route::get('overview', ['uses' => 'KingsCode\\DevTools\\Http\\Controllers\\DevToolsController@overview']);
    Route::post('overview', ['uses' => 'KingsCode\\DevTools\\Http\\Controllers\\DevToolsController@overview']);

    Route::group(['prefix' => 'joij'], static function() {
        Route::get('overview', ['uses' => 'KingsCode\\DevTools\\Http\\Controllers\\Joij\\JoijDevToolsController@overview']);
        Route::post('overview', ['uses' => 'KingsCode\\DevTools\\Http\\Controllers\\Joij\\JoijDevToolsController@overview']);
    });

});