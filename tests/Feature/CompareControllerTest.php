<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Helpers\CompareDiff;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
class CompareControllerTest extends TestCase
{   
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_that_an_authorised_user_can_compare_text_files()
    {   
        //Storage::fake('public');

        $this->signIn();

        $compare = factory('App\Compare')->create();
        // $compare = factory('App\Compare')->create([
        //     'first_file'=>file_get_contents(storage_path('app/public/files/'.$compare->first_file)),
        //     'second_file'=>file_get_contents(storage_path('app/public/files/'.$compare->second_file))
        // ]);
        $compare = factory('App\Compare')->create([
            'first_file'=>UploadedFile::fake()->create('file1.txt'),
            'second_file'=>UploadedFile::fake()->create('file2.txt')
        ]);

        //dd($compare->toArray());
        $diff = CompareDiff::compareFiles(
            storage_path('app/public/files/'.$compare->first_file),
            storage_path('app/public/files/'.$compare->second_file)
         );

        dd($this->post(route('compare.store'),$compare->toArray()));
            //->assertSee($diff[0][0]);

        $this->assertDatabaseHas('compares',['id'=>$compare->id]);
            
        //$this->assertDatabaseHas('results',['compare_id'=>$compare->id]);

    }
}
