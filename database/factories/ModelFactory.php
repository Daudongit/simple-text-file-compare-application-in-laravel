<?php

use App\Helpers\CompareDiff;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Compare::class,function(Faker\Generator $faker){
   $files = dummyTextFile($faker);
   return [ 
       'first_student'=>$faker->name,
       'second_student'=>$faker->name,
       'first_file'=>$files[0],
       'second_file'=>$files[1]
    ];
});

$factory->define(App\Result::class,function(Faker\Generator $faker){
    return [
        'compare_id'=>function(){
            return factory(App\Compare::class)->create()->id;
        },
        'content'=>function(array $result){
            $compare = App\Compare::find($result['compare_id']);
           return CompareDiff::compareFiles(
                storage_path('app/public/files/'.$compare->first_file),
                storage_path('app/public/files/'.$compare->second_file)
           );
        }
    ];
});


function dummyTextFile($faker)
{
    $text = explode('.',$faker->paragraph(12));
    array_pop($text);
    $text1 = $text;
    shuffle($text);
    shuffle($text);
    $text2 = $text;
    $file1 = 'file'.str_random().'.txt';
    $file2 = 'file'.str_random().'.txt';
    file_put_contents(storage_path('app/public/files/'.$file1),implode("\n",$text1));
    file_put_contents(storage_path('app/public/files/'.$file2),implode("\n",$text2));
    return [$file1,$file2];
}