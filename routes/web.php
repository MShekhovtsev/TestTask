<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/



Auth::routes();

Route::group(['middleware' => 'auth'], function (){

    Route::get('/', 'HomeController@index');
    //Route::resource('{model}/rest', 'RestfulController');

    Route::resource('calendar', 'CalendarController');
    Route::any('calendar/{method}', 'CalendarController@parseMethod')->where('method', '[a-z]+');

});

Route::bind('model', function($model){
    $class = 'App\Models\\' . ucfirst($model);
    if(class_exists($class)){
        return new $class;
    }
    throw new \Illuminate\Database\Eloquent\ModelNotFoundException($class);
});



