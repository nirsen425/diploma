<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmptyGroupController extends Controller
{
    public function showEmptyGroupPage()
    {
        return view('empty-group');
    }
}
