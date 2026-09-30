{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <link rel="stylesheet" href="{{asset('assets/lib/fontawesome-free/css/all.min.css')}}"> --}}
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
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
                 <h4 class="text-white">{{ config('app.name') }}</h4>
            </div>
            <ul class="nav flex-column">
                @php($managerDepot = auth()->user()->depot)
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-house-door"></i> Tableau de bord
                    </a>
                </li>
                @if ($managerDepot)
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('manager.receive', 'manager.found.*') ? 'active' : '' }}" href="{{ route('manager.receive') }}">
                        <i class="bi bi-file-earmark-plus"></i> Réceptionner
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('manager.documents.*') ? 'active' : '' }}" href="{{ route('manager.documents.index') }}">
                        <i class="bi bi-folder"></i> Documents / Rendre
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('depot.show') ? 'active' : '' }}" href="{{ route('depot.show', $managerDepot->id) }}">
                        <i class="bi bi-house"></i> Mon dépôt
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('manager.history') ? 'active' : '' }}" href="{{ route('manager.history') }}">
                        <i class="bi bi-clock"></i> Historique
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('depot.index') ? 'active' : '' }}" href="{{ route('depot.index') }}">
                        <i class="bi bi-geo-alt"></i> Tous les dépôts
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}" href="{{ route('notifications.index') }}">
                        <i class="bi bi-bell"></i> Notifications
                        @php($unread = auth()->user()->unreadNotifications()->count())
                        @if ($unread > 0)<span class="badge bg-danger ms-1">{{ $unread }}</span>@endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }}" href="{{ route('user.profile') }}">
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
            
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-app-indicator"></i>  {{ config('app.name') }}
            </a>
            <div class="ml-auto">
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
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
                    <h3>{{ $pageTitle }}</h3>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                        @foreach($breadcrumb as $item)
                            <li class="breadcrumb-item @if($loop->last) active @endif">{{ $item }}</li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
        @yield('content')
    </div>
    <footer class="bg-dark text-white fixed-bottom text-center pt-2">
        <p>© {{ date('Y') }} Lost & Found - Tous droits réservés</p>
    </footer>
    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }
    </script>
</body>

</html>
