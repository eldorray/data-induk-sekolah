<?php

namespace App\Livewire;

use App\Models\LpjBos;
use App\Models\LpjBosAttachment;
use App\Services\LpjBosImageCompressor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class LpjBosDetail extends Component
{
    use WithFileUploads;

    public LpjBos $lpj;

    public array $fotoFiles = [];

    public array $kwitansiFiles = [];

    public array $undanganFiles = [];

    public array $attachmentCaptions = [];

    public function mount(LpjBos $lpj): void
    {
        $this->lpj = $lpj->load(['kuitansi', 'attachments']);
        $this->hydrateCaptions();
    }

    // Auto-process each category right after Livewire finishes binding the
    // temporary upload to the property. This runs only once the files are
    // actually present server-side, avoiding the race a manual "Upload" click
    // had (action firing before the temp upload completed).
    public function updatedFotoFiles(): void
    {
        $this->autoUpload(LpjBosAttachment::CATEGORY_FOTO);
    }

    public function updatedKwitansiFiles(): void
    {
        $this->autoUpload(LpjBosAttachment::CATEGORY_KWITANSI);
    }

    public function updatedUndanganFiles(): void
    {
        $this->autoUpload(LpjBosAttachment::CATEGORY_UNDANGAN);
    }

    private function autoUpload(string $category): void
    {
        $property = $this->propertyForCategory($category);

        if (empty($this->{$property})) {
            return;
        }

        $this->upload($category, app(LpjBosImageCompressor::class));
    }

    private function rulesForCategory(string $category): array
    {
        $property = $this->propertyForCategory($category);

        return [
            $property => 'required|array|min:1',
            $property.'.*' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ];
    }

    public function upload(string $category, LpjBosImageCompressor $compressor): void
    {
        abort_unless(in_array($category, LpjBosAttachment::CATEGORIES, true), 404);

        $this->validate($this->rulesForCategory($category));

        $property = $this->propertyForCategory($category);
        $files = $this->{$property};

        foreach ($files as $file) {
            $isPdf = $file->getMimeType() === 'application/pdf';

            if ($isPdf && $file->getSize() > 5 * 1024 * 1024) {
                $this->addError($property, 'File PDF maksimal 5 MB.');

                continue;
            }

            $directory = 'lpj-bos/'.$this->lpj->id.'/'.$category;
            $path = $isPdf
                ? $file->storeAs($directory, Str::uuid()->toString().'.pdf', 'public')
                : $compressor->store($file, $directory);

            LpjBosAttachment::create([
                'lpj_bos_id' => $this->lpj->id,
                'kategori' => $category,
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $isPdf ? 'application/pdf' : 'image/jpeg',
                'file_size' => Storage::disk('public')->size($path),
                'sort_order' => $this->nextSortOrder($category),
            ]);
        }

        $this->{$property} = [];
        $this->refreshLpj();
        session()->flash('success', 'Lampiran berhasil diupload.');
    }

    public function saveCaption(int $attachmentId): void
    {
        $attachment = $this->findOwnedAttachment($attachmentId);
        $attachment->update(['keterangan' => $this->attachmentCaptions[$attachmentId] ?? null]);
        $this->refreshLpj();
        session()->flash('success', 'Keterangan lampiran berhasil disimpan.');
    }

    public function deleteAttachment(int $attachmentId): void
    {
        $attachment = $this->findOwnedAttachment($attachmentId);
        Storage::disk('public')->delete($attachment->file_path);
        $category = $attachment->kategori;
        $attachment->delete();
        $this->reindexCategory($category);
        $this->refreshLpj();
        session()->flash('success', 'Lampiran berhasil dihapus.');
    }

    public function moveUp(int $attachmentId): void
    {
        $attachment = $this->findOwnedAttachment($attachmentId);
        $previous = LpjBosAttachment::where('lpj_bos_id', $this->lpj->id)
            ->where('kategori', $attachment->kategori)
            ->where('sort_order', '<', $attachment->sort_order)
            ->orderByDesc('sort_order')
            ->first();

        if ($previous) {
            $currentOrder = $attachment->sort_order;
            $attachment->update(['sort_order' => $previous->sort_order]);
            $previous->update(['sort_order' => $currentOrder]);
        }

        $this->refreshLpj();
    }

    public function moveDown(int $attachmentId): void
    {
        $attachment = $this->findOwnedAttachment($attachmentId);
        $next = LpjBosAttachment::where('lpj_bos_id', $this->lpj->id)
            ->where('kategori', $attachment->kategori)
            ->where('sort_order', '>', $attachment->sort_order)
            ->orderBy('sort_order')
            ->first();

        if ($next) {
            $currentOrder = $attachment->sort_order;
            $attachment->update(['sort_order' => $next->sort_order]);
            $next->update(['sort_order' => $currentOrder]);
        }

        $this->refreshLpj();
    }

    private function propertyForCategory(string $category): string
    {
        return match ($category) {
            LpjBosAttachment::CATEGORY_FOTO => 'fotoFiles',
            LpjBosAttachment::CATEGORY_KWITANSI => 'kwitansiFiles',
            LpjBosAttachment::CATEGORY_UNDANGAN => 'undanganFiles',
            default => abort(404),
        };
    }

    private function nextSortOrder(string $category): int
    {
        return ((int) LpjBosAttachment::where('lpj_bos_id', $this->lpj->id)
            ->where('kategori', $category)
            ->max('sort_order')) + 1;
    }

    private function findOwnedAttachment(int $attachmentId): LpjBosAttachment
    {
        return LpjBosAttachment::where('lpj_bos_id', $this->lpj->id)->findOrFail($attachmentId);
    }

    private function reindexCategory(string $category): void
    {
        LpjBosAttachment::where('lpj_bos_id', $this->lpj->id)
            ->where('kategori', $category)
            ->orderBy('sort_order')
            ->get()
            ->values()
            ->each(fn (LpjBosAttachment $attachment, int $index) => $attachment->update(['sort_order' => $index + 1]));
    }

    private function refreshLpj(): void
    {
        $this->lpj = $this->lpj->fresh(['kuitansi', 'attachments']);
        $this->hydrateCaptions();
    }

    private function hydrateCaptions(): void
    {
        $this->attachmentCaptions = $this->lpj->attachments->pluck('keterangan', 'id')->toArray();
    }

    public function render()
    {
        return view('livewire.lpj-bos-detail', [
            'fotoAttachments' => $this->lpj->fotoAttachments()->get(),
            'kwitansiAttachments' => $this->lpj->kwitansiAttachments()->get(),
            'undanganAttachments' => $this->lpj->undanganAttachments()->get(),
        ])->layout('layouts.admin', ['header' => 'Detail LPJ BOS']);
    }
}
