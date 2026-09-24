<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use App\Models\WorkLogClient;

class WorkLogController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->canAccessWorkLog(), 403);
        return view('work-log.index');
    }

    public function manageClients(): View
    {
        Gate::authorize('manage', WorkLogClient::class);
        return view('work-log.clients');
    }

    public function report(): View
    {
        abort_unless(auth()->user()->hasRole(['Super Admin', 'Manager']), 403);
        return view('work-log.report');
    }
}
