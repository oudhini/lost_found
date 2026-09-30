<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Depot;
use App\Models\Document;
use Illuminate\Notifications\Notification;

/**
 * Sent to a depot manager when a supervisor routes a document to their depot.
 */
class DocumentAssignedToDepot extends Notification
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
                'Document à réceptionner : %s (%s) affecté à %s',
                $this->document->nom_present_sur_le_document,
                $this->document->typeLabel(),
                $this->depot->name
            ),
        ];
    }
}
