<?php $__env->startSection('content'); ?>
<div class="container-fluid pb-5">
    <?php echo $__env->make('partials.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger"><ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div>
    <?php endif; ?>
    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between">
                    <span><?php echo e($document->typeLabel()); ?> n° <?php echo e($document->numero_du_document); ?></span>
                    <span class="badge <?php echo e($document->statusBadgeClass()); ?>"><?php echo e($document->statusLabel()); ?></span>
                </div>
                <div class="card-body">
                    <p><strong>Nom sur le document :</strong> <?php echo e($document->nom_present_sur_le_document); ?></p>
                    <p><strong>Lieu / date :</strong> <?php echo e($document->lieu_de_perte); ?> — <?php echo e($document->date_de_perte); ?></p>
                    <p><strong>Informations complémentaires :</strong> <?php echo e($document->additional_info ?: '—'); ?></p>
                    <p><strong>Contact du déclarant :</strong> <?php echo e($document->user?->name); ?> — <?php echo e($document->contact_info); ?></p>
                    <?php if($document->restitue_at): ?>
                        <div class="alert alert-success mb-0">
                            Restitué à <strong><?php echo e($document->restitue_a); ?></strong> le <?php echo e($document->restitue_at->format('d/m/Y H:i')); ?>

                            <?php if($document->handler): ?> par <?php echo e($document->handler->name); ?> <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php if($document->photoUrls()): ?>
                        <hr>
                        <div class="d-flex flex-wrap gap-2">
                            <?php $__currentLoopData = $document->photoUrls(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e($url); ?>" target="_blank" rel="noopener"><img src="<?php echo e($url); ?>" alt="Photo du document" class="img-thumbnail" style="height:120px"></a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <?php if($document->status === \App\Enums\DocumentStatus::AwaitingPickup->value): ?>
                <div class="card mb-3">
                    <div class="card-header">Rendre le document</div>
                    <div class="card-body">
                        <p class="text-muted small">Vérifiez la pièce d'identité de la personne avant de confirmer.</p>
                        <form method="POST" action="<?php echo e(route('manager.documents.restitute', $document->id)); ?>">
                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                            <label for="restitue_a" class="form-label">Remis à (nom de la personne)</label>
                            <input id="restitue_a" name="restitue_a" class="form-control mb-2" required maxlength="255" value="<?php echo e(old('restitue_a', $document->nom_present_sur_le_document)); ?>">
                            <button class="btn btn-success w-100" onclick="return confirm('Confirmer la remise du document ?')">Confirmer la remise</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
            <a href="<?php echo e(route('manager.documents.index')); ?>" class="btn btn-link">← Retour aux documents du dépôt</a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.appgerant', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\projets\example-app\resources\views/manager/documents/show.blade.php ENDPATH**/ ?>