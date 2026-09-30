<?php

declare(strict_types=1);

namespace App\Http\Controllers\Manager;

use App\Enums\DocumentStatus;
use App\Http\Controllers\Controller;
use App\Models\Depot;
use App\Models\Document;
use App\Notifications\DocumentAwaitingPickup;
use App\Notifications\DocumentReturnedToOwner;
use DomainException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Depot-manager workflow. Every query starts from the manager's own depot
 * ($depot->documents()), so a document id from another depot simply yields a 404 (no IDOR).
 * The "has.depot" middleware guarantees the manager has a depot.
 */
class DocumentController extends Controller
{
    private function depot(Request $request): Depot
    {
        return $request->user()->depot ?? abort(403);
    }

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in([DocumentStatus::AwaitingPickup->value, DocumentStatus::Returned->value])],
            'type' => ['nullable', Rule::in(array_keys(Document::TYPES))],
        ]);

        $depot = $this->depot($request);

        $documents = $depot->documents()
            ->search($filters['q'] ?? null)
            ->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status))
            ->when($filters['type'] ?? null, fn (Builder $query, string $type) => $query->where('type_document', $type))
            ->with('user:id,name')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $pageTitle = 'Documents du dépôt';
        $breadcrumb = ['Documents', $depot->name];
        $statuses = [DocumentStatus::AwaitingPickup, DocumentStatus::Returned];
        $types = Document::TYPES;

        return view('manager.documents.index', compact('documents', 'filters', 'statuses', 'types', 'depot', 'pageTitle', 'breadcrumb'));
    }

    public function show(Request $request, int $document): View
    {
        $document = $this->depot($request)->documents()
            ->with(['user:id,name,email,phone', 'handler:id,name'])
            ->findOrFail($document);

        $pageTitle = 'Détail du document';
        $breadcrumb = ['Documents', 'Détail'];

        return view('manager.documents.show', compact('document', 'pageTitle', 'breadcrumb'));
    }

    public function restitute(Request $request, int $document): RedirectResponse
    {
        $document = $this->depot($request)->documents()->findOrFail($document);

        $data = $request->validate([
            'restitue_a' => ['required', 'string', 'max:255'],
        ]);

        try {
            $document->restitute($data['restitue_a'], $request->user());
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        if ($document->user && $document->user->id !== $request->user()->id) {
            $document->user->notify(new DocumentReturnedToOwner($document));
        }

        return redirect()->route('manager.documents.show', $document->id)->with('success', 'Document restitué.');
    }

    /**
     * "Réceptionner": find the matching lost report and bring it into this depot.
     */
    public function receiveForm(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'min:2', 'max:100'],
        ]);

        $term = $filters['q'] ?? null;

        // No search term = no list: managers must look for a specific document, not browse every report.
        $matches = $term === null
            ? collect()
            : Document::where('status', DocumentStatus::Lost->value)
                ->search($term)
                ->with('user:id,name')
                ->latest('id')
                ->limit(20)
                ->get();

        $pageTitle = 'Réceptionner un document';
        $breadcrumb = ['Documents', 'Réceptionner'];

        return view('manager.receive', compact('matches', 'term', 'pageTitle', 'breadcrumb'));
    }

    public function receive(Request $request, int $document): RedirectResponse
    {
        // Only a still-lost report can be received: a document already taken by another depot is a 404 here.
        $report = Document::where('status', DocumentStatus::Lost->value)->findOrFail($document);

        $depot = $this->depot($request);
        $report->assignToDepot($depot);

        if ($report->user && $report->user->id !== $request->user()->id) {
            $report->user->notify(new DocumentAwaitingPickup($report, $depot));
        }

        return redirect()->route('manager.documents.show', $report->id)
            ->with('success', 'Document réceptionné : il est maintenant en attente de retrait dans votre dépôt.');
    }

    public function foundCreate(): View
    {
        $pageTitle = 'Enregistrer un document trouvé';
        $breadcrumb = ['Documents', 'Document trouvé'];
        $types = Document::TYPES;

        return view('manager.found-create', compact('pageTitle', 'breadcrumb', 'types'));
    }

    public function foundStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type_document' => ['required', Rule::in(array_keys(Document::TYPES))],
            'nom_present_sur_le_document' => ['required', 'string', 'max:255'],
            'numero_du_document' => ['nullable', 'string', 'max:100'],
            'lieu_de_perte' => ['required', 'string', 'max:255'],
            'date_de_perte' => ['required', 'date', 'before_or_equal:today'],
            'additional_info' => ['nullable', 'string', 'max:2000'],
            'photos' => ['nullable', 'array', 'max:5'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $depot = $this->depot($request);
        $manager = $request->user();

        // Columns keep their "lost" names: here they hold where and when the document was FOUND.
        $document = Document::create([
            'type_document' => $data['type_document'],
            'nom_present_sur_le_document' => $data['nom_present_sur_le_document'],
            'numero_du_document' => $data['numero_du_document'] ?? 'aucun numéro',
            'status' => DocumentStatus::AwaitingPickup->value,
            'lieu_de_perte' => $data['lieu_de_perte'],
            'date_de_perte' => $data['date_de_perte'],
            'additional_info' => $data['additional_info'] ?? null,
            'photos' => implode(',', Document::storeUploadedPhotos($request->file('photos', []))),
            'user_id' => $manager->id,
            'depot_id' => $depot->id,
            'contact_info' => $depot->contact ?: $manager->phone,
        ]);

        return redirect()->route('manager.documents.show', $document->id)
            ->with('success', 'Document trouvé enregistré dans votre dépôt.');
    }

    public function history(Request $request): View
    {
        $documents = $this->depot($request)->documents()
            ->where('status', DocumentStatus::Returned->value)
            ->with('handler:id,name')
            ->orderByDesc('restitue_at')
            ->paginate(15);

        $pageTitle = 'Historique des restitutions';
        $breadcrumb = ['Historique'];

        return view('manager.history', compact('documents', 'pageTitle', 'breadcrumb'));
    }
}
