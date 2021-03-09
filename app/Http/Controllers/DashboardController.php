<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }
    public function home()
    {
        if (auth()->check()) {
            return view('dashboard');
        } else {
            return view('auth.login');
        }
    }
}
