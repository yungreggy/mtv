{{-- resources/views/musicVideos/labels/show.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Détails du Label</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $label->name }}</h5>
            <p class="card-text"><strong>Site Web :</strong> <a href="{{ $label->website }}" target="_blank">{{ $label->website }}</a></p>
            <p class="card-text"><strong>Description :</strong> {{ $label->description }}</p>
            <p class="card-text"><strong>Année de Fondation :</strong> {{ $label->foundation_year }}</p>

            @if($label->logo_image)
                <div class="mb-3">
                    <img src="{{ asset('storage/'.$label->logo_image) }}" alt="Logo du Label" class="img-fluid">
                </div>
            @endif

            <h3>Albums associés</h3>
@if($label->albums->isEmpty())
    <p>Aucun album n'est associé à ce label.</p>
@else
    <div class="list-group">
        @foreach($label->albums as $album)
            <a href="{{ route('albums.show', $album->id) }}" class="list-group-item list-group-item-action">
                {{ $album->title }} ({{ $album->year }}) - {{ $album->artist->name }}
            </a>
        @endforeach
    </div>
@endif
<br>

            <a href="{{ route('labels.edit', $label->id) }}" class="btn btn-primary">Éditer</a>
            <a href="{{ route('labels.index') }}" class="btn btn-secondary">Retour à la liste</a>
        </div>
    </div>
</div>
@endsection
