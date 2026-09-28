
<?php $__env->startSection('title', 'Espace administrateur'); ?>
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
<?php if(session('success')): ?>
<div class="alert-success-lostdocstore">
     <?php echo e(session('success')); ?>

    <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
</div>
<?php endif; ?> 
<?php if(auth()->guard()->check()): ?>
    <p class="p mt-4 fw-bold">Bienvenue admin, <?php echo e(Auth::user()->name); ?> !</p>
    <!-- Message d'information -->
    <div class="alert alert-info fs-4 fw-bold text-center" role="alert">
        Les totaux ci-dessous concernent l'ensemble de la plateforme.
    </div>
    <div class="row mb-4 py-2 d-flex align-items-stretch">
        <div class="col-md-3">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h5 class="card-title h-50">Total Documents</h5>
                    <p class="card-text h-50 pt-3"><?php echo e($totalDocuments); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <h5 class="card-title h-50">Rendus</h5>
                    <p class="card-text h-50 pt-3"><?php echo e($totalRendu); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning h-100">
                <div class="card-body">
                    <h5 class="card-title h-50">en Attente de retrait</h5>
                    <p class="card-text h-50 pt-3"><?php echo e($totalRetrouve); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger h-100">
                <div class="card-body">
                    <h5 class="card-title h-50">Toujours égarés</h5>
                    <p class="card-text h-50 pt-3"><?php echo e($totalPerdu); ?></p>
                </div>
            </div>
        </div>
    </div>
     <!-- Message d'information deuxieme ligne -->
     <div class="row mb-4 py-2 d-flex align-items-stretch">
        <div class="col-md-3">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h5 class="card-title h-50">Total Gerants</h5>
                    <p class="card-text h-50 pt-3"><?php echo e($totalGerant); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning h-100">
                <div class="card-body">
                    <h5 class="card-title h-50">Totals Depots</h5>
                    <p class="card-text h-50 pt-3"><?php echo e($totalDepot); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <h5 class="card-title h-50">Total Depots actifs</h5>
                    <p class="card-text h-50 pt-3"><?php echo e($totalDepotActif); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger h-100">
                <div class="card-body">
                    <h5 class="card-title h-50">Total Depots inactif</h5>
                    <p class="card-text h-50 pt-3"><?php echo e($totalDepotInactif); ?></p>
                </div>
            </div>
        </div>
    </div>
    <!-- Message d'information -->
    <div class="alert alert-info text-center fs-4 fw-bold" role="alert">
        Les totaux ci-dessous vous  concernent spécialement.
    </div>
    <div class="row mb-4 py-2 d-flex align-items-stretch">   
        <div class="col-md-3">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h5 class="card-title h-50">signalés par vous</h5>
                    <p class="card-text h-50 pt-3"><?php echo e($totalSignale); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <h5 class="card-title h-50">Rendus</h5>
                    <p class="card-text h-50 pt-3"><?php echo e($totalPersoRendu); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning h-100">
                <div class="card-body">
                    <h5 class="card-title h-50">En attente de retrait</h5>
                    <p class="card-text h-50 pt-3"><?php echo e($totalPersoRetrouve); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger h-100">
                <div class="card-body">
                    <h5 class="card-title h-50">Toujours égarés</h5>
                    <p class="card-text h-50 pt-3"><?php echo e($totalPersoPerdu); ?></p>
                </div>
            </div>
        </div>
       
    </div>
<?php else: ?>
    <p>Veuillez vous connecter pour accéder à cette page.</p>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.appsuperviseur', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\projets\example-app\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>