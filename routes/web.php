<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DynamicFieldController;
use App\Http\Controllers\Admin\PushNotificationController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\FrontendController;
use App\Http\Middleware\AdminAuthMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Frontend Website Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [FrontendController::class, 'home'])->name('frontend.home');
Route::get('/about-us', [FrontendController::class, 'about'])->name('frontend.about');
Route::get('/contact-us', [FrontendController::class, 'contact'])->name('frontend.contact');
Route::post('/contact-us', [FrontendController::class, 'contactSubmit'])->name('frontend.contact.submit');
Route::get('/privacy-policy', [FrontendController::class, 'privacy'])->name('frontend.privacy');
Route::get('/terms-and-conditions', [FrontendController::class, 'terms'])->name('frontend.terms');

/*
|--------------------------------------------------------------------------
| Admin Authentication & Management Routes
|--------------------------------------------------------------------------
*/

// Admin Authentication Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    // Protected Admin Panel Routes
    Route::middleware([AdminAuthMiddleware::class])->group(function () {
        
        // 1. Dashboard & Real-time Stats
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // 2. Students Management
        Route::get('/students', [StudentController::class, 'index'])->name('admin.students.index');
        Route::get('/students/{id}', [StudentController::class, 'show'])->name('admin.students.show');
        Route::post('/students/{id}/toggle-status', [StudentController::class, 'toggleStatus'])->name('admin.students.toggle-status');
        Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('admin.students.destroy');

        // 3. Dynamic Profile Fields Management (Classes, States, Preparing For)
        Route::prefix('dynamic-fields')->group(function () {
            // Classes
            Route::get('/classes', [DynamicFieldController::class, 'classes'])->name('admin.fields.classes');
            Route::post('/classes', [DynamicFieldController::class, 'storeClass'])->name('admin.fields.classes.store');
            Route::post('/classes/{id}/toggle', [DynamicFieldController::class, 'toggleClassStatus'])->name('admin.fields.classes.toggle');

            // States
            Route::get('/states', [DynamicFieldController::class, 'states'])->name('admin.fields.states');
            Route::post('/states', [DynamicFieldController::class, 'storeState'])->name('admin.fields.states.store');
            Route::post('/states/{id}/toggle', [DynamicFieldController::class, 'toggleStateStatus'])->name('admin.fields.states.toggle');

            // Preparing For (Target Exams)
            Route::get('/preparing-for', [DynamicFieldController::class, 'preparingFor'])->name('admin.fields.preparing_for');
            Route::post('/preparing-for', [DynamicFieldController::class, 'storePreparingFor'])->name('admin.fields.preparing_for.store');
            Route::post('/preparing-for/{id}/toggle', [DynamicFieldController::class, 'togglePreparingForStatus'])->name('admin.fields.preparing_for.toggle');
        });

        // 4. Gateway Settings (Fast2SMS Keys & Firebase Push Configuration)
        Route::prefix('settings')->group(function () {
            Route::get('/fast2sms', [SettingController::class, 'fast2sms'])->name('admin.settings.fast2sms');
            Route::post('/fast2sms', [SettingController::class, 'updateFast2sms'])->name('admin.settings.fast2sms.update');
            Route::post('/fast2sms/test', [SettingController::class, 'testFast2sms'])->name('admin.settings.fast2sms.test');

            Route::get('/firebase', [SettingController::class, 'firebase'])->name('admin.settings.firebase');
            Route::post('/firebase', [SettingController::class, 'updateFirebase'])->name('admin.settings.firebase.update');
            Route::post('/firebase/test', [SettingController::class, 'testFirebase'])->name('admin.settings.firebase.test');

            // App Controls & Sidebar Dynamic Button
            Route::get('/app-controls', [SettingController::class, 'appControls'])->name('admin.settings.app-controls');
            Route::post('/app-controls', [SettingController::class, 'updateAppControls'])->name('admin.settings.app-controls.update');
        });

        // 5. Firebase Push Notifications
        Route::prefix('notifications')->group(function () {
            Route::get('/create', [PushNotificationController::class, 'create'])->name('admin.notifications.create');
            Route::post('/send', [PushNotificationController::class, 'send'])->name('admin.notifications.send');
            Route::get('/history', [PushNotificationController::class, 'history'])->name('admin.notifications.history');
        });

        // 6. PYQ Previous Years Question Papers Management
        Route::prefix('pyqs')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\PyqController::class, 'index'])->name('admin.pyqs.index');
            Route::get('/create', [\App\Http\Controllers\Admin\PyqController::class, 'create'])->name('admin.pyqs.create');
            Route::post('/', [\App\Http\Controllers\Admin\PyqController::class, 'store'])->name('admin.pyqs.store');
            Route::post('/{id}/toggle', [\App\Http\Controllers\Admin\PyqController::class, 'toggleStatus'])->name('admin.pyqs.toggle');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\PyqController::class, 'destroy'])->name('admin.pyqs.destroy');
        });

        // 7. Mock Tests Management
        Route::prefix('mock-tests')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MockTestController::class, 'index'])->name('admin.mock-tests.index');
            Route::get('/create', [\App\Http\Controllers\Admin\MockTestController::class, 'create'])->name('admin.mock-tests.create');
            Route::post('/', [\App\Http\Controllers\Admin\MockTestController::class, 'store'])->name('admin.mock-tests.store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\MockTestController::class, 'edit'])->name('admin.mock-tests.edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\MockTestController::class, 'update'])->name('admin.mock-tests.update');
            Route::post('/{id}/toggle', [\App\Http\Controllers\Admin\MockTestController::class, 'toggle'])->name('admin.mock-tests.toggle');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\MockTestController::class, 'destroy'])->name('admin.mock-tests.destroy');

            // Questions Management with LaTeX Math Preview
            Route::get('/{id}/questions', [\App\Http\Controllers\Admin\MockTestController::class, 'questions'])->name('admin.mock-tests.questions');
            Route::post('/{id}/questions', [\App\Http\Controllers\Admin\MockTestController::class, 'storeQuestion'])->name('admin.mock-tests.questions.store');
            Route::get('/{id}/questions/{qId}/edit', [\App\Http\Controllers\Admin\MockTestController::class, 'editQuestion'])->name('admin.mock-tests.questions.edit');
            Route::put('/{id}/questions/{qId}', [\App\Http\Controllers\Admin\MockTestController::class, 'updateQuestion'])->name('admin.mock-tests.questions.update');
            Route::delete('/{id}/questions/{qId}', [\App\Http\Controllers\Admin\MockTestController::class, 'destroyQuestion'])->name('admin.mock-tests.questions.destroy');

            // Student Attempts & Performance Analytics
            Route::get('/{id}/attempts', [\App\Http\Controllers\Admin\MockTestController::class, 'attempts'])->name('admin.mock-tests.attempts');
            Route::get('/{id}/attempts/{attemptId}', [\App\Http\Controllers\Admin\MockTestController::class, 'attemptDetail'])->name('admin.mock-tests.attempt-detail');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Live Server Maintenance & Migration Runner (For cPanel without Terminal)
