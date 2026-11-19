<?php

namespace App\Livewire;

use App\Models\LpjInsentif;
use App\Models\LpjInsentifPenerima;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class LpjInsentifDetail extends Component
{
    public LpjInsentif $lpj;

    public bool $showModal = false;

    public bool $showDeleteModal = false;

    public bool $isEditing = false;

    public ?int $penerimaId = null;

    public string $npwp = '';

    public string $nama = '';

    public string $nip_pegid_nuptk = '';

    public string $pangkat = '';

    public string $golongan = '';

    public string $status_pegawai = 'NON PNS';

    public string $jabatan = 'GURU';

    public string $tarif = '650000';

    public string $volume = '3';

    public string $satuan = 'BLN';

    public string $pph21 = '0';

    public string $no_rekening = '';

    protected function rules(): array
    {
        return [
            'npwp' => 'nullable|string|max:30',
            'nama' => 'required|string|max:255',
            'nip_pegid_nuptk' => 'nullable|string|max:30',
            'pangkat' => 'nullable|string|max:50',
            'golongan' => 'nullable|string|max:10',
            'status_pegawai' => 'nullable|in:PNS,PPPK,NON PNS',
            'jabatan' => 'required|string|max:255',
            'tarif' => 'required|integer|min:0',
            'volume' => 'required|integer|min:0',
            'satuan' => 'required|string|max:10',
            'pph21' => 'required|integer|min:0',
            'no_rekening' => 'nullable|string|max:30',
        ];
    }

    public function mount(LpjInsentif $lpj): void
    {
        $this->lpj = $lpj->load('penerimas');
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal(int $penerimaId): void
    {
        $p = LpjInsentifPenerima::where('lpj_insentif_id', $this->lpj->id)->findOrFail($penerimaId);
        $this->penerimaId = $p->id;
        $this->npwp = $p->npwp ?? '';
        $this->nama = $p->nama;
        $this->nip_pegid_nuptk = $p->nip_pegid_nuptk ?? '';
        $this->pangkat = $p->pangkat ?? '';
        $this->golongan = $p->golongan ?? '';
        $this->status_pegawai = $p->status_pegawai ?? '';
        $this->jabatan = $p->jabatan;
        $this->tarif = (string) $p->tarif;
        $this->volume = (string) $p->volume;
        $this->satuan = $p->satuan;
        $this->pph21 = (string) $p->pph21;
        $this->no_rekening = $p->no_rekening ?? '';
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        $data = [
            'npwp' => $validated['npwp'] ?: null,
            'nama' => $validated['nama'],
            'nip_pegid_nuptk' => $validated['nip_pegid_nuptk'] ?: null,
            'pangkat' => $validated['pangkat'] ?: null,
            'golongan' => $validated['golongan'] ?: null,
            'status_pegawai' => $validated['status_pegawai'] ?: null,
            'jabatan' => $validated['jabatan'],
            'tarif' => (int) $validated['tarif'],
            'volume' => (int) $validated['volume'],
            'satuan' => $validated['satuan'],
            'pph21' => (int) $validated['pph21'],
            'no_rekening' => $validated['no_rekening'] ?: null,
        ];

        if ($this->isEditing) {
            LpjInsentifPenerima::where('lpj_insentif_id', $this->lpj->id)->findOrFail($this->penerimaId)->update($data);
            session()->flash('success', 'Penerima berhasil diperbarui.');
        } else {
            $data['lpj_insentif_id'] = $this->lpj->id;
            $data['sort_order'] = ($this->lpj->penerimas()->max('sort_order') ?? 0) + 1;
            LpjInsentifPenerima::create($data);
            session()->flash('success', 'Penerima berhasil ditambahkan.');
        }

        $this->closeModal();
        $this->lpj = $this->lpj->fresh('penerimas');
    }

    public function openDeleteModal(int $penerimaId): void
    {
        $this->penerimaId = $penerimaId;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        LpjInsentifPenerima::where('lpj_insentif_id', $this->lpj->id)->findOrFail($this->penerimaId)->delete();
        $this->showDeleteModal = false;
        $this->penerimaId = null;
        session()->flash('success', 'Penerima berhasil dihapus.');
        $this->lpj = $this->lpj->fresh('penerimas');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->penerimaId = null;
        $this->npwp = '';
        $this->nama = '';
        $this->nip_pegid_nuptk = '';
        $this->pangkat = '';
        $this->golongan = '';
        $this->status_pegawai = 'NON PNS';
        $this->jabatan = 'GURU';
        $this->tarif = '650000';
        $this->volume = '3';
        $this->satuan = 'BLN';
        $this->pph21 = '0';
        $this->no_rekening = '';
        $this->isEditing = false;
        $this->resetErrorBag();
    }

    public function render(): View
    {
        return view('livewire.lpj-insentif-detail')
            ->layout('layouts.admin', ['header' => 'Detail LPJ insentif']);
    }
}
