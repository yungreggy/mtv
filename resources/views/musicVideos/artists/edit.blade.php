@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Modifier l'artiste : {{ $artist->name }}</h1>
    @include('partials.messages')
    <form action="{{ route('artists.update', $artist->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nom de l'artiste</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $artist->name }}" required>
        </div>
        <div class="mb-3">
            <label for="thumbnail_image" class="form-label">Image Miniature</label>
            <input type="file" class="form-control" id="thumbnail_image" name="thumbnail_image">
            @if ($artist->thumbnail_image)
                <img src="{{ Storage::url($artist->thumbnail_image) }}" class="img-fluid mt-2" alt="{{ $artist->name }}">
            @endif
        </div>
        <div class="mb-3">
            <label for="biography" class="form-label">Biographie</label>
            <textarea class="form-control" id="biography" name="biography" rows="3" maxlength="1000">{{ $artist->biography }}</textarea>
        </div>
        <div class="mb-3">
            <label for="website" class="form-label">Site Web</label>
            <input type="url" class="form-control" id="website" name="website" value="{{ $artist->website }}">
        </div>

        <div class="mb-3">
            <label for="main_genre" class="form-label">Genre principal</label>
            <select class="form-control" id="main_genre" name="main_genre" required>
                <option value="">--Sélectionnez un genre--</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre->name }}" {{ $artist->main_genre == $genre->name ? 'selected' : '' }}>{{ $genre->name }}</option>
                @endforeach
            </select>

        <div class="mb-3">
            <label for="career_start_year" class="form-label">Année de début de carrière</label>
            <input type="number" class="form-control" id="career_start_year" name="career_start_year" value="{{ $artist->career_start_year }}">
        </div>
        <div class="mb-3">
            <label for="country_of_origin" class="form-label">Pays d'Origine</label>
            <input type="text" class="form-control" id="country_of_origin" name="country_of_origin" value="{{ $artist->country_of_origin }}">
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour l'artiste</button>
    </form>
</div>
@endsection
