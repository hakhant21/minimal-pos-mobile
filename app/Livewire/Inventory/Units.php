<?php

namespace App\Livewire\Inventory;

use App\Models\Unit;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Units extends Component
{
    public bool $showForm = false;

    public ?int $editingUnitId = null;

    public ?int $deletingUnitId = null;

    public int $perPage = 10;

    #[Rule('required|max:255')]
    public string $name = '';

    public string $search = '';

    public function loadMore(): void
    {
        $this->perPage += 10;
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingUnitId = null;
    }

    public function edit(Unit $unit): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingUnitId = $unit->id;
        $this->name = $unit->name;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->editingUnitId) {
            Unit::findOrFail($this->editingUnitId)->update([
                'name' => $this->name,
            ]);
            session()->flash('message', 'Unit updated successfully.');
        } else {
            Unit::create([
                'name' => $this->name,
            ]);
            session()->flash('message', 'Unit created successfully.');
        }

        $this->resetForm();
    }

    public function confirmDelete(Unit $unit): void
    {
        $this->deletingUnitId = $unit->id;
    }

    public function cancelDelete(): void
    {
        $this->deletingUnitId = null;
    }

    public function delete(): void
    {
        $unit = Unit::withCount('variants')->findOrFail($this->deletingUnitId);

        if ($unit->variants_count > 0) {
            session()->flash('error', 'Cannot delete unit that is in use by product variants.');
            $this->deletingUnitId = null;

            return;
        }

        $unit->delete();
        $this->deletingUnitId = null;
        session()->flash('message', 'Unit deleted successfully.');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->showForm = false;
        $this->editingUnitId = null;
        $this->name = '';
    }

    public function render()
    {
        $unitsQuery = Unit::ordered();

        if (! empty($this->search)) {
            $unitsQuery->where('name', 'like', '%'.$this->search.'%');
        }

        $totalUnits = $unitsQuery->count();

        return view('livewire.inventory.units', [
            'units' => $unitsQuery->take($this->perPage)->get(),
            'hasMorePages' => $totalUnits > $this->perPage,
        ]);
    }
}
