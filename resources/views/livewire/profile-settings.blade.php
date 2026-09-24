<div class="p-3 md:p-5 space-y-4 pb-20! lg:pb-5!">
    {{-- Header --}}
    <div class="bg-surface border border-border rounded-2xl p-4 md:p-5">
        <h2 class="text-base font-semibold text-heading">Settings</h2>
        <p class="text-sm text-muted mt-0.5">Manage your account preferences</p>
    </div>

    <div data-tabs class="grid grid-cols-1 xl:grid-cols-[240px_1fr] gap-4 items-start">
        {{-- Tab nav --}}
        <aside class="bg-surface border border-border rounded-2xl p-2 flex xl:flex-col gap-1 overflow-x-auto">
            <button
                data-tab="profile"
                data-active="true"
                class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium text-text-secondary whitespace-nowrap data-[active=true]:bg-primary-soft data-[active=true]:text-primary hover:bg-subtle transition-colors"
            >
                <i class="ph ph-user text-lg"></i>Profile
            </button>
            <button
                data-tab="security"
                class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium text-text-secondary whitespace-nowrap data-[active=true]:bg-primary-soft data-[active=true]:text-primary hover:bg-subtle transition-colors" style="display: none;"
            >
                <i class="ph ph-lock-key text-lg"></i>Security
            </button>
        </aside>

        {{-- Panels --}}
        <div class="space-y-4">
            {{-- Profile panel --}}
            <div data-tab-panel="profile" class="bg-surface border border-border rounded-2xl p-4 md:p-5">
                <h3 class="text-sm font-semibold text-heading mb-4">Profile Information</h3>

                <div class="flex items-center gap-4 mb-5">
                    <span class="w-16 h-16 rounded-full bg-primary/10 text-primary text-lg font-semibold flex items-center justify-center flex-shrink-0">
                        {{ strtoupper(substr($name, 0, 2)) }}
                    </span>
                    <div>
                        <p class="text-sm font-medium text-heading">{{ auth()->user()->employee_code }}</p>
                        <p class="text-xs text-muted mt-0.5">{{ auth()->user()->roles->pluck('name')->join(', ') }} · {{ auth()->user()->department?->name ?? 'No department' }}</p>
                    </div>
                </div>

                @if($profileSaved)
                    <div class="mb-4 rounded-xl bg-success-soft text-success text-sm px-3.5 py-2.5">
                        Profile updated successfully.
                    </div>
                @endif

                <form wire:submit="updateProfile" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-text-secondary mb-1.5">Name</label>
                        <input
                            type="text" wire:model="name"
                            class="w-full h-10 px-3 rounded-lg bg-subtle border border-border text-sm text-text focus:outline-none focus:border-primary transition-colors"
                        />
                        @error('name') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-text-secondary mb-1.5">Email</label>
                        <input
                            type="email" wire:model="email"
                            class="w-full h-10 px-3 rounded-lg bg-subtle border border-border text-sm text-text focus:outline-none focus:border-primary transition-colors"
                        />
                        @error('email') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-text-secondary mb-1.5">Phone</label>
                        <input
                            type="tel" wire:model="phone"
                            class="w-full h-10 px-3 rounded-lg bg-subtle border border-border text-sm text-text focus:outline-none focus:border-primary transition-colors"
                        />
                        @error('phone') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-text-secondary mb-1.5">Department</label>
                        <input
                            type="text" value="{{ auth()->user()->department?->name ?? '-' }}" disabled
                            class="w-full h-10 px-3 rounded-lg bg-subtle border border-border text-sm text-muted cursor-not-allowed"
                        />
                        <p class="text-[11px] text-muted mt-1">Set by your administrator.</p>
                    </div>

                    <div class="sm:col-span-2 flex flex-wrap justify-end gap-2 pt-1">
                        <button type="submit" class="h-10 px-4 rounded-lg bg-primary text-white text-sm font-medium hover:bg-primary-strong transition-colors">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            {{-- Security panel --}}
            <div data-tab-panel="security" class="hidden space-y-4">
                <div class="bg-surface border border-border rounded-2xl p-4 md:p-5">
                    <h3 class="text-sm font-semibold text-heading mb-4">Change Password</h3>

                    @if($passwordSaved)
                        <div class="mb-4 rounded-xl bg-success-soft text-success text-sm px-3.5 py-2.5">
                            Password changed successfully.
                        </div>
                    @endif

                    <form wire:submit="updatePassword" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-medium text-text-secondary mb-1.5">Current Password</label>
                            <input
                                type="password" wire:model="currentPassword" autocomplete="current-password"
                                placeholder="Enter current password"
                                class="w-full h-10 px-3 rounded-lg bg-subtle border border-border text-sm text-text placeholder:text-faint focus:outline-none focus:border-primary transition-colors"
                            />
                            @error('currentPassword') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-text-secondary mb-1.5">New Password</label>
                            <input
                                type="password" wire:model="newPassword" autocomplete="new-password"
                                placeholder="Enter new password"
                                class="w-full h-10 px-3 rounded-lg bg-subtle border border-border text-sm text-text placeholder:text-faint focus:outline-none focus:border-primary transition-colors"
                            />
                            @error('newPassword') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-text-secondary mb-1.5">Confirm New Password</label>
                            <input
                                type="password" wire:model="newPasswordConfirmation" autocomplete="new-password"
                                placeholder="Re-enter new password"
                                class="w-full h-10 px-3 rounded-lg bg-subtle border border-border text-sm text-text placeholder:text-faint focus:outline-none focus:border-primary transition-colors"
                            />
                        </div>

                        <div class="sm:col-span-2 flex flex-wrap justify-end gap-2 pt-1">
                            <button type="submit" class="h-10 px-4 rounded-lg bg-primary text-white text-sm font-medium hover:bg-primary-strong transition-colors">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
