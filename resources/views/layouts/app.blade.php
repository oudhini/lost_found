<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Lost&Found')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
    body {
         overflow: hidden; /*Empêche le scroll pendant le chargement */
    }

    #loader {
        display: flex; /* Toujours afficher le loader avant le chargement complet */
    }
</style>
</head>
<body id="body" class="col-12 m-0 p-0">
    <div id="loader">
        @include('layouts.loading')
    </div>
    
    <div class="container m-0 p-0">
        @yield('content')
    </div>
    <footer class="text-center py-4 bg-dark text-white mt-1">
        <p>© {{ date('Y') }} Lost & Found - Tous droits réservés by @Oudhini</p>
    </footer>

    {{-- <div>
        @include('layouts.footer')
    </div> --}}
    {{-- <script>
        // Masquer le loader une fois le contenu chargé
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('loader').style.display = 'none';
        });
    </script> --}}

</body>
</html>
