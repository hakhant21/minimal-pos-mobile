<?php

namespace App\Livewire\Inventory;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Categories extends Component
{
    public bool $showForm = false;

    public ?int $editingCategoryId = null;

    public ?int $deletingCategoryId = null;

    public int $perPage = 10;

    #[Rule('required|max:255')]
    public string $name = '';

    #[Rule('nullable|string|max:1000')]
    public ?string $description = null;

    #[Rule('nullable|string|max:50')]
    public ?string $icon = null;

    #[Rule('nullable|string|max:7')]
    public ?string $color = null;

    public bool $is_active = true;

    public function loadMore(): void
    {
        $this->perPage += 10;
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingCategoryId = null;
    }

    public function edit(Category $category): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingCategoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->icon = $category->icon;
        $this->color = $category->color;
        $this->is_active = $category->is_active;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'description' => $this->description,
            'icon' => $this->icon,
            'color' => $this->color,
            'is_active' => $this->is_active,
        ];

        if ($this->editingCategoryId) {
            Category::findOrFail($this->editingCategoryId)->update($data);
            session()->flash('message', 'Category updated.');
        } else {
            Category::create($data);
            session()->flash('message', 'Category created.');
        }

        $this->resetForm();
    }

    public function confirmDelete(Category $category): void
    {
        $this->deletingCategoryId = $category->id;
    }

    public function cancelDelete(): void
    {
        $this->deletingCategoryId = null;
    }

    public function delete(): void
    {
        $category = Category::withCount('products')->findOrFail($this->deletingCategoryId);

        if ($category->products_count > 0) {
            session()->flash('error', 'Cannot delete category with existing products.');
            $this->deletingCategoryId = null;

            return;
        }

        $category->delete();
        $this->deletingCategoryId = null;
        session()->flash('message', 'Category deleted.');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->showForm = false;
        $this->editingCategoryId = null;
        $this->name = '';
        $this->description = null;
        $this->icon = null;
        $this->color = null;
        $this->is_active = true;
    }

    public function render()
    {
        return view('livewire.inventory.categories', [
            'categories' => Category::withCount('products')
                ->orderBy('name')
                ->take($this->perPage)
                ->get(),
            'hasMorePages' => Category::count() > $this->perPage,
        ]);
    }
}
