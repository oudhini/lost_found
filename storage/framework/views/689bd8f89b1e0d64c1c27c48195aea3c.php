<?php $__env->startSection('content'); ?>
<div class="container-fluid pb-5">
    <?php echo $__env->make('partials.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <p class="text-muted">Quelqu'un vient de déposer un document ? Cherchez d'abord s'il a déjà été signalé perdu (nom, numéro ou lieu).</p>
    <form method="GET" action="<?php echo e(route('manager.receive')); ?>" class="d-flex gap-2 mb-3">
        <input type="search" name="q" value="<?php echo e($term); ?>" minlength="2" required class="form-control <?php $__errorArgs = ['q'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Ex. nom sur le document, numéro…" aria-label="Recherche">
        <button class="btn btn-primary"><i class="bi bi-search"></i> Chercher</button>
    </form>
    <?php $__errorArgs = ['q'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger mb-3"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

    <?php if($term !== null): ?>
        <?php if($matches->isEmpty()): ?>
            <div class="alert alert-warning">
                Aucun signalement ne correspond à « <?php echo e($term); ?> ».
                <a href="<?php echo e(route('manager.found.create')); ?>" class="alert-link">Enregistrer ce document comme trouvé</a>.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead><tr><th>Type</th><th>Nom sur le document</th><th>Numéro</th><th>Perdu à</th><th>Date</th><th></th></tr></thead>
                    <tbody>
                    <?php $__currentLoopData = $matches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($document->typeLabel()); ?></td>
                            <td><?php echo e($document->nom_present_sur_le_document); ?></td>
                            <td><?php echo e($document->numero_du_document); ?></td>
                            <td><?php echo e($document->lieu_de_perte); ?></td>
                            <td><?php echo e($document->date_de_perte); ?></td>
                            <td>
                                <form method="POST" action="<?php echo e(route('manager.receive.store', $document->id)); ?>">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <button class="btn btn-success btn-sm" onclick="return confirm('Ce document est-il bien celui que vous avez en main ?')">Réceptionner</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <a href="<?php echo e(route('manager.found.create')); ?>">Aucun ne correspond ? Enregistrer comme document trouvé</a>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.appgerant', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\projets\example-app\resources\views/manager/receive.blade.php ENDPATH**/ ?>