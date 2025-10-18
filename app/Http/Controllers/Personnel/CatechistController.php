<?php

namespace App\Http\Controllers\Personnel;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CatechistController extends Controller
{
    public function index()
    {
        return Inertia::render('personnel/catechists/Index', [
            
        ]);
    }
}
