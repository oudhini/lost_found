<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Depot extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'actif';

    public const STATUS_INACTIVE = 'inactif';

    protected $fillable = ['name', 'address', 'opening_hours', 'latitude', 'longitude', 'gerant_id', 'statut', 'contact'];

    public function gerant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gerant_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * A depot is active if, and only if, it has a manager.
     */
    public function assignManager(User $manager): void
    {
        $this->forceFill([
            'gerant_id' => $manager->id,
            'statut' => self::STATUS_ACTIVE,
        ])->save();
    }

    public function releaseManager(): void
    {
        $this->forceFill([
            'gerant_id' => null,
            'statut' => self::STATUS_INACTIVE,
        ])->save();
    }
}
