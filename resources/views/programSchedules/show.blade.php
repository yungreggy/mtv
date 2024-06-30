@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div style="display: flex; flex-direction: column;">
                <h1>{{ $schedule->name }}</h1>
  
                <strong>{{ $schedule->daysOfWeek->pluck('name')->join(', ') }}</strong>
                <p>{{ date('g:i A', strtotime($schedule->start_time)) }} - {{ date('g:i A', strtotime($schedule->end_time)) }}</p>
            </div>
            

            <a href="{{ route('programs.show', $schedule->program->id) }}">{{ $schedule->program->name }}</a>
       
        
            @if($schedule->type === 'film')
<div style="display: flex; flex-direction: row-reverse;">
    <a href="{{ route('programSchedules.refreshSelection', $schedule->id) }}" title="Refresh film selection" class="refresh-icon">
        <i class="material-icons">refresh</i>
    </a>
</div>
@endif


        </div>

   
        <div class="card-body">
    @include('partials.messages')
    <div class="description-section">
        <p class="card-text">
            <strong>Description:</strong>
            <span class="text-muted">{{ $schedule->description ?? 'Aucune description fournie.' }}</span>
        </p>
    </div>

    @if ($schedule->type === 'tv_show')
        @if ($episodesWithDates->isEmpty())
            <p class="no-episodes-message">Aucun épisode associé à cet horaire.</p>
        @else
            <ul class="list-group episode-list">
                @foreach ($episodesWithDates as $episode)
                    <li class="list-group-item">
                        <div class="episode-details">
                            <div class="episode-header">
                                <strong class="episode-title">
                                    <a href="{{ route('tvShows.seasons.episodes.show', ['tvShow' => $episode->season->tvShow->id, 'season' => $episode->season->id, 'episode' => $episode->id]) }}">
                                        Épisode {{ $episode->episode_number }}: {{ $episode->title }}
                                    </a>
                                </strong>
                                <button class="btn btn-sm btn-link text-primary edit-episode-button" data-schedule-id="{{ $schedule->id }}" data-episode-id="{{ $episode->id }}" data-season-id="{{ $episode->season->id }}">Modifier</button>
                            </div>
                            <div class="episode-meta">
                                <div class="meta-item">
                                    <span class="text-muted">Série:</span>
                                    <strong>
                                        <a href="{{ route('tvShows.show', $episode->season->tvShow->id) }}">{{ $episode->season->tvShow->title }}</a>
                                    </strong>
                                </div>
                                <div class="meta-item">
                                    <span class="text-muted">Saison:</span>
                                    <strong>{{ $episode->season->season_number }}</strong>
                                </div>
                                <div class="meta-item">
                                    <span class="text-muted">Date de diffusion:</span>
                                    <strong>
                                        <a href="{{ route('programDates.show', ['program' => $schedule->program->id, 'date' => $episode->diffusion_date]) }}">
                                            {{ $episode->diffusion_date ? \Carbon\Carbon::parse($episode->diffusion_date)->translatedFormat('l j F Y') : 'Date non disponible' }}
                                        </a>
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    @endif
</div>



<style>


.refresh-icon {
        color: #007bff;
        transition: color 0.3s, transform 0.3s;
        display: flex;
        align-items: center;
    }

    .refresh-icon:hover,
    .refresh-icon:focus {
        color: #0056b3;
        transform: rotate(360deg);
    }
