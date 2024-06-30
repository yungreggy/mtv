@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Ajouter une nouvelle vidéo musicale</h1>
    @include('partials.messages')
    <form action="{{ route('musicVideos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Section Artiste -->
        <h2>Section Artiste</h2>
        <div class="mb-3">
            <label for="artist_name" class="form-label">Nom de l'artiste</label>
            <input type="text" class="form-control no-radius" id="artist_name" name="artist_name" required>
        </div>

        <!-- Section Album -->
        <h2>Section Album</h2>
        <div class="mb-3">
            <label for="album_title" class="form-label">Titre de l'album</label>
            <input type="text" class="form-control no-radius" id="album_title" name="album_title" required>
        </div>

        <div class="mb-3">
            <label for="title" class="form-label">Titre de la vidéo</label>
            <input type="text" class="form-control no-radius" id="title" name="title" required>
        </div>

        <div class="mb-3">
            <label for="album_release_date" class="form-label">Date de sortie de l'album</label>
            <input type="date" class="form-control no-radius" id="album_release_date" name="album_release_date">
        </div>
        <div class="mb-3">
            <label for="album_year" class="form-label">Année de l'album</label>
            <input type="text" class="form-control no-radius" id="album_year" name="album_year" required>
        </div>


        <div class="mb-3">
            <label for="thumbnail_image" class="form-label">Image de l'album</label>
            <input type="file" class="form-control no-radius" id="thumbnail_image" name="thumbnail_image">
        </div>

        <div class="mb-3 row">
    <div class="col-md-6">
        <label for="label1" class="form-label">Label 1</label>
        <input type="text" class="form-control no-radius" id="label1" name="labels[]">
    </div>
    <div class="col-md-6">
        <label for="label2" class="form-label">Label 2</label>
        <input type="text" class="form-control no-radius" id="label2" name="labels[]">
    </div>
</div>


<hr> <!-- Séparateur -->
<br>


<h2>Genres</h2>


        <div class="mb-3">
            <label for="genre_1" class="form-label">Genre #1</label>
            <input type="text" class="form-control no-radius" id="genre_1" name="genre_1">
        </div>
        <div class="mb-3">
            <label for="genre_2" class="form-label">Genre #2</label>
            <input type="text" class="form-control no-radius" id="genre_2" name="genre_2">
        </div>
        <div class="mb-3">
            <label for="genre_3" class="form-label">Genre #3</label>
            <input type="text" class="form-control no-radius" id="genre_3" name="genre_3">
        </div>
       <br>


        <!-- Section Vidéo -->
        <h2>Section Vidéo</h2>
    

        <div class="mb-3">
            <label for="year" class="form-label">Année</label>
            <input type="number" class="form-control no-radius" id="year" name="year" required>
        </div>
        <div class="mb-3">
            <label for="release_date" class="form-label">Date de sortie du clip</label>
            <input type="date" class="form-control no-radius" id="release_date" name="release_date">
        </div>
        <div class="mb-3">
            <label for="director_name" class="form-label">Réalisateur</label>
            <input type="text" class="form-control no-radius" id="director_name" name="director_name">
        </div>
        <div class="mb-3">
            <label for="duration" class="form-label">Durée</label>
            <input type="text" class="form-control no-radius" id="duration" name="duration" placeholder="HH:MM:SS" value="00:00:00">
        </div>
        <div class="mb-3">
            <label for="file_path" class="form-label">Fichier Vidéo</label>
            <input type="file" class="form-control no-radius" id="file_path" name="file_path">
        </div>
        <div class="mb-3">
            <label for="video_quality" class="form-label">Qualité Vidéo</label>
            <select class="form-control no-radius" id="video_quality" name="video_quality">
                <option value="360p">360p</option>
                <option value="480p">480p</option>
                <option value="720p" selected>720p</option>
                <option value="1080p">1080p</option>
                <option value="4K">4K</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="age_rating" class="form-label">Classification par âge</label>
            <select class="form-control no-radius" id="age_rating" name="age_rating">
                <option value="G">G</option>
                <option value="PG">PG</option>
                <option value="PG-13">PG-13</option>
                <option value="R">R</option>
                <option value="NC-17">NC-17</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="language" class="form-label">Langue</label>
            <select class="form-control no-radius" id="language" name="language">
                <option value="English" selected>English</option>
                <option value="French">French</option>
                <option value="Spanish">Spanish</option>
                <!-- Ajoute d'autres langues selon tes besoins -->
            </select>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Statut</label>
            <input type="text" class="form-control no-radius" id="status" name="status">
        </div>
        <div class="mb-3">
            <label for="tags" class="form-label">Tags</label>
            <input type="text" class="form-control no-radius" id="tags" name="tags">
        </div>
        <div class="mb-3">
            <label for="play_frequency" class="form-label">Fréquence de Lecture</label>
            <input type="text" class="form-control no-radius" id="play_frequency" name="play_frequency">
        </div>

        <button type="submit" class="btn btn-primary">Ajouter la vidéo musicale</button>
    </form>
</div>
@endsection
