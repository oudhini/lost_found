@extends('layouts.app1')

@section('title', 'Inscription')
@section('content')
@if (session('success'))
    <div class="alert-success-centered">
        <strong>Succès !</strong> {{ session('success') }}
        <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
    </div>
@endif
@if (session('status'))
    <div class="container mt-3"><div class="alert alert-success" role="status">{{ session('status') }}</div></div>
@endif
<div class="container mt-5 pb-3">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card ">
                <div class="card-header text-dark text-center text-muted border-0 border-top-0 mb-3 "> <h1>Please Sign in</h1> </div>
                <div class="card-body">
                    <form method="POST" action="{{route('login')}}">
                        @csrf
                        <div class="row mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{old('email')}}"id="email" aria-describedby="emailHelp" required autocomplete="email" autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password" id="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input type="checkbox" role="switch" id="flexSwitchCheckDefault" class="form-check-input">
                                    <label for="flexSwitchCheckDefault" name="remember" class="form-check-label" {{old('remember') ? 'checked' :''}}>Remember me</label>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="#">Mot de passe oublié ?</a>
                            </div>
                        </div> --}}
                        <div class="text-end mb-3">
                            <a href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary text-center ml-3 mr-3">Se connecter</button>
                        </div>
                        
                    </form>
                    <div class="mt-3 text-center">
                        <p>Pas encore de compte ? <a href="{{route('register')}}">Inscrivez-vous</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 
@endsection