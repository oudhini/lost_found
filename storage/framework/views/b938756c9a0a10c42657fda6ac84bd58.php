
<?php $__env->startSection('content'); ?>
<div class="container">
    <form action="<?php echo e(route('depot.store')); ?>" method="POST" class="container mt-4 mb-5">
        <?php echo csrf_field(); ?>
        <div class="card shadow-lg p-4">
            <h4 class="text-center mb-4 text-primary">Créer un Point de Dépôt</h4>
            <?php if($errors->any()): ?>
        <div class="alert alert-danger mb-2">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nom du Point de Dépôt</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Ex : Point Alpha" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="address" class="form-label">Adresse(Ville,quartier)</label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="Ex : Douala,village" required>
                </div>
            </div>
    
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="contact" class="form-label">Contact</label>
                    <input type="text" class="form-control" id="contact" name="contact" placeholder="Téléphone ou Email">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="opening_hours" class="form-label">Heures d'Ouverture</label>
                    <input type="text" class="form-control" id="opening_hours" name="opening_hours" placeholder="Ex : 08h - 18h">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="latitude" class="form-label">Latitude</label>
                    <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Ex : 48.8566">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="longitude" class="form-label">Longitude</label>
                    <input type="text" class="form-control" id="longitude" name="longitude" placeholder="Ex : 2.3522">
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary px-4 py-2">Créer le Point de Dépôt</button>
            </div>
        </div>
    </form>
    
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.appsuperviseur', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\projets\example-app\resources\views/depot/create.blade.php ENDPATH**/ ?>