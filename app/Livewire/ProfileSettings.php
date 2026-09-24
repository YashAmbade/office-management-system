<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ProfileSettings extends Component
{
    // Profile tab
    public string $name = '';
    public string $email = '';
    public ?string $phone = null;

    // Security tab
    public string $currentPassword = '';
    public string $newPassword = '';
    public string $newPasswordConfirmation = '';

    public bool $profileSaved = false;
    public bool $passwordSaved = false;

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
    }

    public function updateProfile(): void
    {
        $user = Auth::user();

        $this->validate([
            'name' => 'required|string|max:191',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => 'nullable|string|max:30',
        ]);

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        $this->profileSaved = true;
        $this->dispatch('profile-updated');
    }

    public function updatePassword(): void
    {
        $user = Auth::user();

        $this->validate([
            'currentPassword' => 'required|string',
            'newPassword' => 'required|string|min:8|confirmed:newPasswordConfirmation',
        ]);

        if (! Hash::check($this->currentPassword, $user->password)) {
            $this->addError('currentPassword', 'Your current password is incorrect.');

            return;
        }

        $user->update(['password' => Hash::make($this->newPassword)]);

        $this->reset(['currentPassword', 'newPassword', 'newPasswordConfirmation']);
        $this->passwordSaved = true;
    }

    public function render()
    {
        return view('livewire.profile-settings');
    }
}
