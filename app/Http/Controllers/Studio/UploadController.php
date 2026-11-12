<?php

namespace App\Http\Controllers\Studio;

use App\Http\Controllers\Controller;
use App\Services\ImageUploader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use RuntimeException;

class UploadController extends Controller
{
    public function __invoke(Request $request, ImageUploader $uploader): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'kind' => ['nullable', 'in:project,avatar'],
        ]);

        $key = 'upload|'.$request->user()->id;

        if (RateLimiter::tooManyAttempts($key, 30)) {
            return response()->json(['ok' => false, 'error' => 'Slow down a moment. Too many uploads.'], 429);
        }

        RateLimiter::hit($key, 60);

        try {
            $result = $uploader->store(
                $request->file('file'),
                $request->user()->id,
                $request->input('kind') === 'avatar' ? 512 : 1600,
            );
        } catch (RuntimeException $e) {
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 422);
        }

        return response()->json([
            'ok' => true,
            'path' => $result['path'],
            'url' => $result['url'],
        ]);
    }
}
