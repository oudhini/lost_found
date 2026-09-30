<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Document;
use App\Support\RoleLayout;
use Illuminate\View\View;

/**
 * Read-only detail page shared by every role. Supervisors and managers keep their
 * own dedicated pages (with the moderation actions); this one is the "Voir Détails"
 * destination from the public lost/found listings and from a user's own reports.
 */
class DocumentController extends Controller
{
    public function show(Document $document): View
    {
        $document->load(['user:id,name', 'depot:id,name,address', 'handler:id,name']);

        $isOwner = $document->user_id === auth()->id();
        $pageTitle = $document->typeLabel();
        $breadcrumb = ['Documents', $pageTitle];
        $layout = RoleLayout::for(auth()->user());

        $possibleMatches = $document->findPossibleMatches();

        return view('document.show', compact('document', 'isOwner', 'possibleMatches', 'pageTitle', 'breadcrumb', 'layout'));
    }
}
