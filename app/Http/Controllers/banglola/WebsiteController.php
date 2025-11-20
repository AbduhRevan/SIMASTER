<?php

namespace App\Http\Controllers\Banglola;

use App\Http\Controllers\Controller;

class WebsiteController extends Controller
{
    public function index()
    {
        return view('banglola.website.index');
    }
}