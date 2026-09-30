<?php $__currentLoopData = ['success' => 'success', 'error' => 'danger']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if(session($key)): ?>
        <div class="alert alert-<?php echo e($type); ?> alert-dismissible fade show" role="alert">
            <?php echo e(session($key)); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\Users\DELL\Desktop\projets\example-app\resources\views/partials/flash.blade.php ENDPATH**/ ?>