<?php $__env->startSection('title', 'Espace gerant'); ?>
<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php if(session('login_success')): ?>
<div class="alert-success-login">
    <strong>Bienvenue !</strong> <?php echo e(session('login_success')); ?>

    <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
</div>
<?php endif; ?>
<p class="p mt-4 fw-bold">Bonjour, <?php echo e(Auth::user()->name); ?> !</p>

<?php if($depot === null): ?>
    <div class="alert alert-warning" role="alert">
        <i class="bi bi-exclamation-triangle"></i>
        Aucun dépôt ne vous est assigné pour l'instant. Un superviseur doit vous en attribuer un avant que vous puissiez réceptionner ou rendre des documents.
    </div>
<?php else: ?>
    <div class="alert alert-info" role="alert">
        <i class="bi bi-geo-alt"></i> Votre dépôt : <strong><?php echo e($depot->name); ?></strong> — <?php echo e($depot->address); ?>

        <?php if($depot->opening_hours): ?> <span class="text-muted">(<?php echo e($depot->opening_hours); ?>)</span> <?php endif; ?>
    </div>
    <div class="row mb-4 py-2 d-flex align-items-stretch">
        <div class="col-md-3">
            <a href="<?php echo e(route('manager.documents.index', ['status' => 'en_attente_de_retrait'])); ?>" class="text-decoration-none">
                <div class="card text-white bg-warning h-100"><div class="card-body">
                    <h5 class="card-title">En attente de retrait</h5><p class="card-text fs-3"><?php echo e($depotStats['awaiting']); ?></p>
                </div></div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?php echo e(route('manager.history')); ?>" class="text-decoration-none">
                <div class="card text-white bg-success h-100"><div class="card-body">
                    <h5 class="card-title">Restitués</h5><p class="card-text fs-3"><?php echo e($depotStats['returned']); ?></p>
                </div></div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?php echo e(route('manager.documents.index')); ?>" class="text-decoration-none">
                <div class="card text-white bg-primary h-100"><div class="card-body">
                    <h5 class="card-title">Total du dépôt</h5><p class="card-text fs-3"><?php echo e($depotStats['total']); ?></p>
                </div></div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?php echo e(route('manager.receive')); ?>" class="text-decoration-none">
                <div class="card text-white bg-danger h-100"><div class="card-body">
                    <h5 class="card-title">Signalements à rapprocher</h5><p class="card-text fs-3"><?php echo e($depotStats['toMatch']); ?></p>
                </div></div>
            </a>
        </div>
    </div>

    <div class="d-flex gap-2 mb-4">
        <a href="<?php echo e(route('manager.receive')); ?>" class="btn btn-primary"><i class="bi bi-file-earmark-plus"></i> Réceptionner</a>
        <a href="<?php echo e(route('manager.found.create')); ?>" class="btn btn-outline-primary"><i class="bi bi-plus-circle"></i> Document trouvé sans signalement</a>
    </div>

    <div class="card mb-5">
        <div class="card-header">Derniers documents en attente de retrait</div>
        <ul class="list-group list-group-flush">
            <?php $__empty_1 = true; $__currentLoopData = $latestAwaiting; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="<?php echo e(route('manager.documents.show', $document->id)); ?>"><?php echo e($document->typeLabel()); ?> — <?php echo e($document->nom_present_sur_le_document); ?></a>
                    <small class="text-muted"><?php echo e($document->updated_at?->diffForHumans()); ?></small>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <li class="list-group-item text-muted">Aucun document en attente pour le moment.</li>
            <?php endif; ?>
        </ul>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.appgerant', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\projets\example-app\resources\views/gerant/dashboard.blade.php ENDPATH**/ ?>