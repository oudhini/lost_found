<?php $__env->startSection('content'); ?>
<div class="container pb-5" style="max-width:720px">
    <?php if(session('status') === 'profile-information-updated'): ?> <div class="alert alert-success">Profil mis à jour.</div> <?php endif; ?>
    <?php if(session('status') === 'password-updated'): ?> <div class="alert alert-success">Mot de passe modifié.</div> <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Informations personnelles</div>
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('user-profile-information.update')); ?>">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <?php $__currentLoopData = ['name' => ['Nom complet', 'text'], 'email' => ['Adresse e-mail', 'email'], 'phone' => ['Téléphone', 'tel']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => [$label, $type]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="mb-3">
                        <label for="<?php echo e($field); ?>" class="form-label"><?php echo e($label); ?></label>
                        <input id="<?php echo e($field); ?>" type="<?php echo e($type); ?>" name="<?php echo e($field); ?>" required class="form-control <?php $__errorArgs = [$field, 'updateProfileInformation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old($field, $user->{$field})); ?>">
                        <?php $__errorArgs = [$field, 'updateProfileInformation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <button class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">Changer le mot de passe</div>
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('user-password.update')); ?>">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <?php $__currentLoopData = ['current_password' => 'Mot de passe actuel', 'password' => 'Nouveau mot de passe', 'password_confirmation' => 'Confirmation']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="mb-3">
                        <label for="<?php echo e($field); ?>" class="form-label"><?php echo e($label); ?></label>
                        <input id="<?php echo e($field); ?>" type="password" name="<?php echo e($field); ?>" required autocomplete="<?php echo e($field === 'current_password' ? 'current-password' : 'new-password'); ?>" class="form-control <?php $__errorArgs = [$field, 'updatePassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = [$field, 'updatePassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <button class="btn btn-primary">Modifier le mot de passe</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\projets\example-app\resources\views/profile/show.blade.php ENDPATH**/ ?>