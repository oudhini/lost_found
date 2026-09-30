<?php $__env->startSection('content'); ?>
<div class="container-fluid pb-5">
    <?php echo $__env->make('partials.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <form method="GET" action="<?php echo e(route('admin.documents.index')); ?>" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="search" name="q" value="<?php echo e($filters['q'] ?? ''); ?>" class="form-control" placeholder="Nom, numéro, lieu, signaleur…" aria-label="Recherche">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select" aria-label="Statut">
                <option value="">Tous les statuts</option>
                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($status->value); ?>" <?php if(($filters['status'] ?? '') === $status->value): echo 'selected'; endif; ?>><?php echo e($status->label()); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="type" class="form-select" aria-label="Type">
                <option value="">Tous les types</option>
                <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($value); ?>" <?php if(($filters['type'] ?? '') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="depot_id" class="form-select" aria-label="Dépôt">
                <option value="">Tous les dépôts</option>
                <?php $__currentLoopData = $depots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $depot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($depot->id); ?>" <?php if((string) ($filters['depot_id'] ?? '') === (string) $depot->id): echo 'selected'; endif; ?>><?php echo e($depot->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary"><i class="bi bi-search"></i> Filtrer</button>
            <a class="btn btn-outline-secondary" href="<?php echo e(route('admin.documents.index')); ?>">Réinitialiser</a>
            <a class="btn btn-outline-success ms-auto" href="<?php echo e(route('admin.documents.export', request()->query())); ?>"><i class="bi bi-download"></i> CSV</a>
        </div>
    </form>

    <?php if($documents->isEmpty()): ?>
        <div class="alert alert-warning text-center">Aucun document ne correspond à ces critères.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead><tr><th>#</th><th>Type</th><th>Nom sur le document</th><th>Statut</th><th>Signalé par</th><th>Dépôt</th><th>Date</th><th></th></tr></thead>
                <tbody>
                <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($document->id); ?></td>
                        <td><?php echo e($document->typeLabel()); ?></td>
                        <td><?php echo e($document->nom_present_sur_le_document); ?></td>
                        <td><span class="badge <?php echo e($document->statusBadgeClass()); ?>"><?php echo e($document->statusLabel()); ?></span></td>
                        <td><?php echo e($document->user?->name ?? '—'); ?></td>
                        <td><?php echo e($document->depot?->name ?? '—'); ?></td>
                        <td><?php echo e($document->created_at?->format('d/m/Y')); ?></td>
                        <td><a href="<?php echo e(route('admin.documents.show', $document)); ?>" class="btn btn-info btn-sm" aria-label="Voir le document <?php echo e($document->id); ?>"><i class="bi bi-eye"></i></a></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php echo e($documents->links()); ?>

    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.appsuperviseur', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\projets\example-app\resources\views/admin/documents/index.blade.php ENDPATH**/ ?>