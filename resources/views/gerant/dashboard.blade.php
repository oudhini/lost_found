@extends('layouts.appgerant')
@section('title', 'Espace gerant')
@section('content')
@include('partials.flash')
@if (session('login_success'))
<div class="alert-success-login">
    <strong>Bienvenue !</strong> {{ session('login_success') }}
    <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
</div>
@endif
<p class="p mt-4 fw-bold">Bonjour, {{ Auth::user()->name }} !</p>

@if ($depot === null)
    <div class="alert alert-warning" role="alert">
        <i class="bi bi-exclamation-triangle"></i>
        Aucun dépôt ne vous est assigné pour l'instant. Un superviseur doit vous en attribuer un avant que vous puissiez réceptionner ou rendre des documents.
    </div>
@else
    <div class="alert alert-info" role="alert">
        <i class="bi bi-geo-alt"></i> Votre dépôt : <strong>{{ $depot->name }}</strong> — {{ $depot->address }}
        @if ($depot->opening_hours) <span class="text-muted">({{ $depot->opening_hours }})</span> @endif
    </div>
    <div class="row mb-4 py-2 d-flex align-items-stretch">
        <div class="col-md-3">
            <a href="{{ route('manager.documents.index', ['status' => 'en_attente_de_retrait']) }}" class="text-decoration-none">
                <div class="card text-white bg-warning h-100"><div class="card-body">
                    <h5 class="card-title">En attente de retrait</h5><p class="card-text fs-3">{{ $depotStats['awaiting'] }}</p>
                </div></div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('manager.history') }}" class="text-decoration-none">
                <div class="card text-white bg-success h-100"><div class="card-body">
                    <h5 class="card-title">Restitués</h5><p class="card-text fs-3">{{ $depotStats['returned'] }}</p>
                </div></div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('manager.documents.index') }}" class="text-decoration-none">
                <div class="card text-white bg-primary h-100"><div class="card-body">
                    <h5 class="card-title">Total du dépôt</h5><p class="card-text fs-3">{{ $depotStats['total'] }}</p>
                </div></div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('manager.receive') }}" class="text-decoration-none">
                <div class="card text-white bg-danger h-100"><div class="card-body">
                    <h5 class="card-title">Signalements à rapprocher</h5><p class="card-text fs-3">{{ $depotStats['toMatch'] }}</p>
                </div></div>
            </a>
        </div>
    </div>

    <div class="d-flex gap-2 mb-4">
        <a href="{{ route('manager.receive') }}" class="btn btn-primary"><i class="bi bi-file-earmark-plus"></i> Réceptionner</a>
        <a href="{{ route('manager.found.create') }}" class="btn btn-outline-primary"><i class="bi bi-plus-circle"></i> Document trouvé sans signalement</a>
    </div>

    <div class="card mb-5">
        <div class="card-header">Derniers documents en attente de retrait</div>
        <ul class="list-group list-group-flush">
            @forelse ($latestAwaiting as $document)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{ route('manager.documents.show', $document->id) }}">{{ $document->typeLabel() }} — {{ $document->nom_present_sur_le_document }}</a>
                    <small class="text-muted">{{ $document->updated_at?->diffForHumans() }}</small>
                </li>
            @empty
                <li class="list-group-item text-muted">Aucun document en attente pour le moment.</li>
            @endforelse
        </ul>
    </div>
@endif
@endsection
