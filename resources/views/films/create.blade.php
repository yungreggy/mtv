@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Ajouter un Film</h1>
    @include('partials.messages')  <!-- Feedback and error messages -->
    <form id="film-form" action="{{ route('films.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="title">Titre</label>
            <input type="text" name="title" class="form-control" style="border-radius: 5px;" value="{{ old('title') }}" required>
        </div>
        <div class="form-group">
            <label for="year">Année</label>
            <input type="number" name="year" class="form-control" style="border-radius: 5px;" value="{{ old('year') }}" required>
        </div>
        
        <div class="form-group">
            <label for="director_name">Réalisateur</label>
            <input type="text" id="director_name" name="director_name" class="form-control" style="border-radius: 5px;" value="{{ old('director_name') }}">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" style="border-radius: 5px;" placeholder="Entrez la description du film">{{ old('description') }}</textarea>
        </div>
        <div class="form-group">
            <label for="duration">Durée</label>
            <input type="text" name="duration" class="form-control" style="border-radius: 5px;" value="{{ old('duration', '01:30') }}" required>
        </div>
        <div class="form-group">
            <label for="file_path">Chemin du fichier</label>
            <input type="file" name="file_path" class="form-control" style="border-radius: 5px;">
        </div>

        <div class="form-group">
            <label for="local_image_path">Poster</label>
            <input type="file" name="local_image_path" class="form-control" style="border-radius: 5px;">
        </div>

        <div class="form-group">
            <label for="primary_language">Langue principale</label>
            <select name="primary_language" class="form-control" style="border-radius: 5px;">
                <option value="Anglais" {{ old('primary_language') == 'Anglais' ? 'selected' : '' }}>Anglais</option>
                <option value="Français" {{ old('primary_language') == 'Français' ? 'selected' : '' }}>Français</option>
            </select>
        </div>
        <div class="form-group">
            <label for="country_of_origin">Pays d'origine</label>
            <input type="text" name="country_of_origin" class="form-control" style="border-radius: 5px;" value="{{ old('country_of_origin', 'United States') }}">
        </div>

        <div class="form-group">
            <label for="genres">Genres</label>
            <div>
                @foreach($filmGenres as $genre)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="genres[]" value="{{ $genre->id }}" id="genre_{{ $genre->id }}" {{ (is_array(old('genres')) && in_array($genre->id, old('genres'))) ? 'checked' : '' }}>
                        <label class="form-check-label" for="genre_{{ $genre->id }}">
                            {{ $genre->name }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="form-group">
            <label for="rating">Rating</label>
            <select name="rating" class="form-control" style="border-radius: 5px;">
                <option value="G" {{ old('rating') == 'G' ? 'selected' : '' }}>G</option>
                <option value="PG" {{ old('rating') == 'PG' ? 'selected' : '' }}>PG</option>
                <option value="PG-13" {{ old('rating') == 'PG-13' ? 'selected' : '' }}>PG-13</option>
                <option value="R" {{ old('rating') == 'R' ? 'selected' : '' }}>R</option>
                <option value="NC-17" {{ old('rating') == 'NC-17' ? 'selected' : '' }}>NC-17</option>
                <option value="Unrated" {{ old('rating') == 'Unrated' ? 'selected' : '' }}>Unrated</option>
            </select>
        </div>

        <div class="form-group">
            <label for="tags">Tags</label>
            <textarea name="tags" class="form-control" style="border-radius: 5px;" placeholder="Entrez les tags, séparés par des virgules">{{ old('tags') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="production_company" class="form-label">Compagnie de production</label>
            <input type="text" class="form-control" id="production_company" name="production_company" value="{{ old('production_company') }}">
        </div>

        <div class="mb-3">
            <label for="distributor" class="form-label">Distributeur</label>
            <input type="text" class="form-control" id="distributor" name="distributor" value="{{ old('distributor') }}">
        </div>

        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>
@endsection
