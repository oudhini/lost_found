@extends('layouts.appsuperviseur')
@section('content')
<div class="container-fluid pb-5">
    @include('partials.flash')
    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 mb-3">
        <div class="col-md-4"><input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Nom, e-mail ou téléphone" aria-label="Recherche"></div>
        <div class="col-md-2">
            <select name="role" class="form-select" aria-label="Rôle">
                <option value="">Tous les rôles</option>
                <option value="utilisateur" @selected(($filters['role'] ?? '') === 'utilisateur')>Utilisateur</option>
                <option value="gerant" @selected(($filters['role'] ?? '') === 'gerant')>Gérant</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="active" class="form-select" aria-label="État">
                <option value="">Tous</option>
                <option value="1" @selected(($filters['active'] ?? '') === '1')>Actifs</option>
                <option value="0" @selected(($filters['active'] ?? '') === '0')>Suspendus</option>
            </select>
        </div>
        <div class="col-md-2"><button class="btn btn-primary"><i class="bi bi-search"></i> Filtrer</button></div>
    </form>
    @if ($users->isEmpty())
        <div class="alert alert-warning text-center">Aucun compte trouvé.</div>
    @else
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead><tr><th>Nom</th><th>E-mail</th><th>Téléphone</th><th>Rôle</th><th>Signalements</th><th>État</th><th></th></tr></thead>
            <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td><td>{{ $user->email }}</td><td>{{ $user->phone }}</td>
                    <td>{{ $user->role === 'gerant' ? 'Gérant' : 'Utilisateur' }}</td>
                    <td>{{ $user->documents_count }}</td>
                    <td><span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">{{ $user->is_active ? 'Actif' : 'Suspendu' }}</span></td>
                    <td>
                        <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm {{ $user->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                onclick="return confirm('{{ $user->is_active ? 'Suspendre' : 'Réactiver' }} ce compte ?')">
                                {{ $user->is_active ? 'Suspendre' : 'Réactiver' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $users->links() }}
    @endif
</div>
@endsection
