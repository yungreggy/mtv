@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">

        <div class="card-header">
    <div class="d-flex justify-content-between mb-2">
        <a href="{{ route('programDates.show', ['program' => $program->id, 'date' => $previousDate]) }}" class="text-muted">
            &larr; Jour précédent
        </a>
        <a href="{{ route('programDates.show', ['program' => $program->id, 'date' => $nextDate]) }}" class="text-muted">
            Jour suivant &rarr;
        </a>
    </div>
    <h1><strong>{{ \Carbon\Carbon::parse($programDate->date)->translatedFormat('l, j F Y') }}</strong></h1>
    <div>
        <a href="{{ route('programs.show', $program->id) }}">{{ $program->name }}</a> -
        @foreach ($program->channels as $channel)
            <a href="{{ route('channels.show', $channel->id) }}">{{ $channel->name }}</a>
        @endforeach
    </div>
</div>


        <div class="card-body">
            @if ($errors->has('duplicate'))
                <div class="alert alert-danger">{{ $errors->first('duplicate') }}</div>
            @endif

            <h5 class="section-title">Programmes Associés :</h5>
            <a href="{{ route('programSchedules.createWithDate', ['program' => $program->id, 'date' => $programDate->date]) }}" class="btn btn-primary mb-3">+ Ajouter un programme</a>
            @if ($programSchedules->isEmpty())
                <p class="text-muted">Aucun programme prévu pour cette date.</p>
            @else
                <ul class="list-group">
                    @foreach ($programSchedules as $schedule)
                        <li class="list-group-item">
                            <a href="{{ route('programSchedules.show', $schedule->id) }}" class="h5 d-block">
                                <strong>{{ $schedule->name }}</strong>
                            </a>
                            <span class="d-block">{{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }}</span>

                            <p>{{ $schedule->description }}</p>
                            @if ($schedule->films->isNotEmpty())
                                @foreach ($schedule->films as $film)
                                    <div class="mb-2">
                                        <strong><a href="{{ route('films.show', $film->id) }}" class="medium">{{ $film->title }}</a></strong><br>
                                        <span class="small">Genre: {{ $film->genres->pluck('name')->join(', ') }}</span><br>
                                        <span class="small">Rating: {{ $film->pivot->age_rating }}</span><br>
                                        <a href="{{ route('filmSchedules.editFilm', ['schedule' => $schedule->id, 'film' => $film->id]) }}" class="text-primary small">Modifier</a>
                                    </div>
                                @endforeach
                            @elseif ($schedule->type === 'tv_show' && $schedule->episodes->isNotEmpty())
                                @foreach ($schedule->episodes as $episode)
                                    <div class="mb-2">
                                        <strong>
                                            <a href="{{ route('tvShows.seasons.episodes.show', ['tvShow' => $episode->season->tvShow->id, 'season' => $episode->season->id, 'episode' => $episode->id]) }}">
                                                Épisode {{ $episode->episode_number }}: {{ $episode->title }}
                                            </a>
                                        </strong><br>
                                        @if ($episode->season && $episode->season->tvShow)
                                            <span class="small">
                                                Série: <a href="{{ route('tvShows.show', $episode->season->tvShow->id) }}">{{ $episode->season->tvShow->title }}</a> - Saison: {{ $episode->season->season_number }}
                                            </span><br>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">Aucun contenu associé à cet horaire.</p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>

<!-- Modal pour sélectionner un film -->
<div class="modal fade" id="filmSelectModal" tabindex="-1" aria-labelledby="filmSelectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filmSelectModalLabel">Sélectionner un film</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <select id="filmSelect" class="form-select"></select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button id="saveFilmButton" type="button" class="btn btn-primary">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filmSelectModal = new bootstrap.Modal(document.getElementById('filmSelectModal'));
        const filmSelect = document.getElementById('filmSelect');
        const saveFilmButton = document.getElementById('saveFilmButton');

        document.querySelectorAll('button[data-bs-target="#filmSelectModal"]').forEach(button => {
            button.addEventListener('click', function() {
                const scheduleId = this.getAttribute('data-schedule-id');
                const programDateId = this.getAttribute('data-program-date-id');
                fetch('/film-list', {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(films => {
                    filmSelect.innerHTML = films.map(film => `<option value="${film.id}">${film.title}</option>`).join('');
                    filmSelect.dataset.scheduleId = scheduleId;
                    filmSelect.dataset.programDateId = programDateId;
                })
                .catch(error => console.error('Erreur lors de la récupération des films:', error));
            });
        });

        saveFilmButton.addEventListener('click', function() {
            const scheduleId = filmSelect.dataset.scheduleId;
            const programDateId = filmSelect.dataset.programDateId;
            const filmId = filmSelect.value;

            fetch(`/filmSchedules/${scheduleId}/addFilm`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    film_id: filmId,
                    program_date_id: programDateId
                })
            })
            .then(response => {
                if (response.ok) {
                    location.reload();
                } else {
                    return response.json().then(data => {
                        console.error('Erreur lors de l\'enregistrement du film:', data);
                    });
                }
            })
            .catch(error => console.error('Erreur lors de l\'enregistrement du film:', error));
        });
    });
</script>
@endsection

<style>

.card-header .text-muted {
    font-size: 0.9em;
    color: #777;
    text-decoration: none;
}

.card-header .text-muted:hover {
    color: #555;
    text-decoration: underline;
}

    body {
        font-family: 'Arial, sans-serif';
        background-color: #f4f4f9;
        color: #333;
    }

    .container {
        max-width: 800px;
        margin: 50px auto;
        padding: 20px;
        background: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    .program-title {
        text-align: center;
        font-size: 2em;
        color: #444;
        margin-bottom: 20px;
    }

    .custom-card {
        border: none;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .custom-card-header {
        background-color: #f8f9fa;
        padding: 15px 20px;
        font-size: 1.5em;
        color: #333;
        border-bottom: 1px solid #ddd;
        border-radius: 10px 10px 0 0;
    }

    .custom-card-body {
        padding: 20px;
    }

    .custom-card-text {
        font-size: 1.1em;
        color: #555;
        margin-bottom: 15px;
    }

    .custom-list-group {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .custom-list-group-item {
        padding: 10px 20px;
        border-bottom: 1px solid #ddd;
        font-size: 1.1em;
        color: #555;
    }

    .custom-list-group-item:last-child {
        border-bottom: none;
    }

    .image-container {
        text-align: center;
        margin: 20px 0;
    }

    .program-image {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
    }

    .section-title {
        font-size: 1.4em;
        color: #333;
        margin-top: 20px;
    }

    .channel-link {
        text-decoration: none;
        color: #007bff;
    }

    .channel-link:hover {
        text-decoration: underline;
    }

    .custom-button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .custom-button:hover {
        background-color: #0056b3;
    }

    .no-schedule-message {
        font-size: 1.1em;
        color: #888;
        margin-top: 10px;
    }
</style>
