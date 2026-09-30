<?php $__env->startSection('content'); ?>
<div class="container-fluid pb-5">
    <?php echo $__env->make('partials.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php if($notifications->isEmpty()): ?>
        <div class="alert alert-info text-center">Aucune notification pour le moment.</div>
    <?php else: ?>
        <form method="POST" action="<?php echo e(route('notifications.read-all')); ?>" class="mb-3">
            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
            <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-check2-all"></i> Tout marquer comme lu</button>
        </form>
        <ul class="list-group mb-3">
            <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="list-group-item d-flex justify-content-between align-items-center <?php echo e($notification->read_at ? '' : 'list-group-item-warning'); ?>">
                    <div>
                        <div><?php echo e($notification->data['message'] ?? 'Notification'); ?></div>
                        <small class="text-muted"><?php echo e($notification->created_at->diffForHumans()); ?><?php if(! empty($notification->data['reporter'])): ?> — par <?php echo e($notification->data['reporter']); ?><?php endif; ?></small>
                    </div>
                    <form method="POST" action="<?php echo e(route('notifications.read', $notification->id)); ?>">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <button class="btn btn-sm btn-primary">Ouvrir</button>
                    </form>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <?php echo e($notifications->links()); ?>

    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\projets\example-app\resources\views/notifications/index.blade.php ENDPATH**/ ?>