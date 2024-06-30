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
        display: flex; justify-content: space-between;
        align-items: center;
    }
    .tv-show-card-body {
        padding: 15px;
        font-size: 14px;
    }
    .tv-show-card-body p {
        margin-bottom: 10px;
    }
    .btn {
        font-size: 14px;
        padding: 8px 12px;
        border-radius: 50px;
        background-color: transparent;
        color: #555;
        border: 1px solid #555;
        margin-right: 5px;
    }
    .btn:hover {
        background-color: #555;
        color: #fff;
    }
</style>

<div class="tv-show-container">
    <div class="tv-show-header">
        <h1>{{ $tvShow->title }} - S0{{ $season->season_number }} - E0{{ $episode->episode_number }} - {{ $episode->title }}</h1>
    </div>

    @include('partials.messages')

    <div class="card tv-show-card">
        <div class="card-header tv-show-card-header">
            <h2>Informations sur l'épisode</h2>
            <div>
                <a href="{{ route('tvShows.episodes.edit', ['tvShow' => $tvShow->id, 'season' => $season->id, 'episode' => $episode->id]) }}" class="btn">Modifier</a>
                <form action="{{ route('tvShows.episodes.destroy', ['tvShow' => $tvShow->id, 'season' => $season->id, 'episode' => $episode->id]) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet épisode ?')">Supprimer</button>
                </form>
                @if($nextEpisode)
                    <a href="{{ route('tvShows.episodes.show', ['tvShow' => $tvShow->id, 'season' => $season->id, 'episode' => $nextEpisode->id]) }}" class="btn">
                       <i class="fas fa-arrow-right"></i>
                    </a>
                @endif
            </div>
        </div>
        <div class="card-body tv-show-card-body">
            @if($episode->episode_number)
                <p><strong>Numéro de l'épisode:</strong> {{ $episode->episode_number }}</p>
            @endif
            @if($episode->overall_episode_number)
                <p><strong>Numéro total de l'épisode:</strong> {{ $episode->overall_episode_number }}</p>
            @endif
            @php
                use Carbon\Carbon;
            @endphp

            @if($episode->air_date)
                <p><strong>Date de diffusion:</strong> {{ Carbon::parse($episode->air_date)->translatedFormat('d F Y') }}</p>
            @endif

            @if($episode->description)
                <p><strong>Description:</strong> {{ $episode->description }}</p>
            @endif
            @if($durationInMinutes !== null)
                <p><strong>Durée:</strong> {{ $durationInMinutes }} minutes</p>
            @endif

            @if($episode->guest_stars)
                <p><strong>Invités:</strong> {{ $episode->guest_stars }}</p>
            @endif
            @if($episode->rating)
                <p><strong>Note:</strong> {{ $episode->rating }}</p>
            @endif
            @if($episode->director)
                <p><strong>Réalisateur:</strong> {{ $episode->director->name }}</p>
            @endif
            @if($episode->writer)
                <p><strong>Scénariste:</strong> {{ $episode->writer }}</p>
            @endif
            @if($episode->file_path)
                <div class="mt-4">
                    <video controls width="100%">
                        <source src="{{ Storage::disk('tv_shows')->url($episode->file_path) }}" type="video/mp4">
                        <source src="{{ Storage::disk('tv_shows')->url($episode->file_path) }}" type="video/x-matroska">
                        Votre navigateur ne supporte pas la balise vidéo.
                    </video>
                </div>
            @endif
        </div>
    </div>

    <div class="tags-section">
        <p><strong>Tags :</strong>
            <span id="tagsList">
                @foreach($episode->tags as $tag)
                <a href="{{ route('tags.show', $tag->id) }}" class="badge badge-secondary" style="margin-right: 2px;">{{ $tag->name }}</a>
                @endforeach
            </span>
            <button type="button" class="btn" id="addTagButton">+ </button>
        </p>
        <div id="addTagDiv" style="display: none;">
            <form id="addTagForm">
                @csrf
                <input type="text" id="tagName" class="form-control" placeholder="Entrez un tag">
                <br>
                <button type="submit" class="btn btn-secondary">Ajouter</button>
            </form>
        </div>
    </div>

    <a href="{{ route('tvShows.show', $tvShow->id) }}" class="btn mt-4">Retour à la série</a>
</div>
@endsection

<script>


document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded');

    document.getElementById('addTagButton').addEventListener('click', function() {
        console.log('Add tag button clicked');
        var addTagDiv = document.getElementById('addTagDiv');
        addTagDiv.style.display = 'block';
    });

    document.getElementById('addTagForm').addEventListener('submit', function(e) {
        console.log('Add tag form submitted');
        e.preventDefault();

        let form = e.target;
        let tagName = document.getElementById('tagName').value.trim();

        console.log('Tag name:', tagName);

        if (!tagName) {
            console.log('Tag name is empty');
            alert('Le champ du tag ne peut pas être vide.');
            return;
        }

        // Construire dynamiquement l'URL de la requête
        let url = `{{ url('tvShows/'.$tvShow->id.'/seasons/'.$season->id.'/episodes/'.$episode->id.'/tags') }}`;

        console.log('Sending POST request to ' + url);
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ name: tagName })
        })
        .then(response => {
            console.log('Response received:', response);
            if (!response.ok) {
                throw new Error('Erreur lors de l\'ajout du tag');
            }
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data);
            let tagBadge = document.createElement('span');
            tagBadge.className = 'badge badge-secondary';
            tagBadge.textContent = data.tag.name;

            document.getElementById('tagsList').appendChild(tagBadge);

            document.getElementById('addTagDiv').style.display = 'none';
            form.reset();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de la communication avec le serveur: ' + error.message);
        });
    });
});


</script>
