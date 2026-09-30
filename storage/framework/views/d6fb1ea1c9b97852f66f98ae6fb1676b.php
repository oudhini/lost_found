<?php $__env->startSection('title', 'Espace Utilisateur'); ?>
<?php $__env->startSection('content'); ?>
<?php if(session('login_success')): ?>
<div class="alert-success-login">
    <strong>Bienvenue !</strong> <?php echo e(session('login_success')); ?>

    <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
</div>
<?php endif; ?>
<?php if(session('lostdoc_store')): ?>
<div class="alert-success-lostdocstore">
     <?php echo e(session('lostdoc_store')); ?>

    <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
</div>
<?php endif; ?> 
<?php if(session('succes_supp_doc')): ?>
<div class="alert-success-lostdocstore">
     <?php echo e(session('succes_supp_doc')); ?>

    <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
</div>
<?php endif; ?> 
<?php if(session('cancelled')): ?>
    <div class="alert alert-warning">
        <?php echo e(session('cancelled')); ?>

    </div>
<?php endif; ?>
<div class="container mt-5">
    <h2 class="text-center">Documents que vous avez signalés comme Égarés</h2>
    <?php if($documents->isEmpty()): ?>
        <div class="alert alert-warning text-center mt-3" role="alert">
            Vous n'avez signalé aucun document pour le moment.
        </div>
    <?php else: ?>
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
                        <h5 class="card-title"><?php echo e(str_replace('_', ' ', $document->type_document)); ?></h5>
                        <div class="card-text">
                            <strong class="card-text text-primary">Propriétaire: <?php echo e($document->nom_present_sur_le_document); ?></strong> <br>
                            <strong class="card-text">Statut: <?php echo e($document->status); ?></strong> <br>
                            <small class="text-secondary">Lieu de perte: <?php echo e($document->lieu_de_perte); ?></small> <br>
                            <strong class="card-text"><small class="text-muted">Perdu le: <?php echo e($document->date_de_perte ? \Carbon\Carbon::parse($document->date_de_perte)->format('d M Y') : 'Date non spécifiée'); ?></small></strong> <br>
                            </div>
                        
                        <a href="<?php echo e(route('documents.show', $document->id)); ?>" class="btn btn-primary mx-3">Voir Détails</a>
                        <form action="<?php echo e(route('supp_doc', $document->id)); ?>" method="POST" class="d-inline" onsubmit="return confirmDelete(event)" >
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                        <?php if(($matchesByDocument[$document->id] ?? collect())->isNotEmpty()): ?>
                            <div class="alert alert-success mt-3 mb-0 py-2 px-3">
                                <i class="bi bi-lightbulb"></i> <strong>Ça pourrait être le vôtre :</strong>
                                <ul class="mb-0 ps-3">
                                    <?php $__currentLoopData = $matchesByDocument[$document->id]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $match): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><a href="<?php echo e(route('documents.show', $match->id)); ?>"><?php echo e($match->nom_present_sur_le_document); ?></a> — <?php echo e($match->depot?->name ?? 'dépôt non précisé'); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div class="d-flex justify-content-center">
            <?php echo e($documents->links()); ?> <!-- Affiche les liens de pagination -->
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\projets\example-app\resources\views/user/documentsignale.blade.php ENDPATH**/ ?>