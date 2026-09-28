@extends('layouts.espaceuser')

@section('content')
    <div class="body-index text-center m-0 p-0">
         
        <div class="welcome-container">
            <h1>Bienvenue sur Lost&Found</h1>
            <img src="{{ asset('assets/images/loaderlogo.webp') }}" class="img align-content-center justify-content-center mb-3" alt="Chargement..." style="width: 200px; height: 200px;z-index:100;">
            <p>Une plateforme dédiée pour vous aider à retrouver vos documents égarés en toute simplicité.</p>
            <a href="{{route("welcome")}}" class="btn btn-primary btn-start">Start</a>
        </div>
        
    </div>
    
@endsection