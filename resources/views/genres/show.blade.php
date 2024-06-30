@extends('layouts.app')

@section('content')
<style>
    .genre-show-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
    }
    .genre-header {
        text-align: left;
        color: #444;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .genre-details {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }
    .genre-details p {
        font-size: 14px;
        margin-bottom: 10px;
    }
    .associated-items {
        margin-top: 20px;
    }
    .associated-items h2 {
        font-size: 16px;
        margin-bottom: 10px;
    }
    .associated-items ul {
        list-style-type: none;
        padding: 0;
    }
    .associated-items ul li {
        font-size: 14px;
        margin-bottom: 5px;
    }
    .associated-items ul li a {
        color: #007bff;
        text-decoration: none;
    }
    .associated-items ul li a:hover {
        text-decoration: underline;
    }
    .btn {
        font-size: 14px;
        padding: 8px 12px;
        border-radius: 50px;
    }
    .btn-outline-dark {
        border: 1px solid #555;
        color: #555;
    }
    .btn-outline-dark:hover {
        background-color: #555;
        color: #fff;
    }
</style>

<div class="genre-show-container">
    <div class="genre-header">
        <h1>Détails du Genre</h1>
        <div>
            <a href="{{ route('genres.index') }}" class="btn btn-outline-dark">
                Retour à la liste
            </a>
            <a href="{{ route('genres.edit', $genre->id) }}" class="btn btn-outline-dark">
                <i class="material-icons">edit</i>
            </a>
            @if($nextGenre)
                <a href="{{ route('genres.show', $nextGenre->id) }}" class="btn btn-outline-dark">
                    <i class="material-icons">arrow_forward</i>
                </a>
            @endif
        </div>
    </div>
    
    <!-- Affichage des détails du genre -->
    <div class="genre-details">
        <p><strong>ID :</strong> {{ $genre->id }}</p>
        <p><strong>Nom :</strong> {{ $genre->name }}</p>
        <p><strong>Type :</strong> {{ $genre->type == 'music' ? 'Musique' : 'Film/TV' }}</p>
    </div>

    <div class="associated-items">
        @if($genre->type == 'music')
            <h2>Albums associés</h2>
            <ul>
                @forelse($genre->albums as $album)
                    <li><a href="{{ route('albums.show', $album->id) }}">{{ $album->title }}</a></li>
                @empty
                    <li>Aucun album associé.</li>
                @endforelse
            </ul>

            <h2>Clips vidéo associés</h2>
            <ul>
                @forelse($genre->musicVideos as $musicVideo)
                    <li><a href="{{ route('musicVideos.show', $musicVideo->id) }}">{{ $musicVideo->title }}</a></li>
                @empty
                    <li>Aucun clip vidéo associé.</li>
                @endforelse
            </ul>

            <h2>Artistes associés</h2>
            <ul>
                @forelse($genre->artists as $artist)
                    <li><a href="{{ route('artists.show', $artist->id) }}">{{ $artist->name }}</a></li>
                @empty
                    <li>Aucun artiste associé.</li>
                @endforelse
            </ul>
        @else
            <h2>Émissions de télévision associées</h2>
            <ul>
                @forelse($genre->tvShows as $tvShow)
                    <li><a href="{{ route('tvShows.show', $tvShow->id) }}">{{ $tvShow->title }}</a></li>
                @empty
                    <li>Aucune émission de télévision associée.</li>
                @endforelse
            </ul>

            <h2>Films associés</h2>
            <ul>
                @forelse($genre->films as $film)
                    <li><a href="{{ route('films.show', $film->id) }}">{{ $film->title }}</a></li>
                @empty
                    <li>Aucun film associé.</li>
                @endforelse
            </ul>
        @endif
    </div>
</div>
@endsection

