@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <a href="{{ route('artists.create') }}" class="btn btn-sm btn-outline-dark">
        <i class="material-icons" style="font-size: 15px;">add</i> Ajouter un artiste
    </a>
    <br>
    <br>
    <h1>Résultats de la recherche pour "{{ $query }}"</h1>

    <div class="mt-4">
        <h2>Artistes</h2>
        @if ($artists->isEmpty())
            <p>Aucun artiste trouvé.</p>
        @else
            <ul class="list-group">
                @foreach ($artists as $artist)
                    <li class="list-group-item">
                        <a href="{{ route('artists.show', $artist->id) }}">{{ $artist->name }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="mt-4">
        <h2>Albums</h2>
        @if ($albums->isEmpty())
            <p>Aucun album trouvé.</p>
        @else
            <ul class="list-group">
                @foreach ($albums as $album)
                    <li class="list-group-item">
                        <a href="{{ route('albums.show', $album->id) }}">{{ $album->title }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="mt-4">
        <h2>Clips vidéo</h2>
        @if ($musicVideos->isEmpty())
            <p>Aucun clip vidéo trouvé.</p>
        @else
            <ul class="list-group">
                @foreach ($musicVideos as $musicVideo)
                    <li class="list-group-item">
                        <a href="{{ route('musicVideos.show', $musicVideo->id) }}">{{ $musicVideo->title }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="mt-4">
        <h2>Genres</h2>
        @if ($genres->isEmpty())
            <p>Aucun genre trouvé.</p>
        @else
            <ul class="list-group">
                @foreach ($genres as $genre)
                    <li class="list-group-item">
                        <a href="{{ route('genres.show', $genre->id) }}">{{ $genre->name }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="mt-4">
        <h2>Films</h2>
        @if ($films->isEmpty())
            <p>Aucun film trouvé.</p>
        @else
            <ul class="list-group">
                @foreach ($films as $film)
                    <li class="list-group-item">
                        <a href="{{ route('films.show', $film->id) }}">{{ $film->title }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="mt-4">
        <h2>Séries TV</h2>
        @if ($tvShows->isEmpty())
            <p>Aucune série TV trouvée.</p>
        @else
            <ul class="list-group">
                @foreach ($tvShows as $tvShow)
                    <li class="list-group-item">
                        <a href="{{ route('tvShows.show', $tvShow->id) }}">{{ $tvShow->title }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="mt-4">
        <h2>Saisons de Séries TV</h2>
        @if ($tvShowSeasons->isEmpty())
            <p>Aucune saison trouvée.</p>
        @else
            <ul class="list-group">
                @foreach ($tvShowSeasons as $season)
                    <li class="list-group-item">
                        <a href="{{ route('tvShows.seasons.show', ['tvShow' => $season->tv_show_id, 'season' => $season->id]) }}">Saison {{ $season->season_number }} de {{ $season->tvShow->title }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="mt-4">
        <h2>Épisodes de Séries TV</h2>
        @if ($tvShowEpisodes->isEmpty())
            <p>Aucun épisode trouvé.</p>
        @else
            <ul class="list-group">
                @foreach ($tvShowEpisodes as $episode)
                    <li class="list-group-item">
                        <a href="{{ route('tvShows.episodes.show', ['tvShow' => $episode->season->tvShow->id, 'season' => $episode->season_id, 'episode' => $episode->id]) }}">#{{ $episode->episode_number }} {{ $episode->title }} de {{ $episode->season->tvShow->title }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="mt-4">
        <h2>Labels</h2>
        @if ($labels->isEmpty())
            <p>Aucun label trouvé.</p>
        @else
            <ul class="list-group">
                @foreach ($labels as $label)
                    <li class="list-group-item">
                        <a href="{{ route('labels.show', $label->id) }}">{{ $label->name }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
