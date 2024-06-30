@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Ajouter une Nouvelle Série TV</h1>

    @include('partials.messages') <!-- Inclusion des messages -->

    <form action="{{ route('tvShows.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="title">Titre</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>

        <div class="form-group">
            <label for="years_active">Années d'activité</label>
            <input type="text" class="form-control" id="years_active" name="years_active">
        </div>

        <div class="form-group">
    <label for="genre_ids">Genres</label>
    <select name="genre_ids[]" id="genre_ids" class="form-control" multiple style="height: 200px;">
        @foreach($genres as $genre)
            <option value="{{ $genre->id }}">{{ $genre->name }}</option>
        @endforeach
    </select>
</div>


        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="5"></textarea>
        </div>

        <div class="form-group">
            <label for="poster">Affiche (Poster)</label>
            <input type="file" class="form-control" id="poster" name="poster">
        </div>

        <div class="form-group">
            <label for="creator">Créateur</label>
            <input type="text" class="form-control" id="creator" name="creator">
        </div>

        <div class="form-group">
            <label for="season_count">Nombre de Saisons</label>
            <input type="number" class="form-control" id="season_count" name="season_count" min="1" required>
        </div>

        <div class="form-group">
            <label for="target_audience">Public cible</label>
            <input type="text" class="form-control" id="target_audience" name="target_audience">
        </div>

        <div class="form-group">
            <label for="official_website">Site officiel</label>
            <input type="url" class="form-control" id="official_website" name="official_website">
        </div>

        <div class="form-group">
            <label for="status">Statut</label>
            <input type="text" class="form-control" id="status" name="status">
        </div>

        <div class="form-group">
            <label for="country_of_origin">Pays d'origine</label>
            <input type="text" class="form-control" id="country_of_origin" name="country_of_origin">
        </div>

        <button type="submit" class="btn btn-primary">Ajouter</button>
        <a href="{{ route('tvShows.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
