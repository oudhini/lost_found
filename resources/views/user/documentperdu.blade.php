@extends('layouts.app2')
@section('title', '')
@section('content')
<div class="container mt-5">
    <h1 class="text-center text-primary">Signaler un Document Perdu</h1>
    <p class="text-center">Remplissez le formulaire ci-dessous pour signaler un document perdu.</p>

    <form class="form mb-5" method="POST" action="{{route('store_lost_document')}}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="fullName" class="form-label">Nom complet</label><span class="text-danger mx-1">*</span>
            <input type="text" class="form-control" id="fullName" name="nom_present_sur_le_document" placeholder="Entrez votre nom complet" required>
        </div>
    
        <div class="mb-3">
            <label for="contactInfo" class="form-label">Coordonnées</label><span class="text-danger mx-1">*</span>
            <input type="text" class="form-control" id="contact_info" name="contact_info" placeholder="Numéro de téléphone ou email" required>
        </div>
    
        <div class="mb-3">
            <label for="documentType" class="form-label">Type de Document</label><span class="text-danger mx-1">*</span>
            <select class="form-select" name="type_document" id="documentType" required>
                <option value="">-- Sélectionnez un type --</option>
                <option value="CNI">Carte d'identité</option>
                <option value="passport">Passeport</option>
                <option value="permis_de_conduire">Permis de conduire</option>
                <option value="acte_de_naissance">Acte de naissance</option>
                <option value="autres">Autre</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="serialNumber" class="form-label">Numéro de série</label>
            <input type="text" class="form-control" id="serialNumber" name="numero_du_document" placeholder="Entrez le numéro de série de votre document">
        </div>
        <div class="mb-3">
            <label for="photos" class="form-label">Photos du document</label>
            <input type="file" class="form-control" name="photos[]" id="photos" multiple accept="image/*">
            <small class="text-muted">Vous pouvez sélectionner plusieurs photos</small>
        </div>
        <div class="mb-3">
            <label for="lostLocation" class="form-label">Lieu de perte</label><span class="text-danger mx-1">*</span>
            <input type="text" class="form-control" id="lostLocation" name="lieu_de_perte" placeholder="Précisez le lieu où le document a été perdu" required>
        </div>
        <div class="mb-3">
            <label for="lostDate" class="form-label">Date de perte</label><span class="text-danger mx-1">*</span>
            <input type="date" class="form-control" id="lostDate" name="date_de_perte" placeholder="Précisez quand le document a été perdu" required>
        </div>
        <div class="mb-3">
            <label for="additionalInfo" class="form-label">Informations supplémentaires</label>
            <textarea class="form-control" id="additionalInfo" name="additional_info" rows="4" placeholder="Ajoutez des détails ou une description"></textarea>
        </div>
      
        <button type="submit" class="btn btn-primary w-100">Soumettre</button>
    </form>
</div>

<script>
    console.log("bonjour");
    document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    console.log(form);
    form.addEventListener('submit', function(e) {
        const fullName = document.getElementById('fullName').value;
        const contactInfo = document.getElementById('contactInfo').value;
        const documentType = document.getElementById('documentType').value;
        const lostLocation = document.getElementById('lostLocation').value;

        if (fullName === '' || contactInfo === '' || documentType === '' || lostLocation==='') {
            e.preventDefault();
            alert('Veuillez remplir les champs obligatoires.');
        }
    });
});
</script>
@endsection