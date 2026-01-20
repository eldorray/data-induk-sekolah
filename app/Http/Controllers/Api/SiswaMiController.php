<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiswaMi;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class SiswaMiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = SiswaMi::query();

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('tingkat_rombel', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by tingkat_rombel
        if ($request->has('tingkat_rombel') && $request->tingkat_rombel) {
            $query->where('tingkat_rombel', 'like', "%{$request->tingkat_rombel}%");
        }

        // Filter by jenis_kelamin
        if ($request->has('jenis_kelamin') && $request->jenis_kelamin) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        // Sorting
        $sortField = $request->get('sort_by', 'nama_lengkap');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $siswas = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Data siswa MI berhasil diambil',
            'data' => $siswas->items(),
            'meta' => [
                'current_page' => $siswas->currentPage(),
                'last_page' => $siswas->lastPage(),
                'per_page' => $siswas->perPage(),
                'total' => $siswas->total(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nisn' => 'nullable|string|max:20|unique:siswa_mis,nisn',
            'nik' => 'nullable|string|max:20|unique:siswa_mis,nik',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'tingkat_rombel' => 'nullable|string|max:100',
            'status' => 'nullable|string|in:Aktif,Tidak Aktif,Lulus,Pindah,Keluar',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:20',
            'kebutuhan_khusus' => 'nullable|string|max:255',
            'disabilitas' => 'nullable|string|max:255',
            'nomor_kip_pip' => 'nullable|string|max:50',
            'nama_ayah_kandung' => 'nullable|string|max:255',
            'nama_ibu_kandung' => 'nullable|string|max:255',
            'nama_wali' => 'nullable|string|max:255',
        ]);

        $siswa = SiswaMi::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data siswa MI berhasil ditambahkan',
            'data' => $siswa,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(SiswaMi $siswaMi): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Data siswa MI berhasil diambil',
            'data' => $siswaMi,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SiswaMi $siswaMi): JsonResponse
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nisn' => ['nullable', 'string', 'max:20', Rule::unique('siswa_mis', 'nisn')->ignore($siswaMi->id)],
            'nik' => ['nullable', 'string', 'max:20', Rule::unique('siswa_mis', 'nik')->ignore($siswaMi->id)],
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'tingkat_rombel' => 'nullable|string|max:100',
            'status' => 'nullable|string|in:Aktif,Tidak Aktif,Lulus,Pindah,Keluar',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:20',
            'kebutuhan_khusus' => 'nullable|string|max:255',
            'disabilitas' => 'nullable|string|max:255',
            'nomor_kip_pip' => 'nullable|string|max:50',
            'nama_ayah_kandung' => 'nullable|string|max:255',
            'nama_ibu_kandung' => 'nullable|string|max:255',
            'nama_wali' => 'nullable|string|max:255',
        ]);

        $siswaMi->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data siswa MI berhasil diperbarui',
            'data' => $siswaMi,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SiswaMi $siswaMi): JsonResponse
    {
        $siswaMi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data siswa MI berhasil dihapus',
        ]);
    }

    /**
     * Bulk sync data from external application.
     */
    public function sync(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'data' => 'required|array',
            'data.*.nama_lengkap' => 'required|string|max:255',
            'data.*.nisn' => 'nullable|string|max:20',
            'data.*.nik' => 'nullable|string|max:20',
            'data.*.tempat_lahir' => 'nullable|string|max:100',
            'data.*.tanggal_lahir' => 'nullable|date',
            'data.*.tingkat_rombel' => 'nullable|string|max:100',
            'data.*.status' => 'nullable|string|in:Aktif,Tidak Aktif,Lulus,Pindah,Keluar',
            'data.*.jenis_kelamin' => 'nullable|in:L,P',
            'data.*.alamat' => 'nullable|string',
            'data.*.no_telepon' => 'nullable|string|max:20',
            'data.*.kebutuhan_khusus' => 'nullable|string|max:255',
            'data.*.disabilitas' => 'nullable|string|max:255',
            'data.*.nomor_kip_pip' => 'nullable|string|max:50',
            'data.*.nama_ayah_kandung' => 'nullable|string|max:255',
            'data.*.nama_ibu_kandung' => 'nullable|string|max:255',
            'data.*.nama_wali' => 'nullable|string|max:255',
        ]);

        $created = 0;
        $updated = 0;
        $errors = [];

        foreach ($validated['data'] as $index => $row) {
            try {
                // Check if siswa exists by NISN or NIK
                $siswa = null;
                if (!empty($row['nisn'])) {
                    $siswa = SiswaMi::where('nisn', $row['nisn'])->first();
                }
                if (!$siswa && !empty($row['nik'])) {
                    $siswa = SiswaMi::where('nik', $row['nik'])->first();
                }

                if ($siswa) {
                    $siswa->update($row);
                    $updated++;
                } else {
                    SiswaMi::create($row);
                    $created++;
                }
            } catch (\Exception $e) {
                $errors[] = [
                    'index' => $index,
                    'message' => $e->getMessage(),
                ];
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Sinkronisasi data selesai',
            'data' => [
                'created' => $created,
                'updated' => $updated,
                'errors' => $errors,
            ],
        ]);
    }

    /**
     * Get all data without pagination (for sync).
     */
    public function all(Request $request): JsonResponse
    {
        $query = SiswaMi::query();

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by updated_after (for incremental sync)
        if ($request->has('updated_after') && $request->updated_after) {
            $query->where('updated_at', '>=', $request->updated_after);
        }

        $siswas = $query->orderBy('nama_lengkap')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data siswa MI berhasil diambil',
            'data' => $siswas,
            'total' => $siswas->count(),
        ]);
    }
}
