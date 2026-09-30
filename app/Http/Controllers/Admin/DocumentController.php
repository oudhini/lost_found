<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\DocumentStatus;
use App\Http\Controllers\Controller;
use App\Models\Depot;
use App\Models\Document;
use App\Notifications\DocumentAssignedToDepot;
use App\Notifications\DocumentAwaitingPickup;
use App\Notifications\DocumentReturnedToOwner;
use DomainException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Supervisor-side management of every document on the platform.
 */
class DocumentController extends Controller
{
    private const PER_PAGE = 15;

    public function index(Request $request): View
    {
        $filters = $this->validatedFilters($request);

        $documents = $this->filteredQuery($filters)
            ->with(['user:id,name', 'depot:id,name'])
            ->latest('id')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        $pageTitle = 'Documents';
        $breadcrumb = ['Documents'];
        $statuses = DocumentStatus::cases();
        $types = Document::TYPES;
        $depots = Depot::orderBy('name')->get(['id', 'name']);

        return view('admin.documents.index', compact(
            'documents', 'filters', 'statuses', 'types', 'depots', 'pageTitle', 'breadcrumb'
        ));
    }

    public function show(Document $document): View
    {
        $document->load(['user:id,name,email,phone', 'depot:id,name', 'handler:id,name']);

        $pageTitle = 'Détail du document';
        $breadcrumb = ['Documents', 'Détail'];
        $activeDepots = Depot::where('statut', Depot::STATUS_ACTIVE)->orderBy('name')->get(['id', 'name']);

        return view('admin.documents.show', compact('document', 'activeDepots', 'pageTitle', 'breadcrumb'));
    }

    public function assignDepot(Request $request, Document $document): RedirectResponse
    {
        $data = $request->validate([
            'depot_id' => ['required', Rule::exists('depots', 'id')->where('statut', Depot::STATUS_ACTIVE)],
        ]);

        $depot = Depot::with('gerant')->findOrFail($data['depot_id']);
        $alreadyThere = $document->depot_id === $depot->id;

        try {
            $document->assignToDepot($depot);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        if (! $alreadyThere) {
            $depot->gerant?->notify(new DocumentAssignedToDepot($document, $depot));

            if ($document->user && $document->user->id !== $request->user()->id) {
                $document->user->notify(new DocumentAwaitingPickup($document, $depot));
            }
        }

        return redirect()->route('admin.documents.show', $document)
            ->with('success', 'Document affecté au dépôt : il est maintenant en attente de retrait.');
    }

    public function restitute(Request $request, Document $document): RedirectResponse
    {
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

        return redirect()->route('admin.documents.show', $document)
            ->with('success', 'Document restitué.');
    }

    public function destroy(Document $document): RedirectResponse
    {
        $document->deleteWithPhotos();

        return redirect()->route('admin.documents.index')->with('success', 'Document supprimé.');
    }

    public function export(Request $request): StreamedResponse
    {
        $query = $this->filteredQuery($this->validatedFilters($request))
            ->with(['user:id,name', 'depot:id,name']);

        $filename = 'documents-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($query): void {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM so Excel displays accents correctly

            $this->writeCsvRow($out, [
                'ID', 'Type', 'Nom sur le document', 'Numéro', 'Statut', 'Lieu de perte',
                'Date de perte', 'Signalé par', 'Contact', 'Dépôt', 'Restitué à', 'Restitué le', 'Créé le',
            ]);

            $query->chunkById(500, function ($documents) use ($out): void {
                foreach ($documents as $document) {
                    $this->writeCsvRow($out, [
                        $document->id,
                        $document->typeLabel(),
                        $document->nom_present_sur_le_document,
                        $document->numero_du_document,
                        $document->statusLabel(),
                        $document->lieu_de_perte,
                        $document->date_de_perte,
                        $document->user?->name,
                        $document->contact_info,
                        $document->depot?->name,
                        $document->restitue_a,
                        $document->restitue_at?->format('Y-m-d H:i'),
                        $document->created_at?->format('Y-m-d H:i'),
                    ]);
                }
            });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedFilters(Request $request): array
    {
        return $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(DocumentStatus::values())],
            'type' => ['nullable', Rule::in(array_keys(Document::TYPES))],
            'depot_id' => ['nullable', 'integer', 'exists:depots,id'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function filteredQuery(array $filters): Builder
    {
        return Document::query()
            ->search($filters['q'] ?? null)
            ->when($filters['status'] ?? null, fn (Builder $query, $status) => $query->where('status', $status))
            ->when($filters['type'] ?? null, fn (Builder $query, $type) => $query->where('type_document', $type))
            ->when($filters['depot_id'] ?? null, fn (Builder $query, $depotId) => $query->where('depot_id', $depotId));
    }

    /**
     * @param  resource  $handle
     * @param  array<int, mixed>  $row
     */
    private function writeCsvRow($handle, array $row): void
    {
        fputcsv($handle, array_map([$this, 'neutralizeFormula'], $row), ',', '"', '\\');
    }

    /**
     * Spreadsheet formula injection: a cell starting with = + - @ is executed by Excel.
     * User-supplied text (names, places) reaches this export, so prefix such cells.
     */
    private function neutralizeFormula(mixed $value): mixed
    {
        if (is_string($value) && $value !== '' && in_array($value[0], ['=', '+', '-', '@', "\t", "\r"], true)) {
            return "'".$value;
        }

        return $value;
    }
}
