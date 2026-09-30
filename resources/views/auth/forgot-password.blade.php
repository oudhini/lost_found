@extends('layouts.app1')

@section('title', 'Mot de passe oublié')
@section('content')
<div class="container mt-5 pb-3">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center border-0 mb-3"><h1 class="h3 text-muted">Mot de passe oublié</h1></div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="status">{{ session('status') }}</div>
                    @endif
                    <p class="text-muted">Saisissez l'adresse e-mail de votre compte. Si elle existe, vous recevrez un lien valable 60 minutes pour choisir un nouveau mot de passe.</p>
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse e-mail</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                                   class="form-control @error('email') is-invalid @enderror">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Envoyer le lien</button>
                        </div>
                    </form>
                    <div class="mt-3 text-center"><a href="{{ route('login') }}">Retour à la connexion</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
