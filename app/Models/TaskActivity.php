<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Catatan aktivitas task (audit trail).
 * Diisi server-side bersama operasi task dalam satu transaksi atomik.
 */
#[Fillable(['task_id', 'user_id', 'action'])]
class TaskActivity extends Model
{
    protected $table = 'task_activities';

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}