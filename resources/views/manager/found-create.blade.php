@extends('layouts.appgerant')
@section('content')
<div class="container pb-5" style="max-width:760px">
    <p class="text-muted">Pour un document déposé qui n'a pas (encore) été signalé perdu. Il sera enregistré « en attente de retrait » dans votre dépôt.</p>
    <form method="POST" action="{{ route('manager.found.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="type_document" class="form-label">Type de document <span class="text-danger">*</span></label>
            <select id="type_document" name="type_document" class="form-select @error('type_document') is-invalid @enderror" required>
                <option value="">-- Sélectionnez un type --</option>
                @foreach ($types as $value => $label)
                    <option value="{{ $value }}" @selected(old('type_document') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('type_document') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="nom_present_sur_le_document" class="form-label">Nom présent sur le document <span class="text-danger">*</span></label>
            <input id="nom_present_sur_le_document" name="nom_present_sur_le_document" value="{{ old('nom_present_sur_le_document') }}" required maxlength="255" class="form-control @error('nom_present_sur_le_document') is-invalid @enderror">
            @error('nom_present_sur_le_document') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="numero_du_document" class="form-label">Numéro du document</label>
            <input id="numero_du_document" name="numero_du_document" value="{{ old('numero_du_document') }}" maxlength="100" class="form-control @error('numero_du_document') is-invalid @enderror">
            @error('numero_du_document') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="lieu_de_perte" class="form-label">Lieu où il a été trouvé <span class="text-danger">*</span></label>
                <input id="lieu_de_perte" name="lieu_de_perte" value="{{ old('lieu_de_perte') }}" required maxlength="255" class="form-control @error('lieu_de_perte') is-invalid @enderror">
                @error('lieu_de_perte') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="date_de_perte" class="form-label">Date <span class="text-danger">*</span></label>
                <input type="date" id="date_de_perte" name="date_de_perte" value="{{ old('date_de_perte', now()->toDateString()) }}" max="{{ now()->toDateString() }}" required class="form-control @error('date_de_perte') is-invalid @enderror">
                @error('date_de_perte') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="mb-3">
            <label for="photos" class="form-label">Photos (5 max, 4 Mo chacune)</label>
            <input type="file" id="photos" name="photos[]" multiple accept="image/jpeg,image/png,image/webp" class="form-control @error('photos') is-invalid @enderror @error('photos.*') is-invalid @enderror">
            @error('photos') <div class="invalid-feedback">{{ $message }}</div> @enderror
            @error('photos.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="additional_info" class="form-label">Informations complémentaires</label>
            <textarea id="additional_info" name="additional_info" rows="3" maxlength="2000" class="form-control">{{ old('additional_info') }}</textarea>
        </div>
        <button class="btn btn-primary w-100">Enregistrer le document</button>
    </form>
</div>
@endsection
