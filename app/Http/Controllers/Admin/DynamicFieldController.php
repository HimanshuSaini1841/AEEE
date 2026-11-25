<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PreparingFor;
use App\Models\StudentClass;
use App\Models\State;
use App\Services\DynamicFieldCacheService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DynamicFieldController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Classes Management
    |--------------------------------------------------------------------------
    */
    public function classes(): View
    {
        $classes = StudentClass::withCount('students')->orderBy('display_order')->get();
        return view('admin.dynamic_fields.classes', compact('classes'));
    }

    public function storeClass(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'display_order' => 'nullable|integer',
        ]);

        StudentClass::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . rand(100, 999),
            'display_order' => $validated['display_order'] ?? 0,
            'is_active' => true,
        ]);

        DynamicFieldCacheService::clearCache();

        return back()->with('success', 'Class added successfully.');
    }

    public function toggleClassStatus(int $id): RedirectResponse
    {
        $class = StudentClass::findOrFail($id);
        $class->update(['is_active' => !$class->is_active]);
        DynamicFieldCacheService::clearCache();

        return back()->with('success', 'Class status updated.');
    }

    /*
    |--------------------------------------------------------------------------
    | States Management
    |--------------------------------------------------------------------------
    */
    public function states(): View
    {
        $states = State::withCount('students')->orderBy('display_order')->orderBy('name')->get();
        return view('admin.dynamic_fields.states', compact('states'));
    }

    public function storeState(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:10',
            'display_order' => 'nullable|integer',
        ]);

        State::create([
            'name' => $validated['name'],
            'code' => strtoupper($validated['code'] ?? substr($validated['name'], 0, 2)),
            'display_order' => $validated['display_order'] ?? 0,
            'is_active' => true,
        ]);

        DynamicFieldCacheService::clearCache();

        return back()->with('success', 'State added successfully.');
    }

    public function toggleStateStatus(int $id): RedirectResponse
    {
        $state = State::findOrFail($id);
        $state->update(['is_active' => !$state->is_active]);
        DynamicFieldCacheService::clearCache();

        return back()->with('success', 'State status updated.');
    }

    /*
    |--------------------------------------------------------------------------
    | Preparing For (Target Exams) Management
    |--------------------------------------------------------------------------
    */
    public function preparingFor(): View
    {
        $exams = PreparingFor::withCount('students')->orderBy('display_order')->get();
        return view('admin.dynamic_fields.preparing_for', compact('exams'));
    }

    public function storePreparingFor(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'short_name' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer',
        ]);

        PreparingFor::create([
            'name' => $validated['name'],
            'short_name' => $validated['short_name'] ?? $validated['name'],
            'description' => $validated['description'] ?? null,
            'display_order' => $validated['display_order'] ?? 0,
            'is_active' => true,
        ]);

        DynamicFieldCacheService::clearCache();

        return back()->with('success', 'Target Exam option added successfully.');
    }

    public function togglePreparingForStatus(int $id): RedirectResponse
    {
        $exam = PreparingFor::findOrFail($id);
        $exam->update(['is_active' => !$exam->is_active]);
        DynamicFieldCacheService::clearCache();

        return back()->with('success', 'Target Exam status updated.');
    }
}
