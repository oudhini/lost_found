@extends('layouts.app1')

@section('title', 'Inscription')
@section('content')
@if (session('error'))
    <div class="alert-success-centered text-white bg-danger">
        <strong></strong> {{ session('error') }}
        <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
    </div>
@endif
<div class="container mt-5 mb-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header text-center bg-primary text-white">
                    <h3>Créer un compte</h3>
                </div>
                @if ($errors->any())
                <div class="alert alert-danger mb-2">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
                <div class="card-body">
                    <form method="post" action="{{ route('register') }}">
                        @csrf
                        <!-- Nom complet -->
                        <div class="mb-3">
                            <label for="fullName" class="form-label">Nom complet</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="fullName" value="{{old('name')}}" placeholder="Entrez votre nom complet" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse e-mail</label>
                            <input type="email" aria-describedby="emailHelp"   name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{old('email')}}" placeholder="Entrez votre e-mail" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Numéro de téléphone -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">Numéro de téléphone</label>
                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" id="phone" placeholder="Entrez votre numéro de téléphone" value="{{old('phone')}}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Mot de passe -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" value="{{old('password')}}" placeholder="Entrez un mot de passe" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Confirmation du mot de passe -->
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirmez le mot de passe</label>
                            <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" id="confirmPassword" placeholder="Confirmez votre mot de passe" required>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Bouton -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">S'inscrire</button>
                        </div>
                    </form>
                    <div class="mt-3 text-center">
                        <p>Vous avez déjà un compte ? <a href="{{route('login')}}">Connectez-vous</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
