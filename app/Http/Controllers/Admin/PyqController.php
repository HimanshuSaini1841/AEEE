<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pyq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PyqController extends Controller
{
    /**
     * Display a listing of PYQs with year and subject filters.
     */
    public function index(Request $request): View
    {
        $year = $request->query('year');
        $subject = $request->query('subject');

        $query = Pyq::query();

        if ($year) {
            $query->where('year', $year);
        }

        if ($subject) {
            $query->where('subject', $subject);
        }

        $pyqs = $query->orderBy('year', 'desc')->latest('id')->paginate(20)->withQueryString();

        // Get unique years for the filter dropdown
        $availableYears = Pyq::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');

        return view('admin.pyqs.index', compact('pyqs', 'availableYears', 'year', 'subject'));
    }

    /**
     * Show form for uploading a new PYQ PDF.
     */
    public function create(): View
    {
        $currentYear = (int) date('Y');
        return view('admin.pyqs.create', compact('currentYear'));
    }

    /**
     * Store uploaded PYQ PDF file and create database record.
     */
    public function store(Request $request): RedirectResponse
    {
        // Catch upload errors from PHP's upload_max_filesize before validation
        if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] !== UPLOAD_ERR_OK) {
            $errorCode = $_FILES['pdf_file']['error'];
            if ($errorCode === UPLOAD_ERR_INI_SIZE || $errorCode === UPLOAD_ERR_FORM_SIZE) {
                return back()->withInput()->withErrors([
                    'pdf_file' => 'The uploaded file exceeds the PHP server upload limit. Please stop and restart "php artisan serve" so the 64 MB upload limit takes effect.',
                ]);
            }
        }

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'year' => 'required|integer|min:2000|max:2030',
            'subject' => 'required|string|max:100',
            'total_pages' => 'nullable|integer|min:1|max:500',
            'pdf_file' => 'required|file|max:51200', // Max 50 MB
        ], [
            'pdf_file.required' => 'Please select a PDF file to upload.',
            'pdf_file.max' => 'The PDF file size cannot exceed 50 MB.',
        ]);

        $file = $request->file('pdf_file');
        $ext = strtolower($file->getClientOriginalExtension());
        if ($ext !== 'pdf') {
            return back()->withInput()->withErrors([
                'pdf_file' => 'The uploaded file must be a valid PDF document (ending in .pdf).',
            ]);
        }

        $originalName = $file->getClientOriginalName();
        $fileSizeBytes = $file->getSize();

        // Format file size nicely
        if ($fileSizeBytes >= 1048576) {
            $fileSizeFormatted = round($fileSizeBytes / 1048576, 2) . ' MB';
        } else {
            $fileSizeFormatted = round($fileSizeBytes / 1024, 1) . ' KB';
        }

        // Store file in storage/app/public/pyqs
        $storedPath = $file->store('pyqs', 'public');

        Pyq::create([
            'title' => trim($validated['title']),
            'year' => (int) $validated['year'],
            'subject' => trim($validated['subject']),
            'total_pages' => $validated['total_pages'] ?? null,
            'file_path' => $storedPath,
            'file_name' => $originalName,
            'file_size_formatted' => $fileSizeFormatted,
            'file_size_bytes' => $fileSizeBytes,
            'is_active' => true,
        ]);

        return redirect()->route('admin.pyqs.index')->with('success', "PYQ '{$validated['title']}' for year {$validated['year']} uploaded successfully.");
    }

    /**
     * Toggle visibility of a PYQ.
     */
    public function toggleStatus(int $id): RedirectResponse
    {
        $pyq = Pyq::findOrFail($id);
        $pyq->update(['is_active' => !$pyq->is_active]);

        $status = $pyq->is_active ? 'published' : 'hidden';
        return back()->with('success', "PYQ paper has been marked as {$status}.");
    }

    /**
     * Permanently delete a PYQ and remove its PDF from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $pyq = Pyq::findOrFail($id);
        $title = $pyq->title;

        if (!empty($pyq->file_path)) {
            try {
                if (Storage::disk('public')->exists($pyq->file_path)) {
                    Storage::disk('public')->delete($pyq->file_path);
                }
            } catch (\Throwable $e) {
                // Ignore storage disk failure
            }

            $fullPath = storage_path('app/public/' . $pyq->file_path);
            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }
        }

        $pyq->delete();

        return redirect()->route('admin.pyqs.index')->with('success', "PYQ paper '{$title}' and its PDF file have been deleted.");
    }
}
