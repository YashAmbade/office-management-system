<?php

namespace App\Livewire;

use App\Models\GmbCategory;
use App\Models\GmbSubcategory;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;

class GmbCategoryManagement extends Component
{
    public bool $showCategoryModal = false;
    public ?int $editingCategoryId = null;
    public string $categoryName = '';
    public string $categoryColor = '#6b7280';

    public bool $showSubcategoryModal = false;
    public ?int $editingSubcategoryId = null;
    public ?int $subcategoryCategoryId = null;
    public string $subcategoryName = '';

    public function mount(): void
    {
        Gate::authorize('manageCategories', \App\Models\GmbClient::class);
    }

    #[Computed]
    public function categories()
    {
        return GmbCategory::with('subcategories')->orderBy('sort_order')->get();
    }

    // ---------- Categories ----------

    public function openCategoryModal(?int $id = null): void
    {
        $this->reset(['editingCategoryId', 'categoryName']);
        $this->categoryColor = '#6b7280';

        if ($id) {
            $cat = GmbCategory::findOrFail($id);
            $this->editingCategoryId = $id;
            $this->categoryName = $cat->name;
            $this->categoryColor = $cat->color;
        }

        $this->showCategoryModal = true;
    }

    public function saveCategory(): void
    {
        $this->validate([
            'categoryName' => 'required|string|max:191',
            'categoryColor' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        if ($this->editingCategoryId) {
            GmbCategory::findOrFail($this->editingCategoryId)->update([
                'name' => $this->categoryName,
                'color' => $this->categoryColor,
            ]);
        } else {
            $maxOrder = GmbCategory::max('sort_order') ?? 0;
            GmbCategory::create([
                'name' => $this->categoryName,
                'color' => $this->categoryColor,
                'sort_order' => $maxOrder + 1,
            ]);
        }

        $this->showCategoryModal = false;
        unset($this->categories);
        $this->dispatch('gmb-category-saved');
    }

    public function deleteCategory(int $id): void
    {
        GmbCategory::findOrFail($id)->delete();
        unset($this->categories);
        $this->dispatch('gmb-category-deleted');
    }

    public function moveCategory(int $id, string $direction): void
    {
        $categories = $this->categories;
        $index = $categories->search(fn($c) => $c->id === $id);
        $swapWith = $direction === 'up' ? $index - 1 : $index + 1;

        if ($swapWith < 0 || $swapWith >= $categories->count()) {
            return;
        }

        $a = $categories[$index];
        $b = $categories[$swapWith];
        [$a->sort_order, $b->sort_order] = [$b->sort_order, $a->sort_order];
        $a->save();
        $b->save();

        unset($this->categories);
    }

    // ---------- Subcategories ----------

    public function openSubcategoryModal(int $categoryId, ?int $id = null): void
    {
        $this->reset(['editingSubcategoryId', 'subcategoryName']);
        $this->subcategoryCategoryId = $categoryId;

        if ($id) {
            $sub = GmbSubcategory::findOrFail($id);
            $this->editingSubcategoryId = $id;
            $this->subcategoryName = $sub->name;
        }

        $this->showSubcategoryModal = true;
    }

    public function saveSubcategory(): void
    {
        $this->validate(['subcategoryName' => 'required|string|max:191']);

        if ($this->editingSubcategoryId) {
            GmbSubcategory::findOrFail($this->editingSubcategoryId)->update(['name' => $this->subcategoryName]);
        } else {
            $maxOrder = GmbSubcategory::where('gmb_category_id', $this->subcategoryCategoryId)->max('sort_order') ?? 0;
            GmbSubcategory::create([
                'gmb_category_id' => $this->subcategoryCategoryId,
                'name' => $this->subcategoryName,
                'sort_order' => $maxOrder + 1,
            ]);
        }

        $this->showSubcategoryModal = false;
        unset($this->categories);
        $this->dispatch('gmb-subcategory-saved');
    }

    public function deleteSubcategory(int $id): void
    {
        GmbSubcategory::findOrFail($id)->delete();
        unset($this->categories);
        $this->dispatch('gmb-subcategory-deleted');
    }

    public function render()
    {
        return view('livewire.gmb-category-management');
    }
}
