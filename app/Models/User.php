<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Workspace milikku (aku sebagai owner).
     */
    public function lists(): HasMany
    {
        return $this->hasMany(TaskList::class, 'owner_id');
    }

    /**
     * Workspace tempat aku menjadi member.
     */
    public function memberLists(): BelongsToMany
    {
        return $this->belongsToMany(TaskList::class, 'list_user', 'user_id', 'list_id')
            ->withPivot('joined_at');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
