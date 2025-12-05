<?php

namespace App\Livewire\Master;

use Livewire\Component;
use App\Models\Master\Unit;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class UnitPage extends Component
{
    use WithPagination;

    public $unit_id;
    public $no_unit, $jenis_unit, $kategori;

    public $search = '';
    public $showTrashed = false; // New property to toggle showing trashed units

    public function render()
    {
        $unitsQuery = Unit::search($this->search)
            ->orderBy('no_unit', 'asc');

        if ($this->showTrashed) {
            $unitsQuery->onlyTrashed(); // Show only trashed units
        }

        $units = $unitsQuery->paginate(10);

        return view('livewire.master.unit-page', [
            'units' => $units,
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->openModal();
    }

    public function openModal()
    {
        $this->dispatch('open-modal', 'unit-form-modal');
    }

    private function resetForm()
    {
        $this->unit_id = null;
        $this->no_unit = '';
        $this->jenis_unit = '';
        $this->kategori = '';
    }

    public function store()
    {
        $this->validate([
            'no_unit' => 'required|string|max:255',
            'jenis_unit' => 'nullable|string|max:255',
            'kategori' => 'required|string|max:255',
        ]);

        Unit::updateOrCreate(['id' => $this->unit_id], [
            'no_unit' => $this->no_unit,
            'jenis_unit' => $this->jenis_unit,
            'kategori' => $this->kategori,
        ]);

        session()->flash('success', $this->unit_id ? 'Data Unit berhasil diperbarui.' : 'Data Unit berhasil ditambahkan.');

        $this->dispatch('close-modal', 'unit-form-modal');
        $this->resetForm();
    }

    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        $this->unit_id = $id;
        $this->no_unit = $unit->no_unit;
        $this->jenis_unit = $unit->jenis_unit;
        $this->kategori = $unit->kategori;

        $this->openModal();
    }

    public function delete($id)
    {
        try {
            Unit::find($id)->delete();
            session()->flash('success', 'Data Unit berhasil dihapus (soft delete).');
            $this->resetPage(); // Refresh pagination
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus data unit. ' . $e->getMessage());
        }
    }

    // New method to restore a soft-deleted unit
    public function restoreUnit($id)
    {
        try {
            Unit::withTrashed()->find($id)->restore();
            session()->flash('success', 'Data Unit berhasil dipulihkan.');
            $this->resetPage(); // Refresh pagination
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memulihkan data unit. ' . $e->getMessage());
        }
    }

    // New method to force delete a unit
    public function forceDeleteUnit($id)
    {
        try {
            Unit::withTrashed()->find($id)->forceDelete();
            session()->flash('success', 'Data Unit berhasil dihapus permanen.');
            $this->resetPage(); // Refresh pagination
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus permanen data unit. ' . $e->getMessage());
        }
    }
}