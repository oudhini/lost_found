@extends('layouts.app1')

@section('title', 'Accueil')

@section('content')

<!-- Hero Section -->
<div class="hero-section">
    <h1>Bienvenue sur Lost & Found</h1>
    <p>Récupérez vos documents égarés ou aidez à retrouver ceux des autres en toute simplicité et sans tracasserie.</p>
    <a href="#search-section" class="btn btn-primary btn-lg">Rechercher un document</a>
    <a href="#" class="btn btn-outline-light btn-lg">Déposer un document</a>
</div>

<!-- Search Section -->
<section id="search-section" class="container my-5">
    <h2 class="text-center mb-4">Rechercher un document</h2>
    <form action="{{ route('search') }}" method="GET" class="row g-3">
        <div class="col-md-6">
            <label for="documentType" class="form-label">Type de document</label>
            <select class="form-select" id="documentType" name="type">
                <option value="id_card">Carte d'identité</option>
                <option value="passport">Passeport</option>
                <option value="license">Permis de conduire</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="documentNumber" class="form-label">Numéro de document</label>
            <input type="text" class="form-control" id="documentNumber" name="number" placeholder="Entrez le numéro">
        </div>
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-success btn-lg">Rechercher</button>
        </div>
    </form>
</section>

<!-- Points de Dépôt -->
<section class="container my-5">
    <h2 class="text-center mb-4">Points de dépôt proches</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Point de dépôt 1</h5>
                    <p class="card-text">Adresse 1, Ville</p>
                    <a href="#" class="btn btn-primary">Voir détails</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Point de dépôt 2</h5>
                    <p class="card-text">Adresse 2, Ville</p>
                    <a href="#" class="btn btn-primary">Voir détails</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Point de dépôt 3</h5>
                    <p class="card-text">Adresse 3, Ville</p>
                    <a href="#" class="btn btn-primary">Voir détails</a>
                </div>
            </div>
        </div>
    </div>
</section>
@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@endsection

