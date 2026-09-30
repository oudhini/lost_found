<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Depot;
use App\Models\Document;
use Illuminate\Notifications\Notification;

/**
 * Sent to the person who reported a document lost, once it has been
 * routed to a depot and is ready for them to go pick it up.
 */
class DocumentAwaitingPickup extends Notification
{
    public function __construct(private readonly Document $document, private readonly Depot $depot)
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
                'Bonne nouvelle : votre %s (%s) est disponible au dépôt "%s".',
                $this->document->typeLabel(),
                $this->document->nom_present_sur_le_document,
                $this->depot->name
            ),
        ];
    }
}
