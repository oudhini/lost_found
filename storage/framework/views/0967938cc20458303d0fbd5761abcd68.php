<?php $__env->startSection('content'); ?>
<div class="container-fluid pb-5">
    <?php echo $__env->make('partials.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between">
                    <span><i class="bi bi-file-earmark-text"></i> <?php echo e($document->typeLabel()); ?><?php if($document->numero_du_document): ?> — n° <?php echo e($document->numero_du_document); ?> <?php endif; ?></span>
                    <span class="badge <?php echo e($document->statusBadgeClass()); ?>"><?php echo e($document->statusLabel()); ?></span>
                </div>
                <div class="card-body">
                    <p><strong>Nom sur le document :</strong> <?php echo e($document->nom_present_sur_le_document); ?></p>
                    <p><strong>Lieu / date de perte :</strong> <?php echo e($document->lieu_de_perte); ?> — <?php echo e($document->date_de_perte ? \Carbon\Carbon::parse($document->date_de_perte)->format('d/m/Y') : 'Non spécifiée'); ?></p>
                    <?php if($document->additional_info): ?>
                        <p><strong>Informations complémentaires :</strong> <?php echo e($document->additional_info); ?></p>
                    <?php endif; ?>
                    <?php if($isOwner || $document->status !== \App\Enums\DocumentStatus::Returned->value): ?>
                        <p><strong>Contact :</strong> <?php echo e($document->contact_info); ?></p>
                    <?php endif; ?>
                    <?php if($document->depot): ?>
                        <p><strong>Dépôt :</strong> <?php echo e($document->depot->name); ?> — <?php echo e($document->depot->address); ?></p>
                    <?php endif; ?>
                    <?php if($document->restitue_at): ?>
                        <div class="alert alert-success mb-0">
                            Restitué à <strong><?php echo e($document->restitue_a); ?></strong> le <?php echo e($document->restitue_at->format('d/m/Y H:i')); ?>

                        </div>
                    <?php endif; ?>
                    <?php if($document->photoUrls()): ?>
                        <hr>
                        <div class="d-flex flex-wrap gap-2">
                            <?php $__currentLoopData = $document->photoUrls(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e($url); ?>" target="_blank" rel="noopener"><img src="<?php echo e($url); ?>" alt="Photo du document" class="img-thumbnail" style="height:130px"></a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <?php if($isOwner): ?>
                <div class="card mb-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <span class="text-muted small">C'est votre signalement.</span>
                        <form method="POST" action="<?php echo e(route('supp_doc', $document->id)); ?>" onsubmit="return confirm('Supprimer ce signalement ?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i> Supprimer</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($possibleMatches->isNotEmpty()): ?>
                <div class="card mb-3">
                    <div class="card-header">Ça pourrait être le vôtre</div>
                    <ul class="list-group list-group-flush">
                        <?php $__currentLoopData = $possibleMatches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $match): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="list-group-item">
                                <a href="<?php echo e(route('documents.show', $match->id)); ?>"><?php echo e($match->nom_present_sur_le_document); ?></a>
                                <div class="small text-muted">Déposé à <?php echo e($match->depot?->name ?? '—'); ?></div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <a href="<?php echo e(url()->previous()); ?>" class="btn btn-link">← Retour</a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\projets\example-app\resources\views/document/show.blade.php ENDPATH**/ ?>