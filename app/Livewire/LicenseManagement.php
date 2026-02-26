<?php

namespace App\Livewire;

use App\Models\License;
use Livewire\Component;
use Livewire\WithPagination;

class LicenseManagement extends Component
{
    use WithPagination;

    // Form fields
    public string $school_name = '';
    public ?string $expires_at = null;
    public string $notes = '';

    // Search
    public string $search = '';

    // Generated key display
    public ?string $generatedKey = null;

    // Delete modal
    public bool $showDeleteModal = false;
    public ?int $deleteId = null;

    // Revoke modal
    public bool $showRevokeModal = false;
    public ?int $revokeId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function generateLicense(): void
    {
        $this->validate([
            'school_name' => 'required|string|max:255',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $license = License::create([
            'license_key' => License::generateKey(),
            'school_name' => $this->school_name,
            'expires_at' => $this->expires_at ?: null,
            'notes' => $this->notes ?: null,
            'status' => 'active',
        ]);

        $this->generatedKey = $license->license_key;
        $this->school_name = '';
        $this->expires_at = null;
        $this->notes = '';

        session()->flash('success', "License key berhasil dibuat untuk {$license->school_name}.");
    }

    public function dismissGeneratedKey(): void
    {
        $this->generatedKey = null;
    }

    public function openRevokeModal(int $id): void
    {
        $this->revokeId = $id;
        $this->showRevokeModal = true;
    }

    public function revoke(): void
    {
        $license = License::findOrFail($this->revokeId);
        $license->update(['status' => 'revoked']);
        $this->showRevokeModal = false;
        $this->revokeId = null;
        session()->flash('success', "License {$license->license_key} berhasil dicabut.");
    }

    public function reactivate(int $id): void
    {
        $license = License::findOrFail($id);
        $license->update(['status' => 'active']);
        session()->flash('success', "License {$license->license_key} berhasil diaktifkan kembali.");
    }

    public function resetDomain(int $id): void
    {
        $license = License::findOrFail($id);
        $license->update(['domain' => null]);
        session()->flash('success', "Domain untuk license {$license->license_key} berhasil direset.");
    }

    public function openDeleteModal(int $id): void
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        License::findOrFail($this->deleteId)->delete();
        $this->showDeleteModal = false;
        $this->deleteId = null;
        session()->flash('success', 'License berhasil dihapus.');
    }

    public function closeModal(): void
    {
        $this->showDeleteModal = false;
        $this->showRevokeModal = false;
        $this->deleteId = null;
        $this->revokeId = null;
    }

    public function render()
    {
        $licenses = License::query()
            ->when($this->search, function ($query) {
                $query->where('school_name', 'like', '%' . $this->search . '%')
                    ->orWhere('license_key', 'like', '%' . $this->search . '%')
                    ->orWhere('domain', 'like', '%' . $this->search . '%');
            })
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.license-management', [
            'licenses' => $licenses,
        ])->layout('layouts.admin', ['header' => 'Manajemen Lisensi']);
    }
}
