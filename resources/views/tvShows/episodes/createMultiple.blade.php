@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Ajouter Plusieurs Épisodes à la Saison {{ $season->season_number }} de {{ $tvShow->title }}</h1>

    @include('partials.messages')

    <form action="{{ route('tvShows.episodes.storeMultiple', ['tvShow' => $tvShow->id, 'season' => $season->id]) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="episode_count">Nombre d'Épisodes</label>
            <input type="number" class="form-control" id="episode_count" name="episode_count" min="1" required>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter les Épisodes</button>
        <a href="{{ route('tvShows.show', $tvShow->id) }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection

