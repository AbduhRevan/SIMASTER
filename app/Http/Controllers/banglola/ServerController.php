<?php

namespace App\Http\Controllers\Banglola;

use App\Http\Controllers\Controller;

class ServerController extends Controller
{
    public function index()
    {
        return view('banglola.server.index');
    }
}
