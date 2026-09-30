@extends('layouts.appgerant')
@section('content')
<div class="container-fluid pb-5">
    @include('partials.flash')
    <p class="text-muted">Quelqu'un vient de déposer un document ? Cherchez d'abord s'il a déjà été signalé perdu (nom, numéro ou lieu).</p>
    <form method="GET" action="{{ route('manager.receive') }}" class="d-flex gap-2 mb-3">
        <input type="search" name="q" value="{{ $term }}" minlength="2" required class="form-control @error('q') is-invalid @enderror" placeholder="Ex. nom sur le document, numéro…" aria-label="Recherche">
        <button class="btn btn-primary"><i class="bi bi-search"></i> Chercher</button>
    </form>
    @error('q') <div class="text-danger mb-3">{{ $message }}</div> @enderror

    @if ($term !== null)
        @if ($matches->isEmpty())
            <div class="alert alert-warning">
                Aucun signalement ne correspond à « {{ $term }} ».
                <a href="{{ route('manager.found.create') }}" class="alert-link">Enregistrer ce document comme trouvé</a>.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead><tr><th>Type</th><th>Nom sur le document</th><th>Numéro</th><th>Perdu à</th><th>Date</th><th></th></tr></thead>
                    <tbody>
                    @foreach ($matches as $document)
                        <tr>
                            <td>{{ $document->typeLabel() }}</td>
                            <td>{{ $document->nom_present_sur_le_document }}</td>
                            <td>{{ $document->numero_du_document }}</td>
                            <td>{{ $document->lieu_de_perte }}</td>
                            <td>{{ $document->date_de_perte }}</td>
                            <td>
                                <form method="POST" action="{{ route('manager.receive.store', $document->id) }}">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-success btn-sm" onclick="return confirm('Ce document est-il bien celui que vous avez en main ?')">Réceptionner</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <a href="{{ route('manager.found.create') }}">Aucun ne correspond ? Enregistrer comme document trouvé</a>
        @endif
    @endif
</div>
@endsection
