<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Workspace (istilah UI). Entity/tabel/route tetap "lists".
 * Nama class TaskList karena "list" adalah reserved word di PHP.
 */
#[Fillable(['name', 'owner_id'])]
class TaskList extends Model
{
    protected $table = 'lists';

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'list_user', 'list_id', 'user_id')
            ->withPivot('joined_at');
    }

    /**
     * Task diurutkan berdasarkan tenggat (due_date) terdekat.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'list_id')->orderBy('due_date');
    }

    /**
     * Owner ATAU member workspace ini.
     */
    public function isAccessibleBy(User $user): bool
    {
        return $this->owner_id === $user->id
            || $this->members()->whereKey($user->id)->exists();
    }

    /**
     * done / total × 100; 0 jika belum ada task.
     */
    public function progressPercentage(): int
    {
        $total = $this->tasks()->count();

        if ($total === 0) {
            return 0;
        }

        return (int) round($this->tasks()->done()->count() / $total * 100);
    }
}