.card-body {
    font-family: Arial, sans-serif;
    font-size: 0.875rem; /* Smaller font size */
    color: #333; /* Darker text color for better readability */
    padding: 20px;
    background-color: #f8f9fa; /* Light background for contrast */
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.description-section {
    margin-bottom: 20px;
}

.card-text strong {
    color: #343a40; /* Dark gray for video titles */
}

.card-text .text-muted {
    color: #6c757d;
}

.no-episodes-message {
    color: #dc3545; /* Red color for no episodes message */
    font-weight: bold;
}

.list-group {
    margin: 0;
    padding: 0;
    list-style: none;
}

.list-group-item {
    display: flex;
    flex-direction: column;
    padding: 15px;
    margin-bottom: 10px;
    background-color: #fff;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.list-group-item:hover {
    background-color: #e9ecef;
    transform: scale(1.02);
}

.episode-details {
    display: flex;
    flex-direction: column;
}

.episode-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.episode-title {
    font-size: 1.1rem;
    margin: 0;
}

.episode-meta {
    display: flex;
    flex-wrap: wrap;
}

.meta-item {
    flex: 1 1 100%;
    margin-bottom: 5px;
}

.meta-item a {
    color: #007bff;
    text-decoration: none;
}

.meta-item a:hover {
    text-decoration: underline;
}

.text-muted {
    color: #6c757d !important;
}

.btn-link {
    font-size: 0.75rem; /* Smaller font size for buttons */
    color: #007bff;
    text-decoration: none;
    background-color: transparent;
    border: none;
    cursor: pointer;
}

.btn-link:hover {
    color: #0056b3;
    text-decoration: underline;
}

/* Simple animation for video player */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.player-container {
    animation: fadeIn 1s ease-in-out;
}
/* Override Bootstrap link color */
a,
a:link,
a:visited,
a:hover,
a:active {
    color: inherit; /* Use the current text color */
    text-decoration: none;
    transition: color 0.3s ease;
}

a:hover {
    color: #333; /* Change hover color if necessary */
}

/* Specific link styles inside the card-body */
.card-body a {
    color: #343a40; /* Custom color for links */
}

.card-body a:hover {
    color: #0056b3; /* Custom hover color */
}


</style>





            <!-- Modal pour sélectionner un épisode -->
            <div class="modal fade" id="episodeSelectModal" tabindex="-1" aria-labelledby="episodeSelectModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="episodeSelectModalLabel">Sélectionner un épisode</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editEpisodeForm" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="episode_id">Sélectionner un nouvel épisode</label>
                                    <select class="form-control" id="episode_id" name="episode_id">
                                        @foreach($episodes as $newEpisode)
                                            <option value="{{ $newEpisode->id }}">{{ $newEpisode->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="button" id="saveEpisodeButton" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section pour afficher les films associés -->
            @if ($schedule->type === 'film')
                @if ($filmsWithDates->isEmpty())
                    <p>Aucun film associé à cet horaire.</p>
                @else
                    <ul class="list-group">
                        @foreach ($filmsWithDates as $film)
                            <li class="list-group-item">
                                <strong>
                                    <a href="{{ route('films.show', $film->id) }}">{{ $film->title }}</a>
                                </strong><br>
                                <span class="text-muted">Classification d'âge:</span> <span class="text-muted">{{ $film->age_rating }}</span><br>
                                <span class="text-muted">Date de diffusion:</span>
                                <strong>
                                    <a href="{{ route('programDates.show', ['program' => $schedule->program->id, 'date' => $film->diffusion_date]) }}">
                                        {{ $film->diffusion_date ? \Carbon\Carbon::parse($film->diffusion_date)->translatedFormat('l j F Y') : 'Date non disponible' }}
                                    </a>
                                </strong><br>
                                <button class="btn btn-sm btn-link text-primary edit-film-button" data-schedule-id="{{ $schedule->id }}" data-film-id="{{ $film->id }}" data-program-date-id="{{ $film->program_date_id }}" style="font-size: smaller;">Modifier</button>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <!-- Bouton pour détacher tous les films -->
                <div style="display: flex; gap: 1rem;">
                    <form action="{{ route('filmSchedules.detachAllFilms', $schedule->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir détacher tous les films ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger mt-3">Détacher tous les films</button>
                    </form>

                    <!-- Bouton pour supprimer le programme -->
                    @if($filmsWithDates->isEmpty())
                        <form action="{{ route('programSchedules.destroy', $schedule->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                    @else
                        <button type="button" class="btn btn-danger mt-3" disabled title="Détachez tous les films avant de supprimer l'horaire.">Supprimer l'horaire</button>
                        <p class="text-muted mt-1">Détachez tous les films avant de supprimer l'horaire.</p>
                    @endif
                </div>
                <a href="{{ route('filmSchedules.edit', $schedule->id) }}" class="btn btn-primary">Modifier</a>
            @endif

            <!-- Modal pour sélectionner un film -->
            <div class="modal fade" id="filmSelectModal" tabindex="-1" aria-labelledby="filmSelectModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="filmSelectModalLabel">Sélectionner un film</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editFilmForm" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="film_id">Sélectionner un nouveau film</label>
                                    <select class="form-control" id="film_id" name="film_id">
                                        @foreach($films as $newFilm)
                                            <option value="{{ $newFilm->id }}">{{ $newFilm->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="button" id="saveFilmButton" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editFilmButtons = document.querySelectorAll('.edit-film-button');
    const filmSelectModal = new bootstrap.Modal(document.getElementById('filmSelectModal'));
    const editFilmForm = document.getElementById('editFilmForm');
    const saveFilmButton = document.getElementById('saveFilmButton');
    let currentScheduleId, currentFilmId;

    editFilmButtons.forEach(button => {
        button.addEventListener('click', function() {
            currentScheduleId = this.getAttribute('data-schedule-id');
            currentFilmId = this.getAttribute('data-film-id');
            const programDateId = this.getAttribute('data-program-date-id');
            
            // Pré-selectionner le film actuel
            document.getElementById('film_id').value = currentFilmId;

            // Mettre à jour l'action du formulaire
            editFilmForm.action = `/filmSchedules/${currentScheduleId}/updateFilm/${currentFilmId}`;
            filmSelectModal.show();
        });
    });

    saveFilmButton.addEventListener('click', function() {
        editFilmForm.submit();
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const editEpisodeButtons = document.querySelectorAll('.edit-episode-button');
    const episodeSelectModal = new bootstrap.Modal(document.getElementById('episodeSelectModal'));
    const editEpisodeForm = document.getElementById('editEpisodeForm');
    const saveEpisodeButton = document.getElementById('saveEpisodeButton');
    let currentScheduleId, currentEpisodeId;

    editEpisodeButtons.forEach(button => {
        button.addEventListener('click', function() {
            currentScheduleId = this.getAttribute('data-schedule-id');
            currentEpisodeId = this.getAttribute('data-episode-id');
            const seasonId = this.getAttribute('data-season-id');

            // Pré-selectionner l'épisode actuel
            document.getElementById('episode_id').value = currentEpisodeId;

            // Mettre à jour l'action du formulaire
            editEpisodeForm.action = `/tvShowSchedules/${currentScheduleId}/updateEpisode/${currentEpisodeId}`;
            episodeSelectModal.show();
        });
    });

    saveEpisodeButton.addEventListener('click', function() {
        editEpisodeForm.submit();
    });
});
</script>


