@extends('layouts.appsuperviseur')

@section('content')
<div class="container mt-5 mb-4">
    <h2>Détails du Gérant</h2>
    <div class="card">
        <div class="card-header">
            <h3>{{ $manager->name }}</h3>
        </div>
        <div class="card-body">
            <p><strong>Email:</strong> {{ $manager->email }}</p>
            <p><strong>Téléphone:</strong> {{ $manager->phone }}</p>
            <p><strong>Rôle:</strong> {{ $manager->role }}</p>
            <a href="{{ route('gerant.index') }}" class="btn btn-secondary">Retour à la liste</a>
        </div>
    </div>
</div>
@endsection