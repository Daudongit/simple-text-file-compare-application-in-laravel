<?php

namespace App\Http\Controllers\Api;

use App\Compare;
use App\Helpers\CompareDiff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompareRequest;

class CompareController extends Controller
{
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
        
        return response()->json([
            'code'=>201,
            'message'=>'Added',
            'data'=>$diff
        ],201);
    }

    public function show(Compare $compare)
    {   
        return response()->json([
            'code'=>200,
            'message'=>'ok',
            'data'=>$compare->result->content
        ],200);
    }

    public function update(Compare $compare)
    {   
        $diff = CompareDiff::compareFiles(
            storage_path('app/public/files/'.$compare->first_file),
            storage_path('app/public/files/'.$compare->second_file)
        );
        
        return response()->json([
            'code'=>200,
            'message'=>'ok',
            'data'=>$diff
        ],200);
    }
}
