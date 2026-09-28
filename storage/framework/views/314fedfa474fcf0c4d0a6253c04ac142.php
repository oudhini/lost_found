

<?php $__env->startSection('content'); ?>
<div class="container-table">
    <div class="d-flex justify-content-center mt-4 align-items-center mb-2">
        <h2 class="text-primary">Liste des Points de Dépôt</h2>
    </div>
    
    <?php if(session('success')): ?>
<div class="alert-success-lostdocstore">
     <?php echo e(session('success')); ?>

    <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
</div>
<?php endif; ?> 

    <div class="table-responsive">
        <table class="table mx-0 table-hover table-bordered text-left">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Adresse</th>
                    <th>Contact</th>
                    <th>Statut</th>
                    <th>Heures d'Ouverture</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $depots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $depot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($depot->name); ?></td>
                        <td><?php echo e($depot->address); ?></td>
                        <td><?php echo e($depot->contact); ?></td>
                        <td>
                            <span class="badge <?php echo e($depot->statut == 'actif' ? 'bg-success' : 'bg-danger'); ?>">
                                <?php echo e($depot->statut); ?>

                            </span>
                        </td>
                        <td><?php echo e($depot->opening_hours); ?></td>
                        <td><?php echo e($depot->latitude); ?></td>
                        <td><?php echo e($depot->longitude); ?></td>
                        <td>
                            <a href="<?php echo e(route('depot.show', $depot->id)); ?>" class="btn btn-depot btn-info btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\projets\example-app\resources\views/user/listdepot.blade.php ENDPATH**/ ?>