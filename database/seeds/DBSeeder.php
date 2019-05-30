<?php

use App\Helpers\CompareDiff;
use Illuminate\Database\Seeder;

class DBSeeder extends Seeder
{   
    protected $count =[
        'compare'=>20
    ];

    protected $compares = null;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {  
        Storage::delete(Storage::files('public/files'));

        $this->compares = factory(App\Compare::class,$this->count['compare'])->create();
        $this->admin();
        $this->results();
    }

    private function admin()
    {
        factory(App\User::class)->create([
            'name'=>'Assitance Professor',
            'email'=>'admin@mail.com'
        ]);
    }

    private function results()
    {   
        $this->compares->each(function($compare){
            factory(App\Result::class)->create([
                'compare_id'=>$compare->id,
                'content'=>CompareDiff::compareFiles(
                    storage_path('app/public/files/'.$compare->first_file),
                    storage_path('app/public/files/'.$compare->second_file)
                )
            ]);
        });
    }
}
