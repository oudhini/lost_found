<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Supervisor-only CRUD for depot managers (role "gerant").
 * Every lookup is scoped to managers so this controller can never
 * be used to edit or delete a supervisor or a regular user.
 */
class GerantController extends Controller
{
    private function findManager(int $id): User
    {
        return User::where('role', User::ROLE_MANAGER)->findOrFail($id);
    }

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $managers = User::where('role', User::ROLE_MANAGER)
            ->with('depot:id,name,gerant_id')
            ->when($search !== '', function (Builder $query) use ($search): void {
                $like = '%'.$search.'%';
                $query->where(function (Builder $q) use ($like): void {
                    $q->where('name', 'like', $like)
                        ->orWhere('email', 'like', $like)
                        ->orWhere('phone', 'like', $like);
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $pageTitle = 'Liste Gerants';
        $breadcrumb = ['Gerants', 'Liste Gerants'];

        return view('gerant.index', compact('managers', 'pageTitle', 'breadcrumb', 'search'));
    }

    public function create(): View
    {
        $pageTitle = 'Nouveau Gerant';
        $breadcrumb = ['Gerants', 'Nouveau Gerant'];
        $inactiveDepots = Depot::where('statut', Depot::STATUS_INACTIVE)->orderBy('name')->get();

        return view('gerant.create', compact('pageTitle', 'breadcrumb', 'inactiveDepots'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:15', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'depot_id' => ['nullable', Rule::exists('depots', 'id')->where('statut', Depot::STATUS_INACTIVE)],
        ]);

        DB::transaction(function () use ($data): void {
            $manager = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => $data['password'], // hashed by the model cast
                'role' => User::ROLE_MANAGER,
            ]);

            if (! empty($data['depot_id'])) {
                Depot::findOrFail($data['depot_id'])->assignManager($manager);
            }
        });

        return redirect()->route('gerant.index')->with('success', 'Gérant créé avec succès.');
    }

    public function show(int $id): View
    {
        $manager = $this->findManager($id)->load('depot');
        $pageTitle = 'Détails';
        $breadcrumb = ['Gerants', 'Liste Gerants', 'Détails'];

        return view('gerant.show', compact('manager', 'pageTitle', 'breadcrumb'));
    }

    public function edit(int $id): View
    {
        $manager = $this->findManager($id)->load('depot');
        $pageTitle = 'Modifier';
        $breadcrumb = ['Gerants', 'Liste Gerants', 'Modifier'];

        // Free depots, plus the one this manager already runs.
        $availableDepots = Depot::where('statut', Depot::STATUS_INACTIVE)
            ->orWhere('gerant_id', $manager->id)
            ->orderBy('name')
            ->get();

        return view('gerant.edit', compact('manager', 'pageTitle', 'breadcrumb', 'availableDepots'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $manager = $this->findManager($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($manager->id)],
            'phone' => ['required', 'string', 'max:15', Rule::unique('users', 'phone')->ignore($manager->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'depot_id' => ['nullable', Rule::exists('depots', 'id')->where(function ($query) use ($manager): void {
                // Grouped on purpose: without the nested closure the OR escapes the "id = ?" condition.
                $query->where(function ($group) use ($manager): void {
                    $group->where('statut', Depot::STATUS_INACTIVE)->orWhere('gerant_id', $manager->id);
                });
            })],
        ]);

        DB::transaction(function () use ($manager, $data): void {
            $manager->fill([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
            ]);

            if (! empty($data['password'])) {
                $manager->password = $data['password']; // hashed by the model cast
            }

            $manager->save();

            $newDepotId = $data['depot_id'] ?? null;
            $currentDepot = $manager->depot;

            if ($currentDepot !== null && (int) $newDepotId !== $currentDepot->id) {
                $currentDepot->releaseManager();
            }

            if ($newDepotId !== null && ($currentDepot === null || (int) $newDepotId !== $currentDepot->id)) {
                Depot::findOrFail($newDepotId)->assignManager($manager);
            }
        });

        return redirect()->route('gerant.index')->with('success', 'Gérant mis à jour avec succès.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $manager = $this->findManager($id);

        DB::transaction(function () use ($manager): void {
            // depots.gerant_id has a foreign key on users: free the depot first, and put it back to inactive.
            Depot::where('gerant_id', $manager->id)->get()->each->releaseManager();

            $manager->delete();
        });

        return redirect()->route('gerant.index')->with('success', 'Gérant supprimé avec succès. Son dépôt est de nouveau inactif.');
    }
}
