<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Supervisor-side account moderation for regular users and managers.
 */
class UserController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', Rule::in([User::ROLE_USER, User::ROLE_MANAGER])],
            'active' => ['nullable', Rule::in(['1', '0'])],
        ]);

        $users = User::query()
            ->whereIn('role', [User::ROLE_USER, User::ROLE_MANAGER])
            ->withCount('documents')
            ->when($filters['q'] ?? null, function (Builder $query, string $term): void {
                $like = '%'.trim($term).'%';
                $query->where(function (Builder $q) use ($like): void {
                    $q->where('name', 'like', $like)
                        ->orWhere('email', 'like', $like)
                        ->orWhere('phone', 'like', $like);
                });
            })
            ->when($filters['role'] ?? null, fn (Builder $query, string $role) => $query->where('role', $role))
            ->when(isset($filters['active']), fn (Builder $query) => $query->where('is_active', $filters['active'] === '1'))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $pageTitle = 'Utilisateurs';
        $breadcrumb = ['Utilisateurs'];

        return view('admin.users.index', compact('users', 'filters', 'pageTitle', 'breadcrumb'));
    }

    public function toggleActive(User $user): RedirectResponse
    {
        // Supervisors cannot lock themselves (or each other) out.
        if ($user->isSuperviseur()) {
            return back()->with('error', 'Un compte superviseur ne peut pas être suspendu.');
        }

        $user->forceFill(['is_active' => ! $user->is_active])->save();

        return back()->with('success', $user->is_active
            ? "Le compte de {$user->name} est de nouveau actif."
            : "Le compte de {$user->name} est suspendu.");
    }
}
