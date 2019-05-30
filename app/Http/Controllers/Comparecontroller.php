<?php

namespace App\Http\Controllers;

use App\Result;
use App\Compare;
use App\Helpers\CompareDiff;
use Illuminate\Http\Request;
use App\Http\Requests\CompareRequest;

class CompareController extends Controller
{
     public function create()
     {
        return view('index');
     }

     public function store(CompareRequest $request)
     {
         $firstFile = $request->first_file->store('public/files');
         $secondFile = $request->second_file->store('public/files');
         
         $compare = Compare::create([
            'first_student'=>$request->first_student,
            'second_student'=>$request->second_student,
            'first_file'=>str_replace('public/files/','',$firstFile),
            'second_file'=>str_replace('public/files/','',$secondFile)
         ]);
            
         $diff = CompareDiff::compareFiles(
            $request->first_file,$request->second_file
         );

         $compare->result()->create(['content'=>$diff]);
         
         $results = CompareDiff::toTableAndCount($diff);

         return view('index',compact('results'));
     }

     public function show(Compare $compare)
     {   
         $results = CompareDiff::toTableAndCount(
            $compare->result->content
         );

         return view('index',compact('results'));
     }

     public function update(Compare $compare)
     {   
         $diff = CompareDiff::compareFiles(
            storage_path('app/public/files/'.$compare->first_file),
            storage_path('app/public/files/'.$compare->second_file)
         );
         
         $results = CompareDiff::toTableAndCount($diff);

         return view('index',compact('results'));
     }
}
