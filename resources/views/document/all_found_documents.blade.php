@extends('layouts.app2')
@section('title', 'Espace Utilisateur')
@section('content')

<div class="container mt-5">
    <h2 class="text-center">Documents Retrouvés</h2>
    @if($documents->isEmpty())
        <div class="alert alert-warning text-center mt-3" role="alert">
             aucun document trouvé n'est enregistré sur la plateforme pour le moment.
        </div>
    @else
    <form method="GET" action="{{ route('documents.index.found') }}" class="mb-4">
        <div class="form-group">
            <label for="type">Choisissez un type de document :</label>
            <select name="type" id="type" class="form-control" onchange="this.form.submit()">
                <option value="">Tous les types</option>
                @foreach($types as $type)
                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ str_replace('_', ' ', $type) }}</option>
                @endforeach
            </select>
        </div>
    </form>
    <div class="row">
        @foreach($documents as $document)
            <div class="col-md-4">
                <div class="card">
                    @if($document->photos) <!-- Vérifiez si l'image existe -->
                        <img src="{{ asset('assets/images/documentspictures/' . $document->photos) }}" class="card-img-top" alt="{{ $document->name }}">
                    @else
                <?php 
                ?>
                        <div class="card-img-top d-flex justify-content-center align-items-center" style="height: 150px; background-color: #e9ecef;">
                            <i class="bi bi-file-earmark-text" style="font-size: 50px;"></i> <!-- Icône Bootstrap -->
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $document->type_document }}</h5>
                        <div class="card-text">
                            <strong class="card-text">Propriétaire: {{ $document->nom_present_sur_le_document }}</strong> <br>
                            <strong class="card-text">Statut: {{ $document->status }}</strong> <br>
                            <strong class="card-text">Lieu de perte: {{ $document->lieu_de_perte }}</strong> <br>
                            <strong class="card-text"><small class="text-muted">Perdu le: {{ $document->date_de_perte ? \Carbon\Carbon::parse($document->date_de_perte)->format('d M Y') : 'Date non spécifiée' }}</small></strong> <br>
                            </div>
                        
                        <a href="{{ route('welcome', $document->id) }}" class="btn btn-primary">Voir Détails</a>
                        {{-- <form action="{{ route('welcome', $document->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form> --}}
                        {{-- <a href="{{ route('welcome', $document->id) }}" class="btn btn-warning">Signaler Récupération</a> --}}
                    </div>
                </div>
            </div>
        @endforeach
        <div class="d-flex justify-content-center">
            {{ $documents->appends(request()->input())->links() }} <!-- Affiche les liens de pagination -->
        </div>
    </div>
    @endif
</div>

<style>
  body {
            background-color: #f4f6f9;
        }
        .card {
            margin: 20px 0;
            height: 350px; /* Hauteur fixe pour toutes les cartes */
        }
        .card-img-top {
            height: 150px; /* Hauteur de l'image */
            object-fit: cover; /* Pour garder le ratio de l'image */
        }
</style>
@endsection