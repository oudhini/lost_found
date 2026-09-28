@extends('layouts.app2')

@section('content')
<div class="container-table">
    <div class="d-flex justify-content-center mt-4 align-items-center mb-2">
        <h2 class="text-primary">Liste des Points de Dépôt</h2>
    </div>
    
    @if (session('success'))
<div class="alert-success-lostdocstore">
     {{ session('success') }}
    <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
</div>
@endif 

    <div class="table-responsive">
        <table class="table mx-0 table-hover table-bordered text-left">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Adresse</th>
                    <th>Contact</th>
                    <th>Statut</th>
                    <th>Heures d'Ouverture</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($depots as $depot)
                    <tr>
                        <td>{{ $depot->name }}</td>
                        <td>{{ $depot->address }}</td>
                        <td>{{ $depot->contact }}</td>
                        <td>
                            <span class="badge {{ $depot->statut == 'actif' ? 'bg-success' : 'bg-danger' }}">
                                {{ $depot->statut }}
                            </span>
                        </td>
                        <td>{{ $depot->opening_hours }}</td>
                        <td>{{ $depot->latitude }}</td>
                        <td>{{ $depot->longitude }}</td>
                        <td>
                            <a href="{{ route('depot.show', $depot->id) }}" class="btn btn-depot btn-info btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection