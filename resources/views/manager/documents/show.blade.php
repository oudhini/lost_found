@extends('layouts.appgerant')
@section('content')
<div class="container-fluid pb-5">
    @include('partials.flash')
    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between">
                    <span>{{ $document->typeLabel() }} n° {{ $document->numero_du_document }}</span>
                    <span class="badge {{ $document->statusBadgeClass() }}">{{ $document->statusLabel() }}</span>
                </div>
                <div class="card-body">
                    <p><strong>Nom sur le document :</strong> {{ $document->nom_present_sur_le_document }}</p>
                    <p><strong>Lieu / date :</strong> {{ $document->lieu_de_perte }} — {{ $document->date_de_perte }}</p>
                    <p><strong>Informations complémentaires :</strong> {{ $document->additional_info ?: '—' }}</p>
                    <p><strong>Contact du déclarant :</strong> {{ $document->user?->name }} — {{ $document->contact_info }}</p>
                    @if ($document->restitue_at)
                        <div class="alert alert-success mb-0">
                            Restitué à <strong>{{ $document->restitue_a }}</strong> le {{ $document->restitue_at->format('d/m/Y H:i') }}
                            @if ($document->handler) par {{ $document->handler->name }} @endif
                        </div>
                    @endif
                    @if ($document->photoUrls())
                        <hr>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($document->photoUrls() as $url)
                                <a href="{{ $url }}" target="_blank" rel="noopener"><img src="{{ $url }}" alt="Photo du document" class="img-thumbnail" style="height:120px"></a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            @if ($document->status === \App\Enums\DocumentStatus::AwaitingPickup->value)
                <div class="card mb-3">
                    <div class="card-header">Rendre le document</div>
                    <div class="card-body">
                        <p class="text-muted small">Vérifiez la pièce d'identité de la personne avant de confirmer.</p>
                        <form method="POST" action="{{ route('manager.documents.restitute', $document->id) }}">
                            @csrf @method('PATCH')
                            <label for="restitue_a" class="form-label">Remis à (nom de la personne)</label>
                            <input id="restitue_a" name="restitue_a" class="form-control mb-2" required maxlength="255" value="{{ old('restitue_a', $document->nom_present_sur_le_document) }}">
                            <button class="btn btn-success w-100" onclick="return confirm('Confirmer la remise du document ?')">Confirmer la remise</button>
                        </form>
                    </div>
                </div>
            @endif
            <a href="{{ route('manager.documents.index') }}" class="btn btn-link">← Retour aux documents du dépôt</a>
        </div>
    </div>
</div>
@endsection
