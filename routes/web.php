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

// ---------------------------- akhir [P2] -----------------------------

require __DIR__.'/auth.php';
