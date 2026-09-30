@extends('layouts.appgerant')
@section('content')
<div class="container-fluid pb-5">
    @if ($documents->isEmpty())
        <div class="alert alert-info text-center">Aucun document n'a encore été rendu depuis ce dépôt.</div>
    @else
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead><tr><th>Document</th><th>Remis à</th><th>Date</th><th>Par</th><th></th></tr></thead>
            <tbody>
            @foreach ($documents as $document)
                <tr>
                    <td>{{ $document->typeLabel() }} — {{ $document->nom_present_sur_le_document }}</td>
                    <td>{{ $document->restitue_a }}</td>
                    <td>{{ $document->restitue_at?->format('d/m/Y H:i') }}</td>
                    <td>{{ $document->handler?->name ?? '—' }}</td>
                    <td><a class="btn btn-info btn-sm" href="{{ route('manager.documents.show', $document->id) }}" aria-label="Voir"><i class="bi bi-eye"></i></a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $documents->links() }}
    @endif
</div>
@endsection
