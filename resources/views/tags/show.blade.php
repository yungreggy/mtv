@extends('layouts.app')

@section('content')
<div class="container">
    <br>
    <h1>Tag : {{ $tag->name }}</h1>
    <p>Date d'ajout : {{ $tag->created_at }}</p>
    <br>
    
    <h2>Films associés</h2>
    @if($tag->films->isEmpty())
        <p>Aucun film associé à ce tag.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Année</th>
                    <th>Réalisateur</th>
                    <th>Rating</th>
                    <th>Genres</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tag->films as $film)
                    <tr>
                        <td>{{ $film->id }}</td>
                        <td><a href="{{ route('films.show', $film->id) }}">{{ $film->title }}</a></td>
                        <td>{{ $film->year }}</td>
                        <td><a href="{{ route('directors.show', $film->director->id) }}">{{ $film->director->name ?? 'N/A' }}</a></td>
                        <td>{{ $film->rating }}</td>
                        <td>
                            @foreach($film->genres as $genre)
                                <a href="{{ route('genres.show', $genre->id) }}" class="badge badge-secondary">{{ $genre->name }}</a>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
<br>
    <h2>Épisodes associés</h2>
    @if($tag->episodes->isEmpty())
        <p>Aucun épisode associé à ce tag.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Série</th>
                    <th>Saison</th>
                    <th>Épisode</th>
                    <th>Titre</th>
                    <th>Date de diffusion</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tag->episodes as $episode)
                    <tr>
                        <td>{{ $episode->id }}</td>
                        <td><a href="{{ route('tvShows.show', $episode->season->tvShow->id) }}">{{ $episode->season->tvShow->title }}</a></td>
                        <td>{{ $episode->season->season_number }}</td>
                        <td>{{ $episode->episode_number }}</td>
                        <td><a href="{{ route('tvShows.episodes.show', ['tvShow' => $episode->season->tvShow->id, 'season' => $episode->season->id, 'episode' => $episode->id]) }}">{{ $episode->title }}</a></td>
                        <td>{{ $episode->air_date }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <br>

    <h2>Publicités associées</h2>
    @if($tag->pubs->isEmpty())
        <p>Aucune publicité associée à ce tag.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Durée</th>
                    <th>Type</th>
                    <th>Marque/Magasin</th>
                    <th>Année</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tag->pubs as $pub)
                    <tr>
                        <td>{{ $pub->id }}</td>
                        <td><a href="{{ route('pubs.show', $pub->id) }}">{{ $pub->name }}</a></td>
                        <td>{{ $pub->duration }}</td>
                        <td>{{ $pub->ad_type }}</td>
                        <td>{{ $pub->brandStore ? $pub->brandStore->name : 'N/A' }}</td>
                        <td>{{ $pub->year }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('tags.index') }}" class="btn btn-secondary mt-4">Retour à la liste des tags</a>
</div>
@endsection
