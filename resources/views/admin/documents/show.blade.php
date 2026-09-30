@extends('layouts.appsuperviseur')
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
                    <span><i class="bi bi-file-earmark-text"></i> {{ $document->typeLabel() }} n° {{ $document->numero_du_document }}</span>
                    <span class="badge {{ $document->statusBadgeClass() }}">{{ $document->statusLabel() }}</span>
                </div>
                <div class="card-body">
                    <p><strong>Nom sur le document :</strong> {{ $document->nom_present_sur_le_document }}</p>
                    <p><strong>Lieu / date de perte :</strong> {{ $document->lieu_de_perte }} — {{ $document->date_de_perte }}</p>
                    <p><strong>Contact :</strong> {{ $document->contact_info }}</p>
                    <p><strong>Informations complémentaires :</strong> {{ $document->additional_info ?: '—' }}</p>
                    <p><strong>Signalé par :</strong> {{ $document->user?->name }} ({{ $document->user?->email }}, {{ $document->user?->phone }})</p>
                    <p><strong>Dépôt :</strong> {{ $document->depot?->name ?? 'Non affecté' }}</p>
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
            @if ($document->status !== \App\Enums\DocumentStatus::Returned->value)
                <div class="card mb-3">
                    <div class="card-header">Affecter à un dépôt</div>
                    <div class="card-body">
                        @if ($activeDepots->isEmpty())
                            <div class="alert alert-warning mb-0">Aucun dépôt actif. Créez un gérant et assignez-lui un dépôt.</div>
                        @else
                            <form method="POST" action="{{ route('admin.documents.assign-depot', $document) }}" class="d-flex gap-2">
                                @csrf @method('PATCH')
                                <select name="depot_id" class="form-select" required aria-label="Dépôt actif">
                                    <option value="">-- Choisir --</option>
                                    @foreach ($activeDepots as $depot)
                                        <option value="{{ $depot->id }}" @selected($document->depot_id === $depot->id)>{{ $depot->name }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-primary">Affecter</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif
            @if ($document->status === \App\Enums\DocumentStatus::AwaitingPickup->value)
                <div class="card mb-3">
                    <div class="card-header">Restituer le document</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.documents.restitute', $document) }}">
                            @csrf @method('PATCH')
                            <label for="restitue_a" class="form-label">Remis à (nom de la personne)</label>
                            <input id="restitue_a" name="restitue_a" class="form-control mb-2" required maxlength="255" value="{{ old('restitue_a', $document->nom_present_sur_le_document) }}">
                            <button class="btn btn-success w-100" onclick="return confirm('Confirmer la restitution ?')">Confirmer la restitution</button>
                        </form>
                    </div>
                </div>
            @endif
            <form method="POST" action="{{ route('admin.documents.destroy', $document) }}">
                @csrf @method('DELETE')
                <button class="btn btn-outline-danger w-100" onclick="return confirm('Supprimer définitivement ce document ?')"><i class="bi bi-trash"></i> Supprimer</button>
            </form>
            <a href="{{ route('admin.documents.index') }}" class="btn btn-link mt-2">← Retour à la liste</a>
        </div>
    </div>
</div>
@endsection
