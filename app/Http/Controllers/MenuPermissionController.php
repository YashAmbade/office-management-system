<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class MenuPermissionController extends Controller
{
    public function index(): View
    {
        return view('settings.permissions');
    }
}
