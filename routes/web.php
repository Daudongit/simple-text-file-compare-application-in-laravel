<?php

use App\Helpers\CompareDiff;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('/',function () {
    return redirect(route('login'));
});

Route::group(['middleware'=>'auth'],function(){
    Route::get('compare','CompareController@create')->name('compare.create');
    Route::post('compare','CompareController@store')->name('compare.store');
    Route::get('compare/{compare}','CompareController@show')->name('compare.show');
    Route::put('compare/{compare}','CompareController@update')->name('compare.update');
    Route::get('history','HistoryController@index')->name('history.index');
});

//Route::get('/home', 'HomeController@index')->name('home');
