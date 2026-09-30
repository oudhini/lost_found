<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Notifications\Notification;

/**
 * Sent to the person who reported a document lost, confirming it was
 * handed back (to them or to whoever picked it up on their behalf).
 */
class DocumentReturnedToOwner extends Notification
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
                'Votre %s (%s) a été restitué à %s.',
                $this->document->typeLabel(),
                $this->document->nom_present_sur_le_document,
                $this->document->restitue_a
            ),
        ];
    }
}
