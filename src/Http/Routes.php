<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'dev-tools', 'middleware' => ['web']], static function () {
    Route::get('overview', ['uses' => 'KingsCode\\DevTools\\Http\\Controllers\\DevToolsController@auth']);
    Route::post('overview', ['uses' => 'KingsCode\\DevTools\\Http\\Controllers\\DevToolsController@auth']);

    Route::group(['prefix' => 'joij'], static function() {
        Route::get('overview', ['uses' => 'KingsCode\\DevTools\\Http\\Controllers\\Joij\\JoijDevToolsController@auth']);
        Route::post('overview', ['uses' => 'KingsCode\\DevTools\\Http\\Controllers\\Joij\\JoijDevToolsController@auth']);
    });
});