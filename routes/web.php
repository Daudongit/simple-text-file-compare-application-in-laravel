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

Route::get('/', function (Faker\Generator $faker) {
    //dd(str_random());
    //dd(implode('\n',explode('.',$faker->paragraph)));
    $text = explode('.',$faker->paragraph(12));
    array_pop($text);
    //shuffle($text);
    $text1 = $text;
    shuffle($text);
    shuffle($text);
    $text2 = $text;
    file_put_contents(public_path('file/file1.txt'),implode("\n",$text1));
    file_put_contents(public_path('file/file2.txt'),implode("\n",$text2));
    // $file = fopen(public_path("test.txt"),"x+");
    // echo fwrite($file,"Hello World. Testing!");
    // fclose($file);
    dd('Check it out');
    //return view('welcome');
});

Route::post('test',function(Request $request,CompareDiff $compare){
    //dd($request->all());
    //dd($compare->compareFiles($request->first_file,$request->second_file));
    //dd(CompareDiff::toString(CompareDiff::compareFiles($request->first_file,$request->second_file)));
    //dd(CompareDiff::toHTML(CompareDiff::compareFiles($request->first_file,$request->second_file)));
    //dd(CompareDiff::toTable(CompareDiff::compareFiles($request->first_file,$request->second_file)));
    $results = CompareDiff::toTableAndCount(CompareDiff::compareFiles($request->first_file,$request->second_file));
    return view('home',compact('results'));
})->name('test');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
