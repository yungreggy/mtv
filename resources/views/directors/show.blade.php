@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header">
            <h2>{{ $director->name }}</h2>
        </div>
        <div class="card-body">
            <p><strong>ID :</strong> {{ $director->id }}</p>
            <p><strong>Nom :</strong> {{ $director->name }}</p>
            <p><strong>Date de création :</strong> {{ $director->created_at->format('d-m-Y') }}</p>
            <p><strong>Dernière mise à jour :</strong> {{ $director->updated_at->format('d-m-Y') }}</p>
        </div>
        <div class="card-footer text-right">
            <a href="{{ route('directors.edit', $director->id) }}" class="btn btn-primary">Éditer</a>
            <a href="{{ route('directors.index') }}" class="btn btn-secondary">Retour à la liste</a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h3>Films associés</h3>
        </div>
        <div class="card-body">
            @if($director->films->isEmpty())
                <p>Aucun film associé.</p>
            @else
                <ul class="list-group">
                    @foreach($director->films as $film)
                        <li class="list-group-item">
                            <a href="{{ route('films.show', $film->id) }}">{{ $film->title }}</a>
                            <span class="text-muted">({{ $film->year }})</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Clips associés</h3>
        </div>
        <div class="card-body">
            @if($director->musicVideos->isEmpty())
                <p>Aucun clip associé.</p>
            @else
                <ul class="list-group">
                    @foreach($director->musicVideos as $musicVideo)
                        <li class="list-group-item">
                            <a href="{{ route('musicVideos.show', $musicVideo->id) }}">{{ $musicVideo->title }}</a>
                            <span class="text-muted">({{ $musicVideo->year }})</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection
