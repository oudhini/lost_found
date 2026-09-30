<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Notifications\Notification;

/**
 * Sent to every supervisor when a user reports a lost document.
 * Stored in the database only; it is intentionally not queued so it works
 * without a running queue worker.
 */
class NewLostDocumentReported extends Notification
{
    public function __construct(private readonly Document $document)
    {
    }

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'document_id' => $this->document->id,
            'message' => sprintf(
                'Nouveau document signalé : %s (%s)',
                $this->document->nom_present_sur_le_document,
                $this->document->typeLabel()
            ),
            'reporter' => $this->document->user?->name,
        ];
    }
}
