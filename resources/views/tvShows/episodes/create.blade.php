@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Ajouter un Épisode pour {{ $tvShow->name }}</h1>
    @include('partials.messages')  <!-- Feedback and error messages -->
    <form action="{{ route('tvShows.episodes.store', $tvShow->id) }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="title">Titre de l'épisode</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>

        <div class="form-group">
            <label for="duration">Durée</label>
            <input type="number" class="form-control" id="duration" name="duration" required>
        </div>

        <div class="form-group">
            <label for="file_path">Chemin du fichier</label>
            <input type="text" class="form-control" id="file_path" name="file_path">
        </div>

        <div class="form-group">
            <label for="episode_number">Numéro de l'épisode</label>
            <input type="number" class="form-control" id="episode_number" name="episode_number" required>
        </div>

        <div class="form-group">
            <label for="overall_episode_number">Numéro global de l'épisode</label>
            <input type="number" class="form-control" id="overall_episode_number" name="overall_episode_number" required>
        </div>

        <div class="form-group">
            <label for="air_date">Date de diffusion</label>
            <input type="date" class="form-control" id="air_date" name="air_date" required>
        </div>

        <div class="form-group">
            <label for="guest_stars">Invités spéciaux</label>
            <input type="text" class="form-control" id="guest_stars" name="guest_stars">
        </div>

        <div class="form-group">
            <label for="rating">Note</label>
            <input type="number" class="form-control" id="rating" name="rating" step="0.1" min="0" max="10">
        </div>

        <div class="form-group">
            <label for="director_id">Réalisateur</label>
            <select class="form-control" id="director_id" name="director_id">
                <option value="">Sélectionner un réalisateur</option>
                @foreach(App\Models\Director::all() as $director)
                    <option value="{{ $director->id }}">{{ $director->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="writer">Scénariste</label>
            <input type="text" class="form-control" id="writer" name="writer">
        </div>

        <div class="form-group">
            <label for="streaming_url">URL de streaming</label>
            <input type="text" class="form-control" id="streaming_url" name="streaming_url">
        </div>

        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>
@endsection
