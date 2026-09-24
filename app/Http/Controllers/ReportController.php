<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;

class ReportController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Task::class);

        return view('reports.employees');
    }
}
