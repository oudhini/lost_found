

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <?php if(session('success')): ?>
    <div class="alert-success-lostdocstore">
         <?php echo e(session('success')); ?>

        <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
    </div>
    <?php endif; ?> 
    <!-- Titre de la page -->
    <h1 class="mb-4">Détails du Dépôt : <?php echo e($depot->name); ?></h1>

    <!-- Carte pour afficher la localisation -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="bi bi-geo-alt"></i> Localisation
        </div>
        <div class="card-body p-0">
            <div id="map" style="height: 300px; width: 100%;"></div>
        </div>
    </div>

    <!-- Informations principales -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="bi bi-info-circle"></i> Informations Générales
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nom :</strong> <?php echo e($depot->name); ?></p>
                    <p><strong>Adresse :</strong> <?php echo e($depot->address); ?></p>
                    <p><strong>Contact :</strong> <?php echo e($depot->contact); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Statut :</strong>
                        <span class="badge <?php echo e($depot->statut == 'actif' ? 'bg-success' : 'bg-danger'); ?>">
                            <?php echo e($depot->statut); ?>

                        </span>
                    </p>
                    <p><strong>Heures d'Ouverture :</strong> <?php echo e($depot->opening_hours); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Coordonnées GPS -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="bi bi-pin-map"></i> Coordonnées GPS
        </div>
        <div class="card-body">
            <p><strong>Latitude :</strong> <?php echo e($depot->latitude); ?></p>
            <p><strong>Longitude :</strong> <?php echo e($depot->longitude); ?></p>
        </div>
    </div>

    <!-- Bouton de retour -->
    <div class="text-center">
        <a href="<?php echo e(route('depot.index')); ?>" class="btn btn-primary">
            <i class="bi bi-arrow-left"></i> Retour à la liste
        </a>
    </div>
</div>
<!-- Intégration de Leaflet et OpenStreetMap -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Coordonnées du dépôt
    const depotLocation = [<?php echo e($depot->latitude); ?>, <?php echo e($depot->longitude); ?>];

    // Initialiser la carte
    const map = L.map('map').setView(depotLocation, 15);

    // Ajouter une couche de tuiles OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Ajouter un marqueur pour le dépôt
    L.marker(depotLocation)
        .addTo(map)
        .bindPopup('<?php echo e($depot->name); ?>')
        .openPopup();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.appsuperviseur', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\projets\example-app\resources\views/depot/show.blade.php ENDPATH**/ ?>