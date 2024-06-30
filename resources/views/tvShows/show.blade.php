@extends('layouts.app')

@section('content')
<style>
    .tv-show-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
    }
    .tv-show-header {
        text-align: left;
        color: #444;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .tv-show-header h1 {
        font-size: 20px;
        margin: 0;
    }
    .tv-show-card {
        margin-bottom: 20px;
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .tv-show-card-header {
        background-color: #f1f1f1;
        color: #666;
        padding: 15px;
        font-size: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .tv-show-card-body {
        padding: 15px;
        font-size: 14px;
    }
    .tv-show-card-body p {
        margin-bottom: 10px;
    }
    .tv-show-card-body img {
        max-width: 100%;
        border-radius: 8px;
    }
    .nav-tabs .nav-link {
        font-size: 14px;
        color: #555;
        border: none;
    }
    .nav-tabs .nav-link.active {
        color: #000;
        font-weight: bold;
        border: none;
    }
    .tab-content h3 {
        font-size: 18px;
        margin-top: 20px;
    }
    .tab-content h4 {
        font-size: 16px;
        margin-top: 20px;
    }
    .tab-content p {
        font-size: 14px;
    }
    .tab-content ul {
        padding-left: 20px;
    }
    .tab-content ul li {
        font-size: 14px;
    }
    .btn {
        font-size: 14px;
        padding: 8px 12px;
        border-radius: 50px;
        background-color: transparent;
        color: #555;
        border: 1px solid #555;
    }
    .btn:hover {
        background-color: #555;
        color: #fff;
    }
</style>

<div class="tv-show-container">
    <div class="tv-show-header">
        <h1>{{ $tvShow->title }}</h1>
        <a href="{{ route('tvShows.edit', $tvShow->id) }}" class="btn">Modifier la série TV</a>
    </div>

    @include('partials.messages') <!-- Inclusion des messages -->

    <div class="card tv-show-card">
        <div class="card-header tv-show-card-header">
            <span>Informations générales</span>
        </div>
        <div class="card-body tv-show-card-body">
            <div class="row">
                <div class="col-md-4">
                    @if($tvShow->poster)
                        <img src="{{ asset('storage/' . $tvShow->poster) }}" alt="{{ $tvShow->title }} Poster" class="img-fluid">
                    @else
                        <p>Pas de poster disponible</p>
                    @endif
                </div>
                <div class="col-md-8">
                    <p><strong>Années d'activité:</strong> {{ $tvShow->years_active }}</p>
                    <p><strong>Genres:</strong>
    @if($tvShow->genres->isEmpty())
        N/A
    @else
        @foreach($tvShow->genres as $genre)
            <a href="{{ route('genres.show', $genre->id) }}">{{ $genre->name }}</a>{{ !$loop->last ? ', ' : '' }}
        @endforeach
    @endif
</p>

                    <p><strong>Description:</strong> {{ $tvShow->description }}</p>
                    <p><strong>Créateur:</strong> {{ $tvShow->creator }}</p>
                    <p><strong>Nombre de Saisons:</strong> {{ $tvShow->season_count }}</p>
                    <p><strong>Public cible:</strong> {{ $tvShow->target_audience }}</p>
                    <p><strong>Site officiel:</strong> <a href="{{ $tvShow->official_website }}" target="_blank">{{ $tvShow->official_website }}</a></p>
                    <p><strong>Statut:</strong> {{ $tvShow->status }}</p>
                    <p><strong>Pays d'origine:</strong> {{ $tvShow->country_of_origin }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card tv-show-card">
        <div class="card-header tv-show-card-header">
            <span>Saisons et épisodes</span>
            <a href="{{ route('tvShows.seasons.create', ['tvShow' => $tvShow->id]) }}" class="btn">Ajouter une saison</a>
        </div>
        <div class="card-body tv-show-card-body">
            <ul class="nav nav-tabs" id="seasonsTab" role="tablist">
                @foreach($tvShow->seasons as $season)
                    <li class="nav-item">
                        <a class="nav-link @if($loop->first) active @endif" id="season{{ $season->season_number }}-tab" data-toggle="tab" href="#season{{ $season->season_number }}" role="tab" aria-controls="season{{ $season->season_number }}" aria-selected="true">Saison {{ $season->season_number }}</a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content" id="seasonsTabContent">
                @foreach($tvShow->seasons as $season)
                    <div class="tab-pane fade @if($loop->first) show active @endif" id="season{{ $season->season_number }}" role="tabpanel" aria-labelledby="season{{ $season->season_number }}-tab">
                        <br>
                        <a href="{{ route('tvShows.seasons.edit', ['tvShow' => $tvShow->id, 'season' => $season->id]) }}" class="btn mb-3">Modifier la saison</a>
                        <a href="{{ route('tvShows.episodes.create', ['tvShow' => $tvShow->id, 'season' => $season->id]) }}" class="btn mb-3">Ajouter un épisode</a>
                        
                        <a href="{{ route('tvShows.episodes.createMultiple', ['tvShow' => $tvShow->id, 'season' => $season->id]) }}" class="btn mb-3">Ajouter plusieurs épisodes</a>
                        <p><strong>Année:</strong> {{ $season->year }}</p>
                        <p><strong>Date de début:</strong> {{ $season->start_date }}</p>
                        <p><strong>Date de fin:</strong> {{ $season->end_date }}</p>
                        <p><strong>Nombre d'épisodes:</strong> {{ $season->episode_count }}</p>
                        <p><strong>Description:</strong> {{ $season->description }}</p>

                        @if($season->episodes->count() > 0)
                            <h4>Épisodes</h4>
                            <ul>
                                @foreach($season->episodes as $episode)
                                    <li>
                                        <a href="{{ route('tvShows.episodes.show', ['tvShow' => $tvShow->id, 'season' => $season->id, 'episode' => $episode->id]) }}" class="text-dark">
                                            #{{ $episode->episode_number }} - {{ $episode->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>Aucun épisode disponible pour cette saison.</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <a href="{{ route('tvShows.index') }}" class="btn mt-4">Retour à la liste</a>
</div>
@endsection

