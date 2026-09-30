<?php $__env->startSection('content'); ?>
<div class="container-fluid pb-5">
    <?php echo $__env->make('partials.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <form method="GET" action="<?php echo e(route('admin.users.index')); ?>" class="row g-2 mb-3">
        <div class="col-md-4"><input type="search" name="q" value="<?php echo e($filters['q'] ?? ''); ?>" class="form-control" placeholder="Nom, e-mail ou téléphone" aria-label="Recherche"></div>
        <div class="col-md-2">
            <select name="role" class="form-select" aria-label="Rôle">
                <option value="">Tous les rôles</option>
                <option value="utilisateur" <?php if(($filters['role'] ?? '') === 'utilisateur'): echo 'selected'; endif; ?>>Utilisateur</option>
                <option value="gerant" <?php if(($filters['role'] ?? '') === 'gerant'): echo 'selected'; endif; ?>>Gérant</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="active" class="form-select" aria-label="État">
                <option value="">Tous</option>
                <option value="1" <?php if(($filters['active'] ?? '') === '1'): echo 'selected'; endif; ?>>Actifs</option>
                <option value="0" <?php if(($filters['active'] ?? '') === '0'): echo 'selected'; endif; ?>>Suspendus</option>
            </select>
        </div>
        <div class="col-md-2"><button class="btn btn-primary"><i class="bi bi-search"></i> Filtrer</button></div>
    </form>
    <?php if($users->isEmpty()): ?>
        <div class="alert alert-warning text-center">Aucun compte trouvé.</div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead><tr><th>Nom</th><th>E-mail</th><th>Téléphone</th><th>Rôle</th><th>Signalements</th><th>État</th><th></th></tr></thead>
            <tbody>
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($user->name); ?></td><td><?php echo e($user->email); ?></td><td><?php echo e($user->phone); ?></td>
                    <td><?php echo e($user->role === 'gerant' ? 'Gérant' : 'Utilisateur'); ?></td>
                    <td><?php echo e($user->documents_count); ?></td>
                    <td><span class="badge <?php echo e($user->is_active ? 'bg-success' : 'bg-danger'); ?>"><?php echo e($user->is_active ? 'Actif' : 'Suspendu'); ?></span></td>
                    <td>
                        <form method="POST" action="<?php echo e(route('admin.users.toggle-active', $user)); ?>">
                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                            <button class="btn btn-sm <?php echo e($user->is_active ? 'btn-outline-danger' : 'btn-outline-success'); ?>"
                                onclick="return confirm('<?php echo e($user->is_active ? 'Suspendre' : 'Réactiver'); ?> ce compte ?')">
                                <?php echo e($user->is_active ? 'Suspendre' : 'Réactiver'); ?>

                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php echo e($users->links()); ?>

    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.appsuperviseur', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\projets\example-app\resources\views/admin/users/index.blade.php ENDPATH**/ ?>