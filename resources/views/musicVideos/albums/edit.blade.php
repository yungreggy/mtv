@extends('layouts.app')

@section('content')
<div class="container">
@include('partials.messages')
    <h1 class="mb-4">Modifier l'album : {{ $album->title }}</h1>
    <form action="{{ route('albums.update', $album->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="title" class="form-label">Titre de l'album</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ $album->title }}" required>
        </div>
        <div class="mb-3">
            <label for="year" class="form-label">Année de sortie</label>
            <input type="number" class="form-control" id="year" name="year" value="{{ $album->year }}" required>
        </div>

        <div class="mb-3">
            <label for="label1" class="form-label">Label 1</label>
            <input type="text" class="form-control" id="label1" name="labels[]" value="{{ old('label1', $album->labels[0]->name ?? '') }}">
        </div>

        <div class="mb3">
            <label for="label2" class="form-label">Label 2</label>
            <input type="text" class="form-control" id="label2" name="labels[]" value="{{ old('label2', $album->labels[1]->name ?? '') }}">
        </div>





        <div class="mb-3">
            <label for="artist_id" class="form-label">ID de l'artiste</label>
            <input type="number" class="form-control" id="artist_id" name="artist_id" value="{{ $album->artist_id }}">
        </div>
        <div class="mb-3">
            <label for="thumbnail_image" class="form-label">Image miniature</label>
            <input type="file" class="form-control" id="thumbnail_image" name="thumbnail_image" accept="image/jpeg, image/png, image/gif">
            @if ($album->thumbnail_image)
                <img src="{{ Storage::url($album->thumbnail_image) }}" class="img-fluid mt-2" alt="{{ $album->title }}">
            @endif
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ $album->description }}</textarea>
        </div>
        <div class="mb-3">
            <label for="track_count" class="form-label">Nombre de pistes</label>
            <input type="number" class="form-control" id="track_count" name="track_count" value="{{ $album->track_count }}">
        </div>
      <!-- Champ pour la date de sortie -->
      <div class="form-group">
            <label for="release_date">Date de sortie</label>
            <input type="date" class="form-control" id="release_date" name="release_date" value="{{ old('release_date', $album->release_date ? $album->release_date : '') }}">
        </div>


        <div class="mb-3">
            <label for="url" class="form-label">URL</label>
            <input type="url" class="form-control" id="url" name="url" value="{{ $album->url }}">
        </div>

        <div class="form-group">
    <label for="genres" class="form-label">Genres</label>
    <select multiple class="form-control no-radius" id="genres" name="genres[]">
        @foreach($genres as $genre)
            <option value="{{ $genre->id }}" 
                    {{ in_array($genre->id, $album->genres->pluck('id')->toArray()) ? 'selected' : '' }}>
                {{ $genre->name }}
            </option>
        @endforeach
    </select>
</div>

        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    </form>
</div>
@endsection
