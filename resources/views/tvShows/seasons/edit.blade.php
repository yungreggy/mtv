@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Modifier la Saison</h1>

    @include('partials.messages') <!-- Inclusion des messages -->

    <form action="{{ route('tvShows.seasons.update', ['tvShow' => $tvShow->id, 'season' => $season->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="year">Année</label>
            <input type="number" class="form-control" id="year" name="year" value="{{ old('year', $season->year) }}" required>
        </div>
        <div class="form-group">
            <label for="start_date">Date de début</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', $season->start_date) }}" required>
        </div>
        <div class="form-group">
            <label for="end_date">Date de fin</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date', $season->end_date) }}" required>
        </div>
        <div class="form-group">
            <label for="episode_count">Nombre d'épisodes</label>
            <input type="number" class="form-control" id="episode_count" name="episode_count" value="{{ old('episode_count', $season->episode_count) }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        <a href="{{ route('tvShows.show', $tvShow->id) }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
