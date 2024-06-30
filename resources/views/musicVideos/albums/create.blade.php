@extends('layouts.app')

@section('content')

<div class="container">
    <h1 class="mb-4">Ajouter un album</h1>
    @include('partials.messages')
    <form action="{{ route('albums.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="artist_id" value="{{ $artist_id ?? '' }}">
        <div class="mb-3">
            <label for="title" class="form-label">Titre de l'album</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="year" class="form-label">Année de sortie</label>
            <input type="number" class="form-control" id="year" name="year" required>
        </div>
        <div class="mb-3">
            <label for="release_date" class="form-label">Date de sortie</label>
            <input type="date" class="form-control" id="release_date" name="release_date" >
        </div>

      <div class="mb-3">
            <label for="label1" class="form-label">Label 1</label>
            <input type="text" class="form-control" id="label1" name="labels[]" value="{{ old('label1') }}">
        </div>

        <div class="mb-3">
            <label for="label2" class="form-label">Label 2</label>
            <input type="text" class="form-control" id="label2" name="labels[]" value="{{ old('label2') }}">
        </div>



        <div class="mb-3">
            <label for="thumbnail_image" class="form-label">Image miniature</label>
            <input type="file" class="form-control" id="thumbnail_image" name="thumbnail_image" accept="image/jpeg, image/png, image/gif">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="track_count" class="form-label">Nombre de pistes</label>
            <input type="number" class="form-control" id="track_count" name="track_count" >
        </div>
   
        <div class="mb-3">
            <label for="url" class="form-label">URL</label>
            <input type="url" class="form-control" id="url" name="url">
        </div>

        <div class="form-group">
    <label for="genres" class="form-label">Genres</label>
    <select multiple class="form-control no-radius" id="genres" name="genres[]">
        @foreach($genres as $genre)
            <option value="{{ $genre->id }}">{{ $genre->name }}</option>
        @endforeach
    </select>
</div>

        <button type="submit" class="btn btn-primary">Soumettre</button>
    </form>
</div>
@endsection


