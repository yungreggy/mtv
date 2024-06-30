@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-4">
        @if($previousFilm)
            <a href="{{ route('films.edit', $previousFilm->id) }}" class="text-decoration-none" style="font-size: 1.5rem;">
                <i class="material-icons">chevron_left</i>
            </a>
        @else
            <span style="font-size: 1.5rem; color: #ccc;">
                <i class="material-icons">chevron_left</i>
            </span>
        @endif
        
        <a href="{{ route('films.index') }}" class="btn btn-secondary">Retour à la liste des films</a>

        @if($nextFilm)
            <a href="{{ route('films.edit', $nextFilm->id) }}" class="text-decoration-none" style="font-size: 1.5rem;">
                <i class="material-icons">chevron_right</i>
            </a>
        @else
            <span style="font-size: 1.5rem; color: #ccc;">
                <i class="material-icons">chevron_right</i>
            </span>
        @endif
    </div>
    
    <h1>Modifier un Film</h1>
    @include('partials.messages') <!-- Inclusion des messages -->
    <form action="{{ route('films.update', $film->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="title">Titre</label>
            <input type="text" name="title" class="form-control" style="border-radius: 5px;" value="{{ $film->title }}" required>
        </div>
        <div class="form-group">
            <label for="year">Année</label>
            <input type="number" name="year" class="form-control" style="border-radius: 5px;" value="{{ $film->year }}" required>
        </div>
        <div class="form-group">
            <label for="director_name">Réalisateur</label>
            <input type="text" name="director_name" class="form-control" style="border-radius: 5px;" value="{{ old('director_name', $film->director->name ?? '') }}" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" style="border-radius: 5px;" placeholder="Entrez la description du film">{{ $film->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="duration">Durée</label>
            <input type="text" name="duration" class="form-control" style="border-radius: 5px;" value="{{ old('duration', date('H:i', strtotime($film->duration))) }}" required placeholder="HH:MM">
        </div>
        
        <div class="form-group">
            <label for="file_path">Chemin du fichier</label>
            <input type="text" name="file_path" class="form-control" style="border-radius: 5px;" value="{{ $film->file_path }}" required>
        </div>

        <div class="form-group">
            <label for="local_image_path">Poster</label>
            <input type="file" name="local_image_path" class="form-control" style="border-radius: 5px;">
            @if($film->local_image_path)
            <div><img src="{{ Storage::url($film->local_image_path) }}" alt="Poster du film" style="max-width: 100px;"></div>
            @endif
        </div>
        <div class="form-group">
            <label for="rating">Note (rating)</label>
            <select name="rating" class="form-control" style="border-radius: 5px;">
                <option value="G" {{ $film->rating == 'G' ? 'selected' : '' }}>G</option>
                <option value="PG" {{ $film->rating == 'PG' ? 'selected' : '' }}>PG</option>
                <option value="PG-13" {{ $film->rating == 'PG-13' ? 'selected' : '' }}>PG-13</option>
                <option value="R" {{ $film->rating == 'R' ? 'selected' : '' }}>R</option>
                <option value="NC-17" {{ $film->rating == 'NC-17' ? 'selected' : '' }}>NC-17</option>
                <option value="Unrated" {{ $film->rating == 'Unrated' ? 'selected' : '' }}>Unrated</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="primary_language">Langue principale</label>
            <select name="primary_language" class="form-control" style="border-radius: 5px;">
                <option value="Anglais" {{ $film->primary_language == 'Anglais' ? 'selected' : '' }}>Anglais</option>
                <option value="Français" {{ $film->primary_language == 'Français' ? 'selected' : '' }}>Français</option>
            </select>
        </div>
        <div class="form-group">
            <label for="country_of_origin">Pays d'Origine</label>
            <input type="text" name="country_of_origin" class="form-control" style="border-radius: 5px;" value="{{ $film->country_of_origin }}">
        </div>
        <div class="form-group">
            <label for="genres">Genres</label>
            <div>
                @foreach($filmGenres as $genre)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="genres[]" value="{{ $genre->id }}" id="genre_{{ $genre->id }}" {{ (is_array(old('genres', $film->genres->pluck('id')->toArray())) && in_array($genre->id, old('genres', $film->genres->pluck('id')->toArray()))) ? 'checked' : '' }}>
                        <label class="form-check-label" for="genre_{{ $genre->id }}">
                            {{ $genre->name }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="form-group">
            <label for="tags">Tags</label>
            <div id="tagsContainer">
                @foreach($film->tags as $tag)
                    <span class="badge badge-secondary">
                        {{ $tag->name }}
                        <a href="#" class="text-danger remove-tag" data-tag-id="{{ $tag->id }}">x</a>
                    </span>
                @endforeach
            </div>
            <input type="text" id="newTag" class="form-control mt-2" placeholder="Ajouter un tag">
            <button type="button" class="btn btn-secondary mt-2" id="addTagButton">Ajouter</button>
        </div>
        <div class="mb-3">
            <label for="production_company" class="form-label">Compagnie de production</label>
            <input type="text" class="form-control" id="production_company" name="production_company" value="{{ $film->production_company }}">
        </div>
        <div class="mb-3">
            <label for="distributor" class="form-label">Distributeur</label>
            <input type="text" class="form-control" id="distributor" name="distributor" value="{{ $film->distributor }}">
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addTagButton').addEventListener('click', function() {
        let newTag = document.getElementById('newTag').value.trim();
        if (newTag !== '') {
            fetch('{{ route("films.addTag", $film->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ name: newTag })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let tagBadge = document.createElement('span');
                    tagBadge.className = 'badge badge-secondary';
                    tagBadge.innerHTML = `${data.tag.name} <a href="#" class="text-danger remove-tag" data-tag-id="${data.tag.id}">x</a>`;
                    document.getElementById('tagsContainer').appendChild(tagBadge);
                    document.getElementById('newTag').value = '';
                } else {
                    alert('Erreur lors de l\'ajout du tag');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la communication avec le serveur: ' + error.message);
            });
        }
    });

    document.getElementById('tagsContainer').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-tag')) {
            e.preventDefault();
            var tagId = e.target.getAttribute('data-tag-id');
            fetch(`{{ route("films.removeTag", [$film->id, '']) }}/${tagId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    e.target.parentElement.remove();
                } else {
                    alert('Erreur lors de la suppression du tag');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la communication avec le serveur: ' + error.message);
            });
        }
    });
});
</script>
@endsection
