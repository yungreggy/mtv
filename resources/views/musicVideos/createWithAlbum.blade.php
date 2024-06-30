@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header">
            <h1 class="card-title">Ajouter une nouvelle Vidéo Musicale pour l'Album: {{ $album->title }}</h1>
        </div>
        @include('partials.messages')
        <div class="card-body">
            <form action="{{ route('musicVideos.storeWithAlbum', ['album' => $album->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="title" class="form-label">Titre</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>

    

                <div class="mb-3">
                    <label for="year" class="form-label">Année</label>
                    <input type="number" class="form-control" id="year" name="year" required>
                </div>
                <div class="mb-3">
    <label for="release_date" class="form-label">Date de sortie</label>
    <input type="date" class="form-control no-radius" id="release_date" name="release_date" value="{{ old('release_date') }}">
</div>



                <div class="mb-3">
                    <label for="duration" class="form-label">Durée</label>
                    <input type="text" class="form-control" id="duration" name="duration" placeholder="HH:MM:SS">
                </div>

                <div class="mb-3">
                    <label for="file_path" class="form-label">Chemin du fichier vidéo</label>
                    <input type="file" class="form-control" id="file_path" name="file_path">
                </div>

                <div class="mb-3">
                    <label for="thumbnail_image" class="form-label">Image miniature</label>
                    <input type="file" class="form-control" id="thumbnail_image" name="thumbnail_image">
                </div>

                <div class="mb-3">
                    <label for="video_quality" class="form-label">Qualité vidéo</label>
                    <select class="form-control" id="video_quality" name="video_quality">
                        <option value="360p">360p</option>
                        <option value="480p">480p</option>
                        <option value="720p" selected>720p</option>
                        <option value="1080p">1080p</option>
                        <option value="4K">4K</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="age_rating" class="form-label">Classification par âge</label>
                    <select class="form-control" id="age_rating" name="age_rating">
                        <option value="G">G</option>
                        <option value="PG">PG</option>
                        <option value="PG-13">PG-13</option>
                        <option value="R">R</option>
                        <option value="NC-17">NC-17</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="language" class="form-label">Langue</label>
                    <select class="form-control" id="language" name="language">
                        <option value="English" selected>English</option>
                        <option value="French">French</option>
                        <option value="Spanish">Spanish</option>
                        <!-- Ajoute d'autres langues selon tes besoins -->
                    </select>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Statut</label>
                    <input type="text" class="form-control" id="status" name="status">
                </div>

                <div class="mb-3">
                    <label for="tags" class="form-label">Tags</label>
                    <input type="text" class="form-control" id="tags" name="tags">
                </div>

                <div class="mb-3">
                    <label for="play_frequency" class="form-label">Fréquence de lecture</label>
                    <input type="text" class="form-control" id="play_frequency" name="play_frequency">
                </div>

                <div class="mb-3">
                    <label for="director_id" class="form-label">Réalisateur</label>
                    <select class="form-control" id="director_id" name="director_id">
                        @foreach($directors->sortBy('name') as $director)
                            <option value="{{ $director->id }}" {{ $director->id == 6 ? 'selected' : '' }}>
                                {{ $director->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
    <label for="genres" class="form-label">Genres</label>
    <select multiple class="form-control no-radius" id="genres" name="genres[]" size="10">
        @foreach($genres as $genre)
            <option value="{{ $genre->id }}">{{ $genre->name }}</option>
        @endforeach
    </select>
</div>



                <button type="submit" class="btn btn-primary">Ajouter</button>
                <a href="{{ route('albums.show', $album->id) }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>
@endsection

