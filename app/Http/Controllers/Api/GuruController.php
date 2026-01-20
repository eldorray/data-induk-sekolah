<?php

namespace App\Http\Controllers\Api;

use App\Models\Guru;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class GuruController extends Controller
{
    /**
     * List all gurus with pagination
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $status = $request->get('status'); // active, inactive
        $search = $request->get('search');

        $query = Guru::query();

        if ($status === 'active') {
            $query->active();
        } elseif ($status === 'inactive') {
            $query->inactive();
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('nuptk', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $gurus = $query->orderBy('full_name')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $gurus,
        ]);
    }

    /**
     * Get all gurus without pagination
     */
    public function all(Request $request)
    {
        $status = $request->get('status');
        $updatedAfter = $request->get('updated_after');

        $query = Guru::query();

        if ($status === 'active') {
            $query->active();
        } elseif ($status === 'inactive') {
            $query->inactive();
        }

        if ($updatedAfter) {
            $query->where('updated_at', '>', $updatedAfter);
        }

        $gurus = $query->orderBy('full_name')->get();

        return response()->json([
            'success' => true,
            'count' => $gurus->count(),
            'data' => $gurus,
        ]);
    }

    /**
     * Get single guru
     */
    public function show(int $id)
    {
        $guru = Guru::find($id);

        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Data guru tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $guru,
        ]);
    }

    /**
     * Create new guru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|size:16|unique:gurus,nik',
            'full_name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'nip' => 'nullable|string|max:30',
            'nuptk' => 'nullable|string|max:30',
            'npk' => 'nullable|string|max:30',
            'front_title' => 'nullable|string|max:20',
            'back_title' => 'nullable|string|max:20',
            'pob' => 'nullable|string|max:100',
            'dob' => 'nullable|date',
            'phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'status_pegawai' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $guru = Guru::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil ditambahkan',
            'data' => $guru,
        ], 201);
    }

    /**
     * Update guru
     */
    public function update(Request $request, int $id)
    {
        $guru = Guru::find($id);

        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Data guru tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nik' => 'sometimes|required|string|size:16|unique:gurus,nik,' . $id,
            'full_name' => 'sometimes|required|string|max:255',
            'gender' => 'sometimes|required|in:L,P',
            'nip' => 'nullable|string|max:30',
            'nuptk' => 'nullable|string|max:30',
            'npk' => 'nullable|string|max:30',
            'front_title' => 'nullable|string|max:20',
            'back_title' => 'nullable|string|max:20',
            'pob' => 'nullable|string|max:100',
            'dob' => 'nullable|date',
            'phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'status_pegawai' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $guru->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil diperbarui',
            'data' => $guru,
        ]);
    }

    /**
     * Delete guru
     */
    public function destroy(int $id)
    {
        $guru = Guru::find($id);

        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Data guru tidak ditemukan',
            ], 404);
        }

        $guru->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil dihapus',
        ]);
    }

    /**
     * Bulk sync gurus
     */
    public function sync(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gurus' => 'required|array',
            'gurus.*.nik' => 'required|string|size:16',
            'gurus.*.full_name' => 'required|string|max:255',
            'gurus.*.gender' => 'required|in:L,P',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $created = 0;
        $updated = 0;
        $errors = [];

        foreach ($request->gurus as $index => $data) {
            try {
                $guru = Guru::where('nik', $data['nik'])->first();

                if ($guru) {
                    $guru->update($data);
                    $updated++;
                } else {
                    Guru::create($data);
                    $created++;
                }
            } catch (\Exception $e) {
                $errors[] = "Baris " . ($index + 1) . ": " . $e->getMessage();
            }
        }

        return response()->json([
            'success' => count($errors) === 0,
            'message' => "Sinkronisasi selesai. Dibuat: {$created}, Diperbarui: {$updated}",
            'created' => $created,
            'updated' => $updated,
            'errors' => $errors,
        ]);
    }
}
