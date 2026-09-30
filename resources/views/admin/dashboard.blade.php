@extends('layouts.appsuperviseur')
@section('title', 'Espace administrateur')
@section('content')
@if (session('login_success'))
<div class="alert-success-login">
    <strong>Bienvenue !</strong> {{ session('login_success') }}
    <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
</div>
@endif
@if (session('lostdoc_store'))
<div class="alert-success-lostdocstore">
     {{ session('lostdoc_store') }}
    <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
</div>
@endif 
@if (session('success'))
<div class="alert-success-lostdocstore">
     {{ session('success') }}
    <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
</div>
@endif 
@auth
    <p class="p mt-4 fw-bold">Bienvenue admin, {{ Auth::user()->name }} !</p>
    <!-- Message d'information -->
    <div class="alert alert-info fs-4 fw-bold text-center" role="alert">
        Les totaux ci-dessous concernent l'ensemble de la plateforme.
    </div>
    <div class="row mb-4 py-2 d-flex align-items-stretch">
        <div class="col-md-3">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h5 class="card-title h-50">Total Documents</h5>
                    <p class="card-text h-50 pt-3">{{ $totalDocuments }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <h5 class="card-title h-50">Rendus</h5>
                    <p class="card-text h-50 pt-3">{{ $totalRendu }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning h-100">
                <div class="card-body">
                    <h5 class="card-title h-50">en Attente de retrait</h5>
                    <p class="card-text h-50 pt-3">{{ $totalRetrouve }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger h-100">
                <div class="card-body">
                    <h5 class="card-title h-50">Toujours égarés</h5>
                    <p class="card-text h-50 pt-3">{{ $totalPerdu }}</p>
                </div>
            </div>
        </div>
    </div>
     <!-- Message d'information deuxieme ligne -->
     <div class="row mb-4 py-2 d-flex align-items-stretch">
        <div class="col-md-3">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h5 class="card-title h-50">Total Gerants</h5>
                    <p class="card-text h-50 pt-3">{{ $totalGerant }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning h-100">
                <div class="card-body">
                    <h5 class="card-title h-50">Totals Depots</h5>
                    <p class="card-text h-50 pt-3">{{ $totalDepot }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <h5 class="card-title h-50">Total Depots actifs</h5>
                    <p class="card-text h-50 pt-3">{{ $totalDepotActif }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger h-100">
                <div class="card-body">
                    <h5 class="card-title h-50">Total Depots inactif</h5>
                    <p class="card-text h-50 pt-3">{{ $totalDepotInactif }}</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Message d'information -->
    <div class="alert alert-info text-center fs-4 fw-bold" role="alert">
        Les totaux ci-dessous vous  concernent spécialement.
    </div>
    <div class="row mb-4 py-2 d-flex align-items-stretch">   
        <div class="col-md-3">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h5 class="card-title h-50">signalés par vous</h5>
                    <p class="card-text h-50 pt-3">{{ $totalSignale }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <h5 class="card-title h-50">Rendus</h5>
                    <p class="card-text h-50 pt-3">{{ $totalPersoRendu }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning h-100">
                <div class="card-body">
                    <h5 class="card-title h-50">En attente de retrait</h5>
                    <p class="card-text h-50 pt-3">{{ $totalPersoRetrouve }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger h-100">
                <div class="card-body">
                    <h5 class="card-title h-50">Toujours égarés</h5>
                    <p class="card-text h-50 pt-3">{{ $totalPersoPerdu }}</p>
                </div>
            </div>
        </div>
       
    </div>
    <div class="card mb-5">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-clock-history"></i> Derniers signalements ({{ $totalUtilisateurs }} utilisateurs inscrits)</span>
            <a href="{{ route('admin.documents.index') }}" class="btn btn-sm btn-primary">Tout voir</a>
        </div>
        <ul class="list-group list-group-flush">
            @forelse ($recentDocuments as $document)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{ route('admin.documents.show', $document) }}">{{ $document->typeLabel() }} — {{ $document->nom_present_sur_le_document }}</a>
                    <span class="badge {{ $document->statusBadgeClass() }}">{{ $document->statusLabel() }}</span>
                </li>
            @empty
                <li class="list-group-item text-muted">Aucun document signalé pour le moment.</li>
            @endforelse
        </ul>
    </div>
@else
    <p>Veuillez vous connecter pour accéder à cette page.</p>
@endauth
@endsection