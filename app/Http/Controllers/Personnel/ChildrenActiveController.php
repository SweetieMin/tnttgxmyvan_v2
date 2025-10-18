<?php

namespace App\Http\Controllers\Personnel;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChildrenActiveController extends Controller
{
    public function index()
    {
        return Inertia::render('personnel/children-active/Index', [
            
        ]);
    }
}
