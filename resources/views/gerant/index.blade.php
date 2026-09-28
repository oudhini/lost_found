@extends('layouts.appsuperviseur')

@section('content')
<div class="container mt-5 mb-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h2 class="text-primary">Liste des Gérants</h2>
        <a href="{{ route('gerant.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Ajouter un Gérant</a>
    </div>
    @if($managers->isEmpty())
        <div class="alert alert-warning text-center mt-3" role="alert">
            Aucun gerant créer pour le moment.
        </div>
    @else
    @if (session('success'))
        <div class="alert-success-lostdocstore">
        {{ session('success') }}
        <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
        </div>
    @endif 
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($managers as $manager)
                <tr>
                    <td>{{ $manager->id }}</td>
                    <td>{{ $manager->name }}</td>
                    <td>{{ $manager->email }}</td>
                    <td>{{ $manager->phone }}</td>
                    <td>
                        <a href="{{ route('gerant.show', $manager->id) }}" class="btn btn-info"><i class="bi bi-eye mr-1"></i>Voir</a>
                        <a href="{{ route('gerant.edit', $manager->id) }}" class="btn btn-warning">                     <i class="bi bi-pencil"></i>Modifier
                        </a>
                        <form action="{{ route('gerant.destroy', $manager->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce gérant ?')"><i class="bi bi-trash"></i>Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif 
</div>
@endsection