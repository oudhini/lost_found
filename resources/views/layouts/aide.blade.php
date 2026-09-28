<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signalement de Documents Perdus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">DocFinder</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Signaler un Document</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Documents Trouvés</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center">Signaler un Document Perdu</h1>
        <p class="text-center">Remplissez le formulaire ci-dessous pour signaler un document perdu.</p>

        <form>
            <div class="mb-3">
                <label for="fullName" class="form-label">Nom complet</label>
                <input type="text" class="form-control" id="fullName" placeholder="Entrez votre nom complet" required>
            </div>

            <div class="mb-3">
                <label for="contactInfo" class="form-label">Coordonnées</label>
                <input type="text" class="form-control" id="contactInfo" placeholder="Numéro de téléphone ou email" required>
            </div>

            <div class="mb-3">
                <label for="documentType" class="form-label">Type de Document</label>
                <select class="form-select" id="documentType" required>
                    <option value="">-- Sélectionnez un type --</option>
                    <option value="id_card">Carte d'identité</option>
                    <option value="passport">Passeport</option>
                    <option value="driving_license">Permis de conduire</option>
                    <option value="other">Autre</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="lostLocation" class="form-label">Lieu de perte</label>
                <input type="text" class="form-control" id="lostLocation" placeholder="Précisez le lieu où le document a été perdu" required>
            </div>

            <div class="mb-3">
                <label for="additionalInfo" class="form-label">Informations supplémentaires</label>
                <textarea class="form-control" id="additionalInfo" rows="4" placeholder="Ajoutez des détails ou une description"></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">Soumettre</button>
        </form>
    </div>

    <footer class="bg-primary text-white text-center py-3 mt-5">
        <p>&copy; 2025 DocFinder. Tous droits réservés.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


{{-- $photos = explode(',', $document->photos);
foreach ($photos as $photo) {
    echo '<img src="' . asset('storage/photos/' . $photo) . '" alt="Photo">';
} --}}