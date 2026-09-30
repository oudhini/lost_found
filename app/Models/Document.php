<?php

namespace App\Models;

use App\Enums\DocumentStatus;
use DomainException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Document extends Model
{
    use HasFactory;

    /** Directory (relative to /public) where uploaded document photos live. */
    public const PHOTO_DIRECTORY = 'assets/images/documentspictures';

    /** @var array<string, string> value stored in DB => label shown to users */
    public const TYPES = [
        'passport' => 'Passeport',
        'permis_de_conduire' => 'Permis de conduire',
        'CNI' => "Carte d'identité",
        'acte_de_naissance' => 'Acte de naissance',
        'diplome_academique' => 'Diplôme académique',
        'autres' => 'Autre',
    ];

    protected $fillable = [
        'type_document',
        'nom_present_sur_le_document',
        'status',
        'lieu_de_perte',
        'user_id',
        'photos',
        'additional_info',
        'numero_du_document',
        'depot_id',
        'contact_info',
        'date_de_perte',
    ];

    protected function casts(): array
    {
        return [
            'restitue_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function depot(): BelongsTo
    {
        return $this->belongsTo(Depot::class);
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);

        if ($term === '') {
            return $query;
        }

        $like = '%'.$term.'%';

        return $query->where(function (Builder $q) use ($like): void {
            $q->where('nom_present_sur_le_document', 'like', $like)
                ->orWhere('numero_du_document', 'like', $like)
                ->orWhere('lieu_de_perte', 'like', $like)
                ->orWhereHas('user', function (Builder $u) use ($like): void {
                    $u->where('name', 'like', $like)->orWhere('email', 'like', $like);
                });
        });
    }

    /**
     * Moves uploaded images into public/ under random server-side names
     * (the client file name is never trusted) and returns the stored names.
     *
     * @param  array<int, UploadedFile>  $images
     * @return list<string>
     */
    public static function storeUploadedPhotos(array $images): array
    {
        $names = [];

        foreach ($images as $image) {
            $name = Str::random(40).'.'.$image->guessExtension();
            $image->move(public_path(self::PHOTO_DIRECTORY), $name);
            $names[] = $name;
        }

        return $names;
    }

    /**
     * Simple heuristic used to surface "this might be yours" suggestions: same
     * document type, still awaiting pickup at a depot, name starting the same way.
     * Kept intentionally simple (no fuzzy matching / scoring) to stay cheap and predictable.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, self>
     */
    public function findPossibleMatches(int $limit = 3): \Illuminate\Database\Eloquent\Collection
    {
        if ($this->status !== DocumentStatus::Lost->value) {
            return new \Illuminate\Database\Eloquent\Collection();
        }

        $firstWord = strtok($this->nom_present_sur_le_document, ' ') ?: '';

        return self::where('status', DocumentStatus::AwaitingPickup->value)
            ->where('type_document', $this->type_document)
            ->when($firstWord !== '', fn (Builder $query) => $query->where('nom_present_sur_le_document', 'like', $firstWord.'%'))
            ->with('depot:id,name')
            ->limit($limit)
            ->get();
    }

    public function statusEnum(): ?DocumentStatus
    {
        return DocumentStatus::tryFrom((string) $this->status);
    }

    public function statusLabel(): string
    {
        return $this->statusEnum()?->label() ?? (string) $this->status;
    }

    public function statusBadgeClass(): string
    {
        return $this->statusEnum()?->badgeClass() ?? 'bg-secondary';
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type_document] ?? (string) $this->type_document;
    }

    /**
     * Photos are stored as a comma separated list of file names.
     *
     * @return list<string>
     */
    public function photoNames(): array
    {
        $names = array_map(
            static fn (string $name): string => basename(trim($name)),
            explode(',', (string) $this->photos)
        );

        return array_values(array_filter($names, static fn (string $name): bool => $name !== ''));
    }

    /**
     * @return list<string>
     */
    public function photoUrls(): array
    {
        return array_map(
            static fn (string $name): string => asset(self::PHOTO_DIRECTORY.'/'.$name),
            $this->photoNames()
        );
    }

    /**
     * Business rule: a document can be (re)assigned to a depot while it is
     * still lost or already waiting for pickup, never once it was returned.
     */
    public function assignToDepot(Depot $depot): void
    {
        if (! in_array($this->status, [DocumentStatus::Lost->value, DocumentStatus::AwaitingPickup->value], true)) {
            throw new DomainException('Un document déjà restitué ne peut plus être affecté à un dépôt.');
        }

        $this->forceFill([
            'depot_id' => $depot->id,
            'status' => DocumentStatus::AwaitingPickup->value,
        ])->save();
    }

    /**
     * Business rule: only a document waiting for pickup can be handed back.
     */
    public function restitute(string $recipient, User $handler): void
    {
        if ($this->status !== DocumentStatus::AwaitingPickup->value) {
            throw new DomainException('Seul un document en attente de retrait peut être restitué.');
        }

        $this->forceFill([
            'status' => DocumentStatus::Returned->value,
            'restitue_a' => $recipient,
            'restitue_at' => now(),
            'handled_by' => $handler->id,
        ])->save();
    }

    /**
     * Deletes the record and its photo files. Legacy uploads kept the client's
     * original file name, so a file still referenced by another document is kept.
     */
    public function deleteWithPhotos(): void
    {
        $names = $this->photoNames();

        $this->delete();

        foreach ($names as $name) {
            $stillUsed = self::query()->where('photos', 'like', '%'.$name.'%')->exists();

            if (! $stillUsed) {
                File::delete(public_path(self::PHOTO_DIRECTORY.'/'.$name));
            }
        }
    }
}
