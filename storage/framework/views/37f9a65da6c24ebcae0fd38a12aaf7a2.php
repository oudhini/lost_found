<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(config('app.name')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
        }

        .sidebar {
            height: 100vh;
            width: 240px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #343a40;
            color: #fff;
            padding-top: 20px;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link {
            color: #fff;
            padding: 15px;
        }

        .sidebar .nav-link:hover {
            background-color: #495057;
            border-radius: 5px;
        }

        .sidebar .nav-link.active {
            background-color: #007bff;
        }

        .sidebar .nav-item {
            margin-bottom: 10px;
        }

        .navbar-custom {
            background-color: #343a40;
            color: white;
        }

        .content-wrapper {
            margin-left: 250px;
            padding: 30px;
        }

        .content-wrapper h2 {
            color: #007bff;
        }

        .content-wrapper p {
            font-size: 18px;
        }

        .navbar-custom .navbar-brand {
            color: #fff;
            font-size: 24px;
        }

        .navbar-custom .navbar-toggler {
            border-color: white;
        }

        .navbar-custom .navbar-toggler-icon {
            background-color: white;
        }

        @media (max-width: 991px) {
            .sidebar {
                position: fixed;
                width: 0;
                height: 100vh;
                transition: all 0.3s ease;
            }

            .sidebar.open {
                width: 250px;
            }

            .content-wrapper {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="container">
            <div class="text-center mb-4">
                <h4 class="text-white"><?php echo e(config('app.name')); ?></h4>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?php echo e(Request::is('/dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">
                        <i class="bi bi-house-door"></i> Tableau de bord
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(Request::is('/signaler_document_perdu') ? 'active' : ''); ?>" href="<?php echo e(route('signaler')); ?>">
                        <i class="bi bi-file-earmark"></i> Signaler un document
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(Request::is('user/documents') ? 'active' : ''); ?>" href="#"
                       data-bs-toggle="collapse" data-bs-target="#documentsSubmenu" aria-expanded="false" aria-controls="documentsSubmenu">
                        <i class="bi bi-folder"></i> Documents
                    </a>
                    <div class="collapse" id="documentsSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(Request::is('user/documents/signalés') ? 'active' : ''); ?>" href="<?php echo e(route('docs_signales')); ?>">
                                    <i class="bi bi-exclamation-circle"></i> Documents signalés
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-7 <?php echo e(Request::is('user/documents/perdus') ? 'active' : ''); ?>" href="<?php echo e(route('docs_perdus')); ?>">
                                    <i class="bi bi-file-earmark-x"></i> Documents perdus
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(Request::is('user/documents/trouves') ? 'active' : ''); ?>" href="<?php echo e(route('docs_trouves')); ?>">
                                    <i class="bi bi-file"></i> Documents trouvés
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-7 <?php echo e(Request::is('/depot/index') ? 'active' : ''); ?>" href="<?php echo e(route('depot.index')); ?>">
                        <i class="bi bi-list"></i> Listes des Points
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('notifications.*') ? 'active' : ''); ?>" href="<?php echo e(route('notifications.index')); ?>">
                        <i class="bi bi-bell"></i> Notifications
                        <?php ($unread = auth()->user()->unreadNotifications()->count()); ?>
                        <?php if($unread > 0): ?><span class="badge bg-danger ms-1"><?php echo e($unread); ?></span><?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('user.profile') ? 'active' : ''); ?>" href="<?php echo e(route('user.profile')); ?>">
                        <i class="bi bi-person"></i> Mon Profil
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" onclick="toggleSidebar()">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <a class="navbar-brand" href="<?php echo e(route('dashboard')); ?>">
                <i class="bi bi-app-indicator"></i>  <?php echo e(config('app.name')); ?>

            </a>
            <div class="ml-auto">
                <form action="<?php echo e(route('logout')); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-outline-light">Déconnexion</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Content Wrapper -->
    <div class="content-wrapper mt-3">
        <div class="row mt-4">
            <div class="col-md-12 mt-2">
                <div class="breadcrumb-container d-flex justify-content-between align-items-center py-1">
                    <h3><?php echo e($pageTitle); ?></h3>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                        <?php $__currentLoopData = $breadcrumb; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="breadcrumb-item <?php if($loop->last): ?> active <?php endif; ?>"><?php echo e($item); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ol>
                </div>
            </div>
        </div>
        <?php echo $__env->yieldContent('content'); ?>
    </div>
    <footer class="bg-dark text-white fixed-bottom text-center pt-2">
        <p>© <?php echo e(date('Y')); ?> Lost & Found - Tous droits réservés</p>
    </footer>
    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }
    </script>
</body>

</html>
<?php /**PATH C:\Users\DELL\Desktop\projets\example-app\resources\views/layouts/app2.blade.php ENDPATH**/ ?>