<?php

namespace App\Http\Controllers;

use App\Enums\DocumentStatus;
use App\Models\Depot;
use App\Models\Document;
use App\Models\User;
use App\Notifications\NewLostDocumentReported;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AppController extends Controller
{
    public function index(): View
    {
        return view('test');
    }

    /**
     * Sends the authenticated user to the dashboard matching their role.
     */
    public function testuser(): View|RedirectResponse
    {
        $user = auth()->user();

        if ($user === null) {
            return redirect()->route('login');
        }

        $data = $this->dashboardData($user);

        if ($user->isGerant()) {
            $data += $this->managerDashboardData($user);
        }

        return match ($user->role) {
            User::ROLE_USER => view('user.dashboard', $data),
            User::ROLE_MANAGER => view('gerant.dashboard', $data),
            User::ROLE_SUPERVISOR => view('admin.dashboard', $data + $this->supervisorDashboardData()),
            default => abort(403),
        };
    }

    /**
     * @return array<string, mixed>
     */
    private function dashboardData(User $user): array
    {
        $lost = DocumentStatus::Lost->value;
        $awaiting = DocumentStatus::AwaitingPickup->value;
        $returned = DocumentStatus::Returned->value;

        return [
            'pageTitle' => 'Tableau de Bord',
            'breadcrumb' => ['Tableau de Bord'],
            'totalDocuments' => Document::count(),
            'totalRetrouve' => Document::where('status', $awaiting)->count(),
            'totalPersoRetrouve' => Document::where('status', $awaiting)->where('user_id', $user->id)->count(),
            'totalPerdu' => Document::where('status', $lost)->count(),
            'totalPersoPerdu' => Document::where('status', $lost)->where('user_id', $user->id)->count(),
            'totalRendu' => Document::where('status', $returned)->count(),
            'totalPersoRendu' => Document::where('status', $returned)->where('user_id', $user->id)->count(),
            'totalSignale' => Document::where('user_id', $user->id)->count(),
        ];
    }

    /**
     * Figures for the manager's own depot (null depot = manager not assigned yet).
     *
     * @return array<string, mixed>
     */
    private function managerDashboardData(User $user): array
    {
        $depot = $user->depot;

        if ($depot === null) {
            return ['depot' => null, 'depotStats' => null, 'latestAwaiting' => collect()];
        }

        return [
            'depot' => $depot,
            'depotStats' => [
                'awaiting' => $depot->documents()->where('status', DocumentStatus::AwaitingPickup->value)->count(),
                'returned' => $depot->documents()->where('status', DocumentStatus::Returned->value)->count(),
                'total' => $depot->documents()->count(),
                'toMatch' => Document::where('status', DocumentStatus::Lost->value)->count(),
            ],
            'latestAwaiting' => $depot->documents()
                ->where('status', DocumentStatus::AwaitingPickup->value)
                ->latest('id')->limit(5)->get(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function supervisorDashboardData(): array
    {
        return [
            'totalDepot' => Depot::count(),
            'totalGerant' => User::where('role', User::ROLE_MANAGER)->count(),
            'totalDepotActif' => Depot::where('statut', Depot::STATUS_ACTIVE)->count(),
            'totalDepotInactif' => Depot::where('statut', Depot::STATUS_INACTIVE)->count(),
            'totalUtilisateurs' => User::where('role', User::ROLE_USER)->count(),
            'recentDocuments' => Document::with('user:id,name')->latest()->limit(5)->get(),
        ];
    }

    public function signaler(): View
    {
        $pageTitle = 'Signaler Document(Egaré)';
        $breadcrumb = ['Signaler Document(Egaré)'];

        return view('user.documentperdu', compact('pageTitle', 'breadcrumb'));
    }

    public function store_lost_document(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type_document' => ['required', Rule::in(array_keys(Document::TYPES))],
            'nom_present_sur_le_document' => ['required', 'string', 'max:255'],
            'contact_info' => ['required', 'string', 'max:255'],
            'lieu_de_perte' => ['required', 'string', 'max:255'],
            'date_de_perte' => ['required', 'date', 'before_or_equal:today'],
            'numero_du_document' => ['nullable', 'string', 'max:100'],
            'additional_info' => ['nullable', 'string', 'max:2000'],
            'photos' => ['nullable', 'array', 'max:5'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        // Random server-side names: the client file name is never trusted (overwrite / path tricks).
        $photoNames = Document::storeUploadedPhotos($request->file('photos', []));

        $document = Document::create([
            'type_document' => $data['type_document'],
            'nom_present_sur_le_document' => $data['nom_present_sur_le_document'],
            'status' => DocumentStatus::Lost->value,
            'lieu_de_perte' => $data['lieu_de_perte'],
            'date_de_perte' => $data['date_de_perte'],
            'user_id' => $request->user()->id,
            'photos' => implode(',', $photoNames),
            'additional_info' => $data['additional_info'] ?? null,
            'numero_du_document' => $data['numero_du_document'] ?? 'aucun numéro',
            'contact_info' => $data['contact_info'],
        ]);

        Notification::send(
            User::where('role', User::ROLE_SUPERVISOR)->get(),
            new NewLostDocumentReported($document)
        );

        // Redirect (POST/redirect/GET) so a page refresh cannot submit the report twice.
        return redirect()->route('dashboard')
            ->with('lostdoc_store', 'Votre document a été signalé perdu avec succès !');
    }

    public function docs_perdu(Request $request): View
    {
        return $this->index1($request);
    }

    public function docs_trouver(Request $request): View
    {
        return $this->index_found($request);
    }

    public function docs_signaler(): View
    {
        $pageTitle = 'Documents Signalés';
        $breadcrumb = ['Documents Signalés'];
        $documents = Document::where('user_id', Auth::id())->latest('id')->paginate(3);

        // Only computed for the current page: a handful of rows, so no pagination overhead.
        $matchesByDocument = $documents->mapWithKeys(
            fn (Document $document) => [$document->id => $document->findPossibleMatches()]
        );

        return view('user.documentsignale', compact('documents', 'matchesByDocument', 'pageTitle', 'breadcrumb'));
    }

    public function index1(Request $request): View
    {
        $pageTitle = 'Documents Perdus';
        $breadcrumb = ['Documents Perdus'];
        $q = trim((string) $request->input('q', ''));
        $types = Document::select('type_document')->where('status', DocumentStatus::Lost->value)->distinct()->pluck('type_document');

        $documents = Document::where('status', DocumentStatus::Lost->value)
            ->when($request->input('type'), fn ($query) => $query->where('type_document', $request->input('type')))
            ->search($q)
            ->paginate(6)
            ->withQueryString();

        return view('document.all_lost_documents', compact('documents', 'types', 'q', 'pageTitle', 'breadcrumb'));
    }

    public function index_found(Request $request): View
    {
        $pageTitle = 'Documents Trouvés';
        $breadcrumb = ['Documents Trouvés'];
        $q = trim((string) $request->input('q', ''));
        $types = Document::select('type_document')->where('status', DocumentStatus::AwaitingPickup->value)->distinct()->pluck('type_document');

        $documents = Document::where('status', DocumentStatus::AwaitingPickup->value)
            ->when($request->input('type'), fn ($query) => $query->where('type_document', $request->input('type')))
            ->search($q)
            ->paginate(6)
            ->withQueryString();

        return view('document.all_found_documents', compact('documents', 'types', 'q', 'pageTitle', 'breadcrumb'));
    }

    public function supprimer_doc(Request $request, int $id): RedirectResponse
    {
        $document = Document::findOrFail($id);
        $user = $request->user();

        // Previously any logged-in user could delete any document by guessing its id.
        abort_unless($document->user_id === $user->id || $user->isSuperviseur(), 403);

        $document->deleteWithPhotos();

        return redirect()->route('docs_signales')
            ->with('succes_supp_doc', 'Cette signalisation a été supprimé avec succès.');
    }
}
