<?php

namespace App\Livewire;

use App\Models\SyaratPindahan;
use Livewire\Component;

class SyaratPindahanManagement extends Component
{
    public string $newSyarat = '';

    // Edit state
    public ?int $editingId = null;
    public string $editingValue = '';

    public function rules(): array
    {
        return [
            'newSyarat' => 'required|string|max:255',
            'editingValue' => 'required|string|max:255',
        ];
    }

    public function addSyarat(): void
    {
        $validated = $this->validateOnly('newSyarat');
        $value = trim($validated['newSyarat']);

        if ($value === '') {
            return;
        }

        SyaratPindahan::create([
            'syarat' => $value,
            'sort_order' => SyaratPindahan::nextSortOrder(),
            'is_active' => true,
        ]);

        $this->reset('newSyarat');
        $this->resetErrorBag();
        session()->flash('success', 'Syarat berhasil ditambahkan.');
    }

    public function startEdit(int $id): void
    {
        $syarat = SyaratPindahan::findOrFail($id);
        $this->editingId = $id;
        $this->editingValue = $syarat->syarat;
    }

    public function cancelEdit(): void
    {
        $this->reset(['editingId', 'editingValue']);
        $this->resetErrorBag();
    }

    public function saveEdit(): void
    {
        $validated = $this->validateOnly('editingValue');
        $value = trim($validated['editingValue']);

        if ($value === '' || $this->editingId === null) {
            return;
        }

        $syarat = SyaratPindahan::findOrFail($this->editingId);
        $syarat->update(['syarat' => $value]);
        $this->cancelEdit();
        session()->flash('success', 'Syarat berhasil diperbarui.');
    }

    public function toggleActive(int $id): void
    {
        $syarat = SyaratPindahan::findOrFail($id);
        $syarat->update(['is_active' => ! $syarat->is_active]);
    }

    public function moveUp(int $id): void
    {
        $current = SyaratPindahan::findOrFail($id);
        $previous = SyaratPindahan::where('sort_order', '<', $current->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();

        if (! $previous) {
            return;
        }

        // Swap sort_order
        $temp = $current->sort_order;
        $current->update(['sort_order' => $previous->sort_order]);
        $previous->update(['sort_order' => $temp]);
    }

    public function moveDown(int $id): void
    {
        $current = SyaratPindahan::findOrFail($id);
        $next = SyaratPindahan::where('sort_order', '>', $current->sort_order)
            ->orderBy('sort_order', 'asc')
            ->first();

        if (! $next) {
            return;
        }

        $temp = $current->sort_order;
        $current->update(['sort_order' => $next->sort_order]);
        $next->update(['sort_order' => $temp]);
    }

    public function deleteSyarat(int $id): void
    {
        SyaratPindahan::findOrFail($id)->delete();
        session()->flash('success', 'Syarat berhasil dihapus.');
    }

    public function render()
    {
        $syarats = SyaratPindahan::orderBy('sort_order')->orderBy('id')->get();

        return view('livewire.syarat-pindahan-management', [
            'syarats' => $syarats,
        ])->layout('layouts.admin', ['header' => 'Manajemen Syarat Pindahan']);
    }
}
