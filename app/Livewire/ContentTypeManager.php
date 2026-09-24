<?php

namespace App\Livewire;

use App\Models\AppSetting;
use App\Models\ContentType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ContentTypeManager extends Component
{
    public bool $showModal = false;
    public string $newTypeName = '';

    #[Computed]
    public function types()
    {
        return ContentType::orderBy('name')->get();
    }

    #[Computed]
    public function tlAccessEnabled(): bool
    {
        return AppSetting::get('tl_can_create_content_types', '0') === '1';
    }

    public function open(): void
    {
        Gate::authorize('viewAny', ContentType::class);
        $this->newTypeName = '';
        $this->showModal = true;
    }

    public function addType(): void
    {
        Gate::authorize('create', ContentType::class);

        $this->validate(['newTypeName' => 'required|string|max:100|unique:content_types,name']);

        ContentType::create([
            'name' => trim($this->newTypeName),
            'is_system' => false,
            'created_by' => Auth::id(),
        ]);

        $this->newTypeName = '';
        unset($this->types);
        $this->dispatch('content-type-created');
    }

    public function toggleTlAccess(): void
    {
        Gate::authorize('toggleTeamLeadAccess', \App\Models\ContentType::class);

        $current = $this->tlAccessEnabled;
        AppSetting::set('tl_can_create_content_types', $current ? '0' : '1');
    }

    public function render()
    {
        return view('livewire.content-type-manager');
    }
}
