<?php $__env->startSection('title', 'Espace Utilisateur'); ?>
<?php $__env->startSection('content'); ?>

<div class="container mt-5">
    <h2 class="text-center">Documents Retrouvés</h2>
    <?php if($documents->isEmpty()): ?>
        <div class="alert alert-warning text-center mt-3" role="alert">
             aucun document trouvé n'est enregistré sur la plateforme pour le moment.
        </div>
    <?php else: ?>
    <form method="GET" action="<?php echo e(route('documents.index.found')); ?>" class="row g-2 mb-4">
        <div class="col-md-8">
            <label for="q" class="form-label">Rechercher par nom, numéro ou lieu</label>
            <input type="search" name="q" id="q" value="<?php echo e($q); ?>" class="form-control" placeholder="Ex. Alice, CNI 123..., Marché Mokolo">
        </div>
        <div class="col-md-4">
            <label for="type" class="form-label">Type de document</label>
            <select name="type" id="type" class="form-select">
                <option value="">Tous les types</option>
                <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($type); ?>" <?php echo e(request('type') == $type ? 'selected' : ''); ?>><?php echo e(str_replace('_', ' ', $type)); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-12">
            <button class="btn btn-primary"><i class="bi bi-search"></i> Rechercher</button>
        </div>
    </form>
    <div class="row">
        <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-4">
                <div class="card">
                    <?php if($document->photoUrls()): ?>
                        <img src="<?php echo e($document->photoUrls()[0]); ?>" class="card-img-top" alt="Photo du document">
                    <?php else: ?>
                        <div class="card-img-top d-flex justify-content-center align-items-center" style="height: 150px; background-color: #e9ecef;">
                            <i class="bi bi-file-earmark-text" style="font-size: 50px;"></i>
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo e($document->type_document); ?></h5>
                        <div class="card-text">
                            <strong class="card-text">Propriétaire: <?php echo e($document->nom_present_sur_le_document); ?></strong> <br>
                            <strong class="card-text">Statut: <?php echo e($document->status); ?></strong> <br>
                            <strong class="card-text">Lieu de perte: <?php echo e($document->lieu_de_perte); ?></strong> <br>
                            <strong class="card-text"><small class="text-muted">Perdu le: <?php echo e($document->date_de_perte ? \Carbon\Carbon::parse($document->date_de_perte)->format('d M Y') : 'Date non spécifiée'); ?></small></strong> <br>
                            </div>
                        
                        <a href="<?php echo e(route('documents.show', $document->id)); ?>" class="btn btn-primary">Voir Détails</a>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div class="d-flex justify-content-center">
            <?php echo e($documents->appends(request()->input())->links()); ?> <!-- Affiche les liens de pagination -->
        </div>
    </div>
    <?php endif; ?>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\projets\example-app\resources\views/document/all_found_documents.blade.php ENDPATH**/ ?>