|--------------------------------------------------------------------------
*/
Route::get('/system-setup-runner-2026', function (\Illuminate\Http\Request $request) {
    $secret = $request->query('key', 'aeee2026');
    if ($secret !== 'aeee2026') {
        abort(403, 'Unauthorized access.');
    }

    $action = $request->query('action', 'all');
    $output = '';

    try {
        if ($action === 'migrate' || $action === 'all') {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            $output .= "=== MIGRATIONS OUTPUT ===\n" . \Illuminate\Support\Facades\Artisan::output() . "\n";
        }
        if ($action === 'seed' || $action === 'all') {
            \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'MockTestSeeder', '--force' => true]);
            $output .= "=== SEEDER OUTPUT ===\n" . \Illuminate\Support\Facades\Artisan::output() . "\n";
        }
        if ($action === 'clear_cache' || $action === 'all') {
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
            \Illuminate\Support\Facades\Artisan::call('view:clear');
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            \Illuminate\Support\Facades\Artisan::call('route:clear');
            $output .= "=== CACHE CLEARED SUCCESSFULLY ===\n";
        }

        return response("<html><body style='font-family: monospace; background: #0f172a; color: #38bdf8; padding: 24px;'>
            <h2>🚀 CrackAEEE System Maintenance Runner</h2>
            <div style='margin-bottom: 16px;'>
                <a style='color: #a855f7; font-weight: bold; margin-right: 14px;' href='?key=aeee2026&action=all'>[⚡ Run All (Migrate + Seed MockTests + Clear Cache)]</a>
                <a style='color: #4ade80; margin-right: 14px;' href='?key=aeee2026&action=migrate'>[Run Migrate]</a>
                <a style='color: #facc15; margin-right: 14px;' href='?key=aeee2026&action=seed'>[Run MockTest Seeder]</a>
                <a style='color: #f43f5e; margin-right: 14px;' href='?key=aeee2026&action=clear_cache'>[Clear Cache]</a>
            </div>
            <pre style='background: #1e293b; padding: 16px; border-radius: 8px; color: #f8fafc; overflow-x: auto;'>" . htmlspecialchars($output) . "</pre>
        </body></html>");
    } catch (\Throwable $e) {
        return response("<html><body style='font-family: monospace; background: #0f172a; color: #ef4444; padding: 24px;'>
            <h2>❌ Error: " . htmlspecialchars($e->getMessage()) . "</h2>
            <pre style='background: #1e293b; padding: 16px; border-radius: 8px; color: #f8fafc;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>
        </body></html>", 500);
    }
});
