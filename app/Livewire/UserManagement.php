<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin', ['header' => 'Manajemen User'])]
class UserManagement extends Component
{
    use WithPagination;

    public string $search = '';

    // Form fields
    public bool $showModal = false;

    public ?int $editingId = null;

    #[Rule('required|string|min:2|max:255')]
    public string $name = '';

    #[Rule('required|email|max:255')]
    public string $email = '';

    public string $password = '';

    #[Rule('required|in:admin,guru')]
    public string $role = 'guru';

    // Delete
    public bool $showDeleteModal = false;

    public ?int $deletingId = null;

    public string $deletingName = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $user = User::findOrFail($id);
        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $rules = [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255|unique:users,email'.($this->editingId ? ','.$this->editingId : ''),
            'role' => 'required|in:admin,guru',
        ];

        if (! $this->editingId) {
            $rules['password'] = 'required|string|min:8';
        } else {
            $rules['password'] = 'nullable|string|min:8';
        }

        $this->validate($rules);

        if ($this->editingId) {
            $user = User::findOrFail($this->editingId);
            $user->name = $this->name;
            $user->email = $this->email;
            $user->role = $this->role;
            if ($this->password !== '') {
                $user->password = Hash::make($this->password);
            }
            $user->save();
            session()->flash('success', 'User "'.$user->name.'" berhasil diperbarui.');
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => $this->role,
            ]);
            session()->flash('success', 'User "'.$user->name.'" berhasil dibuat dengan role '.$user->role_label.'.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $user = User::findOrFail($id);
        $this->deletingId = $user->id;
        $this->deletingName = $user->name;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if (! $this->deletingId) {
            return;
        }

        $user = User::findOrFail($this->deletingId);

        // Jangan hapus diri sendiri
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Tidak bisa menghapus akun sendiri.');
            $this->showDeleteModal = false;

            return;
        }

        $user->delete();
        session()->flash('success', 'User "'.$this->deletingName.'" berhasil dihapus.');
        $this->showDeleteModal = false;
        $this->deletingId = null;
        $this->deletingName = '';
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = 'guru';
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($q) {
                $q->where(function ($q2) {
                    $q2->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.user-management', [
            'users' => $users,
        ]);
    }
}