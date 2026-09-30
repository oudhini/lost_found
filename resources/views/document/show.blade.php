@extends($layout)
@section('content')
<div class="container-fluid pb-5">
    @include('partials.flash')
    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between">
                    <span><i class="bi bi-file-earmark-text"></i> {{ $document->typeLabel() }}@if($document->numero_du_document) — n° {{ $document->numero_du_document }} @endif</span>
                    <span class="badge {{ $document->statusBadgeClass() }}">{{ $document->statusLabel() }}</span>
                </div>
                <div class="card-body">
                    <p><strong>Nom sur le document :</strong> {{ $document->nom_present_sur_le_document }}</p>
                    <p><strong>Lieu / date de perte :</strong> {{ $document->lieu_de_perte }} — {{ $document->date_de_perte ? \Carbon\Carbon::parse($document->date_de_perte)->format('d/m/Y') : 'Non spécifiée' }}</p>
                    @if ($document->additional_info)
                        <p><strong>Informations complémentaires :</strong> {{ $document->additional_info }}</p>
                    @endif
                    @if ($isOwner || $document->status !== \App\Enums\DocumentStatus::Returned->value)
                        <p><strong>Contact :</strong> {{ $document->contact_info }}</p>
                    @endif
                    @if ($document->depot)
                        <p><strong>Dépôt :</strong> {{ $document->depot->name }} — {{ $document->depot->address }}</p>
                    @endif
                    @if ($document->restitue_at)
                        <div class="alert alert-success mb-0">
                            Restitué à <strong>{{ $document->restitue_a }}</strong> le {{ $document->restitue_at->format('d/m/Y H:i') }}
                        </div>
                    @endif
                    @if ($document->photoUrls())
                        <hr>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($document->photoUrls() as $url)
                                <a href="{{ $url }}" target="_blank" rel="noopener"><img src="{{ $url }}" alt="Photo du document" class="img-thumbnail" style="height:130px"></a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            @if ($isOwner)
                <div class="card mb-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <span class="text-muted small">C'est votre signalement.</span>
                        <form method="POST" action="{{ route('supp_doc', $document->id) }}" onsubmit="return confirm('Supprimer ce signalement ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i> Supprimer</button>
                        </form>
                    </div>
                </div>
            @endif

            @if ($possibleMatches->isNotEmpty())
                <div class="card mb-3">
                    <div class="card-header">Ça pourrait être le vôtre</div>
                    <ul class="list-group list-group-flush">
                        @foreach ($possibleMatches as $match)
                            <li class="list-group-item">
                                <a href="{{ route('documents.show', $match->id) }}">{{ $match->nom_present_sur_le_document }}</a>
                                <div class="small text-muted">Déposé à {{ $match->depot?->name ?? '—' }}</div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <a href="{{ url()->previous() }}" class="btn btn-link">← Retour</a>
        </div>
    </div>
</div>
@endsection
