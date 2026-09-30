<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use App\Models\User;
use App\Support\RoleLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DepotController extends Controller
{
    private const PER_PAGE_OPTIONS = [2, 3, 4, 5, 10];

    private const DEFAULT_PER_PAGE = 3;

    /**
     * Rules shared by store() and update(). Only these keys ever reach the model,
     * so a client can never set `gerant_id` or `statut` through the form.
     *
     * @return array<string, array<int, mixed>>
     */
    private function depotRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],
            'opening_hours' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'statut' => ['nullable', Rule::in([Depot::STATUS_ACTIVE, Depot::STATUS_INACTIVE])],
            'perPage' => ['nullable', 'integer', Rule::in(self::PER_PAGE_OPTIONS)],
        ]);

        $user = $request->user();
        $statut = $filters['statut'] ?? '';
        $perPage = (int) ($filters['perPage'] ?? self::DEFAULT_PER_PAGE);
        $statuts = Depot::query()->select('statut')->distinct()->pluck('statut');

        $depots = Depot::query()
            ->when($statut !== '', fn ($query) => $query->where('statut', $statut))
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        $pageTitle = 'Liste Point de dépot';
        $breadcrumb = ['Points de Dépot', 'Liste Point de dépot'];

        $view = match ($user->role) {
            User::ROLE_SUPERVISOR => 'depot.index',
            User::ROLE_MANAGER => 'gerant.listedepot',
            default => 'user.listdepot',
        };

        return view($view, compact('depots', 'pageTitle', 'breadcrumb', 'perPage', 'statuts', 'statut'));
    }

    public function create(): View
    {
        $pageTitle = 'Nouveau Point de dépot';
        $breadcrumb = ['Points de dépot', 'Nouveau Point'];

        return view('depot.create', compact('pageTitle', 'breadcrumb'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->depotRules());

        // A new depot has no manager yet, hence it starts inactive.
        Depot::create($data + ['gerant_id' => null, 'statut' => Depot::STATUS_INACTIVE]);

        return redirect()->route('depot.index')->with('success', 'Dépôt créé avec succès.');
    }

    public function show(Request $request, int $id): View
    {
        $depot = Depot::with('gerant:id,name,phone')->findOrFail($id);
        $pageTitle = 'Detail dépot';
        $breadcrumb = ['Detail dépot'];
        $layout = RoleLayout::for($request->user());

        return view('depot.show', compact('depot', 'pageTitle', 'breadcrumb', 'layout'));
    }

    public function edit(int $id): View
    {
        $depot = Depot::findOrFail($id);
        $pageTitle = 'Modification dépot';
        $breadcrumb = ['Liste Depot', 'Modification Depot'];

        return view('depot.edit', compact('depot', 'pageTitle', 'breadcrumb'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $depot = Depot::findOrFail($id);

        $depot->update($request->validate($this->depotRules()));

        return redirect()->route('depot.show', $depot->id)->with('success', 'Dépôt mis à jour avec succès.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $depot = Depot::withCount('documents')->findOrFail($id);

        // documents.depot_id is ON DELETE CASCADE: deleting a depot silently wiped its documents.
        if ($depot->documents_count > 0) {
            return redirect()->route('depot.index')->with(
                'error',
                "Ce dépôt contient {$depot->documents_count} document(s) : transférez-les avant de le supprimer."
            );
        }

        $depot->delete();

        return redirect()->route('depot.index')->with('success', 'Dépôt supprimé avec succès.');
    }
}
