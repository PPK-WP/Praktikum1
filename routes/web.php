<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Tamu otomatis diarahkan ke /login oleh middleware auth.
Route::redirect('/', '/dashboard');

// Dashboard bawaan baseline — P1 menggantinya dengan DashboardController (redirect per role).
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// =====================================================================
// [P1] AUTH, WORKSPACE & TASK CRUD — Programmer 1 (SRS-001 s.d. SRS-004)
// Route name: lists.index/create/store/show/edit/update/destroy |
//             tasks.store | tasks.edit | tasks.update | tasks.destroy
// =====================================================================

// ---------------------------- akhir [P1] -----------------------------

// =====================================================================
// [P2] TASK STATUS, COLLABORATION, PROGRESS & ADMIN — Programmer 2
//      (SRS-005 s.d. SRS-008)
// Route name: tasks.toggle | members.store | members.destroy |
//             admin.users.index/create/store/destroy
// =====================================================================

Route::middleware('auth')->group(function () {
    // SRS-005: Task Status
    Route::patch('/tasks/{task}/toggle', [\App\Http\Controllers\TaskStatusController::class, 'toggle'])->name('tasks.toggle');

    // SRS-006: Collaboration
    Route::post('/lists/{list}/members', [\App\Http\Controllers\MemberController::class, 'store'])->name('members.store');
    Route::delete('/lists/{list}/members/{user}', [\App\Http\Controllers\MemberController::class, 'destroy'])->name('members.destroy');
});

// SRS-008: Admin User Management
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.users.store');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');
});

// ---------------------------- akhir [P2] -----------------------------

require __DIR__.'/auth.php';
