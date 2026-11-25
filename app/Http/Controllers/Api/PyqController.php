<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pyq;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PyqController extends Controller
{
    /**
     * Get list of all published PYQs.
     * Supports optional ?year=2025 or ?subject=Physics filter.
     */
    public function index(Request $request): JsonResponse
    {
        $year = $request->query('year');
        $subject = $request->query('subject');

        $query = Pyq::query()->active();

        if ($year) {
            $query->where('year', (int) $year);
        }

        if ($subject) {
            $query->where('subject', $subject);
        }

        $pyqs = $query->with(['mockTest' => function ($q) {
                $q->where('is_active', true);
            }])
            ->orderBy('year', 'desc')
            ->latest('id')
            ->get();

        // Pre-fetch active PYQ mock tests mapped by year as fallback
        $pyqMockTestsByYear = \App\Models\MockTest::active()
            ->where('is_pyq', true)
            ->whereNotNull('pyq_year')
            ->get(['id', 'pyq_year', 'title'])
            ->keyBy('pyq_year');

        // Group by year for the app UI
        $availableYears = Pyq::active()->distinct()->orderBy('year', 'desc')->pluck('year');

        return response()->json([
            'success' => true,
            'count' => $pyqs->count(),
            'available_years' => $availableYears,
            'data' => $pyqs->map(function ($item) use ($pyqMockTestsByYear) {
                $cbtMockTest = $item->mockTest ?: ($pyqMockTestsByYear->get($item->year));

                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'year' => $item->year,
                    'subject' => $item->subject,
                    'file_name' => $item->file_name,
                    'file_size' => $item->file_size_formatted,
                    'file_size_bytes' => $item->file_size_bytes,
                    'total_pages' => $item->total_pages,
                    'download_url' => url("/api/v1/pyqs/{$item->id}/download"),
                    'has_cbt_test' => !empty($cbtMockTest),
                    'cbt_mock_test_id' => $cbtMockTest?->id,
                    'created_at' => $item->created_at?->format('d M Y'),
                ];
            }),
        ]);
    }

    /**
     * Secure PDF file stream / download for in-app viewing.
     * Prevents browser indexing and handles direct binary delivery to the app.
     */
    public function download(int $id)
    {
        try {
            $pyq = Pyq::active()->find($id);

            if (!$pyq) {
                return response()->json([
                    'success' => false,
                    'message' => 'Question paper record not found.',
                ], 404);
            }

            $relativePath = $pyq->file_path;
            $fullPath = storage_path('app/public/' . $relativePath);

            if (!file_exists($fullPath) || !is_readable($fullPath)) {
                $altPath = storage_path('app/' . $relativePath);
                if (file_exists($altPath) && is_readable($altPath)) {
                    $fullPath = $altPath;
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'PDF file is missing on server: ' . $pyq->file_name,
                        'path_checked' => $fullPath,
                    ], 404);
                }
            }

            // Increment view count safely
            try {
                $pyq->increment('view_count');
            } catch (\Throwable $e) {}

            return response()->file($fullPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $pyq->file_name . '"',
                'Cache-Control' => 'private, max-age=86400',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error serving PDF: ' . $e->getMessage(),
            ], 500);
        }
    }
}
