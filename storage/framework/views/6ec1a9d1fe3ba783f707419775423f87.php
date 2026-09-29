

<?php $__env->startSection('content'); ?>
<div class="container mt-5 mb-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h2 class="text-primary">Liste des Gérants</h2>
        <a href="<?php echo e(route('gerant.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Ajouter un Gérant</a>
    </div>
    <?php if($managers->isEmpty()): ?>
        <div class="alert alert-warning text-center mt-3" role="alert">
            Aucun gerant créer pour le moment.
        </div>
    <?php else: ?>
    <?php if(session('success')): ?>
        <div class="alert-success-lostdocstore">
        <?php echo e(session('success')); ?>

        <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
        </div>
    <?php endif; ?> 
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $managers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $manager): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($manager->id); ?></td>
                    <td><?php echo e($manager->name); ?></td>
                    <td><?php echo e($manager->email); ?></td>
                    <td><?php echo e($manager->phone); ?></td>
                    <td>
                        <a href="<?php echo e(route('gerant.show', $manager->id)); ?>" class="btn btn-info"><i class="bi bi-eye mr-1"></i>Voir</a>
                        <a href="<?php echo e(route('gerant.edit', $manager->id)); ?>" class="btn btn-warning">                     <i class="bi bi-pencil"></i>Modifier
                        </a>
                        <form action="<?php echo e(route('gerant.destroy', $manager->id)); ?>" method="POST" style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce gérant ?')"><i class="bi bi-trash"></i>Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php endif; ?> 
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.appsuperviseur', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\projets\example-app\resources\views/gerant/index.blade.php ENDPATH**/ ?>