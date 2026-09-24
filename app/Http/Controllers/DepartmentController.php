<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    public function index(): View
    {
        abort_unless(Auth::user()->hasRole('Super Admin'), 403);

        return view('departments.index');
    }
}
