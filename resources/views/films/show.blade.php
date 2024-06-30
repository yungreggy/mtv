@extends('layouts.app')

@section('content')
<style>
    .film-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
    }
    .film-header {
        text-align: left;
        color: #444;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .film-header h1 {
        font-size: 20px;
        margin: 0;
    }
    .film-card {
        margin-bottom: 20px;
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .film-card-header {
        background-color: #f1f1f1;
        color: #666;
        padding: 15px;
        font-size: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .film-card-body {
        padding: 15px;
        font-size: 14px;
    }
    .film-card-body p {
        margin-bottom: 10px;
    }
    .film-card-body img {
        max-width: 100%;
        border-radius: 8px;
    }
    .btn {
        font-size: 14px;
        padding: 5px 10px;
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

<div class="film-container">
    <div class="film-header">
        <h1>{{ $film->title }}</h1>
        <a href="{{ route('films.edit', $film->id) }}" class="btn">Modifier le film</a>
    </div>

    @include('partials.messages')

    <div class="card film-card">
        <div class="card-header film-card-header">
            <span>Informations sur le film</span>
        </div>
        <div class="card-body film-card-body">
            <div class="row">
                <div class="col-md-4">
                    @if($film->local_image_path)
                        <img src="{{ asset('storage/' . $film->local_image_path) }}" alt="Poster du film {{ $film->title }}" class="img-fluid" title="{{ $film->title }}" loading="lazy">
                    @else
                        <p>Pas de poster disponible</p>
                    @endif
                </div>
                <div class="col-md-8">
                    <p><strong>Année:</strong> {{ $film->year }}</p>
                    <p><strong>Réalisateur:</strong> <a href="{{ route('directors.show', $film->director_id) }}">{{ $film->director->name }}</a></p>
                    <p><strong>Description:</strong> {{ $film->description }}</p>
                    <p><strong>Durée:</strong> {{ $film->duration }}</p>
                    <p><strong>Rating:</strong> {{ $film->rating }}</p>
                    <p><strong>Langue principale:</strong> {{ $film->primary_language }}</p>
                    <p><strong>Pays d'origine:</strong> {{ $film->country_of_origin }}</p>
                    <p><strong>Genres :</strong>
                        @foreach($film->genres as $genre)
                            <a href="{{ route('genres.show', $genre->id) }}" class="badge badge-secondary">
                                {{ $genre->name }}
                            </a>
                        @endforeach
                        <button type="button" class="btn" id="addGenreButton">+</button>
                    </p>
                    <p><strong>Tags :</strong>
                        <span id="tagsList">
                            @foreach($film->tags as $tag)
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

                    <div id="addGenreDiv" style="display: none;">
                        <form id="linkGenreForm">
                            @csrf
                            <div class="form-group">
                                <label for="genre_select">Ajouter un genre</label>
                                <select class="form-control" id="genre_select" name="genre_id">
                                    @foreach($genres as $genre)
                                        <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-secondary outline">Lier le genre</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <p>URL générée : {{ Storage::disk('films')->url($film->file_path) }}</p>
        <video controls width="100%">
            <source src="{{ Storage::disk('films')->url($film->file_path) }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

    <a href="{{ route('films.index') }}" class="btn mt-4">Retour à la liste des films</a>
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

        console.log('Sending POST request to {{ route("films.addTag", $film->id) }}');
        fetch('{{ route("films.addTag", $film->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ name: tagName })
        })
        .then(response => response.json())
        .then(data => {
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

