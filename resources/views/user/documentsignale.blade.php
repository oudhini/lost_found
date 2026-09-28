@extends('layouts.app2')
@section('title', 'Espace Utilisateur')
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
@if (session('succes_supp_doc'))
<div class="alert-success-lostdocstore">
     {{ session('succes_supp_doc') }}
    <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
</div>
@endif 
@if(session('cancelled'))
    <div class="alert alert-warning">
        {{ session('cancelled') }}
    </div>
@endif
<div class="container mt-5">
    <h2 class="text-center">Documents que vous avez signalés comme Égarés</h2>
    @if($documents->isEmpty())
        <div class="alert alert-warning text-center mt-3" role="alert">
            Vous n'avez signalé aucun document pour le moment.
        </div>
    @else
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
                        <h5 class="card-title">{{ str_replace('_', ' ', $document->type_document) }}</h5>
                        <div class="card-text">
                            <strong class="card-text text-primary">Propriétaire: {{ $document->nom_present_sur_le_document }}</strong> <br>
                            <strong class="card-text">Statut: {{ $document->status }}</strong> <br>
                            <small class="text-secondary">Lieu de perte: {{ $document->lieu_de_perte }}</small> <br>
                            <strong class="card-text"><small class="text-muted">Perdu le: {{ $document->date_de_perte ? \Carbon\Carbon::parse($document->date_de_perte)->format('d M Y') : 'Date non spécifiée' }}</small></strong> <br>
                            </div>
                        
                        <a href="{{ route('welcome', $document->id) }}" class="btn btn-primary mx-3">Voir Détails</a>
                        <form action="{{ route('supp_doc', $document->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(event)" >
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                        {{-- <a href="{{ route('welcome', $document->id) }}" class="btn btn-warning">Signaler Récupération</a> --}}
                    </div>
                </div>
            </div>
        @endforeach
        <div class="d-flex justify-content-center">
            {{ $documents->links() }} <!-- Affiche les liens de pagination -->
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
<script>
    function confirmDelete(event) {
        event.preventDefault(); // Empêche l'envoi du formulaire par défaut
        const confirmation = confirm("Êtes-vous sûr de vouloir supprimer ce document ?");
        
        if (confirmation) {
            event.target.submit(); // Soumet le formulaire si l'utilisateur confirme
        } else {
            alert("Suppression annulée."); // Affiche un message si l'utilisateur annule
        }
    }
</script>
@endsection