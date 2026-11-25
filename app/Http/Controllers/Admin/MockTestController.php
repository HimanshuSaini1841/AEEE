<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MockTest;
use App\Models\MockTestAttempt;
use App\Models\MockTestQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MockTestController extends Controller
{
    /**
     * Display a listing of mock tests with key statistics.
     */
    public function index(Request $request): View
    {
        $mockTests = MockTest::withCount(['questions', 'attempts'])
            ->orderBy('order_num', 'asc')
            ->latest('id')
            ->paginate(15);

        return view('admin.mock_tests.index', compact('mockTests'));
    }

    /**
     * Show form for creating a new mock test.
     */
    public function create(): View
    {
        return view('admin.mock_tests.create');
    }

    /**
     * Store a newly created mock test.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:191',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1|max:600',
            'duration_hours' => 'nullable|integer|min:0|max:10',
            'duration_mins' => 'nullable|integer|min:0|max:59',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'total_marks' => 'required|integer|min:10|max:1000',
            'total_questions' => 'required|integer|min:1|max:300',
            'positive_marks_default' => 'required|numeric|min:0.5|max:10',
            'negative_marks_default' => 'required|numeric|min:0|max:10',
            'instructions' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'order_num' => 'nullable|integer',
        ]);

        // Compute total duration in minutes if hours/mins provided
        $hours = (int) ($request->input('duration_hours') ?? 0);
        $mins = (int) ($request->input('duration_mins') ?? 0);
        if ($hours > 0 || $mins > 0) {
            $validated['duration_minutes'] = ($hours * 60) + $mins;
        } elseif (empty($validated['duration_minutes'])) {
            $validated['duration_minutes'] = 150;
        }
        unset($validated['duration_hours'], $validated['duration_mins']);

        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(5);
        $validated['is_active'] = $request->has('is_active');
        $validated['order_num'] = $validated['order_num'] ?? 0;

        $mockTest = MockTest::create($validated);

        return redirect()->route('admin.mock-tests.questions', $mockTest->id)
            ->with('success', "Mock Test '{$mockTest->title}' created successfully! You can now add questions.");
    }

    /**
     * Show form for editing mock test metadata.
     */
    public function edit(int $id): View
    {
        $mockTest = MockTest::withCount(['questions', 'attempts'])->findOrFail($id);
        return view('admin.mock_tests.edit', compact('mockTest'));
    }

    /**
     * Update mock test metadata.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $mockTest = MockTest::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:191',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1|max:600',
            'duration_hours' => 'nullable|integer|min:0|max:10',
            'duration_mins' => 'nullable|integer|min:0|max:59',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'total_marks' => 'required|integer|min:10|max:1000',
            'total_questions' => 'required|integer|min:1|max:300',
            'positive_marks_default' => 'required|numeric|min:0.5|max:10',
            'negative_marks_default' => 'required|numeric|min:0|max:10',
            'instructions' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'order_num' => 'nullable|integer',
        ]);

        // Compute total duration in minutes if hours/mins provided
        $hours = (int) ($request->input('duration_hours') ?? 0);
        $mins = (int) ($request->input('duration_mins') ?? 0);
        if ($hours > 0 || $mins > 0) {
            $validated['duration_minutes'] = ($hours * 60) + $mins;
        } elseif (empty($validated['duration_minutes'])) {
            $validated['duration_minutes'] = $mockTest->duration_minutes ?: 150;
        }
        unset($validated['duration_hours'], $validated['duration_mins']);

        $validated['is_active'] = $request->has('is_active');
        $mockTest->update($validated);

        return redirect()->route('admin.mock-tests.index')
            ->with('success', "Mock Test '{$mockTest->title}' updated successfully!");
    }

    /**
     * Toggle active status.
     */
    public function toggle(int $id): RedirectResponse
    {
        $mockTest = MockTest::findOrFail($id);
        $mockTest->is_active = !$mockTest->is_active;
        $mockTest->save();

        $status = $mockTest->is_active ? 'Activated' : 'Deactivated';
        return back()->with('success', "Mock Test {$status} successfully.");
    }

    /**
     * Delete mock test and its related questions and attempts.
     */
    public function destroy(int $id): RedirectResponse
    {
        $mockTest = MockTest::findOrFail($id);
        $title = $mockTest->title;
        $mockTest->delete();

        return redirect()->route('admin.mock-tests.index')
            ->with('success', "Mock test '{$title}' and all its data were permanently deleted.");
    }

    /**
     * Questions management for a specific mock test.
     */
    public function questions(Request $request, int $id): View
    {
        $mockTest = MockTest::withCount('questions')->findOrFail($id);
        
        $subject = $request->query('subject');
        $query = $mockTest->questions();

        if ($subject && $subject !== 'All') {
            $query->where('subject', $subject);
        }

        $questions = $query->orderBy('order_num', 'asc')->paginate(25)->withQueryString();

        // Subject stats
        $subjectStats = MockTestQuestion::where('mock_test_id', $id)
            ->selectRaw('subject, count(*) as count')
            ->groupBy('subject')
            ->pluck('count', 'subject')
            ->toArray();

        return view('admin.mock_tests.questions', compact('mockTest', 'questions', 'subject', 'subjectStats'));
    }

    /**
     * Store a question into the mock test.
     */
    public function storeQuestion(Request $request, int $id): RedirectResponse
    {
        $mockTest = MockTest::findOrFail($id);

        $validated = $request->validate([
            'subject' => 'required|string|in:Mathematics,Physics,Chemistry,English',
            'topic' => 'nullable|string|max:100',
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_option' => 'required|string|in:A,B,C,D',
            'explanation' => 'nullable|string',
            'positive_marks' => 'nullable|numeric|min:0.5|max:10',
            'negative_marks' => 'nullable|numeric|min:0|max:10',
            'order_num' => 'nullable|integer',
        ]);

        $validated['mock_test_id'] = $mockTest->id;
        $validated['positive_marks'] = $validated['positive_marks'] ?? $mockTest->positive_marks_default;
        $validated['negative_marks'] = $validated['negative_marks'] ?? $mockTest->negative_marks_default;

        // Auto assign next order number if omitted
        if (empty($validated['order_num'])) {
            $maxOrder = MockTestQuestion::where('mock_test_id', $id)->max('order_num');
            $validated['order_num'] = ($maxOrder ?? 0) + 1;
        }

        MockTestQuestion::create($validated);
        $this->syncMockTestMetrics($mockTest);

        return back()->with('success', "Question #{$validated['order_num']} added successfully!");
    }

    /**
     * Show form for editing a question.
     */
    public function editQuestion(int $testId, int $questionId): View
    {
        $mockTest = MockTest::findOrFail($testId);
        $question = MockTestQuestion::where('mock_test_id', $testId)->findOrFail($questionId);

        return view('admin.mock_tests.edit_question', compact('mockTest', 'question'));
    }

    /**
     * Update an existing question.
     */
    public function updateQuestion(Request $request, int $testId, int $questionId): RedirectResponse
    {
        $question = MockTestQuestion::where('mock_test_id', $testId)->findOrFail($questionId);

        $validated = $request->validate([
            'subject' => 'required|string|in:Mathematics,Physics,Chemistry,English',
            'topic' => 'nullable|string|max:100',
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_option' => 'required|string|in:A,B,C,D',
            'explanation' => 'nullable|string',
            'positive_marks' => 'required|numeric|min:0.5|max:10',
            'negative_marks' => 'required|numeric|min:0|max:10',
            'order_num' => 'required|integer',
        ]);

        $question->update($validated);
        $this->syncMockTestMetrics($question->mockTest);

        return redirect()->route('admin.mock-tests.questions', $testId)
            ->with('success', "Question #{$question->order_num} updated successfully!");
    }

    /**
     * Delete a question.
     */
    public function destroyQuestion(int $testId, int $questionId): RedirectResponse
    {
        $mockTest = MockTest::findOrFail($testId);
        $question = MockTestQuestion::where('mock_test_id', $testId)->findOrFail($questionId);
        $order = $question->order_num;
        $question->delete();

        $this->syncMockTestMetrics($mockTest);

        return back()->with('success', "Question #{$order} deleted successfully.");
    }

    /**
     * Dynamically recalculate total questions and marks from actual test questions.
     */
    private function syncMockTestMetrics(MockTest $mockTest): void
    {
        $mockTest->total_questions = $mockTest->questions()->count();
        $mockTest->total_marks = (int) $mockTest->questions()->sum('positive_marks');
        $mockTest->save();
        $mockTest->clearCache();
    }

    /**
     * View all student attempts for a mock test.
     */
    public function attempts(Request $request, int $id): View
    {
        $mockTest = MockTest::findOrFail($id);

        $attempts = MockTestAttempt::with('student')
            ->where('mock_test_id', $id)
            ->orderBy('score', 'desc')
            ->latest('id')
            ->paginate(25);

        $totalAttempts = MockTestAttempt::where('mock_test_id', $id)->count();
        $avgScore = MockTestAttempt::where('mock_test_id', $id)->avg('score') ?? 0;
        $topScore = MockTestAttempt::where('mock_test_id', $id)->max('score') ?? 0;

        return view('admin.mock_tests.attempts', compact('mockTest', 'attempts', 'totalAttempts', 'avgScore', 'topScore'));
    }

    /**
     * View student attempt question breakdown.
     */
    public function attemptDetail(int $testId, int $attemptId): View
    {
        $mockTest = MockTest::findOrFail($testId);
        $attempt = MockTestAttempt::with(['student', 'answers.question'])
            ->where('mock_test_id', $testId)
            ->findOrFail($attemptId);

        return view('admin.mock_tests.attempt_detail', compact('mockTest', 'attempt'));
    }
}
