<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Document;
use App\Support\RoleLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = $request->user()->notifications()->paginate(15);
        $pageTitle = 'Notifications';
        $breadcrumb = ['Notifications'];
        $layout = RoleLayout::for($request->user());

        return view('notifications.index', compact('notifications', 'pageTitle', 'breadcrumb', 'layout'));
    }

    public function markAsRead(Request $request, string $id): RedirectResponse
    {
        // Scoped to the current user's notifications: another user's id yields a 404.
        $notification = $request->user()->notifications()->whereKey($id)->firstOrFail();
        $notification->markAsRead();

        $documentId = $notification->data['document_id'] ?? null;
        $user = $request->user();

        if ($documentId !== null) {
            // A manager can only open documents that are (still) in their own depot.
            if ($user->isGerant()) {
                if ($user->depot?->documents()->whereKey($documentId)->exists()) {
                    return redirect()->route('manager.documents.show', $documentId);
                }
            } elseif ($user->isSuperviseur()) {
                if (Document::whereKey($documentId)->exists()) {
                    return redirect()->route('admin.documents.show', $documentId);
                }
            } elseif (Document::whereKey($documentId)->exists()) {
                // Regular user: the shared read-only page works for any document they can be notified about.
                return redirect()->route('documents.show', $documentId);
            }
        }

        return redirect()->route('notifications.index');
    }

    public function markAllAsRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        return redirect()->route('notifications.index')->with('success', 'Toutes les notifications sont marquées comme lues.');
    }
}
