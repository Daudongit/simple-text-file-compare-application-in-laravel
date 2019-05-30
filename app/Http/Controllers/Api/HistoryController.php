<?php

namespace App\Http\Controllers\Api;

use App\Compare;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HistoryController extends Controller
{
    public function index()
    {   
        $histories = Compare::paginate(20);
        
        return response()->json([
            'code'=>200,
            'message'=>'ok',
            'data'=>$histories
        ],200);

    }
}
