@extends('layouts.appsuperviseur')
@section('content')
<div class="container">
    <form action="{{ route('depot.update', $depot->id) }}" method="POST" class="container mt-4 mb-5">
        @csrf
        @method('PUT') <!-- Utiliser la méthode PUT pour la mise à jour -->

        <div class="card shadow-lg p-4">
            <h4 class="text-center mb-4 text-primary">Modifier le Point de Dépôt : {{ $depot->name }}</h4>

            <!-- Afficher les erreurs de validation -->
            @if ($errors->any())
                <div class="alert alert-danger mb-2">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nom du Point de Dépôt</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Ex : Point Alpha" value="{{ old('name', $depot->name) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="address" class="form-label">Adresse (Ville, quartier)</label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="Ex : Douala, village" value="{{ old('address', $depot->address) }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="contact" class="form-label">Contact</label>
                    <input type="text" class="form-control" id="contact" name="contact" placeholder="Téléphone ou Email" value="{{ old('contact', $depot->contact) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="opening_hours" class="form-label">Heures d'Ouverture</label>
                    <input type="text" class="form-control" id="opening_hours" name="opening_hours" placeholder="Ex : 08h - 18h" value="{{ old('opening_hours', $depot->opening_hours) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="latitude" class="form-label">Latitude</label>
                    <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Ex : 48.8566" value="{{ old('latitude', $depot->latitude) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="longitude" class="form-label">Longitude</label>
                    <input type="text" class="form-control" id="longitude" name="longitude" placeholder="Ex : 2.3522" value="{{ old('longitude', $depot->longitude) }}">
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary px-4 py-2">Enregistrer les modifications</button>
                <a href="{{ route('depot.show', $depot->id) }}" class="btn btn-secondary px-4 py-2">Annuler</a>
            </div>
        </div>
    </form>
</div>
@endsection