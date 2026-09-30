<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_USER = 'utilisateur';

    public const ROLE_MANAGER = 'gerant';

    public const ROLE_SUPERVISOR = 'superviseur';

    /**
     * The attributes that are mass assignable.
     *
     * `is_active` is deliberately absent: only a supervisor action may change it.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
            'is_active' => 'boolean',
        ];
    }

    public function isGerant(): bool
    {
        return $this->role === self::ROLE_MANAGER;
    }

    public function isSuperviseur(): bool
    {
        return $this->role === self::ROLE_SUPERVISOR;
    }

    /**
     * Depot managed by this user (only meaningful for managers).
     */
    public function depot(): HasOne
    {
        return $this->hasOne(Depot::class, 'gerant_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
