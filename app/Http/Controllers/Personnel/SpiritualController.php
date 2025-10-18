<?php

namespace App\Http\Controllers\Personnel;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SpiritualController extends Controller
{
    public function index()
    {
        $users = [
            'id' => 1,
            'code' => 'LH001',
            'name' => 'Linh hướng 1',
            'avatar' => 'https://via.placeholder.com/150',
            'position' => 'Linh hướng',
        ];
        return Inertia::render('personnel/spirituals/Index', [
            'users' => $users,
        ]);
    }
}
