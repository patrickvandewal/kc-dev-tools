<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'dev-tools' , 'middleware' => ['web']], static function () {
    Route::get('overview', ['uses' => 'DevToolsController@overview']);
    Route::post('overview', ['uses' => 'DevToolsController@overview']);
    Route::post('process', ['uses' => 'DevToolsController@process']);
});
