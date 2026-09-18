<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Tamu otomatis diarahkan ke /login oleh middleware auth.
Route::redirect('/', '/dashboard');

// SRS-001: Dashboard redirect per role (admin → /admin/users, user → /lists).
Route::get('/dashboard', DashboardController::class)
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// =====================================================================
// [P1] AUTH, WORKSPACE & TASK CRUD — Programmer 1 (SRS-001 s.d. SRS-004)
// Route name: lists.index/create/store/show/edit/update/destroy |
//             tasks.store | tasks.edit | tasks.update | tasks.destroy
// =====================================================================

Route::middleware('auth')->group(function () {
    Route::get('/lists', [ListController::class, 'index'])->name('lists.index');
    Route::get('/lists/create', [ListController::class, 'create'])->name('lists.create');
    Route::post('/lists', [ListController::class, 'store'])->name('lists.store');
    Route::get('/lists/{list}', [ListController::class, 'show'])->name('lists.show');
    Route::get('/lists/{list}/edit', [ListController::class, 'edit'])->name('lists.edit');
    Route::put('/lists/{list}', [ListController::class, 'update'])->name('lists.update');
    Route::delete('/lists/{list}', [ListController::class, 'destroy'])->name('lists.destroy');

    Route::post('/lists/{list}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/lists/{list}/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/lists/{list}/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/lists/{list}/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});

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

// =====================================================================
// [PR3-P2] MY TASKS & SEARCH — SRS-010 (Programmer 2 Pertemuan 3)
// Route name: mytasks.index
// =====================================================================

Route::get('/mytasks', [\App\Http\Controllers\MyTaskController::class, 'index'])
    ->middleware('auth')
    ->name('mytasks.index');

// -------------------------- akhir [PR3-P2] ---------------------------

require __DIR__.'/auth.php';
