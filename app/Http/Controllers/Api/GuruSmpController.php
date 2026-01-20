<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GuruSmp;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class GuruSmpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = GuruSmp::query();

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('nuptk', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        // Filter by status_pegawai
        if ($request->has('status_pegawai') && $request->status_pegawai) {
            $query->where('status_pegawai', $request->status_pegawai);
        }

        // Filter by is_active
        if ($request->has('is_active')) {
            $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
        }

        // Filter by gender
        if ($request->has('gender') && $request->gender) {
            $query->where('gender', $request->gender);
        }

        // Sorting
        $sortField = $request->get('sort_by', 'full_name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $gurus = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Data guru SMP berhasil diambil',
            'data' => $gurus->items(),
            'meta' => [
                'current_page' => $gurus->currentPage(),
                'last_page' => $gurus->lastPage(),
                'per_page' => $gurus->perPage(),
                'total' => $gurus->total(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nip' => 'nullable|string|max:30|unique:guru_smps,nip',
            'nuptk' => 'nullable|string|max:30|unique:guru_smps,nuptk',
            'npk' => 'nullable|string|max:30',
            'nik' => 'nullable|string|max:20|unique:guru_smps,nik',
            'front_title' => 'nullable|string|max:50',
            'full_name' => 'required|string|max:255',
            'back_title' => 'nullable|string|max:50',
            'gender' => 'nullable|in:L,P',
            'pob' => 'nullable|string|max:100',
            'dob' => 'nullable|date',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status_pegawai' => 'nullable|string|in:PNS,GTY,GTT',
            'is_active' => 'nullable|boolean',
        ]);

        $guru = GuruSmp::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data guru SMP berhasil ditambahkan',
            'data' => $guru,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(GuruSmp $guruSmp): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Data guru SMP berhasil diambil',
            'data' => $guruSmp,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GuruSmp $guruSmp): JsonResponse
    {
        $validated = $request->validate([
            'nip' => ['nullable', 'string', 'max:30', Rule::unique('guru_smps', 'nip')->ignore($guruSmp->id)],
            'nuptk' => ['nullable', 'string', 'max:30', Rule::unique('guru_smps', 'nuptk')->ignore($guruSmp->id)],
            'npk' => 'nullable|string|max:30',
            'nik' => ['nullable', 'string', 'max:20', Rule::unique('guru_smps', 'nik')->ignore($guruSmp->id)],
            'front_title' => 'nullable|string|max:50',
            'full_name' => 'required|string|max:255',
            'back_title' => 'nullable|string|max:50',
            'gender' => 'nullable|in:L,P',
            'pob' => 'nullable|string|max:100',
            'dob' => 'nullable|date',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status_pegawai' => 'nullable|string|in:PNS,GTY,GTT',
            'is_active' => 'nullable|boolean',
        ]);

        $guruSmp->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data guru SMP berhasil diperbarui',
            'data' => $guruSmp,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GuruSmp $guruSmp): JsonResponse
    {
        $guruSmp->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data guru SMP berhasil dihapus',
        ]);
    }

    /**
     * Bulk sync data from external application.
     */
    public function sync(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'data' => 'required|array',
            'data.*.full_name' => 'required|string|max:255',
            'data.*.nip' => 'nullable|string|max:30',
            'data.*.nuptk' => 'nullable|string|max:30',
            'data.*.npk' => 'nullable|string|max:30',
            'data.*.nik' => 'nullable|string|max:20',
            'data.*.front_title' => 'nullable|string|max:50',
            'data.*.back_title' => 'nullable|string|max:50',
            'data.*.gender' => 'nullable|in:L,P',
            'data.*.pob' => 'nullable|string|max:100',
            'data.*.dob' => 'nullable|date',
            'data.*.phone_number' => 'nullable|string|max:20',
            'data.*.address' => 'nullable|string',
            'data.*.status_pegawai' => 'nullable|string|in:PNS,GTY,GTT',
            'data.*.is_active' => 'nullable|boolean',
        ]);

        $created = 0;
        $updated = 0;
        $errors = [];

        foreach ($validated['data'] as $index => $row) {
            try {
                // Check if guru exists by NIP, NUPTK, or NIK
                $guru = null;
                if (!empty($row['nip'])) {
                    $guru = GuruSmp::where('nip', $row['nip'])->first();
                }
                if (!$guru && !empty($row['nuptk'])) {
                    $guru = GuruSmp::where('nuptk', $row['nuptk'])->first();
                }
                if (!$guru && !empty($row['nik'])) {
                    $guru = GuruSmp::where('nik', $row['nik'])->first();
                }

                if ($guru) {
                    $guru->update($row);
                    $updated++;
                } else {
                    GuruSmp::create($row);
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
        $query = GuruSmp::query();

        // Filter by is_active
        if ($request->has('is_active')) {
            $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
        }

        // Filter by updated_after (for incremental sync)
        if ($request->has('updated_after') && $request->updated_after) {
            $query->where('updated_at', '>=', $request->updated_after);
        }

        $gurus = $query->orderBy('full_name')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data guru SMP berhasil diambil',
            'data' => $gurus,
            'total' => $gurus->count(),
        ]);
    }
}
