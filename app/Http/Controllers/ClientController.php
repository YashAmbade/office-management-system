<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;

class ClientController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Client::class);

        return view('clients.index');
    }

    public function show(Client $client): View
    {
        Gate::authorize('viewAny', Client::class);

        return view('clients.show', ['client' => $client]);
    }
    public function general(): View
    {
        Gate::authorize('viewAny', \App\Models\GeneralClient::class);

        return view('clients.general');
    }
}
