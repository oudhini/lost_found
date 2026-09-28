@extends('layouts.appsuperviseur')
@section('content')
<div class="container-table">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h2 class="text-primary">Liste des Points de Dépôt</h2>
        <a href="{{ route('depot.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Ajouter</a>
    </div>
    @if($depots->isEmpty())
        <div class="alert alert-warning text-center mt-3" role="alert">
            Aucun depot créé pour le moment.
        </div>
    @else
    <div class="row">
        <div class="col-6">
            <form method="GET" action="{{ route('depot.index') }}" class="mb-3">
                <div class="form-group">
                    <label for="perPage">Depots par page:</label>
                    <select name="perPage" id="perPage" class="form-control" onchange="this.form.submit()">
                        <option value="2" {{ request('perPage') == 2 ? 'selected' : '' }}>2</option>
                        <option value="3" {{ request('perPage') == 3 ? 'selected' : '' }}>3</option>
                        <option value="4" {{ request('perPage') == 4 ? 'selected' : '' }}>4</option>
                        <option value="5" {{ request('perPage') == 5 ? 'selected' : '' }}>5</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="col-6">
            <form method="GET" action="{{ route('depot.index') }}" class="mb-3">
                <div class="row">
                    <label for="type" class="col-6 form-label text-end">Trier par statut :</label>
                    <select name="statut" id="type" class="col-6 form-control-select text-start" onchange="this.form.submit()">
                        <option value="">Tous les statuts</option>
                        @foreach($statuts as $statut)
                            <option value="{{ $statut }}" {{ request('statut') == $statut ? 'selected' : '' }}>{{ $statut }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>
    
    @if (session('success'))
        <div class="alert-success-lostdocstore">
        {{ session('success') }}
        <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
        </div>
    @endif 

<div class="table-responsive">
    <table class="table mx-0 mb-2 table-hover table-bordered text-left" aria-label="Liste des dépôts">
        <thead>
            <tr>
                <th scope="col">Nom</th>
                <th scope="col">Adresse</th>
                <th scope="col">Contact</th>
                <th scope="col">Statut</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($depots as $depot)
                <tr>
                    <td>{{ $depot->name }}</td>
                    <td>{{ $depot->address }}</td>
                    <td>{{ $depot->contact }}</td>
                    <td>
                        <span class="badge {{ $depot->statut == 'actif' ? 'bg-success' : 'bg-danger' }}">
                            {{ $depot->statut }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('depot.show', $depot->id) }}" class="btn btn-depot btn-info btn-sm" title="Voir" aria-label="Voir les détails du dépôt">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('depot.edit', $depot->id) }}" class="btn btn-depot btn-warning btn-sm" title="Modifier" aria-label="Modifier le dépôt">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('depot.destroy', $depot->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-depot btn-danger btn-sm" title="Supprimer" aria-label="Supprimer le dépôt" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce dépôt ?');">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{-- {{ $depots->appends(['perPage' => $perPage,'statut' => $statut])->links() }} <!-- Affiche les liens de pagination --> --}}
       {{$depots->appends(request()->input())->links()}}
    </div>
</div>
@endif
</div>
<style>
    #perPage {
    width: auto; /* Ajuster la largeur si nécessaire */
    display: inline-block; /* Afficher en ligne avec d'autres éléments */
    }
    .table {
        border-collapse:collapse;
        border-spacing: 0 10px; /* Espacement entre les lignes */
    }
    .table th, .table td {
        padding: 12px;
        vertical-align: middle;
    }
    .table thead th {
        background-color: powderblue; /* Couleur de fond pour l'en-tête */
        border-bottom: 2px solid #dee2e6;
    }
    .table tbody tr {
        background-color: #ffffff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Ombre légère */
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .table tbody tr:hover {
        transform: translateY(-2px); /* Effet de levée au survol */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    .badge {
        font-size: 0.9em;
        padding: 6px 10px;
        border-radius:  12px; /* Coins arrondis pour les badges */
    }
    .btn-depot {
        transition: background-color 0.3s ease, transform 0.2s ease;
    }
    .btn-depot:hover {
        transform: scale(1.05); /* Effet de zoom au survol */
    }
</style>
@endsection
