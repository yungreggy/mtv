@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Ajouter une nouvelle publicité</h1>

    <!-- Inclusion des messages d'erreurs et de succès -->
    @include('partials.messages')

    <form action="{{ route('pubs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nom de la publicité</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="brand_store" class="form-label">Marque/Magasin</label>
            <input type="text" class="form-control" id="brand_store" name="brand_store">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="duration" class="form-label">Durée (h:m:s)</label>
            <input type="text" class="form-control" id="duration" name="duration" value="00:00:30" pattern="\d{2}:\d{2}:\d{2}">
            <small class="form-text text-muted">Format: HH:MM:SS</small>
        </div>

        <div class="mb-3">
            <label for="year" class="form-label">Année</label>
            <input type="number" class="form-control" id="year" name="year" required>
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
                <option value="Commercial">Commercial</option>
                <option value="Music Video Ad">Music Video Ad</option>
                <option value="Event Sponsorship">Event Sponsorship</option>
                <option value="MTV Bumper">MTV Bumper</option>
                <option value="MTV Intro">MTV Intro</option>
                <option value="MTV Outro">MTV Outro</option>
                <option value="MTV Promo">MTV Promo</option>
                <option value="Show Trailer">Show Trailer</option>
                <option value="Celebrity Endorsement">Celebrity Endorsement</option>
                <option value="Public Service Announcement">Public Service Announcement</option>
                <option value="Festival">Festival</option>
                <option value="Album Release">Album Release</option>
                <option value="Movie TV Spot">Movie TV Spot</option>
                <option value="Concert Promo">Concert Promo</option>
                <option value="Merchandise Ad">Merchandise Ad</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="target_demographic" class="form-label">Démographique Ciblée</label>
            <select class="form-control" id="target_demographic" name="target_demographic">
                <option value="">Neutre</option>
                <option value="Children">Enfants</option>
                <option value="Teens">Adolescents</option>
                <option value="Young Adults">Jeunes adultes</option>
                <option value="Adults">Adultes</option>
                <option value="Seniors">Personnes âgées</option>
                <option value="Families">Familles</option>
                <option value="Men">Hommes</option>
                <option value="Women">Femmes</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="frequency" class="form-label">Fréquence</label>
            <input type="number" class="form-control" id="frequency" name="frequency">
        </div>
        <button type="submit" class="btn btn-primary">Ajouter la publicité</button>
    </form>
</div>
@endsection
