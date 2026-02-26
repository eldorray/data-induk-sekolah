<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\License;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LicenseVerifyController extends Controller
{
    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'license_key' => 'required|string',
            'domain' => 'required|string',
        ]);

        $license = License::where('license_key', $request->license_key)->first();

        if (!$license) {
            return response()->json([
                'valid' => false,
                'message' => 'License key tidak ditemukan.',
            ], 404);
        }

        if ($license->status === 'revoked') {
            return response()->json([
                'valid' => false,
                'message' => 'License key telah dicabut.',
            ], 403);
        }

        if ($license->status === 'expired' || ($license->expires_at && $license->expires_at->isPast())) {
            return response()->json([
                'valid' => false,
                'message' => 'License key sudah expired.',
            ], 403);
        }

        $requestDomain = $request->domain;
        $isLocalDomain = in_array($requestDomain, ['localhost', '127.0.0.1', '::1'])
            || str_ends_with($requestDomain, '.test')
            || str_ends_with($requestDomain, '.local');

        // Localhost/dev domains always pass — no domain binding
        if (!$isLocalDomain) {
            // Production domain: bind on first activation, check on subsequent
            if (!$license->domain) {
                $license->update([
                    'domain' => $requestDomain,
                    'activated_at' => now(),
                ]);
            } elseif ($license->domain !== $requestDomain) {
                return response()->json([
                    'valid' => false,
                    'message' => 'License key sudah diaktivasi untuk domain lain: ' . $license->domain,
                ], 403);
            }
        }

        // Set activated_at if not yet set (e.g. first activation from localhost)
        if (!$license->activated_at) {
            $license->update(['activated_at' => now()]);
        }

        return response()->json([
            'valid' => true,
            'school_name' => $license->school_name,
            'expires_at' => $license->expires_at?->toIso8601String(),
            'activated_at' => $license->activated_at?->toIso8601String(),
        ]);
    }
}
