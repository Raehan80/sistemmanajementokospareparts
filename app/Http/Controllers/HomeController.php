<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        return view('admin.dashboard');
    }
}
