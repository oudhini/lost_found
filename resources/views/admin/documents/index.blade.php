@extends('layouts.appsuperviseur')
@section('content')
<div class="container-fluid pb-5">
    @include('partials.flash')
    <form method="GET" action="{{ route('admin.documents.index') }}" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Nom, numéro, lieu, signaleur…" aria-label="Recherche">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select" aria-label="Statut">
                <option value="">Tous les statuts</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected(($filters['status'] ?? '') === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="type" class="form-select" aria-label="Type">
                <option value="">Tous les types</option>
                @foreach ($types as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['type'] ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="depot_id" class="form-select" aria-label="Dépôt">
                <option value="">Tous les dépôts</option>
                @foreach ($depots as $depot)
                    <option value="{{ $depot->id }}" @selected((string) ($filters['depot_id'] ?? '') === (string) $depot->id)>{{ $depot->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary"><i class="bi bi-search"></i> Filtrer</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.documents.index') }}">Réinitialiser</a>
            <a class="btn btn-outline-success ms-auto" href="{{ route('admin.documents.export', request()->query()) }}"><i class="bi bi-download"></i> CSV</a>
        </div>
    </form>

    @if ($documents->isEmpty())
        <div class="alert alert-warning text-center">Aucun document ne correspond à ces critères.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead><tr><th>#</th><th>Type</th><th>Nom sur le document</th><th>Statut</th><th>Signalé par</th><th>Dépôt</th><th>Date</th><th></th></tr></thead>
                <tbody>
                @foreach ($documents as $document)
                    <tr>
                        <td>{{ $document->id }}</td>
                        <td>{{ $document->typeLabel() }}</td>
                        <td>{{ $document->nom_present_sur_le_document }}</td>
                        <td><span class="badge {{ $document->statusBadgeClass() }}">{{ $document->statusLabel() }}</span></td>
                        <td>{{ $document->user?->name ?? '—' }}</td>
                        <td>{{ $document->depot?->name ?? '—' }}</td>
                        <td>{{ $document->created_at?->format('d/m/Y') }}</td>
                        <td><a href="{{ route('admin.documents.show', $document) }}" class="btn btn-info btn-sm" aria-label="Voir le document {{ $document->id }}"><i class="bi bi-eye"></i></a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $documents->links() }}
    @endif
</div>
@endsection
