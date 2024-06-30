@extends('layouts.app')

@section('content')
<style>
    .form-control {
        border-radius: 5px; /* Légèrement arrondi */
    }
    .btn {
        font-size: 14px;
        padding: 8px 12px;
        border-radius: 5px;
        background-color: transparent;
        color: #555;
        border: 1px solid #555;
        margin-right: 5px;
        display: inline-flex;
        align-items: center;
    }
    .btn:hover {
        background-color: #555;
        color: #fff;
    }
    .btn-primary {
        background-color: #555;
        color: #fff;
        border: none;
    }
    .btn-primary:hover {
        background-color: #444;
    }
    .btn-secondary {
        background-color: #888;
        color: #fff;
        border: none;
    }
    .btn-secondary:hover {
        background-color: #666;
    }
</style>

<div class="container">
    <h1 class="mb-4">Modifier l'Épisode</h1>

    @include('partials.messages') <!-- Inclusion des messages -->

    <div class="d-flex justify-content-between mb-3">
        @if($previousEpisode)
            <a href="{{ route('tvShows.episodes.edit', ['tvShow' => $tvShow->id, 'season' => $episode->season->id, 'episode' => $previousEpisode->id]) }}" class="btn">
                <i class="material-icons">arrow_back</i>
            </a>
        @else
            <span class="btn disabled"><i class="material-icons">arrow_back</i></span>
        @endif

        <a href="{{ route('tvShows.show', $tvShow->id) }}" class="btn">Retour à la série</a>

        @if($nextEpisode)
            <a href="{{ route('tvShows.episodes.edit', ['tvShow' => $tvShow->id, 'season' => $episode->season->id, 'episode' => $nextEpisode->id]) }}" class="btn">
                <i class="material-icons">arrow_forward</i>
            </a>
        @else
            <span class="btn disabled"><i class="material-icons">arrow_forward</i></span>
        @endif
    </div>

    <form action="{{ route('tvShows.episodes.update', ['tvShow' => $tvShow->id, 'season' => $episode->season->id, 'episode' => $episode->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="title">Titre</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $episode->title) }}" required>
        </div>
        <div class="form-group">
            <label for="episode_number">Numéro de l'Épisode</label>
            <input type="number" class="form-control" id="episode_number" name="episode_number" value="{{ old('episode_number', $episode->episode_number) }}" required>
        </div>
        <div class="form-group">
            <label for="overall_episode_number">Numéro total de l'Épisode</label>
            <input type="number" class="form-control" id="overall_episode_number" name="overall_episode_number" value="{{ old('overall_episode_number', $episode->overall_episode_number) }}">
        </div>
        <div class="form-group">
            <label for="air_date">Date de diffusion</label>
            <input type="date" class="form-control" id="air_date" name="air_date" value="{{ old('air_date', $episode->air_date) }}">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" >{{ old('description', $episode->description) }}</textarea>
        </div>
        <div class="form-group">
    <label for="duration">Durée</label>
    <input type="time" class="form-control" id="duration" name="duration" value="{{ old('duration', $episode->duration ? \Carbon\Carbon::parse($episode->duration)->format('H:i') : '') }}">
</div>

        <div class="form-group">
            <label for="file_path">Chemin du fichier</label>
            <input type="text" class="form-control" id="file_path" name="file_path" value="{{ old('file_path', $episode->file_path) }}">
        </div>
        <div class="form-group">
            <label for="guest_stars">Invités</label>
            <textarea class="form-control" id="guest_stars" name="guest_stars" rows="2">{{ old('guest_stars', $episode->guest_stars) }}</textarea>
        </div>
        <div class="form-group">
            <label for="rating">Note</label>
            <input type="number" class="form-control" id="rating" name="rating" step="0.1" value="{{ old('rating', $episode->rating) }}">
        </div>
        <div class="form-group">
            <label for="director_id">Réalisateur</label>
            <select class="form-control" id="director_id" name="director_id">
                <option value="">Sélectionner un réalisateur</option>
                @foreach($directors as $director)
                    <option value="{{ $director->id }}" {{ $episode->director_id == $director->id ? 'selected' : '' }}>{{ $director->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="writer">Scénariste</label>
            <input type="text" class="form-control" id="writer" name="writer" value="{{ old('writer', $episode->writer) }}">
        </div>
        <div class="form-group">
            <label for="streaming_url">URL de Streaming</label>
            <input type="url" class="form-control" id="streaming_url" name="streaming_url" value="{{ old('streaming_url', $episode->streaming_url) }}">
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        <a href="{{ route('tvShows.episodes.show', ['tvShow' => $tvShow->id, 'season' => $episode->season->id, 'episode' => $episode->id]) }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
