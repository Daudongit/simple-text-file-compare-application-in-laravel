<?php

namespace App\Http\Controllers;

use App\Compare;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
     public function index()
     {   
         $histories = Compare::paginate(20);
         
        return view('history',\compact('histories'));
     }

}
