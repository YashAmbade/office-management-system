<div class="p-3 md:p-5 space-y-4">
    <div class="bg-surface border border-border rounded-2xl p-4 md:p-5 flex items-center justify-between gap-3">
        <div>
            <h2 class="text-base font-semibold text-heading">GMB Checklist Categories</h2>
            <p class="text-sm text-muted mt-0.5">Manage the columns that appear on the monthly checklist</p>
        </div>
        <button wire:click="openCategoryModal"
            class="inline-flex items-center gap-1.5 h-10 px-4 rounded-xl bg-primary text-white text-sm font-medium hover:bg-primary-strong transition-colors">
            <i class="ph ph-plus"></i>Add Category
        </button>
    </div>

    <div class="space-y-3">
        @forelse ($this->categories as $cat)
            <div wire:key="gmb-cat-{{ $cat->id }}"
                class="bg-surface border border-border rounded-2xl overflow-hidden">
                <div
                    class="flex items-center justify-between gap-3 px-4 py-3 bg-subtle/50 border-b border-border-subtle">
                    <div class="flex items-center gap-2">
                        <div class="flex flex-col">
                            <button wire:click="moveCategory({{ $cat->id }}, 'up')"
                                class="text-muted hover:text-heading"><i class="ph ph-caret-up text-xs"></i></button>
                            <button wire:click="moveCategory({{ $cat->id }}, 'down')"
                                class="text-muted hover:text-heading"><i class="ph ph-caret-down text-xs"></i></button>
                        </div>
                        <span class="w-2.5 h-2.5 rounded-full flex-shrink-0"
                            style="background-color: {{ $cat->color }};"></span>
                        <h3 class="text-sm font-semibold text-heading">{{ $cat->name }}</h3>
                    </div>
                    <div class="flex items-center gap-1">
                        <button wire:click="openSubcategoryModal({{ $cat->id }})"
                            class="h-8 px-3 rounded-lg text-xs font-medium text-primary hover:bg-primary-soft transition-colors">
                            <i class="ph ph-plus"></i> Subcategory
                        </button>
                        <button wire:click="openCategoryModal({{ $cat->id }})"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:text-primary"><i
                                class="ph ph-pencil-simple"></i></button>
                        <button wire:click="deleteCategory({{ $cat->id }})"
                            onclick="return confirm('Delete this category and ALL its subcategories? This also permanently deletes historical checkbox data tied to it.')"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:text-danger"><i
                                class="ph ph-trash"></i></button>
                    </div>
                </div>

                <div class="divide-y divide-border-subtle">
                    @forelse ($cat->subcategories as $sub)
                        <div wire:key="gmb-sub-{{ $sub->id }}"
                            class="flex items-center justify-between px-4 py-2.5 pl-10">
                            <span class="flex items-center gap-2 text-sm text-text">
                                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0"
                                    style="background-color: {{ $sub->color }};"></span>
                                {{ $sub->name }}
                            </span>
                            <div class="flex items-center gap-1">
                                <button wire:click="openSubcategoryModal({{ $cat->id }}, {{ $sub->id }})"
                                    class="w-7 h-7 rounded-lg flex items-center justify-center text-muted hover:text-primary"><i
                                        class="ph ph-pencil-simple text-sm"></i></button>
                                <button wire:click="deleteSubcategory({{ $sub->id }})"
                                    onclick="return confirm('Delete this subcategory? This removes historical checkbox data for it too.')"
                                    class="w-7 h-7 rounded-lg flex items-center justify-center text-muted hover:text-danger"><i
                                        class="ph ph-trash text-sm"></i></button>
                            </div>
                        </div>
                    @empty
                        <p class="px-4 py-3 pl-10 text-xs text-muted">No subcategories yet.</p>
                    @endforelse
                </div>
            </div>
        @empty
            <div class="bg-surface border border-border rounded-2xl p-8 text-center text-sm text-muted">
                No categories yet — click "Add Category" to create your first one.
            </div>
        @endforelse
    </div>

    {{-- Category modal --}}
    @if ($showCategoryModal)
        <div class="fixed inset-0 z-[70] flex items-center justify-center bg-overlay p-4">
            <div class="w-full max-w-sm bg-surface rounded-2xl border border-border shadow-2xl">
                <div class="px-5 h-14 flex items-center justify-between border-b border-border-subtle">
                    <h3 class="text-base font-semibold text-heading">{{ $editingCategoryId ? 'Edit' : 'Add' }} Category
                    </h3>
                    <button wire:click="$set('showCategoryModal', false)"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle"><i
                            class="ph ph-x"></i></button>
                </div>
                <form wire:submit="saveCategory" class="p-5 space-y-4">
                    <div>
                        <label class="text-xs font-medium text-muted">Category Name</label>
                        <input type="text" wire:model="categoryName"
                            class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                        @error('categoryName')
                            <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-medium text-muted">Color</label>
                        <div class="mt-1 flex items-center gap-2">
                            <input type="color" wire:model="categoryColor"
                                class="w-10 h-10 rounded-lg border border-border cursor-pointer p-0.5" />
                            <input type="text" wire:model="categoryColor"
                                class="flex-1 h-10 px-3 rounded-lg border border-border bg-surface text-sm font-mono" />
                        </div>
                        @error('categoryColor')
                            <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror

                        <div class="mt-2 flex items-center gap-1.5 flex-wrap">
                            @foreach (['#6b7280', '#ef4444', '#f59e0b', '#22c55e', '#3b82f6', '#a855f7', '#ec4899'] as $preset)
                                <button type="button" wire:click="$set('categoryColor', '{{ $preset }}')"
                                    style="background-color: {{ $preset }};"
                                    class="w-6 h-6 rounded-full border-2 {{ $categoryColor === $preset ? 'border-heading' : 'border-transparent' }}"></button>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" wire:click="$set('showCategoryModal', false)"
                            class="h-10 px-4 rounded-xl border border-border text-sm font-medium text-text-secondary">Cancel</button>
                        <button type="submit"
                            class="h-10 px-4 rounded-xl bg-primary text-white text-sm font-medium">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Subcategory modal — no color field here anymore --}}
    @if ($showSubcategoryModal)
        <div class="fixed inset-0 z-[70] flex items-center justify-center bg-overlay p-4">
            <div class="w-full max-w-sm bg-surface rounded-2xl border border-border shadow-2xl">
                <div class="px-5 h-14 flex items-center justify-between border-b border-border-subtle">
                    <h3 class="text-base font-semibold text-heading">{{ $editingSubcategoryId ? 'Edit' : 'Add' }}
                        Subcategory</h3>
                    <button wire:click="$set('showSubcategoryModal', false)"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle"><i
                            class="ph ph-x"></i></button>
                </div>
                <form wire:submit="saveSubcategory" class="p-5 space-y-4">
                    <div>
                        <label class="text-xs font-medium text-muted">Subcategory Name</label>
                        <input type="text" wire:model="subcategoryName"
                            class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                        @error('subcategoryName')
                            <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" wire:click="$set('showSubcategoryModal', false)"
                            class="h-10 px-4 rounded-xl border border-border text-sm font-medium text-text-secondary">Cancel</button>
                        <button type="submit"
                            class="h-10 px-4 rounded-xl bg-primary text-white text-sm font-medium">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endif


</div>
