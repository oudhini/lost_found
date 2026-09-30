@extends($layout)
@section('content')
<div class="container pb-5" style="max-width:720px">
    @if (session('status') === 'profile-information-updated') <div class="alert alert-success">Profil mis à jour.</div> @endif
    @if (session('status') === 'password-updated') <div class="alert alert-success">Mot de passe modifié.</div> @endif

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Informations personnelles</div>
        <div class="card-body">
            <form method="POST" action="{{ route('user-profile-information.update') }}">
                @csrf @method('PUT')
                @foreach (['name' => ['Nom complet', 'text'], 'email' => ['Adresse e-mail', 'email'], 'phone' => ['Téléphone', 'tel']] as $field => [$label, $type])
                    <div class="mb-3">
                        <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                        <input id="{{ $field }}" type="{{ $type }}" name="{{ $field }}" required class="form-control @error($field, 'updateProfileInformation') is-invalid @enderror" value="{{ old($field, $user->{$field}) }}">
                        @error($field, 'updateProfileInformation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                @endforeach
                <button class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">Changer le mot de passe</div>
        <div class="card-body">
            <form method="POST" action="{{ route('user-password.update') }}">
                @csrf @method('PUT')
                @foreach (['current_password' => 'Mot de passe actuel', 'password' => 'Nouveau mot de passe', 'password_confirmation' => 'Confirmation'] as $field => $label)
                    <div class="mb-3">
                        <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                        <input id="{{ $field }}" type="password" name="{{ $field }}" required autocomplete="{{ $field === 'current_password' ? 'current-password' : 'new-password' }}" class="form-control @error($field, 'updatePassword') is-invalid @enderror">
                        @error($field, 'updatePassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                @endforeach
                <button class="btn btn-primary">Modifier le mot de passe</button>
            </form>
        </div>
    </div>
</div>
@endsection
