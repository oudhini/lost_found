@extends('layouts.appgerant')
@section('content')
<div class="container-fluid pb-5">
    @include('partials.flash')
    <form method="GET" action="{{ route('manager.documents.index') }}" class="row g-2 mb-3">
        <div class="col-md-4"><input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Nom, numéro, lieu…" aria-label="Recherche"></div>
        <div class="col-md-3">
            <select name="status" class="form-select" aria-label="Statut">
                <option value="">Tous les statuts</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected(($filters['status'] ?? '') === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="type" class="form-select" aria-label="Type">
                <option value="">Tous les types</option>
                @foreach ($types as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['type'] ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2"><button class="btn btn-primary w-100"><i class="bi bi-search"></i> Filtrer</button></div>
    </form>
    @if ($documents->isEmpty())
        <div class="alert alert-warning text-center">Aucun document dans votre dépôt pour ces critères.</div>
    @else
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead><tr><th>#</th><th>Type</th><th>Nom sur le document</th><th>Numéro</th><th>Statut</th><th>Reçu le</th><th></th></tr></thead>
            <tbody>
            @foreach ($documents as $document)
                <tr>
                    <td>{{ $document->id }}</td>
                    <td>{{ $document->typeLabel() }}</td>
                    <td>{{ $document->nom_present_sur_le_document }}</td>
                    <td>{{ $document->numero_du_document }}</td>
                    <td><span class="badge {{ $document->statusBadgeClass() }}">{{ $document->statusLabel() }}</span></td>
                    <td>{{ $document->updated_at?->format('d/m/Y') }}</td>
                    <td><a class="btn btn-info btn-sm" href="{{ route('manager.documents.show', $document->id) }}" aria-label="Voir le document {{ $document->id }}"><i class="bi bi-eye"></i></a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $documents->links() }}
    @endif
</div>
@endsection
