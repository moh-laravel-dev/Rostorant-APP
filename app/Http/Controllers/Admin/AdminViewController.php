<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminViewController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function login()
    {
        return view('admin.login');
    }
}
