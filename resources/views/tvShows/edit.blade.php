@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Modifier la Série TV</h1>

    @include('partials.messages') <!-- Inclusion des messages -->

    <form action="{{ route('tvShows.update', $tvShow->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="title">Titre</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $tvShow->title) }}" required>
        </div>
        <div class="form-group">
            <label for="years_active">Années d'activité</label>
            <input type="text" class="form-control" id="years_active" name="years_active" value="{{ old('years_active', $tvShow->years_active) }}">
        </div>
   
        <div class="form-group">
    <label for="genre_ids">Genres</label>
    <select name="genre_ids[]" id="genre_ids" class="form-control" multiple style="height: 200px;">
        @foreach($genres as $genre)
            <option value="{{ $genre->id }}" {{ in_array($genre->id, $tvShow->genres->pluck('id')->toArray()) ? 'selected' : '' }}>
                {{ $genre->name }}
            </option>
        @endforeach
    </select>
</div>


        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $tvShow->description) }}</textarea>
        </div>


        <div class="form-group">
            <label for="creator">Créateur</label>
            <input type="text" class="form-control" id="creator" name="creator" value="{{ old('creator', $tvShow->creator) }}" required>
        </div>
        <div class="form-group">
            <label for="season_count">Nombre de saisons</label>
            <input type="number" class="form-control" id="season_count" name="season_count" value="{{ old('season_count', $tvShow->season_count) }}">
        </div>
        <div class="form-group">
            <label for="target_audience">Public cible</label>
            <input type="text" class="form-control" id="target_audience" name="target_audience" value="{{ old('target_audience', $tvShow->target_audience) }}" >
        </div>
      
        <div class="form-group">
            <label for="status">Statut</label>
            <input type="text" class="form-control" id="status" name="status" value="{{ old('status', $tvShow->status) }}" required>
        </div>
        <div class="form-group">
            <label for="country_of_origin">Pays d'origine</label>
            <input type="text" class="form-control" id="country_of_origin" name="country_of_origin" value="{{ old('country_of_origin', $tvShow->country_of_origin) }}" >
        </div>

        <div class="form-group">
            <label for="poster">Poster</label>
            <input type="file" class="form-control-file" id="poster" name="poster">
            @if($tvShow->poster)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $tvShow->poster) }}" alt="{{ $tvShow->title }} Poster" class="img-fluid" style="max-width: 200px;">
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        <a href="{{ route('tvShows.show', $tvShow->id) }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
