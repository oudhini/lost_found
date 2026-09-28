<?php if($paginator->hasPages()): ?>
    <ul class="pagination justify-content-center mb-5">
        
        <?php if($paginator->onFirstPage()): ?>
            <li class="disabled page-item"><span class="page-link">&laquo;</span></li>
        <?php else: ?>
            <li class="page-item"><a class="page-link" href="<?php echo e($paginator->url(1)); ?>">&laquo;</a></li>
        <?php endif; ?>

        
        <?php if($paginator->onFirstPage()): ?>
            <li class="disabled page-item"><span class="page-link">&lsaquo;</span></li>
        <?php else: ?>
            <li class="page-item"><a class="page-link" href="<?php echo e($paginator->previousPageUrl()); ?>">&lsaquo;</a></li>
        <?php endif; ?>

        
        <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
            <?php if(is_string($element)): ?>
                <li class="disabled page-item"><span class="page-link"><?php echo e($element); ?></span></li>
            <?php endif; ?>

            
            <?php if(is_array($element)): ?>
                <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($page == $paginator->currentPage()): ?>
                        <li class="active page-item"><span class="page-link"><?php echo e($page); ?></span></li>
                    <?php else: ?>
                        <li class="page-item"><a class="page-link" href="<?php echo e($url); ?>"><?php echo e($page); ?></a></li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php if($paginator->hasMorePages()): ?>
            <li class="page-item"><a class="page-link" href="<?php echo e($paginator->nextPageUrl()); ?>">&rsaquo;</a></li>
        <?php else: ?>
            <li class="disabled page-item"><span class="page-link">&rsaquo;</span></li>
        <?php endif; ?>

        
        <?php if($paginator->hasMorePages()): ?>
            <li class="page-item"><a class="page-link" href="<?php echo e($paginator->url($paginator->lastPage())); ?>">&raquo;</a></li>
        <?php else: ?>
            <li class="disabled page-item"><span class="page-link">&raquo;</span></li>
        <?php endif; ?>
    </ul>
<?php endif; ?>
<?php /**PATH C:\Users\DELL\Desktop\projets\example-app\resources\views/vendor/pagination/tailwind.blade.php ENDPATH**/ ?>