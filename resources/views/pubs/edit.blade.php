@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Modifier la publicité : {{ $pub->name }}</h1>

    <!-- Inclusion des messages d'erreurs et de succès -->
    @include('partials.messages')

    <form action="{{ route('pubs.update', $pub->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nom de la publicité</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $pub->name) }}" required>
        </div>
        <div class="mb-3">
            <label for="brand_store" class="form-label">Marque/Magasin</label>
            <input type="text" class="form-control" id="brand_store" name="brand_store" value="{{ old('brand_store', $pub->brandStore ? $pub->brandStore->name : '') }}">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $pub->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label for="duration" class="form-label">Durée</label>
            <input type="text" class="form-control" id="duration" name="duration" value="{{ old('duration', $pub->duration) }}" pattern="\d{2}:\d{2}:\d{2}">
            <small class="form-text text-muted">Format: HH:MM:SS</small>
        </div>
        <div class="mb-3">
            <label for="year" class="form-label">Année</label>
            <input type="number" class="form-control" id="year" name="year" value="{{ old('year', $pub->year) }}" required>
        </div>
        <div class="mb-3">
            <label for="file_path" class="form-label">Fichier</label>
            <input type="file" class="form-control" id="file_path" name="file_path" accept="video/*">
        </div>
        <div class="mb-3">
            <label for="thumbnail_image" class="form-label">Image Miniature</label>
            <input type="file" class="form-control" id="thumbnail_image" name="thumbnail_image" accept="image/*">
        </div>
        <div class="mb-3">
            <label for="ad_type" class="form-label">Type de Publicité</label>
            <select class="form-control" id="ad_type" name="ad_type">
                <option value="Commercial" {{ old('ad_type', $pub->ad_type) == 'Commercial' ? 'selected' : '' }}>Commercial</option>
                <option value="Music Video Ad" {{ old('ad_type', $pub->ad_type) == 'Music Video Ad' ? 'selected' : '' }}>Music Video Ad</option>
                <option value="Event Sponsorship" {{ old('ad_type', $pub->ad_type) == 'Event Sponsorship' ? 'selected' : '' }}>Event Sponsorship</option>
                <option value="MTV Bumper" {{ old('ad_type', $pub->ad_type) == 'MTV Bumper' ? 'selected' : '' }}>MTV Bumper</option>
                <option value="MTV Intro" {{ old('ad_type', $pub->ad_type) == 'MTV Intro' ? 'selected' : '' }}>MTV Intro</option>
                <option value="MTV Outro" {{ old('ad_type', $pub->ad_type) == 'MTV Outro' ? 'selected' : '' }}>MTV Outro</option>
                <option value="MTV Promo" {{ old('ad_type', $pub->ad_type) == 'MTV Promo' ? 'selected' : '' }}>MTV Promo</option>
                <option value="Show Trailer" {{ old('ad_type', $pub->ad_type) == 'Show Trailer' ? 'selected' : '' }}>Show Trailer</option>
                <option value="Celebrity Endorsement" {{ old('ad_type', $pub->ad_type) == 'Celebrity Endorsement' ? 'selected' : '' }}>Celebrity Endorsement</option>
                <option value="Public Service Announcement" {{ old('ad_type', $pub->ad_type) == 'Public Service Announcement' ? 'selected' : '' }}>Public Service Announcement</option>
                <option value="Festival" {{ old('ad_type', $pub->ad_type) == 'Festival' ? 'selected' : '' }}>Festival</option>
                <option value="Album Release" {{ old('ad_type', $pub->ad_type) == 'Album Release' ? 'selected' : '' }}>Album Release</option>
                <option value="Movie TV Spot" {{ old('ad_type', $pub->ad_type) == 'Movie TV Spot' ? 'selected' : '' }}>Movie TV Spot</option>
                <option value="Concert Promo" {{ old('ad_type', $pub->ad_type) == 'Concert Promo' ? 'selected' : '' }}>Concert Promo</option>
                <option value="Merchandise Ad" {{ old('ad_type', $pub->ad_type) == 'Merchandise Ad' ? 'selected' : '' }}>Merchandise Ad</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="target_demographic" class="form-label">Démographique Ciblée</label>
            <select class="form-control" id="target_demographic" name="target_demographic">
                <option value="">Neutre</option>
                <option value="Children" {{ old('target_demographic', $pub->target_demographic) == 'Children' ? 'selected' : '' }}>Enfants</option>
                <option value="Teens" {{ old('target_demographic', $pub->target_demographic) == 'Teens' ? 'selected' : '' }}>Adolescents</option>
                <option value="Young Adults" {{ old('target_demographic', $pub->target_demographic) == 'Young Adults' ? 'selected' : '' }}>Jeunes adultes</option>
                <option value="Adults" {{ old('target_demographic', $pub->target_demographic) == 'Adults' ? 'selected' : '' }}>Adultes</option>
                <option value="Seniors" {{ old('target_demographic', $pub->target_demographic) == 'Seniors' ? 'selected' : '' }}>Personnes âgées</option>
                <option value="Families" {{ old('target_demographic', $pub->target_demographic) == 'Families' ? 'selected' : '' }}>Familles</option>
                <option value="Men" {{ old('target_demographic', $pub->target_demographic) == 'Men' ? 'selected' : '' }}>Hommes</option>
                <option value="Women" {{ old('target_demographic', $pub->target_demographic) == 'Women' ? 'selected' : '' }}>Femmes</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="frequency" class="form-label">Fréquence</label>
            <input type="number" class="form-control" id="frequency" name="frequency" value="{{ old('frequency', $pub->frequency) }}">
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    </form>
</div>
@endsection
