<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MapelSmp;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class MapelSmpController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = MapelSmp::query();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_mapel', 'like', "%{$search}%")
                    ->orWhere('kode_mapel', 'like', "%{$search}%");
            });
        }

        if ($request->has('kelompok') && $request->kelompok) {
            $query->where('kelompok', $request->kelompok);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
        }

        $sortField = $request->get('sort_by', 'nama_mapel');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        $perPage = $request->get('per_page', 15);
        $mapels = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Data mata pelajaran SMP berhasil diambil',
            'data' => $mapels->items(),
            'meta' => [
                'current_page' => $mapels->currentPage(),
                'last_page' => $mapels->lastPage(),
                'per_page' => $mapels->perPage(),
                'total' => $mapels->total(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kode_mapel' => 'nullable|string|max:20|unique:mapel_smps,kode_mapel',
            'nama_mapel' => 'required|string|max:255',
            'kelompok' => 'nullable|in:PAI,Umum',
            'jurusan' => 'nullable|string|max:255',
            'jam_per_minggu' => 'nullable|integer|min:1|max:20',
            'is_active' => 'nullable|boolean',
        ]);

        $mapel = MapelSmp::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data mata pelajaran SMP berhasil ditambahkan',
            'data' => $mapel,
        ], 201);
    }

    public function show(MapelSmp $mapelSmp): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Data mata pelajaran SMP berhasil diambil',
            'data' => $mapelSmp,
        ]);
    }

    public function update(Request $request, MapelSmp $mapelSmp): JsonResponse
    {
        $validated = $request->validate([
            'kode_mapel' => ['nullable', 'string', 'max:20', Rule::unique('mapel_smps', 'kode_mapel')->ignore($mapelSmp->id)],
            'nama_mapel' => 'required|string|max:255',
            'kelompok' => 'nullable|in:PAI,Umum',
            'jurusan' => 'nullable|string|max:255',
            'jam_per_minggu' => 'nullable|integer|min:1|max:20',
            'is_active' => 'nullable|boolean',
        ]);

        $mapelSmp->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data mata pelajaran SMP berhasil diperbarui',
            'data' => $mapelSmp,
        ]);
    }

    public function destroy(MapelSmp $mapelSmp): JsonResponse
    {
        $mapelSmp->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data mata pelajaran SMP berhasil dihapus',
        ]);
    }

    public function sync(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'data' => 'required|array',
            'data.*.nama_mapel' => 'required|string|max:255',
            'data.*.kode_mapel' => 'nullable|string|max:20',
            'data.*.kelompok' => 'nullable|in:PAI,Umum',
            'data.*.jurusan' => 'nullable|string|max:255',
            'data.*.jam_per_minggu' => 'nullable|integer|min:1|max:20',
            'data.*.is_active' => 'nullable|boolean',
        ]);

        $created = 0;
        $updated = 0;
        $errors = [];

        foreach ($validated['data'] as $index => $row) {
            try {
                $mapel = null;
                if (!empty($row['kode_mapel'])) {
                    $mapel = MapelSmp::where('kode_mapel', $row['kode_mapel'])->first();
                }

                if ($mapel) {
                    $mapel->update($row);
                    $updated++;
                } else {
                    MapelSmp::create($row);
                    $created++;
                }
            } catch (\Exception $e) {
                $errors[] = ['index' => $index, 'message' => $e->getMessage()];
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Sinkronisasi data selesai',
            'data' => ['created' => $created, 'updated' => $updated, 'errors' => $errors],
        ]);
    }

    public function all(Request $request): JsonResponse
    {
        $query = MapelSmp::query();

        if ($request->has('is_active')) {
            $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
        }

        if ($request->has('updated_after') && $request->updated_after) {
            $query->where('updated_at', '>=', $request->updated_after);
        }

        $mapels = $query->orderBy('nama_mapel')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data mata pelajaran SMP berhasil diambil',
            'data' => $mapels,
            'total' => $mapels->count(),
        ]);
    }
}
