<?php $__env->startSection('content'); ?>
<div class="container-fluid pb-5">
    <?php if($documents->isEmpty()): ?>
        <div class="alert alert-info text-center">Aucun document n'a encore été rendu depuis ce dépôt.</div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead><tr><th>Document</th><th>Remis à</th><th>Date</th><th>Par</th><th></th></tr></thead>
            <tbody>
            <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($document->typeLabel()); ?> — <?php echo e($document->nom_present_sur_le_document); ?></td>
                    <td><?php echo e($document->restitue_a); ?></td>
                    <td><?php echo e($document->restitue_at?->format('d/m/Y H:i')); ?></td>
                    <td><?php echo e($document->handler?->name ?? '—'); ?></td>
                    <td><a class="btn btn-info btn-sm" href="<?php echo e(route('manager.documents.show', $document->id)); ?>" aria-label="Voir"><i class="bi bi-eye"></i></a></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php echo e($documents->links()); ?>

    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.appgerant', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\projets\example-app\resources\views/manager/history.blade.php ENDPATH**/ ?>