@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Épisodes de {{ $tvShow->name }}</h1>
    <a href="{{ route('tvShows.episodes.create', $tvShow->id) }}" class="btn btn-primary">Ajouter un épisode</a>
    <ul>
        @foreach ($episodes as $episode)
            <li>
                {{ $episode->title }} - <a href="{{ route('tvShows.episodes.show', [$tvShow->id, $episode->id]) }}">Voir</a> | <a href="{{ route('tvShows.episodes.edit', [$tvShow->id, $episode->id]) }}">Modifier</a>
                <form action="{{ route('tvShows.episodes.destroy', [$tvShow->id, $episode->id]) }}" method="POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </li>
        @endforeach
    </ul>
</div>
@endsection
