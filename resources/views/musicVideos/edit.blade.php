@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header">
            <h1 class="card-title">Éditer : {{ $musicVideo->title }}</h1>
        </div>
        @include('partials.messages')
        <div class="card-body">
            <form action="{{ route('musicVideos.update', $musicVideo->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label">Titre</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ $musicVideo->title }}" required>
                </div>

                <div class="mb-3">
    <label for="release_date" class="form-label">Date de sortie</label>
    <input type="date" class="form-control no-radius" id="release_date" name="release_date" value="{{ old('release_date', $musicVideo->release_date ? date('Y-m-d', strtotime($musicVideo->release_date)) : '') }}">
</div>



                <div class="mb-3">
                    <label for="year" class="form-label">Année</label>
                    <input type="number" class="form-control" id="year" name="year" value="{{ $musicVideo->year }}" required>
                </div>
              
                <div class="mb-3">
                    <label for="duration" class="form-label">Durée</label>
                    <input type="text" class="form-control" id="duration" name="duration" value="{{ $musicVideo->duration }}" placeholder="HH:MM:SS">
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
                        <option value="360p" {{ $musicVideo->video_quality == '360p' ? 'selected' : '' }}>360p</option>
                        <option value="480p" {{ $musicVideo->video_quality == '480p' ? 'selected' : '' }}>480p</option>
                        <option value="720p" {{ $musicVideo->video_quality == '720p' ? 'selected' : '' }}>720p</option>
                        <option value="1080p" {{ $musicVideo->video_quality == '1080p' ? 'selected' : '' }}>1080p</option>
                        <option value="4K" {{ $musicVideo->video_quality == '4K' ? 'selected' : '' }}>4K</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="age_rating" class="form-label">Classification par âge</label>
                    <select class="form-control" id="age_rating" name="age_rating">
                        <option value="G" {{ $musicVideo->age_rating == 'G' ? 'selected' : '' }}>G</option>
                        <option value="PG" {{ $musicVideo->age_rating == 'PG' ? 'selected' : '' }}>PG</option>
                        <option value="PG-13" {{ $musicVideo->age_rating == 'PG-13' ? 'selected' : '' }}>PG-13</option>
                        <option value="R" {{ $musicVideo->age_rating == 'R' ? 'selected' : '' }}>R</option>
                        <option value="NC-17" {{ $musicVideo->age_rating == 'NC-17' ? 'selected' : '' }}>NC-17</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="language" class="form-label">Langue</label>
                    <select class="form-control" id="language" name="language">
                        <option value="English" {{ $musicVideo->language == 'English' ? 'selected' : '' }}>English</option>
                        <option value="French" {{ $musicVideo->language == 'French' ? 'selected' : '' }}>French</option>
                        <option value="Spanish" {{ $musicVideo->language == 'Spanish' ? 'selected' : '' }}>Spanish</option>
                        <!-- Ajoute d'autres langues selon tes besoins -->
                    </select>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Statut</label>
                    <input type="text" class="form-control" id="status" name="status" value="{{ $musicVideo->status }}">
                </div>

                <div class="mb-3">
                    <label for="tags" class="form-label">Tags</label>
                    <input type="text" class="form-control" id="tags" name="tags" value="{{ $musicVideo->tags }}">
                </div>

                <div class="mb-3">
                    <label for="play_frequency" class="form-label">Fréquence de lecture</label>
                    <input type="text" class="form-control" id="play_frequency" name="play_frequency" value="{{ $musicVideo->play_frequency }}">
                </div>

                <div class="mb-3">
                    <label for="director_id" class="form-label">Réalisateur</label>
                    <select class="form-control" id="director_id" name="director_id">
                        <option value="">--Sélectionnez un réalisateur--</option>
                        @foreach($directors->sortBy('name') as $director)
                            <option value="{{ $director->id }}" {{ $musicVideo->director_id == $director->id ? 'selected' : '' }}>
                                {{ $director->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
    <label for="genres" class="form-label">Genres</label>
    @foreach($genres as $category => $categoryGenres)
        <div class="mb-2">
            <strong>{{ $category }}</strong>
            @foreach($categoryGenres as $genre)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="genre_{{ $genre->id }}" name="genres[]" value="{{ $genre->id }}" {{ in_array($genre->id, $musicVideo->genres->pluck('id')->toArray()) ? 'checked' : '' }}>
                    <label class="form-check-label" for="genre_{{ $genre->id }}">
                        {{ $genre->name }}
                    </label>
                </div>
            @endforeach
        </div>
    @endforeach
</div>



                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="{{ route('musicVideos.index') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>
@endsection
