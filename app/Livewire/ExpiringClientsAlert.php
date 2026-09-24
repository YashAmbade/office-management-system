<?php

namespace App\Livewire;

use App\Models\Client;
use Livewire\Component;
use Livewire\Attributes\Computed;

class ExpiringClientsAlert extends Component
{
    public bool $showExpiringModal = false;

    public function mount()
    {
        // Only bother checking/loading anything for the right roles
        if (! auth()->check() || ! auth()->user()->hasRole(['Super Admin', 'Manager'])) {
            return;
        }

        // Avoid showing on every single page load / navigation.
        // Show once per session (or swap for a daily cache key, see note below).
        if (! session()->has('expiring_modal_shown') && $this->expiringClients->isNotEmpty()) {
            $this->showExpiringModal = true;
            session()->put('expiring_modal_shown', true);
        }
    }

    #[Computed]
    public function expiringClients()
    {
        if (! auth()->user()->hasRole(['Super Admin', 'Manager'])) {
            return collect();
        }

        return Client::where('status', '!=', 'inactive')
            ->whereNotNull('end_date')
            ->whereBetween('end_date', [now()->startOfDay(), now()->addDays(5)->endOfDay()])
            ->orderBy('end_date')
            ->get();
    }

    public function render()
    {
        return view('livewire.expiring-clients-alert');
    }
}
