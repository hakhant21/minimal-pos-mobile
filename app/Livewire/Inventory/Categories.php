<?php

namespace App\Livewire\Inventory;

use App\Models\Category;
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
    }

    public function save(): void
    {
        $this->validate();

        if ($this->editingCategoryId) {
            $category = Category::findOrFail($this->editingCategoryId);
            $category->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);
            session()->flash('message', 'Category updated.');
        } else {
            Category::create([
                'name' => $this->name,
                'description' => $this->description,
            ]);
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
    }

    public function render()
    {
        return view('livewire.inventory.categories', [
            'categories' => Category::withCount('products')->orderBy('name')->take($this->perPage)->get(),
            'hasMorePages' => Category::count() > $this->perPage,
        ]);
    }
}
