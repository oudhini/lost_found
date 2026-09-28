<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Lost & Found</title>
    <!-- Bootstrap CSS -->
    @vite(['resources/css/app.css'])
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://via.placeholder.com/1920x600') no-repeat center center/cover;
            color: #fff;
            padding: 100px 0;
            text-align: center;
        }
        .hero-section h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        .hero-section p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        .card:hover {
            transform: scale(1.05);
            transition: transform 0.3s;
        }
        .cont {
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Assure que le conteneur prend toute la hauteur de la viewport */
            }

            .content {
            flex: 1; /* Permet au contenu de s'étendre pour occuper tout l'espace disponible */
            }

            footer {
            background-color: #f0f0f0;
            padding: 20px;
            }
    </style>
</head>
<body>
    <div class="cont">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="#">
                    Lost & Found</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="{{route('welcome')}}">Accueil</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{route('register')}}">Inscription</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{route('login')}}">Connexion</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Témoignages</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="content">
            @yield('content')
        </main>

        <footer class="bg-dark text-white text-center py-3">
            <p>© {{ date('Y') }} Lost & Found - Tous droits réservés</p>
        </footer>
    </div>
    @vite(['resources/js/app.js'])
</body>
</html>
