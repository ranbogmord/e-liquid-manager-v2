<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    public function app(Request $request)
    {
        return view('app.app');
    }
}
